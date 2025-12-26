<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PenerimaanServisModel;
use App\Models\WorkOrderModel;

class Pembayaran extends BaseController
{
    protected $pembayaranModel;
    protected $penerimaanServisModel;
    protected $workOrderModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->penerimaanServisModel = new PenerimaanServisModel();
        $this->workOrderModel = new WorkOrderModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->pembayaranModel
            ->select('pembayaran.*, penerimaan_servis.nomor_servis, pelanggan.nama as nama_pelanggan, 
                     kendaraan.nomor_plat')
            ->join('penerimaan_servis', 'penerimaan_servis.id = pembayaran.penerimaan_servis_id')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id');
        
        if ($search) {
            $query->groupStart()
                  ->like('pembayaran.nomor_invoice', $search)
                  ->orLike('pelanggan.nama', $search)
                  ->orLike('kendaraan.nomor_plat', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Data Pembayaran',
            'pembayaran' => $query->orderBy('pembayaran.tanggal_bayar', 'DESC')->paginate(10),
            'pager' => $this->pembayaranModel->pager,
            'search' => $search
        ];

        return view('pembayaran/index', $data);
    }

    public function create()
    {
        // Get servis_id dari URL jika ada
        $servisId = $this->request->getGet('servis_id');
        
        // Get servis yang sudah selesai tapi belum dibayar
        $query = $this->penerimaanServisModel
            ->select('penerimaan_servis.*, pelanggan.nama as nama_pelanggan, kendaraan.nomor_plat')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->where('penerimaan_servis.status', 'Selesai')
            ->whereNotIn('penerimaan_servis.id', function($builder) {
                $builder->select('penerimaan_servis_id')->from('pembayaran');
            });
        
        // Jika ada servis_id dari parameter, tampilkan juga servis tersebut
        if ($servisId) {
            $query->orWhere('penerimaan_servis.id', $servisId);
        }
        
        $data = [
            'title' => 'Tambah Pembayaran',
            'penerimaan_servis' => $query->findAll()
        ];

        return view('pembayaran/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'penerimaan_servis_id' => 'required|integer',
            'metode_pembayaran' => 'required|in_list[Cash,Transfer,QRIS]',
            'tanggal_bayar' => 'required'
        ], [
            'penerimaan_servis_id' => [
                'required' => 'Servis harus dipilih'
            ],
            'metode_pembayaran' => [
                'required' => 'Metode pembayaran harus dipilih'
            ],
            'tanggal_bayar' => [
                'required' => 'Tanggal pembayaran harus diisi'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Cek apakah servis ini sudah dibayar
        $penerimaanServisId = $this->request->getPost('penerimaan_servis_id');
        $sudahBayar = $this->pembayaranModel->where('penerimaan_servis_id', $penerimaanServisId)->first();
        
        if ($sudahBayar) {
            return redirect()->back()->with('error', 'Servis ini sudah dibayar dengan invoice: ' . $sudahBayar['nomor_invoice']);
        }

        // Get work order data untuk calculate total
        $db = \Config\Database::connect();
        
        $workOrder = $this->workOrderModel
            ->where('penerimaan_servis_id', $penerimaanServisId)
            ->first();

        if (!$workOrder) {
            return redirect()->back()->with('error', 'Work order tidak ditemukan untuk servis ini. Pastikan work order sudah dibuat.');
        }

        // Calculate total jasa
        $totalJasa = $db->table('work_order_jasa')
            ->where('work_order_id', $workOrder['id'])
            ->selectSum('harga')
            ->get()
            ->getRow()
            ->harga ?? 0;

        // Calculate total sparepart
        $totalSparepart = $db->table('work_order_sparepart')
            ->where('work_order_id', $workOrder['id'])
            ->selectSum('subtotal')
            ->get()
            ->getRow()
            ->subtotal ?? 0;

        $totalBiaya = $totalJasa + $totalSparepart;

        // Generate nomor invoice
        $nomorInvoice = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $data = [
            'nomor_invoice' => $nomorInvoice,
            'penerimaan_servis_id' => $penerimaanServisId,
            'total_jasa' => $totalJasa,
            'total_sparepart' => $totalSparepart,
            'total_biaya' => $totalBiaya,
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'status_bayar' => 'Lunas',
            'tanggal_bayar' => $this->request->getPost('tanggal_bayar')
        ];

        try {
            $this->pembayaranModel->insert($data);

            return redirect()->to('/pembayaran')->with('success', 'Pembayaran berhasil dengan nomor invoice: ' . $nomorInvoice);
        } catch (\Exception $e) {
            log_message('error', 'Error pembayaran: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pembayaran = $this->pembayaranModel
            ->select('pembayaran.*, penerimaan_servis.nomor_servis, penerimaan_servis.keluhan,
                     pelanggan.nama as nama_pelanggan, pelanggan.alamat, pelanggan.no_telepon,
                     kendaraan.nomor_plat, kendaraan.merk, kendaraan.tipe')
            ->join('penerimaan_servis', 'penerimaan_servis.id = pembayaran.penerimaan_servis_id')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->find($id);

        if (!$pembayaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Get work order details
        $db = \Config\Database::connect();
        $workOrder = $this->workOrderModel
            ->where('penerimaan_servis_id', $pembayaran['penerimaan_servis_id'])
            ->first();

        $jasaServis = [];
        $sparepart = [];

        if ($workOrder) {
            $jasaServis = $db->table('work_order_jasa')
                ->select('work_order_jasa.*, jasa_servis.nama_layanan')
                ->join('jasa_servis', 'jasa_servis.id = work_order_jasa.jasa_servis_id')
                ->where('work_order_jasa.work_order_id', $workOrder['id'])
                ->get()
                ->getResultArray();

            $sparepart = $db->table('work_order_sparepart')
                ->select('work_order_sparepart.*, sparepart.nama_barang, sparepart.satuan')
                ->join('sparepart', 'sparepart.id = work_order_sparepart.sparepart_id')
                ->where('work_order_sparepart.work_order_id', $workOrder['id'])
                ->get()
                ->getResultArray();
        }

        $data = [
            'title' => 'Detail Pembayaran',
            'pembayaran' => $pembayaran,
            'jasa_servis' => $jasaServis,
            'sparepart' => $sparepart
        ];

        return view('pembayaran/show', $data);
    }

    public function cetakInvoice($id)
    {
        $pembayaran = $this->pembayaranModel
            ->select('pembayaran.*, penerimaan_servis.nomor_servis, penerimaan_servis.keluhan,
                     pelanggan.nama as nama_pelanggan, pelanggan.alamat, pelanggan.no_telepon,
                     kendaraan.nomor_plat, kendaraan.merk, kendaraan.tipe')
            ->join('penerimaan_servis', 'penerimaan_servis.id = pembayaran.penerimaan_servis_id')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->find($id);

        if (!$pembayaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $workOrder = $this->workOrderModel
            ->where('penerimaan_servis_id', $pembayaran['penerimaan_servis_id'])
            ->first();

        $jasaServis = [];
        $sparepart = [];

        if ($workOrder) {
            $jasaServis = $db->table('work_order_jasa')
                ->select('work_order_jasa.*, jasa_servis.nama_layanan')
                ->join('jasa_servis', 'jasa_servis.id = work_order_jasa.jasa_servis_id')
                ->where('work_order_jasa.work_order_id', $workOrder['id'])
                ->get()
                ->getResultArray();

            $sparepart = $db->table('work_order_sparepart')
                ->select('work_order_sparepart.*, sparepart.nama_barang, sparepart.satuan')
                ->join('sparepart', 'sparepart.id = work_order_sparepart.sparepart_id')
                ->where('work_order_sparepart.work_order_id', $workOrder['id'])
                ->get()
                ->getResultArray();
        }

        $data = [
            'pembayaran' => $pembayaran,
            'jasa_servis' => $jasaServis,
            'sparepart' => $sparepart
        ];

        return view('pembayaran/cetak_invoice', $data);
    }

    public function delete($id)
    {
        $pembayaran = $this->pembayaranModel->find($id);
        
        if ($pembayaran) {
            // Kembalikan status penerimaan servis ke "Selesai"
            $this->penerimaanServisModel->update(
                $pembayaran['penerimaan_servis_id'], 
                ['status' => 'Selesai']
            );
        }
        
        $this->pembayaranModel->delete($id);
        return redirect()->to('/pembayaran')->with('success', 'Data pembayaran berhasil dihapus');
    }
}


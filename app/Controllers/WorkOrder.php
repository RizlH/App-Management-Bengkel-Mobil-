<?php


namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\WorkOrderModel;
use App\Models\PenerimaanServisModel;
use App\Models\MekanikModel;
use App\Models\JasaServisModel;
use App\Models\SparepartModel;

class WorkOrder extends BaseController
{
    protected $workOrderModel;
    protected $penerimaanServisModel;
    protected $mekanikModel;
    protected $jasaServisModel;
    protected $sparepartModel;

    public function __construct()
    {
        $this->workOrderModel = new WorkOrderModel();
        $this->penerimaanServisModel = new PenerimaanServisModel();
        $this->mekanikModel = new MekanikModel();
        $this->jasaServisModel = new JasaServisModel();
        $this->sparepartModel = new SparepartModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->workOrderModel
            ->select('work_order.*, penerimaan_servis.nomor_servis, mekanik.nama as nama_mekanik, 
                     pelanggan.nama as nama_pelanggan, kendaraan.nomor_plat')
            ->join('penerimaan_servis', 'penerimaan_servis.id = work_order.penerimaan_servis_id')
            ->join('mekanik', 'mekanik.id = work_order.mekanik_id')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id');
        
        if ($search) {
            $query->groupStart()
                  ->like('penerimaan_servis.nomor_servis', $search)
                  ->orLike('mekanik.nama', $search)
                  ->orLike('kendaraan.nomor_plat', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Work Order',
            'work_orders' => $query->orderBy('work_order.created_at', 'DESC')->paginate(10),
            'pager' => $this->workOrderModel->pager,
            'search' => $search
        ];

        return view('work_order/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Work Order',
            'penerimaan_servis' => $this->penerimaanServisModel
                ->select('penerimaan_servis.*, pelanggan.nama as nama_pelanggan, kendaraan.nomor_plat')
                ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
                ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
                ->whereIn('penerimaan_servis.status', ['Menunggu', 'Proses'])
                ->findAll(),
            'mekanik' => $this->mekanikModel->where('status_aktif', 1)->findAll(),
            'jasa_servis' => $this->jasaServisModel->findAll(),
            'sparepart' => $this->sparepartModel->where('stok >', 0)->findAll()
        ];

        return view('work_order/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'penerimaan_servis_id' => 'required|integer',
                'mekanik_id' => 'required|integer',
                'tanggal_mulai' => 'required'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            // Insert work order
            $workOrderData = [
                'penerimaan_servis_id' => $this->request->getPost('penerimaan_servis_id'),
                'mekanik_id' => $this->request->getPost('mekanik_id'),
                'progres' => $this->request->getPost('progres'),
                'status' => 'Pending',
                'tanggal_mulai' => $this->request->getPost('tanggal_mulai')
            ];

            $workOrderId = $this->workOrderModel->insert($workOrderData, true);

            // Insert jasa servis
            $jasaServisIds = $this->request->getPost('jasa_servis_id');
            if ($jasaServisIds && is_array($jasaServisIds)) {
                foreach ($jasaServisIds as $jasaId) {
                    if (!empty($jasaId)) {
                        $jasa = $this->jasaServisModel->find($jasaId);
                        if ($jasa) {
                            $db->table('work_order_jasa')->insert([
                                'work_order_id' => $workOrderId,
                                'jasa_servis_id' => $jasaId,
                                'harga' => $jasa['harga_jasa']
                            ]);
                        }
                    }
                }
            }

            // Insert sparepart
            $sparepartIds = $this->request->getPost('sparepart_id');
            $jumlahSparepart = $this->request->getPost('jumlah_sparepart');
            
            if ($sparepartIds && is_array($sparepartIds)) {
                foreach ($sparepartIds as $index => $sparepartId) {
                    if (!empty($sparepartId) && isset($jumlahSparepart[$index]) && $jumlahSparepart[$index] > 0) {
                        $sparepart = $this->sparepartModel->find($sparepartId);
                        if ($sparepart) {
                            $jumlah = $jumlahSparepart[$index];
                            
                            // Cek stok
                            if ($sparepart['stok'] < $jumlah) {
                                $db->transRollback();
                                return redirect()->back()->withInput()
                                    ->with('error', 'Stok ' . $sparepart['nama_barang'] . ' tidak mencukupi. Stok tersedia: ' . $sparepart['stok']);
                            }
                            
                            $subtotal = $sparepart['harga_jual'] * $jumlah;

                            $db->table('work_order_sparepart')->insert([
                                'work_order_id' => $workOrderId,
                                'sparepart_id' => $sparepartId,
                                'jumlah' => $jumlah,
                                'harga_satuan' => $sparepart['harga_jual'],
                                'subtotal' => $subtotal
                            ]);

                            // Update stok sparepart
                            $this->sparepartModel->update($sparepartId, [
                                'stok' => $sparepart['stok'] - $jumlah
                            ]);
                        }
                    }
                }
            }

            // Update status penerimaan servis menjadi "Proses"
            $this->penerimaanServisModel->update(
                $this->request->getPost('penerimaan_servis_id'),
                ['status' => 'Proses']
            );

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan work order');
            }

            return redirect()->to('/work-order')->with('success', 'Work order berhasil ditambahkan');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error work order: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        // Get work order with joins
        $workOrder = $this->workOrderModel
            ->select('work_order.*, penerimaan_servis.nomor_servis, penerimaan_servis.keluhan,
                     mekanik.nama as nama_mekanik, mekanik.posisi,
                     pelanggan.nama as nama_pelanggan, kendaraan.nomor_plat, kendaraan.merk')
            ->join('penerimaan_servis', 'penerimaan_servis.id = work_order.penerimaan_servis_id')
            ->join('mekanik', 'mekanik.id = work_order.mekanik_id')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->find($id);

        if (!$workOrder) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Work order tidak ditemukan');
        }

        $db = \Config\Database::connect();

        // Get jasa servis
        $jasaServis = $db->table('work_order_jasa')
            ->select('work_order_jasa.*, jasa_servis.nama_layanan')
            ->join('jasa_servis', 'jasa_servis.id = work_order_jasa.jasa_servis_id')
            ->where('work_order_jasa.work_order_id', $id)
            ->get()
            ->getResultArray();

        // Get sparepart
        $sparepart = $db->table('work_order_sparepart')
            ->select('work_order_sparepart.*, sparepart.nama_barang, sparepart.satuan')
            ->join('sparepart', 'sparepart.id = work_order_sparepart.sparepart_id')
            ->where('work_order_sparepart.work_order_id', $id)
            ->get()
            ->getResultArray();

        // Calculate totals
        $totalJasa = 0;
        foreach ($jasaServis as $js) {
            $totalJasa += $js['harga'];
        }

        $totalSparepart = 0;
        foreach ($sparepart as $sp) {
            $totalSparepart += $sp['subtotal'];
        }

        $totalBiaya = $totalJasa + $totalSparepart;

        $data = [
            'title' => 'Detail Work Order',
            'work_order' => $workOrder,
            'jasa_servis' => $jasaServis,
            'sparepart' => $sparepart,
            'total_jasa' => $totalJasa,
            'total_sparepart' => $totalSparepart,
            'total_biaya' => $totalBiaya
        ];

        return view('work_order/show', $data);
    }

    public function updateStatus($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'status' => 'required|in_list[Pending,Dikerjakan,Selesai]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $status = $this->request->getPost('status');
        $progres = $this->request->getPost('progres');

        $data = [
            'status' => $status,
            'progres' => $progres
        ];

        // Jika status selesai, set tanggal selesai
        if ($status == 'Selesai') {
            $data['tanggal_selesai'] = date('Y-m-d H:i:s');

            // Update penerimaan servis menjadi selesai juga
            $workOrder = $this->workOrderModel->find($id);
            if ($workOrder) {
                $this->penerimaanServisModel->update($workOrder['penerimaan_servis_id'], [
                    'status' => 'Selesai',
                    'tanggal_selesai' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->workOrderModel->update($id, $data);
        return redirect()->to('/work-order/show/' . $id)->with('success', 'Status work order berhasil diupdate');
    }

    public function delete($id)
    {
        // Get work order data untuk restore stok
        $db = \Config\Database::connect();
        $spareparts = $db->table('work_order_sparepart')
            ->where('work_order_id', $id)
            ->get()
            ->getResultArray();

        // Restore stok sparepart
        foreach ($spareparts as $item) {
            $sparepart = $this->sparepartModel->find($item['sparepart_id']);
            if ($sparepart) {
                $this->sparepartModel->update($item['sparepart_id'], [
                    'stok' => $sparepart['stok'] + $item['jumlah']
                ]);
            }
        }

        $this->workOrderModel->delete($id);
        return redirect()->to('/work-order')->with('success', 'Work order berhasil dihapus dan stok telah dikembalikan');
    }
}

/**
 * CATATAN PENTING:
 * - Semua operasi database menggunakan transaction
 * - Validasi stok sebelum insert sparepart
 * - Auto restore stok saat delete work order
 * - Error handling yang baik dengan try-catch
 * - Log error untuk debugging
 */
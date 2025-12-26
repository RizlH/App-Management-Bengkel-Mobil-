<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenjualanSparepartModel;
use App\Models\SparepartModel;

class PenjualanSparepart extends BaseController
{
    protected $penjualanSparepartModel;
    protected $sparepartModel;

    public function __construct()
    {
        $this->penjualanSparepartModel = new PenjualanSparepartModel();
        $this->sparepartModel = new SparepartModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->penjualanSparepartModel;
        
        if ($search) {
            $query->groupStart()
                  ->like('nomor_penjualan', $search)
                  ->orLike('nama_pembeli', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Penjualan Sparepart',
            'penjualan' => $query->orderBy('tanggal_penjualan', 'DESC')->paginate(10),
            'pager' => $this->penjualanSparepartModel->pager,
            'search' => $search
        ];

        return view('penjualan_sparepart/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Penjualan Sparepart',
            'sparepart' => $this->sparepartModel->where('stok >', 0)->findAll()
        ];

        return view('penjualan_sparepart/create', $data);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'nama_pembeli' => 'required|min_length[3]',
                'tanggal_penjualan' => 'required'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            // Generate nomor penjualan
            $nomorPenjualan = 'PJ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

            $sparepartIds = $this->request->getPost('sparepart_id');
            $jumlahSparepart = $this->request->getPost('jumlah');
            $hargaJual = $this->request->getPost('harga_jual');

            // Validasi input
            if (empty($sparepartIds) || !is_array($sparepartIds)) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Silakan pilih minimal 1 sparepart!');
            }

            $totalPenjualan = 0;

            // Validasi stok dan calculate total
            foreach ($sparepartIds as $index => $sparepartId) {
                if (!empty($sparepartId)) {
                    $sparepart = $this->sparepartModel->find($sparepartId);
                    if (!$sparepart) {
                        $db->transRollback();
                        return redirect()->back()->withInput()->with('error', 'Sparepart tidak ditemukan!');
                    }

                    $jumlah = $jumlahSparepart[$index] ?? 0;
                    $harga = $hargaJual[$index] ?? 0;

                    // Cek stok
                    if ($sparepart['stok'] < $jumlah) {
                        $db->transRollback();
                        return redirect()->back()->withInput()
                            ->with('error', 'Stok ' . $sparepart['nama_barang'] . ' tidak mencukupi! Stok tersedia: ' . $sparepart['stok']);
                    }

                    $totalPenjualan += $harga * $jumlah;
                }
            }

            // Insert penjualan sparepart
            $penjualanData = [
                'nomor_penjualan' => $nomorPenjualan,
                'nama_pembeli' => $this->request->getPost('nama_pembeli'),
                'tanggal_penjualan' => $this->request->getPost('tanggal_penjualan'),
                'total_penjualan' => $totalPenjualan
            ];

            $penjualanId = $this->penjualanSparepartModel->insert($penjualanData, true);

            // Insert detail and update stok
            foreach ($sparepartIds as $index => $sparepartId) {
                if (!empty($sparepartId)) {
                    $jumlah = $jumlahSparepart[$index];
                    $harga = $hargaJual[$index];
                    $subtotal = $harga * $jumlah;

                    $db->table('detail_penjualan_sparepart')->insert([
                        'penjualan_sparepart_id' => $penjualanId,
                        'sparepart_id' => $sparepartId,
                        'jumlah' => $jumlah,
                        'harga_jual' => $harga,
                        'subtotal' => $subtotal
                    ]);

                    // KURANGI stok sparepart
                    $sparepart = $this->sparepartModel->find($sparepartId);
                    $this->sparepartModel->update($sparepartId, [
                        'stok' => $sparepart['stok'] - $jumlah
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan penjualan');
            }

            return redirect()->to('/penjualan-sparepart')->with('success', 'Penjualan sparepart berhasil dengan nomor: ' . $nomorPenjualan);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error penjualan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $penjualan = $this->penjualanSparepartModel->find($id);

        if (!$penjualan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $detail = $db->table('detail_penjualan_sparepart')
            ->select('detail_penjualan_sparepart.*, sparepart.nama_barang, sparepart.satuan')
            ->join('sparepart', 'sparepart.id = detail_penjualan_sparepart.sparepart_id')
            ->where('detail_penjualan_sparepart.penjualan_sparepart_id', $id)
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Detail Penjualan Sparepart',
            'penjualan' => $penjualan,
            'detail' => $detail
        ];

        return view('penjualan_sparepart/show', $data);
    }

    public function delete($id)
    {
        // Get detail penjualan untuk restore stok
        $db = \Config\Database::connect();
        $details = $db->table('detail_penjualan_sparepart')
            ->where('penjualan_sparepart_id', $id)
            ->get()
            ->getResultArray();

        // Restore stok sparepart (tambahkan kembali)
        foreach ($details as $item) {
            $sparepart = $this->sparepartModel->find($item['sparepart_id']);
            if ($sparepart) {
                $this->sparepartModel->update($item['sparepart_id'], [
                    'stok' => $sparepart['stok'] + $item['jumlah']
                ]);
            }
        }

        $this->penjualanSparepartModel->delete($id);
        return redirect()->to('/penjualan-sparepart')->with('success', 'Data penjualan berhasil dihapus dan stok dikembalikan');
    }

    // Method untuk laporan penjualan sparepart
    public function laporan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $query = $this->penjualanSparepartModel
            ->where('MONTH(tanggal_penjualan)', $bulan)
            ->where('YEAR(tanggal_penjualan)', $tahun)
            ->orderBy('tanggal_penjualan', 'DESC')
            ->findAll();

        // Calculate totals
        $totalPenjualan = 0;
        $totalTransaksi = count($query);
        foreach ($query as $item) {
            $totalPenjualan += $item['total_penjualan'];
        }

        $data = [
            'title' => 'Laporan Penjualan Sparepart',
            'penjualan' => $query,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'total_penjualan' => $totalPenjualan,
            'total_transaksi' => $totalTransaksi
        ];

        return view('penjualan_sparepart/laporan', $data);
    }
}

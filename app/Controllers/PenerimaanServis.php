<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenerimaanServisModel;
use App\Models\PelangganModel;
use App\Models\KendaraanModel;

class PenerimaanServis extends BaseController
{
    protected $penerimaanServisModel;
    protected $pelangganModel;
    protected $kendaraanModel;

    public function __construct()
    {
        $this->penerimaanServisModel = new PenerimaanServisModel();
        $this->pelangganModel = new PelangganModel();
        $this->kendaraanModel = new KendaraanModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        
        $query = $this->penerimaanServisModel
            ->select('penerimaan_servis.*, pelanggan.nama as nama_pelanggan, kendaraan.nomor_plat, kendaraan.merk')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id');
        
        if ($search) {
            $query->groupStart()
                  ->like('nomor_servis', $search)
                  ->orLike('pelanggan.nama', $search)
                  ->orLike('kendaraan.nomor_plat', $search)
                  ->groupEnd();
        }

        if ($status) {
            $query->where('penerimaan_servis.status', $status);
        }

        $data = [
            'title' => 'Penerimaan Servis',
            'penerimaan_servis' => $query->orderBy('penerimaan_servis.tanggal_masuk', 'DESC')->paginate(10),
            'pager' => $this->penerimaanServisModel->pager,
            'search' => $search,
            'status' => $status
        ];

        return view('penerimaan_servis/index', $data);
    }

    public function create()
    {
        // Query untuk mendapatkan kendaraan yang TIDAK memiliki penerimaan servis aktif
        $db = \Config\Database::connect();
        
        // Subquery: kendaraan dengan penerimaan servis aktif (Menunggu/Proses)
        $subQuery = $db->table('penerimaan_servis')
            ->select('kendaraan_id')
            ->whereIn('status', ['Menunggu', 'Proses']);
        
        // Eksekusi subquery untuk mendapatkan array ID kendaraan
        $activeVehicleIds = $subQuery->get()->getResultArray();
        $activeVehicleIds = array_column($activeVehicleIds, 'kendaraan_id');
        
        // Jika tidak ada kendaraan aktif, set array kosong
        $activeVehicleIds = empty($activeVehicleIds) ? [0] : $activeVehicleIds;
        
        // Main query: kendaraan yang tidak ada di list kendaraan aktif
        $query = $db->table('kendaraan')
            ->select('kendaraan.*, 
                    pelanggan.id as pelanggan_id,
                    pelanggan.nama as nama_pemilik, 
                    pelanggan.no_telepon, 
                    pelanggan.alamat')
            ->join('pelanggan', 'pelanggan.id = kendaraan.pelanggan_id', 'left');
        
        // Gunakan whereNotIn dengan array yang benar
        if (!empty($activeVehicleIds)) {
            $query->whereNotIn('kendaraan.id', $activeVehicleIds);
        }
        
        $query->orderBy('kendaraan.nomor_plat', 'ASC');
        
        $kendaraanList = $query->get()->getResultArray();
        
        // Debug log
        log_message('debug', 'Available vehicles for new service: ' . count($kendaraanList));
        log_message('debug', 'Active vehicle IDs: ' . print_r($activeVehicleIds, true));
        
        $data = [
            'title' => 'Tambah Penerimaan Servis',
            'kendaraan' => $kendaraanList,
            'errors' => session()->getFlashdata('errors') ?? []
        ];
        
        return view('penerimaan_servis/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'kendaraan_id'   => 'required|integer',
            'pelanggan_id'   => 'required|integer',
            'keluhan'        => 'required|min_length[10]',
            'estimasi_biaya' => 'required|numeric',
            'tanggal_masuk'  => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $kendaraanId = $this->request->getPost('kendaraan_id');
        $pelangganId = $this->request->getPost('pelanggan_id');
        
        // Validasi tambahan: Cek apakah kendaraan sudah memiliki servis aktif
        $servisAktif = $this->penerimaanServisModel
            ->where('kendaraan_id', $kendaraanId)
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->countAllResults();
        
        if ($servisAktif > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kendaraan ini masih dalam proses servis. Silakan pilih kendaraan lain.');
        }
        
        // Validasi: Pastikan kendaraan milik pelanggan yang dipilih
        $kendaraan = $this->kendaraanModel
            ->where('id', $kendaraanId)
            ->where('pelanggan_id', $pelangganId)
            ->first();
        
        if (!$kendaraan) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kendaraan tidak sesuai dengan pelanggan yang dipilih');
        }

        // Generate nomor servis
        $nomorServis = 'SRV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $data = [
            'nomor_servis'   => $nomorServis,
            'pelanggan_id'   => $pelangganId,
            'kendaraan_id'   => $kendaraanId,
            'keluhan'        => $this->request->getPost('keluhan'),
            'estimasi_biaya' => $this->request->getPost('estimasi_biaya'),
            'status'         => 'Menunggu',
            'tanggal_masuk'  => $this->request->getPost('tanggal_masuk')
        ];

        $this->penerimaanServisModel->insert($data);

        return redirect()->to('/penerimaan-servis')
            ->with('success', 'Penerimaan servis berhasil ditambahkan');
    }

    public function show($id)
    {
        $penerimaanServis = $this->penerimaanServisModel
            ->select('penerimaan_servis.*, pelanggan.nama as nama_pelanggan, pelanggan.alamat, pelanggan.no_telepon, 
                     kendaraan.nomor_plat, kendaraan.merk, kendaraan.tipe, kendaraan.tahun, kendaraan.foto')
            ->join('pelanggan', 'pelanggan.id = penerimaan_servis.pelanggan_id')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->find($id);

        if (!$penerimaanServis) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        // Get work orders
        $workOrderModel = new \App\Models\WorkOrderModel();
        $workOrders = $workOrderModel
            ->select('work_order.*, mekanik.nama as nama_mekanik')
            ->join('mekanik', 'mekanik.id = work_order.mekanik_id')
            ->where('work_order.penerimaan_servis_id', $id)
            ->findAll();

        $data = [
            'title' => 'Detail Penerimaan Servis',
            'penerimaan_servis' => $penerimaanServis,
            'work_orders' => $workOrders
        ];

        return view('penerimaan_servis/show', $data);
    }

    public function edit($id)
    {
        // Ambil data penerimaan servis yang sedang diedit
        $penerimaanServis = $this->penerimaanServisModel->find($id);
        
        if (!$penerimaanServis) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }
        
        // Query untuk mendapatkan kendaraan yang TIDAK memiliki penerimaan servis aktif
        // KECUALI kendaraan yang sedang diedit ini
        $db = \Config\Database::connect();
        
        // Subquery: kendaraan dengan penerimaan servis aktif (kecuali yang sedang diedit)
        $subQuery = $db->table('penerimaan_servis')
            ->select('kendaraan_id')
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->where('id !=', $id);
        
        // Eksekusi subquery untuk mendapatkan array ID kendaraan
        $activeVehicleIds = $subQuery->get()->getResultArray();
        $activeVehicleIds = array_column($activeVehicleIds, 'kendaraan_id');
        
        // Jika tidak ada kendaraan aktif, set array kosong
        $activeVehicleIds = empty($activeVehicleIds) ? [0] : $activeVehicleIds;
        
        // Main query: kendaraan yang tidak ada di list kendaraan aktif ATAU kendaraan yang sedang diedit
        $query = $db->table('kendaraan')
            ->select('kendaraan.*, 
                    pelanggan.id as pelanggan_id,
                    pelanggan.nama as nama_pemilik, 
                    pelanggan.no_telepon, 
                    pelanggan.alamat')
            ->join('pelanggan', 'pelanggan.id = kendaraan.pelanggan_id')
            ->groupStart()
                ->whereNotIn('kendaraan.id', $activeVehicleIds)
                ->orWhere('kendaraan.id', $penerimaanServis['kendaraan_id'])
            ->groupEnd()
            ->orderBy('kendaraan.nomor_plat', 'ASC');
        
        $kendaraanList = $query->get()->getResultArray();
        
        $data = [
            'title' => 'Edit Penerimaan Servis',
            'penerimaan_servis' => $penerimaanServis,
            'kendaraan' => $kendaraanList,
            'errors' => session()->getFlashdata('errors') ?? []
        ];

        return view('penerimaan_servis/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'kendaraan_id' => 'required|integer',
            'pelanggan_id' => 'required|integer',
            'keluhan' => 'required|min_length[10]',
            'estimasi_biaya' => 'required|numeric',
            'status' => 'required|in_list[Menunggu,Proses,Selesai]',
            'tanggal_masuk' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $kendaraanId = $this->request->getPost('kendaraan_id');
        $pelangganId = $this->request->getPost('pelanggan_id');
        
        // Validasi: Cek apakah kendaraan sudah memiliki servis aktif (kecuali yang sedang diedit)
        $servisAktif = $this->penerimaanServisModel
            ->where('kendaraan_id', $kendaraanId)
            ->whereIn('status', ['Menunggu', 'Proses'])
            ->where('id !=', $id)
            ->countAllResults();
        
        if ($servisAktif > 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kendaraan ini masih dalam proses servis. Pilih kendaraan lain.');
        }
        
        // Validasi: Pastikan kendaraan milik pelanggan tersebut
        $kendaraan = $this->kendaraanModel
            ->where('id', $kendaraanId)
            ->where('pelanggan_id', $pelangganId)
            ->first();

        if (!$kendaraan) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kendaraan tidak sesuai dengan pelanggan');
        }

        $data = [
            'pelanggan_id' => $pelangganId,
            'kendaraan_id' => $kendaraanId,
            'keluhan' => $this->request->getPost('keluhan'),
            'estimasi_biaya' => $this->request->getPost('estimasi_biaya'),
            'status' => $this->request->getPost('status'),
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk')
        ];

        // Update tanggal selesai jika status berubah menjadi Selesai
        if ($this->request->getPost('status') == 'Selesai') {
            $data['tanggal_selesai'] = date('Y-m-d H:i:s');
        }

        $this->penerimaanServisModel->update($id, $data);
        return redirect()->to('/penerimaan-servis')->with('success', 'Data penerimaan servis berhasil diupdate');
    }

    public function delete($id)
    {
        $this->penerimaanServisModel->delete($id);
        return redirect()->to('/penerimaan-servis')->with('success', 'Data penerimaan servis berhasil dihapus');
    }

    // AJAX: Get kendaraan by pelanggan
    public function getKendaraanByPelanggan($pelangganId)
    {
        $kendaraan = $this->kendaraanModel->where('pelanggan_id', $pelangganId)->findAll();
        return $this->response->setJSON($kendaraan);
    }
}
<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;
use App\Models\KendaraanModel;
use App\Models\PenerimaanServisModel;

class Pelanggan extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->pelangganModel;
        
        if ($search) {
            $query->groupStart()
                  ->like('nama', $search)
                  ->orLike('no_telepon', $search)
                  ->orLike('alamat', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Data Pelanggan',
            'pelanggan' => $query->paginate(10),
            'pager' => $this->pelangganModel->pager,
            'search' => $search
        ];

        return view('pelanggan/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Pelanggan'];
        return view('pelanggan/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'alamat' => 'required|min_length[3]',
            'no_telepon' => 'required|min_length[10]|max_length[15]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon')
        ];

        $this->pelangganModel->insert($data);
        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    public function show($id)
    {
        $pelanggan = $this->pelangganModel->find($id);

        if (!$pelanggan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pelanggan tidak ditemukan');
        }

        // Get riwayat kendaraan
        $kendaraanModel = new KendaraanModel();
        $riwayatKendaraan = $kendaraanModel->where('pelanggan_id', $id)->findAll();

        // Get riwayat servis
        $servisModel = new PenerimaanServisModel();
        $riwayatServis = $servisModel
            ->select('penerimaan_servis.*, kendaraan.nomor_plat, kendaraan.merk')
            ->join('kendaraan', 'kendaraan.id = penerimaan_servis.kendaraan_id')
            ->where('penerimaan_servis.pelanggan_id', $id)
            ->orderBy('penerimaan_servis.tanggal_masuk', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Detail Pelanggan',
            'pelanggan' => $pelanggan,
            'riwayat_kendaraan' => $riwayatKendaraan,
            'riwayat_servis' => $riwayatServis
        ];

        return view('pelanggan/show', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Pelanggan',
            'pelanggan' => $this->pelangganModel->find($id)
        ];

        if (!$data['pelanggan']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pelanggan tidak ditemukan');
        }

        return view('pelanggan/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'alamat' => 'required|min_length[3]',
            'no_telepon' => 'required|min_length[10]|max_length[15]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'alamat' => $this->request->getPost('alamat'),
            'no_telepon' => $this->request->getPost('no_telepon')
        ];

        $this->pelangganModel->update($id, $data);
        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil diupdate');
    }

    public function delete($id)
    {
        $this->pelangganModel->delete($id);
        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil dihapus');
    }
}

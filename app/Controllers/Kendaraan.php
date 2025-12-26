<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KendaraanModel;
use App\Models\PelangganModel;

class Kendaraan extends BaseController
{
    protected $kendaraanModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->kendaraanModel = new KendaraanModel();
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->kendaraanModel
            ->select('kendaraan.*, pelanggan.nama as nama_pemilik')
            ->join('pelanggan', 'pelanggan.id = kendaraan.pelanggan_id');
        
        if ($search) {
            $query->groupStart()
                  ->like('kendaraan.nomor_plat', $search)
                  ->orLike('kendaraan.merk', $search)
                  ->orLike('pelanggan.nama', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Data Kendaraan',
            'kendaraan' => $query->paginate(10),
            'pager' => $this->kendaraanModel->pager,
            'search' => $search
        ];

        return view('kendaraan/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kendaraan',
            'pelanggan' => $this->pelangganModel->findAll()
        ];

        return view('kendaraan/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'pelanggan_id' => 'required|integer',
            'nomor_plat' => 'required|is_unique[kendaraan.nomor_plat]',
            'merk' => 'required|min_length[2]',
            'tipe' => 'required|min_length[2]',
            'tahun' => 'required|integer|min_length[4]|max_length[4]',
            'foto' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $foto = $this->request->getFile('foto');
        $fotoName = $foto->getRandomName();
        $foto->move('uploads/kendaraan', $fotoName);

        $data = [
            'pelanggan_id' => $this->request->getPost('pelanggan_id'),
            'nomor_plat' => strtoupper($this->request->getPost('nomor_plat')),
            'merk' => $this->request->getPost('merk'),
            'tipe' => $this->request->getPost('tipe'),
            'tahun' => $this->request->getPost('tahun'),
            'foto' => $fotoName
        ];

        $this->kendaraanModel->insert($data);
        return redirect()->to('/kendaraan')->with('success', 'Data kendaraan berhasil ditambahkan');
    }

    public function show($id)
    {
        $kendaraan = $this->kendaraanModel
            ->select('kendaraan.*, pelanggan.nama as nama_pemilik, pelanggan.alamat, pelanggan.no_telepon')
            ->join('pelanggan', 'pelanggan.id = kendaraan.pelanggan_id')
            ->find($id);

        if (!$kendaraan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kendaraan tidak ditemukan');
        }

        // Get riwayat servis
        $penerimaanModel = new \App\Models\PenerimaanServisModel();
        $riwayatServis = $penerimaanModel
            ->where('kendaraan_id', $id)
            ->orderBy('tanggal_masuk', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Detail Kendaraan',
            'kendaraan' => $kendaraan,
            'riwayat_servis' => $riwayatServis
        ];

        return view('kendaraan/show', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Kendaraan',
            'kendaraan' => $this->kendaraanModel->find($id),
            'pelanggan' => $this->pelangganModel->findAll()
        ];

        if (!$data['kendaraan']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kendaraan tidak ditemukan');
        }

        return view('kendaraan/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'pelanggan_id' => 'required|integer',
            'nomor_plat' => "required|is_unique[kendaraan.nomor_plat,id,$id]",
            'merk' => 'required|min_length[2]',
            'tipe' => 'required|min_length[2]',
            'tahun' => 'required|integer|min_length[4]|max_length[4]',
            'foto' => 'max_size[foto,2048]|is_image[foto]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $kendaraan = $this->kendaraanModel->find($id);
        $data = [
            'pelanggan_id' => $this->request->getPost('pelanggan_id'),
            'nomor_plat' => strtoupper($this->request->getPost('nomor_plat')),
            'merk' => $this->request->getPost('merk'),
            'tipe' => $this->request->getPost('tipe'),
            'tahun' => $this->request->getPost('tahun')
        ];

        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid()) {
            // Hapus foto lama
            if ($kendaraan['foto'] && file_exists('uploads/kendaraan/' . $kendaraan['foto'])) {
                unlink('uploads/kendaraan/' . $kendaraan['foto']);
            }
            
            $fotoName = $foto->getRandomName();
            $foto->move('uploads/kendaraan', $fotoName);
            $data['foto'] = $fotoName;
        }

        $this->kendaraanModel->update($id, $data);
        return redirect()->to('/kendaraan')->with('success', 'Data kendaraan berhasil diupdate');
    }

    public function delete($id)
    {
        $kendaraan = $this->kendaraanModel->find($id);
        
        if ($kendaraan && $kendaraan['foto']) {
            if (file_exists('uploads/kendaraan/' . $kendaraan['foto'])) {
                unlink('uploads/kendaraan/' . $kendaraan['foto']);
            }
        }

        $this->kendaraanModel->delete($id);
        return redirect()->to('/kendaraan')->with('success', 'Data kendaraan berhasil dihapus');
    }
}
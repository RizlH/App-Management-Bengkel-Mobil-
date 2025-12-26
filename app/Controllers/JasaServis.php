<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JasaServisModel;

class JasaServis extends BaseController
{
    protected $jasaServisModel;

    public function __construct()
    {
        $this->jasaServisModel = new JasaServisModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->jasaServisModel;
        
        if ($search) {
            $query->groupStart()
                  ->like('nama_layanan', $search)
                  ->orLike('kategori_servis', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Data Jasa Servis',
            'jasa_servis' => $query->paginate(10),
            'pager' => $this->jasaServisModel->pager,
            'search' => $search
        ];

        return view('jasa_servis/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Jasa Servis'];
        return view('jasa_servis/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama_layanan' => 'required|min_length[3]',
            'harga_jasa' => 'required|decimal',
            'kategori_servis' => 'required|in_list[Ringan,Berat,Tune Up,Cuci,Lainnya]',
            'estimasi_durasi' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_layanan' => $this->request->getPost('nama_layanan'),
            'harga_jasa' => $this->request->getPost('harga_jasa'),
            'kategori_servis' => $this->request->getPost('kategori_servis'),
            'estimasi_durasi' => $this->request->getPost('estimasi_durasi')
        ];

        $this->jasaServisModel->insert($data);
        return redirect()->to('/jasa-servis')->with('success', 'Data jasa servis berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Jasa Servis',
            'jasa_servis' => $this->jasaServisModel->find($id)
        ];

        if (!$data['jasa_servis']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Jasa servis tidak ditemukan');
        }

        return view('jasa_servis/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama_layanan' => 'required|min_length[3]',
            'harga_jasa' => 'required|decimal',
            'kategori_servis' => 'required|in_list[Ringan,Berat,Tune Up,Cuci,Lainnya]',
            'estimasi_durasi' => 'required|integer'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_layanan' => $this->request->getPost('nama_layanan'),
            'harga_jasa' => $this->request->getPost('harga_jasa'),
            'kategori_servis' => $this->request->getPost('kategori_servis'),
            'estimasi_durasi' => $this->request->getPost('estimasi_durasi')
        ];

        $this->jasaServisModel->update($id, $data);
        return redirect()->to('/jasa-servis')->with('success', 'Data jasa servis berhasil diupdate');
    }

    public function delete($id)
    {
        $this->jasaServisModel->delete($id);
        return redirect()->to('/jasa-servis')->with('success', 'Data jasa servis berhasil dihapus');
    }
}

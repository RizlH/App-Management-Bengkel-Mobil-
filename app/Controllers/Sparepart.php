<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SparepartModel;

class Sparepart extends BaseController
{
    protected $sparepartModel;

    public function __construct()
    {
        $this->sparepartModel = new SparepartModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->sparepartModel;
        
        if ($search) {
            $query->groupStart()
                  ->like('nama_barang', $search)
                  ->orLike('kategori', $search)
                  ->orLike('pemasok', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Data Sparepart',
            'sparepart' => $query->paginate(10),
            'pager' => $this->sparepartModel->pager,
            'search' => $search
        ];

        return view('sparepart/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Sparepart',
            'errors' => session()->getFlashdata('errors') ?? []
        ];
        return view('sparepart/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama_barang' => 'required|min_length[3]',
            'kategori' => 'required|min_length[2]',
            'pemasok' => 'required|min_length[3]',
            'kontak_pemasok' => 'required|min_length[10]',
            'alamat_pemasok' => 'required|min_length[3]',
            'harga_beli' => 'required|decimal',
            'harga_jual' => 'required|decimal',
            'stok' => 'required|integer',
            'satuan' => 'required|min_length[2]'
        ], [
            'pemasok' => [
                'required' => 'Nama pemasok harus diisi',
                'min_length' => 'Nama pemasok minimal 3 karakter'
            ],
            'kontak_pemasok' => [
                'required' => 'Kontak pemasok harus diisi',
                'min_length' => 'Kontak pemasok minimal 10 karakter'
            ],
            'alamat_pemasok' => [
                'required' => 'Alamat pemasok harus diisi',
                'min_length' => 'Alamat pemasok minimal 3 karakter'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori' => $this->request->getPost('kategori'),
            'pemasok' => $this->request->getPost('pemasok'),
            'kontak_pemasok' => $this->request->getPost('kontak_pemasok'),
            'alamat_pemasok' => $this->request->getPost('alamat_pemasok'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'stok' => $this->request->getPost('stok'),
            'satuan' => $this->request->getPost('satuan')
        ];

        try {
            $this->sparepartModel->insert($data);
            return redirect()->to('/sparepart')->with('success', 'Data sparepart berhasil ditambahkan');
        } catch (\Exception $e) {
            log_message('error', 'Error insert sparepart: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $sparepart = $this->sparepartModel->find($id);

        if (!$sparepart) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Sparepart',
            'sparepart' => $sparepart
        ];

        return view('sparepart/show', $data);
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Sparepart',
            'sparepart' => $this->sparepartModel->find($id),
            'errors' => session()->getFlashdata('errors') ?? []
        ];

        if (!$data['sparepart']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data tidak ditemukan');
        }

        return view('sparepart/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama_barang' => 'required|min_length[3]',
            'kategori' => 'required|min_length[2]',
            'pemasok' => 'required|min_length[3]',
            'kontak_pemasok' => 'required|min_length[10]',
            'alamat_pemasok' => 'required|min_length[3]',
            'harga_beli' => 'required|decimal',
            'harga_jual' => 'required|decimal',
            'stok' => 'required|integer',
            'satuan' => 'required|min_length[2]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori' => $this->request->getPost('kategori'),
            'pemasok' => $this->request->getPost('pemasok'),
            'kontak_pemasok' => $this->request->getPost('kontak_pemasok'),
            'alamat_pemasok' => $this->request->getPost('alamat_pemasok'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'stok' => $this->request->getPost('stok'),
            'satuan' => $this->request->getPost('satuan')
        ];

        $this->sparepartModel->update($id, $data);
        return redirect()->to('/sparepart')->with('success', 'Data sparepart berhasil diupdate');
    }

    public function delete($id)
    {
        $this->sparepartModel->delete($id);
        return redirect()->to('/sparepart')->with('success', 'Data sparepart berhasil dihapus');
    }
}


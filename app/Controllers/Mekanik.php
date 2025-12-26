<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MekanikModel;

class Mekanik extends BaseController
{
    protected $mekanikModel;

    public function __construct()
    {
        $this->mekanikModel = new MekanikModel();
    }

    public function index()
    {
        $search = $this->request->getGet('search');
        
        $query = $this->mekanikModel;
        
        if ($search) {
            $query->groupStart()
                  ->like('nama', $search)
                  ->orLike('posisi', $search)
                  ->groupEnd();
        }

        $data = [
            'title' => 'Data Mekanik',
            'mekanik' => $query->paginate(10),
            'pager' => $this->mekanikModel->pager,
            'search' => $search
        ];

        return view('mekanik/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Mekanik'];
        return view('mekanik/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'posisi' => 'required|in_list[Mesin,Kelistrikan,Ban,Umum]',
            'kontak' => 'required|min_length[10]',
            'level_skill' => 'required|in_list[Junior,Senior,Expert]',
            'status_aktif' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'posisi' => $this->request->getPost('posisi'),
            'kontak' => $this->request->getPost('kontak'),
            'level_skill' => $this->request->getPost('level_skill'),
            'status_aktif' => $this->request->getPost('status_aktif')
        ];

        $this->mekanikModel->insert($data);
        return redirect()->to('/mekanik')->with('success', 'Data mekanik berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Mekanik',
            'mekanik' => $this->mekanikModel->find($id)
        ];

        if (!$data['mekanik']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Mekanik tidak ditemukan');
        }

        return view('mekanik/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nama' => 'required|min_length[3]',
            'posisi' => 'required|in_list[Mesin,Kelistrikan,Ban,Umum]',
            'kontak' => 'required|min_length[10]',
            'level_skill' => 'required|in_list[Junior,Senior,Expert]',
            'status_aktif' => 'required|in_list[0,1]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'posisi' => $this->request->getPost('posisi'),
            'kontak' => $this->request->getPost('kontak'),
            'level_skill' => $this->request->getPost('level_skill'),
            'status_aktif' => $this->request->getPost('status_aktif')
        ];

        $this->mekanikModel->update($id, $data);
        return redirect()->to('/mekanik')->with('success', 'Data mekanik berhasil diupdate');
    }

    public function delete($id)
    {
        $this->mekanikModel->delete($id);
        return redirect()->to('/mekanik')->with('success', 'Data mekanik berhasil dihapus');
    }
}

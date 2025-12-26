<?php

namespace App\Models;

use CodeIgniter\Model;

class SparepartModel extends Model
{
    protected $table = 'sparepart';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_barang', 
        'kategori', 
        'pemasok',          
        'kontak_pemasok',   
        'alamat_pemasok',  
        'harga_beli', 
        'harga_jual', 
        'stok', 
        'satuan'
    ];
    protected $useTimestamps = true;
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanSparepartModel extends Model
{
    protected $table = 'penjualan_sparepart';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nomor_penjualan', 'nama_pembeli', 'tanggal_penjualan', 'total_penjualan'];
    protected $useTimestamps = true;
}
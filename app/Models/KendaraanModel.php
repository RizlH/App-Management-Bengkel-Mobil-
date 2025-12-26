<?php

namespace App\Models;

use CodeIgniter\Model;

class KendaraanModel extends Model
{
    protected $table = 'kendaraan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pelanggan_id', 'nomor_plat', 'merk', 'tipe', 'tahun', 'foto'];
    protected $useTimestamps = true;
    
    protected $returnType = 'array';
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class JasaServisModel extends Model
{
    protected $table = 'jasa_servis';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_layanan', 'harga_jasa', 'kategori_servis', 'estimasi_durasi'];
    protected $useTimestamps = true;
}

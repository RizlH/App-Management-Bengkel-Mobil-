<?php

namespace App\Models;

use CodeIgniter\Model;

class PenerimaanServisModel extends Model
{
    protected $table = 'penerimaan_servis';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nomor_servis', 'pelanggan_id', 'kendaraan_id', 'keluhan', 'estimasi_biaya', 'status', 'tanggal_masuk', 'tanggal_selesai'];
    protected $useTimestamps = true;
}
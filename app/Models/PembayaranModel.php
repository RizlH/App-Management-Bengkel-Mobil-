<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nomor_invoice', 'penerimaan_servis_id', 'total_jasa', 'total_sparepart', 'total_biaya', 'metode_pembayaran', 'status_bayar', 'tanggal_bayar'];
    protected $useTimestamps = true;
}
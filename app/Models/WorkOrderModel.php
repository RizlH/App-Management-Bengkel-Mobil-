<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkOrderModel extends Model
{
    protected $table = 'work_order';
    protected $primaryKey = 'id';
    protected $allowedFields = ['penerimaan_servis_id', 'mekanik_id', 'progres', 'status', 'tanggal_mulai', 'tanggal_selesai'];
    protected $useTimestamps = true;
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class MekanikModel extends Model
{
    protected $table = 'mekanik';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'posisi', 'kontak', 'level_skill', 'status_aktif'];
    protected $useTimestamps = true;
}
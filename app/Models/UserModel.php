<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'security_answer', 'full_name', 'reset_token', 'reset_token_expire'];
    protected $useTimestamps = true;
}
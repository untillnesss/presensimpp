<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id_user';
    protected $returnType = 'array';

    protected $allowedFields = [
        'email', 'password', 'role', 'is_active', 'email_verified', 'created_at', 'device_token'
    ];
}

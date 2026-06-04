<?php

namespace App\Models;

use CodeIgniter\Model;

class InstansiModel extends Model
{
    protected $table = 'instansi';
    protected $primaryKey = 'id_instansi';

    protected $allowedFields = [
        'nama_instansi',
        'status_aktif'
    ];
}
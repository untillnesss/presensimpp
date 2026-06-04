<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table      = 'data_presensi';
    protected $primaryKey = 'id_presensi';

    protected $allowedFields = [
        'id_user',
        'id_instansi',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'foto_masuk',
        'foto_pulang',
        'latitude',
        'longitude',
        'status',
        'keterlambatan', // ← ini yang kurang!
        'status_pulang',
        'sumber'
    ];

    protected $useTimestamps = false;
}
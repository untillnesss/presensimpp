<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingAbsenModel extends Model
{
    protected $table      = 'setting_absen';
    protected $primaryKey = 'id_setting';

    protected $allowedFields = [
        'jam_masuk_mulai',
        'jam_masuk_selesai',
        'batas_terlambat',
        'jam_pulang_mulai',
        'jam_pulang_selesai',
        'latitude',
        'longitude',
        'radius',
        'update_at',
    ];
}

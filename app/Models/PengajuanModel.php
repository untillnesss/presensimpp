<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table      = 'pengajuan';
    protected $primaryKey = 'id_pengajuan';

    protected $allowedFields = [
        'id_user',
        'id_instansi', // 🔥 INI YANG WAJIB ADA (FIX UTAMA)
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis',
        'keterangan',
        'status_pengajuan',
        'file_bukti',
        'created_at'
    ];

    protected $useTimestamps = false;
}
<?php
namespace App\Models;

use CodeIgniter\Model;

class OtpModel extends Model
{
    protected $table = 'otp';
    protected $primaryKey = 'id_otp';
    protected $allowedFields = [
        'id_user',
        'kode_otp',
        'expired_at',
        'is_used'
    ];
}
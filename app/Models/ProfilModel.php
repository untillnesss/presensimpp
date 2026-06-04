<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table      = 'profil';
    protected $primaryKey = 'id_profil';

    protected $allowedFields = [
        'id_user',
        'no_id',
        'nama',
        'jabatan',
        'id_instansi',
        'foto',
        'update_at',
    ];

    // ── Cek apakah no_id sudah dipakai pegawai lain ───────────────────────────
    public function isNoIdTaken(string $noId, ?int $excludeIdUser = null): bool
    {
        $builder = $this->where('no_id', $noId);
        if ($excludeIdUser !== null) {
            $builder->where('id_user !=', $excludeIdUser);
        }
        return $builder->countAllResults() > 0;
    }
}

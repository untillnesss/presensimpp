<?php

use App\Models\LogModel;
use App\Models\ProfilModel;

if (!function_exists('logAktivitas')) {
    function logAktivitas($aksi, $keterangan = null)
    {
        $session = session();

        $idUser = $session->get('id_user');

        if (!$idUser) {
            return; // biar tidak error
        }

        // 🔥 AMBIL NAMA DARI TABEL PROFIL
        $profilModel = new ProfilModel();
        $profil = $profilModel->where('id_user', $idUser)->first();

        $nama = $profil['nama'] ?? 'Tidak diketahui';

        $data = [
            'id_user'    => $idUser,
            'nama_user'  => $nama,
            'role'       => $session->get('role'),
            'aksi'       => $aksi,
            'keterangan' => $keterangan,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $logModel = new LogModel();
        $logModel->insert($data);
    }
}
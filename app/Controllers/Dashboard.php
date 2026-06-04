<?php

namespace App\Controllers;

use App\Models\ProfilModel;
use App\Models\PresensiModel;
use App\Models\SettingAbsenModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $idUser = session()->get('id_user');
        $today  = date('Y-m-d');

        $profilModel   = new ProfilModel();
        $presensiModel = new PresensiModel();
        $settingModel  = new SettingAbsenModel();

        $profil = $profilModel
            ->select('profil.*, instansi.nama_instansi')
            ->join('instansi', 'instansi.id_instansi = profil.id_instansi', 'left')
            ->where('profil.id_user', $idUser)
            ->first();

        $setting = $settingModel->first();

        $presensiHariIni = $presensiModel
            ->where('id_user', $idUser)
            ->where('tanggal', $today)
            ->first();

        // Ambil menit terlambat langsung dari kolom keterlambatan di database
        // Kalau NULL, hitung manual dari jam_masuk vs jam_masuk_selesai
        $menitTerlambat = 0;
        if ($presensiHariIni && $presensiHariIni['status'] === 'terlambat') {
            if (!empty($presensiHariIni['keterlambatan'])) {
                $menitTerlambat = (int) $presensiHariIni['keterlambatan'];
            } else {
                $selisih = strtotime($presensiHariIni['jam_masuk']) - strtotime($setting['jam_masuk_selesai']);
                if ($selisih > 0) {
                    $menitTerlambat = (int) ceil($selisih / 60);
                }
            }
        }

        return view('dashboard/index', [
            'profil'          => $profil,
            'setting'         => $setting,
            'presensiHariIni' => $presensiHariIni,
            'menitTerlambat'  => $menitTerlambat,
        ]);
    }
}
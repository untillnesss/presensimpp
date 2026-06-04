<?php

namespace App\Controllers\Sekretariat;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use App\Models\InstansiModel;
use App\Models\ProfilModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $presensiModel = new PresensiModel();
        $instansiModel = new InstansiModel();
        $profilModel   = new ProfilModel();

        $hariIni = date('Y-m-d');
        $idUser  = session()->get('id_user');

        $profil = $profilModel->where('id_user', $idUser)->first();
        if ($profil) {
            session()->set('foto_profil', $profil['foto']);
            session()->set('nama', $profil['nama']);
            session()->set('jabatan', $profil['jabatan']);
        }

        $semuaInstansi    = $instansiModel->where('status_aktif', 1)->findAll();

        // Hitung hadir: hanya instansi yang punya presensi dengan status 'hadir' atau 'terlambat'
        $hadirResult      = $presensiModel->select('id_instansi')
                                ->where('tanggal', $hariIni)
                                ->whereIn('status', ['hadir', 'terlambat'])
                                ->groupBy('id_instansi')
                                ->findAll();
        $hadirIds         = array_column($hadirResult, 'id_instansi');
        $jumlahHadir      = count($hadirIds);
        $jumlahTidakHadir = count($semuaInstansi) - $jumlahHadir;

        $jumlahTerlambat  = $presensiModel->where('tanggal', $hariIni)->where('status', 'terlambat')->countAllResults();
        $jumlahIzin       = $presensiModel->where('tanggal', $hariIni)->where('status', 'izin')->countAllResults();
        $jumlahSakit      = $presensiModel->where('tanggal', $hariIni)->where('status', 'sakit')->countAllResults();

        return view('sekretariat/dashboard', [
            'jumlahHadir'      => $jumlahHadir,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'jumlahTerlambat'  => $jumlahTerlambat,
            'jumlahIzin'       => $jumlahIzin,
            'jumlahSakit'      => $jumlahSakit,
        ]);
    }

    // ================= DETAIL PER KATEGORI =================
    public function detail($kategori)
    {
        $presensiModel = new PresensiModel();
        $instansiModel = new InstansiModel();
        $hariIni       = date('Y-m-d');

        $judul = ucfirst($kategori);
        $data  = [];

        if ($kategori == 'hadir') {
            $judul = 'Instansi Hadir';
            $data  = $presensiModel
                ->select('data_presensi.*, profil.nama, instansi.nama_instansi')
                ->join('profil', 'profil.id_user = data_presensi.id_user', 'left')
                ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi', 'left')
                ->where('tanggal', $hariIni)
                ->whereIn('status', ['hadir', 'terlambat'])
                ->findAll();

        } elseif ($kategori == 'tidak-hadir') {
            $judul    = 'Instansi Tidak Hadir';
            $hadir    = $presensiModel->select('id_instansi')
                            ->where('tanggal', $hariIni)
                            ->whereIn('status', ['hadir', 'terlambat'])
                            ->findAll();
            $hadirIds = array_column($hadir, 'id_instansi');
            $data     = !empty($hadirIds)
                ? $instansiModel->where('status_aktif', 1)->whereNotIn('id_instansi', $hadirIds)->findAll()
                : $instansiModel->where('status_aktif', 1)->findAll();

        } elseif ($kategori == 'terlambat') {
            $judul = 'Pegawai ' . ucfirst($kategori);
            $data  = $presensiModel
                ->select('data_presensi.*, profil.nama, instansi.nama_instansi')
                ->join('profil', 'profil.id_user = data_presensi.id_user', 'left')
                ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi', 'left')
                ->where('tanggal', $hariIni)
                ->where('status', $kategori)
                ->findAll();
        }

        return view('sekretariat/detail', [
            'judul'    => $judul,
            'kategori' => $kategori,
            'data'     => $data,
            'hariIni'  => $hariIni,
        ]);
    }
}
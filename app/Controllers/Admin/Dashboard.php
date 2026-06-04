<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use App\Models\InstansiModel;
use App\Models\PengajuanModel;
use App\Models\ProfilModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $presensiModel  = new PresensiModel();
        $instansiModel  = new InstansiModel();
        $pengajuanModel = new PengajuanModel();
        $profilModel    = new ProfilModel();

        $hariIni = date('Y-m-d');
        $idUser  = session()->get('id_user');

        $profil = $profilModel->where('id_user', $idUser)->first();
        if ($profil) {
            session()->set('foto_profil', $profil['foto']);
            session()->set('nama', $profil['nama']);
            session()->set('jabatan', $profil['jabatan']);
        }

        $semuaInstansi = $instansiModel->where('status_aktif', 1)->findAll();

        // HADIR = hanya instansi yang punya presensi status 'hadir' atau 'terlambat'
        // Izin/sakit/cuti TIDAK dihitung sebagai hadir
        $hadirResult = $presensiModel
            ->select('id_instansi')
            ->where('tanggal', $hariIni)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->groupBy('id_instansi')
            ->findAll();
        $hadirIds = array_column($hadirResult, 'id_instansi');

        $jumlahHadir      = count($hadirIds);
        $jumlahTidakHadir = count($semuaInstansi) - $jumlahHadir;

        $jumlahTerlambat = $presensiModel->where('tanggal', $hariIni)->where('status', 'terlambat')->countAllResults();
        $jumlahIzin      = $presensiModel->where('tanggal', $hariIni)->where('status', 'izin')->countAllResults();
        $jumlahSakit     = $presensiModel->where('tanggal', $hariIni)->where('status', 'sakit')->countAllResults();

        $jumlahPending   = $pengajuanModel->where('status_pengajuan', 'menunggu')->countAllResults();
        $jumlahDisetujui = $pengajuanModel->where('status_pengajuan', 'disetujui')->countAllResults();
        $jumlahDitolak   = $pengajuanModel->where('status_pengajuan', 'ditolak')->countAllResults();

        return view('admin/dashboard', [
            'jumlahHadir'      => $jumlahHadir,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'jumlahPending'    => $jumlahPending,
            'jumlahDisetujui'  => $jumlahDisetujui,
            'jumlahDitolak'    => $jumlahDitolak,
            'jumlahTerlambat'  => $jumlahTerlambat,
            'jumlahIzin'       => $jumlahIzin,
            'jumlahSakit'      => $jumlahSakit,
        ]);
    }

    public function hadir()
    {
        $model   = new PresensiModel();
        $hariIni = date('Y-m-d');
        // Hanya instansi dengan status hadir atau terlambat
        $data = $model
            ->select('instansi.nama_instansi')
            ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi')
            ->where('tanggal', $hariIni)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->groupBy('data_presensi.id_instansi')
            ->findAll();
        return view('admin/hadir', ['data' => $data]);
    }

    public function tidakHadir()
    {
        $presensiModel = new PresensiModel();
        $instansiModel = new InstansiModel();
        $hariIni       = date('Y-m-d');

        // Instansi yang HADIR = hanya yang punya status hadir/terlambat
        $hadir    = $presensiModel
            ->select('id_instansi')
            ->where('tanggal', $hariIni)
            ->whereIn('status', ['hadir', 'terlambat'])
            ->findAll();
        $hadirIds = array_column($hadir, 'id_instansi');

        if (!empty($hadirIds)) {
            $data = $instansiModel->where('status_aktif', 1)->whereNotIn('id_instansi', $hadirIds)->findAll();
        } else {
            $data = $instansiModel->where('status_aktif', 1)->findAll();
        }
        return view('admin/tidak_hadir', ['data' => $data]);
    }

    public function detail($kategori)
    {
        $presensiModel = new PresensiModel();
        $instansiModel = new InstansiModel();
        $hariIni       = date('Y-m-d');

        $data  = [];
        $judul = ucfirst($kategori);

        // Handle semua kategori pegawai: terlambat, izin, sakit, dll.
        if (in_array($kategori, ['terlambat', 'izin', 'sakit', 'alpha'])) {
            $judul = 'Pegawai ' . ucfirst($kategori);
            $data  = $presensiModel
                ->select('data_presensi.*, profil.nama, instansi.nama_instansi')
                ->join('profil',   'profil.id_user = data_presensi.id_user', 'left')
                ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi', 'left')
                ->where('tanggal', $hariIni)
                ->where('status', $kategori)
                ->findAll();
        }

        return view('admin/dashboard_detail', [
            'title'    => $judul,
            'judul'    => $judul,
            'kategori' => $kategori,
            'data'     => $data,
            'hariIni'  => $hariIni,
        ]);
    }

    public function pengajuan()
    {
        $model = new PengajuanModel();
        $data  = $model
            ->select('pengajuan.*, profil.nama, instansi.nama_instansi')
            ->join('profil', 'profil.id_user = pengajuan.id_user')
            ->join('instansi', 'instansi.id_instansi = pengajuan.id_instansi')
            ->where('status_pengajuan', 'menunggu')
            ->orderBy('created_at', 'DESC')
            ->findAll();
        return view('admin/pengajuan/index', ['data' => $data]);
    }
}
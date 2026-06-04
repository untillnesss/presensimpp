<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\PresensiModel;
use App\Models\ProfilModel;

class Pengajuan extends BaseController
{
    protected $model;
    protected $presensiModel;
    protected $profilModel;

    public function __construct()
    {
        helper('log'); // 🔥 aktifkan helper
        $this->model = new PengajuanModel();
        $this->presensiModel = new PresensiModel();
        $this->profilModel = new ProfilModel();
    }

    public function index()
    {
        $data = $this->model
            ->select('pengajuan.*, profil.nama, instansi.nama_instansi')
            ->join('profil', 'profil.id_user = pengajuan.id_user')
            ->join('instansi', 'instansi.id_instansi = pengajuan.id_instansi')
            ->orderBy("
                CASE 
                    WHEN status_pengajuan = 'menunggu' THEN 1
                    WHEN status_pengajuan = 'disetujui' THEN 2
                    WHEN status_pengajuan = 'ditolak' THEN 3
                END
            ", 'ASC')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/pengajuan/index', ['data' => $data]);
    }

    public function acc($id)
    {
        $pengajuan = $this->model->find($id);

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $profil = $this->profilModel->where('id_user', $pengajuan['id_user'])->first();
        $nama = $profil['nama'] ?? 'User';

        $this->model->update($id, [
            'status_pengajuan' => 'disetujui'
        ]);

        $current = strtotime($pengajuan['tanggal_mulai']);
        $end     = strtotime($pengajuan['tanggal_selesai']);

        while ($current <= $end) {

            $tanggal = date('Y-m-d', $current);

            $cek = $this->presensiModel
                ->where('id_user', $pengajuan['id_user'])
                ->where('tanggal', $tanggal)
                ->first();

            if (!$cek) {
                $this->presensiModel->insert([
                    'id_user'     => $pengajuan['id_user'],
                    'id_instansi' => $pengajuan['id_instansi'],
                    'tanggal'     => $tanggal,
                    'status'      => $pengajuan['jenis'],
                    'created_at'  => date('Y-m-d H:i:s')
                ]);
            }

            $current = strtotime('+1 day', $current);
        }

        // 🔥 LOG
        logAktivitas('ACC Pengajuan', 'Menyetujui pengajuan: ' . $nama);

        return redirect()->back()->with('success', 'Pengajuan disetujui');
    }

    public function tolak($id)
    {
        $pengajuan = $this->model->find($id);

        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $profil = $this->profilModel->where('id_user', $pengajuan['id_user'])->first();
        $nama = $profil['nama'] ?? 'User';

        $this->model->update($id, [
            'status_pengajuan' => 'ditolak'
        ]);

        // 🔥 LOG
        logAktivitas('Tolak Pengajuan', 'Menolak pengajuan: ' . $nama);

        return redirect()->back()->with('success', 'Pengajuan ditolak');
    }
}
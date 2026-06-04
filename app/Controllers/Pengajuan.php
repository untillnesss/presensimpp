<?php

namespace App\Controllers;

use App\Models\PengajuanModel;
use App\Models\ProfilModel;

class Pengajuan extends BaseController
{
    // ===============================
    // LIST DATA PENGAJUAN USER
    // ===============================
    public function index()
    {
        $idUser = session()->get('id_user');

        if (!$idUser) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu');
        }

        $model = new PengajuanModel();

        $data['pengajuan'] = $model
            ->where('id_user', $idUser)
            ->orderBy('id_pengajuan', 'DESC')
            ->findAll();

        return view('pengajuan/index', $data);
    }

    // ===============================
    // FORM TAMBAH PENGAJUAN
    // ===============================
    public function tambah()
    {
        if (!session()->get('id_user')) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu');
        }

        return view('pengajuan/tambah');
    }

    // ===============================
    // SIMPAN PENGAJUAN
    // ===============================
    public function simpan()
    {
        $idUser = session()->get('id_user');

        if (!$idUser) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu');
        }

        $model   = new PengajuanModel();
        $hariIni = date('Y-m-d');

        // ====================================================
        // VALIDASI TANGGAL: tidak boleh pilih tanggal masa lalu
        // ====================================================
        $mulai   = $this->request->getPost('mulai');
        $selesai = $this->request->getPost('selesai');

        if (!empty($mulai) && $mulai < $hariIni) {
            return redirect()->back()
                ->with('error', 'Tanggal mulai tidak boleh tanggal yang sudah lewat. Pilih hari ini atau ke depan.')
                ->withInput();
        }

        if (!empty($selesai) && $selesai < $hariIni) {
            return redirect()->back()
                ->with('error', 'Tanggal selesai tidak boleh tanggal yang sudah lewat. Pilih hari ini atau ke depan.')
                ->withInput();
        }

        if (!empty($mulai) && !empty($selesai) && $selesai < $mulai) {
            return redirect()->back()
                ->with('error', 'Tanggal selesai tidak boleh sebelum tanggal mulai.')
                ->withInput();
        }

        // CEK SUDAH ADA PENGAJUAN HARI INI (limit 1/hari)
        $cek = $model
            ->where('id_user', $idUser)
            ->where('DATE(created_at)', $hariIni)
            ->first();

        if ($cek) {
            return redirect()->back()
                ->with('error', 'Kamu hanya bisa 1 pengajuan per hari!')
                ->withInput();
        }

        // Ambil profil user
        $profil = (new ProfilModel())
            ->where('id_user', $idUser)
            ->first();

        if (!$profil) {
            return redirect()->back()
                ->with('error', 'Profil tidak ditemukan')
                ->withInput();
        }

        $idInstansi = (int) ($profil['id_instansi'] ?? 0);

        if ($idInstansi <= 0) {
            return redirect()->back()
                ->with('error', 'Instansi belum diatur di profil')
                ->withInput();
        }

        $db = \Config\Database::connect();
        $cekInstansi = $db->table('instansi')
            ->where('id_instansi', $idInstansi)
            ->countAllResults();

        if ($cekInstansi == 0) {
            return redirect()->back()
                ->with('error', 'Instansi tidak ditemukan')
                ->withInput();
        }

        $jenis      = $this->request->getPost('jenis');
        $keterangan = $this->request->getPost('keterangan');

        if (empty($mulai) || empty($selesai) || empty($jenis)) {
            return redirect()->back()
                ->with('error', 'Semua field wajib diisi')
                ->withInput();
        }

        $fileBukti     = $this->request->getFile('file_bukti');
        $namaFileBukti = null;

        if (!$fileBukti || !$fileBukti->isValid() || $fileBukti->hasMoved()) {
            return redirect()->back()
                ->with('error', 'File bukti wajib diupload!')
                ->withInput();
        }

        $ekstensiBoleh = ['jpg', 'jpeg', 'png', 'pdf'];
        if (!in_array(strtolower($fileBukti->getExtension()), $ekstensiBoleh)) {
            return redirect()->back()
                ->with('error', 'Format file bukti harus JPG, PNG, atau PDF!')
                ->withInput();
        }

        if (!is_dir(FCPATH . 'uploads/pengajuan/')) {
            mkdir(FCPATH . 'uploads/pengajuan/', 0775, true);
        }

        $namaFileBukti = 'bukti_' . time() . '_' . random_int(100, 999) . '.' . $fileBukti->getExtension();
        $fileBukti->move(FCPATH . 'uploads/pengajuan/', $namaFileBukti);

        try {
            $model->insert([
                'id_user'          => (int) $idUser,
                'id_instansi'      => $idInstansi,
                'tanggal_mulai'    => $mulai,
                'tanggal_selesai'  => $selesai,
                'jenis'            => $jenis,
                'keterangan'       => $keterangan,
                'file_bukti'       => $namaFileBukti,
                'status_pengajuan' => 'menunggu',
                'created_at'       => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal simpan: ' . $e->getMessage())
                ->withInput();
        }

        return redirect()->to('/pengajuan')
            ->with('success', 'Pengajuan berhasil disimpan');
    }

    // ===============================
    // FORM EDIT (HANYA MENUNGGU)
    // ===============================
    public function edit($id)
    {
        $model = new \App\Models\PengajuanModel();
        $data  = $model->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($data['id_user'] != session()->get('id_user')) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        if (strtolower($data['status_pengajuan']) !== 'menunggu') {
            return redirect()->back()->with('error', 'Tidak bisa edit, sudah diproses');
        }

        return view('pengajuan/edit', ['data' => $data]);
    }

    // ===============================
    // UPDATE DATA
    // ===============================
    public function update($id)
    {
        $model = new \App\Models\PengajuanModel();
        $data  = $model->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($data['id_user'] != session()->get('id_user')) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        if (strtolower($data['status_pengajuan']) !== 'menunggu') {
            return redirect()->back()->with('error', 'Tidak bisa edit');
        }

        $hariIni = date('Y-m-d');
        $mulai   = $this->request->getPost('mulai');
        $selesai = $this->request->getPost('selesai');

        // ====================================================
        // VALIDASI TANGGAL pada edit: tidak boleh masa lalu
        // ====================================================
        if (!empty($mulai) && $mulai < $hariIni) {
            return redirect()->back()
                ->with('error', 'Tanggal mulai tidak boleh tanggal yang sudah lewat.')
                ->withInput();
        }

        if (!empty($selesai) && $selesai < $hariIni) {
            return redirect()->back()
                ->with('error', 'Tanggal selesai tidak boleh tanggal yang sudah lewat.')
                ->withInput();
        }

        if (!empty($mulai) && !empty($selesai) && $selesai < $mulai) {
            return redirect()->back()
                ->with('error', 'Tanggal selesai tidak boleh sebelum tanggal mulai.')
                ->withInput();
        }

        $jenis      = $this->request->getPost('jenis');
        $keterangan = $this->request->getPost('keterangan');

        if (empty($mulai) || empty($selesai) || empty($jenis)) {
            return redirect()->back()
                ->with('error', 'Semua field wajib diisi')
                ->withInput();
        }

        $updateData = [
            'tanggal_mulai'   => $mulai,
            'tanggal_selesai' => $selesai,
            'jenis'           => $jenis,
            'keterangan'      => $keterangan,
        ];

        $fileBukti = $this->request->getFile('file_bukti');
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $ekstensiBoleh = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array(strtolower($fileBukti->getExtension()), $ekstensiBoleh)) {
                return redirect()->back()
                    ->with('error', 'Format file bukti harus JPG, PNG, atau PDF!')
                    ->withInput();
            }
            if (!is_dir(FCPATH . 'uploads/pengajuan/')) {
                mkdir(FCPATH . 'uploads/pengajuan/', 0775, true);
            }
            $namaFileBukti = 'bukti_' . time() . '_' . random_int(100, 999) . '.' . $fileBukti->getExtension();
            $fileBukti->move(FCPATH . 'uploads/pengajuan/', $namaFileBukti);
            $updateData['file_bukti'] = $namaFileBukti;
        }

        $model->update($id, $updateData);

        return redirect()->to('/pengajuan')
            ->with('success', 'Pengajuan berhasil diupdate');
    }

    // ===============================
    // DELETE DATA (HANYA MENUNGGU)
    // ===============================
    public function delete($id)
    {
        $model = new \App\Models\PengajuanModel();
        $data  = $model->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        if ($data['id_user'] != session()->get('id_user')) {
            return redirect()->back()->with('error', 'Akses ditolak');
        }

        if (strtolower($data['status_pengajuan']) !== 'menunggu') {
            return redirect()->back()->with('error', 'Hanya pengajuan menunggu yang bisa dihapus');
        }

        $model->delete($id);

        return redirect()->back()->with('success', 'Pengajuan berhasil dihapus');
    }
}
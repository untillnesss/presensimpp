<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\ProfilModel;
use App\Models\InstansiModel;

class Pegawai extends BaseController
{
    protected $userModel;
    protected $profilModel;
    protected $instansiModel;

    public function __construct()
    {
        helper('log');
        $this->userModel     = new UserModel();
        $this->profilModel   = new ProfilModel();
        $this->instansiModel = new InstansiModel();
    }

    // ── Halaman utama: daftar pegawai + menunggu + perangkat ─────
    public function index()
    {
        // Semua pegawai aktif
        $pegawai = $this->profilModel
            ->select('profil.*, user.email, user.is_active, user.device_token, instansi.nama_instansi')
            ->join('user', 'user.id_user = profil.id_user', 'left')
            ->join('instansi', 'instansi.id_instansi = profil.id_instansi', 'left')
            ->where('user.role', 'pegawai')
            ->where('user.is_active', 1)
            ->orderBy('profil.nama', 'ASC')
            ->findAll();

        // Akun menunggu persetujuan (hanya yang emailnya SUDAH terverifikasi OTP)
        $menunggu = $this->userModel
            ->select('user.id_user, user.email, user.created_at, profil.nama, profil.no_id, profil.jabatan, instansi.nama_instansi')
            ->join('profil', 'profil.id_user = user.id_user', 'left')
            ->join('instansi', 'instansi.id_instansi = profil.id_instansi', 'left')
            ->where('user.is_active', 0)
            ->where('user.email_verified', 1)
            ->where('user.role', 'pegawai')
            ->orderBy('user.created_at', 'DESC')
            ->findAll();

        return view('admin/pegawai/index', [
            'title'    => 'Kelola Pegawai',
            'pegawai'  => $pegawai,
            'menunggu' => $menunggu,
            'instansi' => $this->instansiModel->where('status_aktif', 1)->findAll(),
        ]);
    }

    // ── Simpan pegawai baru ───────────────────────────────────────
    public function simpan()
    {
        $nama       = trim($this->request->getPost('nama'));
        $noId       = trim($this->request->getPost('no_id'));
        $email      = trim($this->request->getPost('email'));
        $password   = $this->request->getPost('password');
        $jabatan    = trim($this->request->getPost('jabatan'));
        $idInstansi = $this->request->getPost('id_instansi');

        if (empty($nama) || empty($noId) || empty($email) || empty($password) || empty($jabatan) || empty($idInstansi)) {
            return redirect()->to('/admin/pegawai?tab=tambah')
                ->with('error', 'Semua field wajib diisi!');
        }

        if ($this->userModel->where('email', $email)->first()) {
            return redirect()->to('/admin/pegawai?tab=tambah')
                ->withInput()->with('error', 'Email "' . $email . '" sudah digunakan.');
        }

        if ($this->profilModel->where('no_id', $noId)->first()) {
            return redirect()->to('/admin/pegawai?tab=tambah')
                ->withInput()->with('error', 'No. ID "' . $noId . '" sudah digunakan pegawai lain.');
        }

        $idUserBaru = $this->userModel->insert([
            'email'          => $email,
            'password'       => password_hash($password, PASSWORD_DEFAULT),
            'role'           => 'pegawai',
            'is_active'      => 1,
            'email_verified' => 1, // dibuat langsung oleh admin, tidak perlu OTP
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        $this->profilModel->insert([
            'id_user'     => $idUserBaru,
            'no_id'       => $noId,
            'nama'        => $nama,
            'jabatan'     => $jabatan,
            'id_instansi' => $idInstansi,
            'foto'        => '',
            'update_at'   => date('Y-m-d H:i:s'),
        ]);

        logAktivitas('Tambah Pegawai', 'Admin tambah pegawai: ' . $nama . ' (' . $noId . ')');

        return redirect()->to('/admin/pegawai')
            ->with('success', 'Akun "' . $nama . '" berhasil dibuat. Email: ' . $email . ' | Password: ' . $password);
    }

    // ── Setujui akun yang daftar sendiri ─────────────────────────
    public function setujui($idUser)
    {
        $user   = $this->userModel->find($idUser);
        $profil = $this->profilModel->where('id_user', $idUser)->first();

        if (!$user) return redirect()->back()->with('error', 'Akun tidak ditemukan');

        $this->userModel->update($idUser, ['is_active' => 1]);
        logAktivitas('Setujui Akun', 'Menyetujui akun: ' . ($profil['nama'] ?? $user['email']));

        return redirect()->back()
            ->with('success', 'Akun "' . ($profil['nama'] ?? $user['email']) . '" berhasil disetujui.');
    }

    // ── Tolak & hapus akun pendaftar ─────────────────────────────
    public function tolakPendaftar($idUser)
    {
        $user   = $this->userModel->find($idUser);
        $profil = $this->profilModel->where('id_user', $idUser)->first();

        if (!$user) return redirect()->back()->with('error', 'Akun tidak ditemukan');

        (new \App\Models\OtpModel())->where('id_user', $idUser)->delete();
        $this->profilModel->where('id_user', $idUser)->delete();
        $this->userModel->delete($idUser);
        logAktivitas('Tolak Akun', 'Menolak akun: ' . ($profil['nama'] ?? $user['email']));

        return redirect()->back()
            ->with('success', 'Akun "' . ($profil['nama'] ?? $user['email']) . '" ditolak dan dihapus.');
    }

    // ── Aktifkan / Nonaktifkan ────────────────────────────────────
    public function toggleAktif($idUser)
    {
        $user = $this->userModel->find($idUser);
        if (!$user) return redirect()->back()->with('error', 'Pegawai tidak ditemukan');

        $statusBaru = $user['is_active'] == 1 ? 0 : 1;
        $this->userModel->update($idUser, ['is_active' => $statusBaru]);

        $pesan = $statusBaru == 1 ? 'diaktifkan' : 'dinonaktifkan';
        logAktivitas('Toggle Akun', 'Akun ID ' . $idUser . ' ' . $pesan);

        return redirect()->back()->with('success', 'Akun berhasil ' . $pesan);
    }

    // ── Reset password ────────────────────────────────────────────
    public function resetPassword($idUser)
    {
        $user   = $this->userModel->find($idUser);
        $profil = $this->profilModel->where('id_user', $idUser)->first();

        if (!$user) return redirect()->back()->with('error', 'Pegawai tidak ditemukan');

        $passwordBaru = 'pegawai123';
        $this->userModel->update($idUser, [
            'password' => password_hash($passwordBaru, PASSWORD_DEFAULT)
        ]);

        logAktivitas('Reset Password', 'Reset password: ' . ($profil['nama'] ?? 'ID ' . $idUser));

        return redirect()->back()
            ->with('success', 'Password "' . ($profil['nama'] ?? '') . '" direset menjadi: <strong>' . $passwordBaru . '</strong>');
    }

    // ── Reset device token ────────────────────────────────────────
    public function resetDevice($idUser)
    {
        $user   = $this->userModel->find($idUser);
        $profil = $this->profilModel->where('id_user', $idUser)->first();

        if (!$user) return redirect()->back()->with('error', 'Akun tidak ditemukan');

        $this->userModel->update($idUser, ['device_token' => null]);
        logAktivitas('Reset Device', 'Reset device: ' . ($profil['nama'] ?? $user['email']));

        return redirect()->back()
            ->with('success', 'Perangkat "' . ($profil['nama'] ?? '') . '" berhasil direset. Pegawai bisa login dari HP baru.');
    }

    // ── Hapus pegawai ─────────────────────────────────────────────
    public function delete($idUser)
    {
        $profil = $this->profilModel->where('id_user', $idUser)->first();
        (new \App\Models\OtpModel())->where('id_user', $idUser)->delete();
        $this->profilModel->where('id_user', $idUser)->delete();
        $this->userModel->delete($idUser);

        logAktivitas('Hapus Pegawai', 'Hapus: ' . ($profil['nama'] ?? 'ID ' . $idUser));

        return redirect()->back()->with('success', 'Pegawai berhasil dihapus');
    }
}
<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OtpModel;

class Auth extends BaseController
{
    /* ================= LOGIN ================= */

    public function login()
    {
        return view('auth/login');
    }

    public function processLogin()
    {
        $userModel = new UserModel();

        $email       = $this->request->getPost('email');
        $password    = $this->request->getPost('password');
        $deviceToken = trim($this->request->getPost('device_token') ?? '');

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar');
        }

        if ($user['is_active'] != 1) {
            return redirect()->back()->with('error',
                'Akunmu belum disetujui admin. Silakan hubungi admin MPP Tuban untuk aktivasi akun.'
            );
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }

        // ── VALIDASI 1 PERANGKAT 1 AKUN (khusus pegawai) ────────────
        if ($user['role'] === 'pegawai' && !empty($deviceToken)) {
            $tokenTersimpan = $user['device_token'] ?? '';

            if (!empty($tokenTersimpan) && $tokenTersimpan !== $deviceToken) {
                // Perangkat berbeda dari yang pernah dipakai login
                return redirect()->back()->with('error',
                    'Akun ini sudah terdaftar di perangkat lain. '
                    . 'Setiap akun hanya boleh digunakan di 1 perangkat. '
                    . 'Hubungi admin jika perangkatmu berganti.'
                );
            }

            // Simpan/update device token jika belum ada
            if (empty($tokenTersimpan)) {
                $userModel->update($user['id_user'], ['device_token' => $deviceToken]);
            }
        }
        // ── END VALIDASI PERANGKAT ────────────────────────────────────

        // ✅ SET SESSION
        session()->set([
            'id_user'   => $user['id_user'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        // ✅ REDIRECT BERDASARKAN ROLE
        if ($user['role'] == 'admin') {
            return redirect()->to('/admin/dashboard');
        } elseif ($user['role'] == 'sekretariat') {
            return redirect()->to('/sekretariat/dashboard');
        } else {
            return redirect()->to('/dashboard');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    /* ================= REGISTER ================= */

    public function register()
    {
        $instansiModel = new \App\Models\InstansiModel();
        return view('auth/register', [
            'instansi' => $instansiModel->where('status_aktif', 1)->findAll(),
        ]);
    }

    public function processRegister()
    {
        $userModel  = new UserModel();
        $profilModel = new \App\Models\ProfilModel();
        $instansiModel = new \App\Models\InstansiModel();

        $email      = trim($this->request->getPost('email'));
        $password   = $this->request->getPost('password');
        $nama       = trim($this->request->getPost('nama'));
        $noId       = trim($this->request->getPost('no_id'));
        $jabatan    = trim($this->request->getPost('jabatan'));
        $idInstansi = $this->request->getPost('id_instansi');

        // Validasi semua field wajib
        if (empty($nama) || empty($noId) || empty($jabatan) || empty($idInstansi) || empty($email) || empty($password)) {
            return redirect()->back()->withInput()
                ->with('error', 'Semua field wajib diisi!');
        }

        // Cek email duplikat
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Email "' . $email . '" sudah terdaftar. Gunakan email lain atau langsung login.');
        }

        // Cek No. ID duplikat
        if ($profilModel->where('no_id', $noId)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'No. ID "' . $noId . '" sudah terdaftar. Jika kamu sudah punya akun, silakan login.');
        }

        // Buat akun user
        $idUserBaru = $userModel->insert([
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => 'pegawai',
            'is_active'  => 0, // menunggu persetujuan admin
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Buat profil langsung sekaligus
        $profilModel->insert([
            'id_user'     => $idUserBaru,
            'no_id'       => $noId,
            'nama'        => $nama,
            'jabatan'     => $jabatan,
            'id_instansi' => $idInstansi,
            'foto'        => '',
            'update_at'   => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/login')
            ->with('success', 'Akun berhasil dibuat! Akunmu sedang menunggu persetujuan admin MPP Tuban. Silakan hubungi admin untuk aktivasi.');
    }

    /* ================= OTP VERIFIKASI ================= */

    public function verifikasiOtp()
    {
        return view('auth/verifikasi_otp');
    }

    public function processVerifikasiOtp()
    {
        $otpInput = $this->request->getPost('otp');
        $userId   = session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->to('/login');
        }

        $otpModel  = new OtpModel();
        $userModel = new UserModel();

        $otp = $otpModel
            ->where('id_user', $userId)
            ->where('kode_otp', $otpInput)
            ->where('is_used', 0)
            ->where('expired_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$otp) {
            return redirect()->back()
                ->with('error', 'Kode OTP salah atau sudah kadaluarsa');
        }

        $userModel->update($userId, ['is_active' => 1]);
        $otpModel->update($otp['id_otp'], ['is_used' => 1]);

        session()->remove('otp_user_id');

        return redirect()->to('/login')
            ->with('success', 'Akun berhasil diverifikasi, silakan login');
    }

    /* ================= LUPA PASSWORD ================= */

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function sendOtp()
    {
        $email = $this->request->getPost('email');

        $userModel = new UserModel();
        $otpModel  = new OtpModel();

        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar');
        }

        $otp = rand(100000, 999999);

        $otpModel->insert([
            'id_user'     => $user['id_user'],
            'kode_otp'    => $otp,
            'expired_at'  => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'is_used'     => 0
        ]);

        session()->set('reset_id_user', $user['id_user']);

        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setFrom('presensi.mpp@gmail.com', 'Sistem Presensi');
        $emailService->setSubject('OTP Reset Password');
        $emailService->setMessage("Kode OTP reset password: <b>$otp</b>");
        $emailService->send();

        return redirect()->to('/reset-password')
            ->with('success', 'Kode OTP telah dikirim');
    }

    public function resetPassword()
    {
        return view('auth/reset_password');
    }

    public function processResetPassword()
    {
        $otpInput = $this->request->getPost('otp');
        $password = $this->request->getPost('password');
        $userId   = session()->get('reset_id_user');

        $otpModel  = new OtpModel();
        $userModel = new UserModel();

        $otp = $otpModel
            ->where('id_user', $userId)
            ->where('kode_otp', $otpInput)
            ->where('is_used', 0)
            ->where('expired_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$otp) {
            return redirect()->back()
                ->with('error', 'OTP salah atau kadaluarsa');
        }

        $userModel->update($userId, [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $otpModel->update($otp['id_otp'], ['is_used' => 1]);

        session()->remove('reset_id_user');

        return redirect()->to('/login')
            ->with('success', 'Password berhasil diubah');
    }
}
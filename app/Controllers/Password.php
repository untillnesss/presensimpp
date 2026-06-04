<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\OtpModel;

class Password extends BaseController
{
    // FORM EMAIL
    public function lupaPassword()
    {
        return view('forgot_password');
    }

    // KIRIM OTP
    public function kirimOtp()
    {
        $email = $this->request->getPost('email');
        $mode  = $this->request->getPost('mode') ?? 'forgot';

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        $otp = rand(100000, 999999);

        $otpModel = new OtpModel();
        $otpModel->insert([
            'id_user'    => $user['id_user'],
            'kode_otp'   => $otp,
            'expired_at' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'is_used'    => 0
        ]);

        session()->set([
            'reset_user' => $user['id_user'],
            'reset_mode' => $mode
        ]);

        return redirect()->to('/reset-password');
    }

    // SIMPAN PASSWORD BARU
    public function resetPassword()
    {
        $otpInput = $this->request->getPost('otp');
        $password = $this->request->getPost('password');
        $idUser   = session()->get('reset_user');

        $otpModel = new OtpModel();
        $otp = $otpModel
            ->where('id_user', $idUser)
            ->where('kode_otp', $otpInput)
            ->where('is_used', 0)
            ->where('expired_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$otp) {
            return back()->with('error', 'OTP salah atau kadaluarsa');
        }

        $otpModel->update($otp['id_otp'], ['is_used' => 1]);

        $userModel = new UserModel();
        $userModel->update($idUser, [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        $mode = session()->get('reset_mode');
        session()->remove(['reset_user', 'reset_mode']);

        return $mode === 'change'
            ? redirect()->to('/profil')->with('success', 'Password berhasil diubah')
            : redirect()->to('/login')->with('success', 'Password berhasil direset');
    }
}
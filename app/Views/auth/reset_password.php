<?= $this->extend('layout/auth') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="w-100" style="max-width:420px;">

<h5 class="text-center text-primary fw-bold mb-3">Reset Password</h5>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<form method="post" action="/reset-password"
      class="card shadow-sm p-4 rounded-4">

    <div class="mb-3">
        <label>Kode OTP</label>
        <input type="text"
               name="otp"
               class="form-control"
               placeholder="Masukkan kode OTP"
               required>
    </div>

    <div class="mb-3">
        <label>Password Baru</label>
        <input type="password"
               name="password"
               class="form-control"
               placeholder="Masukkan password baru"
               required>
    </div>

    <button class="btn btn-primary w-100 rounded-pill">
        Simpan
    </button>

    <!-- MINTA KODE ULANG -->
    <div class="text-center mt-3">
        <small class="text-muted">
            Kode OTP berlaku 3 menit.<br>
            Tidak menerima kode?
            <a href="/kirim-ulang-otp"
               class="text-primary fw-semibold text-decoration-none">
                Kirim ulang kode
            </a>
        </small>
    </div>

</form>

<?= $this->endSection() ?>

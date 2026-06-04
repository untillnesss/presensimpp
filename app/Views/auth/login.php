<?= $this->extend('layout/auth') ?>
<?= $this->section('content') ?>

<!-- LOGO + JUDUL -->
<div class="text-center mt-4 mb-4">
    <img src="<?= base_url('assets/img/logo-mpp.png') ?>"
         alt="Logo MPP Tuban"
         class="img-fluid mb-3"
         style="max-width:180px;">
    <h5 class="fw-bold text-primary text-center mb-4">
        Presensi MPP Tuban
    </h5>
</div>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<form method="post" action="/login"
      class="card shadow-sm p-4 rounded-4"
      style="margin-bottom:16px;"
      id="formLogin">

    <!-- Device token dikirim otomatis via JS -->
    <input type="hidden" name="device_token" id="deviceToken">

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button class="btn btn-primary w-100 rounded-pill">Masuk</button>

    <div class="text-center mt-3">
        <a href="/lupa-password" class="text-decoration-none">Lupa Password?</a>
    </div>

    <div class="text-center mt-2">
        <span class="text-dark">Belum punya akun?</span>
        <a href="/register" class="text-primary fw-semibold text-decoration-none"> Daftar</a>
    </div>
</form>

<script>
// Buat device fingerprint dari karakteristik browser & perangkat
// Ini tidak bisa 100% akurat tapi cukup untuk mencegah nitip absen
function buatDeviceToken() {
    const info = [
        navigator.userAgent,
        navigator.language,
        screen.width + 'x' + screen.height,
        screen.colorDepth,
        new Date().getTimezoneOffset(),
        navigator.hardwareConcurrency || '',
        navigator.platform || '',
    ].join('|');

    // Hash sederhana dari string
    let hash = 0;
    for (let i = 0; i < info.length; i++) {
        const char = info.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32bit integer
    }
    return 'dev_' + Math.abs(hash).toString(36);
}

document.getElementById('deviceToken').value = buatDeviceToken();
</script>

<?= $this->endSection() ?>

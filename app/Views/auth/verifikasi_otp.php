<?= $this->extend('layout/auth') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="w-100" style="max-width:420px;">

        <h5 class="text-center text-primary fw-bold mb-1">Verifikasi Email</h5>
        <p class="text-center text-muted mb-3" style="font-size:13px;">
            Kode OTP sudah dikirim ke
            <b><?= esc($email ?? '') ?></b>.
            Masukkan kode 6 digit di bawah ini.
        </p>

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

        <form method="post" action="/verifikasi-otp" class="card shadow-sm p-4 rounded-4">

            <div class="mb-3">
                <label class="form-label fw-semibold">Kode OTP</label>
                <input type="text" name="otp" class="form-control text-center"
                       style="letter-spacing:6px;font-size:20px;font-weight:700;"
                       maxlength="6" inputmode="numeric" pattern="[0-9]*"
                       placeholder="------" required autofocus>
            </div>

            <button class="btn btn-primary w-100 rounded-pill" style="padding:12px;font-weight:700;">
                Verifikasi
            </button>

            <div class="text-center mt-3">
                <small class="text-muted">
                    Kode OTP berlaku 5 menit.<br>
                    Tidak menerima kode?
                    <a href="/verifikasi-otp/kirim-ulang" class="text-primary fw-semibold text-decoration-none">
                        Kirim ulang kode
                    </a>
                </small>
            </div>
        </form>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->extend('layout/auth') ?>
<?= $this->section('content') ?>

<?php $mode = $_GET['mode'] ?? 'forgot'; ?>

<div class="d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="w-100" style="max-width:420px;">

        <h5 class="text-center text-primary fw-bold mb-3">
            <?= $mode === 'change' ? 'Ubah Password' : 'Lupa Password' ?>
        </h5>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/lupa-password"
              class="card p-4 shadow rounded-4">

            <input type="hidden" name="mode" value="<?= $mode ?>">

            <input type="email"
                   name="email"
                   class="form-control mb-3"
                   placeholder="Masukkan email terdaftar"
                   required>

            <button class="btn btn-primary w-100 rounded-pill">
                Kirim OTP
            </button>
        </form>

        <?php if($mode === 'forgot'): ?>
            <div class="text-center mt-3">
                <a href="/login" class="text-decoration-none">
                    Kembali ke Login
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>

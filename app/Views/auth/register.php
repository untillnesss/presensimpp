<?= $this->extend('layout/auth') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-center align-items-center" style="min-height:80vh;padding:20px 0;">
<div class="w-100" style="max-width:460px;">

<h5 class="text-center fw-bold mb-1" style="color:#6366f1;">Daftar Akun Pegawai</h5>
<p class="text-center text-muted mb-4" style="font-size:13px;">MPP Tuban — isi data lengkap agar langsung bisa absen</p>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<form method="post" action="/register" class="card shadow-sm p-4 rounded-4">

    <div style="font-size:11px;font-weight:700;color:#9ca3af;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px;">
        Data Diri
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama" class="form-control"

               value="<?= old('nama') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">No. ID / NIP / NIK <span class="text-danger">*</span></label>
        <input type="text" name="no_id" class="form-control"
               placeholder="Nomor identitas dari instansi asal"
               value="<?= old('no_id') ?>" required>
        <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
            <i class="bi bi-info-circle me-1"></i>Tidak boleh sama dengan pegawai lain yang sudah terdaftar.
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
        <input type="text" name="jabatan" class="form-control"
               value="<?= old('jabatan') ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Instansi <span class="text-danger">*</span></label>
        <select name="id_instansi" class="form-select" required>
            <option value="">-- Pilih Instansi --</option>
            <?php foreach($instansi as $i): ?>
            <option value="<?= $i['id_instansi'] ?>" <?= old('id_instansi') == $i['id_instansi'] ? 'selected' : '' ?>>
                <?= esc($i['nama_instansi']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <hr style="border-color:#e5e7eb;margin:16px 0;">
    <div style="font-size:11px;font-weight:700;color:#9ca3af;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px;">
        Data Login
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control"
               value="<?= old('email') ?>" required>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
        <div style="position:relative;">
            <input type="password" name="password" id="inputPw" class="form-control"
                   placeholder="Minimal 6 karakter"
                   required minlength="6" style="padding-right:44px;">
            <button type="button" onclick="togglePw()" tabindex="-1"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;">
                <i class="bi bi-eye" id="iconEye"></i>
            </button>
        </div>
    </div>

    <button class="btn btn-primary w-100 rounded-pill" style="padding:12px;font-weight:700;">
        <i class="bi bi-person-check-fill me-2"></i>Daftar Sekarang
    </button>

    <div class="text-center mt-3">
        <span class="text-muted" style="font-size:13px;">Sudah punya akun?</span>
        <a href="/login" class="fw-semibold text-decoration-none" style="color:#6366f1;"> Login</a>
    </div>
</form>
</div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('inputPw');
    const ico = document.getElementById('iconEye');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    ico.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
<?= $this->endSection() ?>

<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4>Tambah Pegawai</h4>
        <p>Buat akun pegawai baru secara manual</p>
    </div>
    <a href="/admin/pegawai" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i><?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Form Tambah Akun Pegawai</div>
            <div class="card-header-sub">Isi data lengkap pegawai. Pegawai bisa langsung login setelah ini.</div>
        </div>
        <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-person-plus-fill" style="color:#fff;font-size:17px;"></i>
        </div>
    </div>
    <div class="card-body p-4">

        <!-- Panduan -->
        <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac;border-radius:12px;padding:14px 18px;margin-bottom:24px;">
            <div style="font-weight:700;color:#166534;font-size:13px;margin-bottom:4px;">
                <i class="bi bi-lightbulb-fill me-1"></i>Gunakan form ini ketika:
            </div>
            <div style="font-size:12px;color:#15803d;">
                Pegawai baru pertama kali bertugas di MPP dan belum punya akun, 
                atau HP-nya rusak sehingga tidak bisa daftar sendiri. 
                Setelah akun dibuat, admin bisa langsung catat presensinya lewat menu 
                <strong>Data Presensi → Tambah</strong>.
            </div>
        </div>

        <form method="post" action="/admin/pegawai/simpan" id="formTambahPegawai">

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-person me-1" style="color:#6366f1;"></i>Nama Lengkap <span class="text-danger">*</span>
                </label>
                <input type="text" name="nama" class="form-control"
                       placeholder="Contoh: Budi Santoso"
                       value="<?= old('nama') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-card-text me-1" style="color:#6366f1;"></i>No. ID / NIP / NIK <span class="text-danger">*</span>
                </label>
                <input type="text" name="no_id" class="form-control"
                       placeholder="Contoh: 199001012020011001"
                       value="<?= old('no_id') ?>" required>
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    <i class="bi bi-info-circle me-1"></i>Nomor identitas unik dari instansi asal. Tidak boleh sama dengan pegawai lain.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-briefcase me-1" style="color:#6366f1;"></i>Jabatan <span class="text-danger">*</span>
                </label>
                <input type="text" name="jabatan" class="form-control"
                       placeholder="Contoh: Petugas Pelayanan BPJS"
                       value="<?= old('jabatan') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-building me-1" style="color:#6366f1;"></i>Instansi <span class="text-danger">*</span>
                </label>
                <select name="id_instansi" class="form-select" required>
                    <option value="">-- Pilih Instansi --</option>
                    <?php foreach($instansi as $i): ?>
                    <option value="<?= $i['id_instansi'] ?>" <?= old('id_instansi') == $i['id_instansi'] ? 'selected' : '' ?>>
                        <?= esc($i['nama_instansi']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <hr style="border-color:#e5e7eb;margin:20px 0;">
            <div style="font-size:12px;font-weight:700;color:#6b7280;letter-spacing:1px;text-transform:uppercase;margin-bottom:14px;">
                <i class="bi bi-shield-lock me-1"></i>Data Login
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-envelope me-1" style="color:#6366f1;"></i>Email <span class="text-danger">*</span>
                </label>
                <input type="email" name="email" class="form-control"
                       placeholder="Contoh: budi@bpjs.go.id"
                       value="<?= old('email') ?>" required>
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    <i class="bi bi-info-circle me-1"></i>Email ini digunakan untuk login. Harus unik.
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-key me-1" style="color:#6366f1;"></i>Password <span class="text-danger">*</span>
                </label>
                <div style="position:relative;">
                    <input type="password" name="password" id="inputPassword" class="form-control"
                           placeholder="Minimal 6 karakter"
                           value="pegawai123" required minlength="6"
                           style="padding-right:44px;">
                    <button type="button" onclick="togglePw()" tabindex="-1"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;font-size:16px;">
                        <i class="bi bi-eye" id="iconEye"></i>
                    </button>
                </div>
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    <i class="bi bi-info-circle me-1"></i>Default: <strong>pegawai123</strong>. Ganti sesuai kebutuhan. Sampaikan ke pegawai untuk login pertama kali.
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding:13px;justify-content:center;">
                <i class="bi bi-person-check-fill me-2"></i> Buat Akun Pegawai
            </button>
        </form>
    </div>
</div>
</div>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('inputPassword');
    const ico = document.getElementById('iconEye');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bi bi-eye-slash';
    } else {
        inp.type = 'password';
        ico.className = 'bi bi-eye';
    }
}

document.getElementById('formTambahPegawai').addEventListener('submit', function(e) {
    const fields = ['nama','no_id','jabatan','id_instansi','email','password'];
    let valid = true;
    fields.forEach(function(f) {
        const el = document.querySelector('[name="'+f+'"]');
        if (!el || el.value.trim() === '') {
            valid = false;
            el.style.borderColor = '#ef4444';
        } else {
            el.style.borderColor = '';
        }
    });
    if (!valid) {
        e.preventDefault();
        alert('Semua field wajib diisi!');
    }
});
</script>

<?= $this->endSection() ?>

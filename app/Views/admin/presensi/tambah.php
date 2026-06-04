<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4>Tambah Presensi Manual</h4>
        <p>Input data presensi secara manual oleh admin</p>
    </div>
</div>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span><?= session()->getFlashdata('error') ?></span>
</div>
<?php endif; ?>

<?php if(empty($jenis)): ?>
<!-- ═══════════════════════════════════════════════════════════════
     STEP 1: Pilih jenis presensi (Masuk atau Pulang)
════════════════════════════════════════════════════════════════ -->
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Pilih Jenis Presensi</div>
            <div class="card-header-sub">Pilih apakah ini presensi masuk atau pulang</div>
        </div>
        <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-clipboard-check-fill" style="color:#fff;font-size:17px;"></i>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-6">
                <a href="?jenis=masuk" class="btn w-100 py-4 d-flex flex-column align-items-center gap-2"
                   style="background:linear-gradient(135deg,#10b981,#059669);color:#fff;border-radius:16px;box-shadow:0 4px 16px rgba(16,185,129,0.35);text-decoration:none;">
                    <i class="bi bi-box-arrow-in-right" style="font-size:2rem;"></i>
                    <span style="font-weight:700;font-size:16px;">Presensi Masuk</span>
                    <small style="opacity:0.85;">Pegawai baru datang</small>
                </a>
            </div>
            <div class="col-6">
                <a href="?jenis=pulang" class="btn w-100 py-4 d-flex flex-column align-items-center gap-2"
                   style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border-radius:16px;box-shadow:0 4px 16px rgba(239,68,68,0.35);text-decoration:none;">
                    <i class="bi bi-box-arrow-right" style="font-size:2rem;"></i>
                    <span style="font-weight:700;font-size:16px;">Presensi Pulang</span>
                    <small style="opacity:0.85;">Pegawai akan pulang</small>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php else: ?>
<!-- ═══════════════════════════════════════════════════════════════
     STEP 2: Form Presensi (Masuk atau Pulang)
════════════════════════════════════════════════════════════════ -->

<?php
// Data pegawai untuk JavaScript auto-fill
$pegawaiJson = json_encode(array_column($pegawai, null, 'id_user'));
$warnaMasuk  = $jenis === 'masuk';
$gradien     = $warnaMasuk
    ? 'linear-gradient(135deg,#10b981,#059669)'
    : 'linear-gradient(135deg,#ef4444,#dc2626)';
$shadow      = $warnaMasuk
    ? '0 4px 16px rgba(16,185,129,0.35)'
    : '0 4px 16px rgba(239,68,68,0.35)';
$ikonJenis   = $warnaMasuk ? 'bi-box-arrow-in-right' : 'bi-box-arrow-right';
$labelJenis  = $warnaMasuk ? 'Masuk' : 'Pulang';
$fotoField   = $warnaMasuk ? 'foto_masuk' : 'foto_pulang';
?>

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header-inner" style="background:<?= $gradien ?>;">
        <div>
            <div class="card-header-title">Form Presensi <?= $labelJenis ?> Manual</div>
            <div class="card-header-sub">Tanggal & jam akan otomatis sesuai waktu saat disimpan</div>
        </div>
        <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
            <i class="bi <?= $ikonJenis ?>" style="color:#fff;font-size:17px;"></i>
        </div>
    </div>
    <div class="card-body p-4">

        <!-- Info waktu otomatis -->
        <div class="alert d-flex align-items-center gap-2 mb-4"
             style="background:<?= $warnaMasuk ? '#f0fdf4' : '#fff5f5' ?>;border:1px solid <?= $warnaMasuk ? '#bbf7d0' : '#fecaca' ?>;border-radius:12px;color:<?= $warnaMasuk ? '#065f46' : '#7f1d1d' ?>;">
            <i class="bi bi-clock-fill" style="color:<?= $warnaMasuk ? '#10b981' : '#ef4444' ?>;font-size:18px;"></i>
            <div>
                <strong>Tanggal & Jam Otomatis</strong><br>
                <small>Waktu saat ini: <strong id="waktuSekarang"></strong></small>
            </div>
        </div>

        <form method="post" action="/admin/presensi/simpan" enctype="multipart/form-data" id="formAbsen">
            <input type="hidden" name="jenis" value="<?= $jenis ?>">

            <!-- Dropdown Pegawai -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-person me-1" style="color:#6366f1;"></i>Nama Pegawai <span class="text-danger">*</span>
                </label>
                <select name="id_user" id="selectPegawai" class="form-select" required
                        onchange="autofillPegawai(this.value)">
                    <option value="">-- Pilih Pegawai --</option>
                    <?php foreach($pegawai as $p): ?>
                    <option value="<?= $p['id_user'] ?>"
                        <?= old('id_user') == $p['id_user'] ? 'selected' : '' ?>>
                        <?= esc($p['nama']) ?> — <?= esc($p['nama_instansi'] ?? '-') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- No. ID (auto-fill, readonly) -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-card-text me-1" style="color:#6366f1;"></i>No. ID
                </label>
                <input type="text" id="noIdDisplay" class="form-control"
                       placeholder="Otomatis terisi setelah pilih pegawai"
                       readonly
                       style="background:#f3f4f6;color:#374151;font-family:monospace;font-weight:700;cursor:not-allowed;">
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    <i class="bi bi-info-circle me-1"></i>Terisi otomatis dari data profil pegawai yang dipilih.
                </div>
            </div>

            <!-- Instansi (auto-fill, bisa diubah) -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-building me-1" style="color:#6366f1;"></i>Instansi <span class="text-danger">*</span>
                </label>
                <select name="id_instansi" id="selectInstansi" class="form-select" required>
                    <option value="">-- Otomatis dari pegawai --</option>
                    <?php foreach($instansi as $i): ?>
                    <option value="<?= $i['id_instansi'] ?>" <?= old('id_instansi') == $i['id_instansi'] ? 'selected' : '' ?>>
                        <?= esc($i['nama_instansi']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label fw-semibold">
                    <i class="bi bi-tag me-1" style="color:#6366f1;"></i>Status Kehadiran <span class="text-danger">*</span>
                </label>
                <select name="status" class="form-select" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="hadir"       <?= old('status') === 'hadir'       ? 'selected' : '' ?>>✅ Hadir</option>
                    <option value="terlambat"   <?= old('status') === 'terlambat'   ? 'selected' : '' ?>>⏰ Terlambat</option>
                    <option value="izin"        <?= old('status') === 'izin'        ? 'selected' : '' ?>>📅 Izin</option>
                    <option value="sakit"       <?= old('status') === 'sakit'       ? 'selected' : '' ?>>💊 Sakit</option>
                    <option value="tidak hadir" <?= old('status') === 'tidak hadir' ? 'selected' : '' ?>>❌ Tidak Hadir</option>
                </select>
                <?php if($jenis === 'masuk'): ?>
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    <i class="bi bi-info-circle me-1"></i>Jika pilih Hadir/Terlambat, status dihitung ulang otomatis berdasarkan jam masuk.
                </div>
                <?php endif; ?>
            </div>

            <!-- Upload Foto -->
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-camera me-1" style="color:#6366f1;"></i>Foto Presensi <?= $labelJenis ?> <span class="text-danger">*</span>
                </label>
                <input type="file" name="<?= $fotoField ?>" class="form-control"
                       accept="image/*" required id="inputFoto">
                <div id="previewFoto" class="mt-2" style="display:none;">
                    <img id="imgPreview" style="width:100px;height:100px;object-fit:cover;border-radius:12px;border:2px solid #dde1f7;">
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn flex-grow-1"
                        style="padding:13px;background:<?= $gradien ?>;color:#fff;box-shadow:<?= $shadow ?>;border:none;font-weight:700;border-radius:12px;">
                    <i class="bi <?= $ikonJenis ?> me-2"></i>Simpan Presensi <?= $labelJenis ?>
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<script>
// Data pegawai dari PHP
const dataPegawai = <?= $pegawaiJson ?? '{}' ?>;

// Auto-fill No. ID dan Instansi saat pilih pegawai
function autofillPegawai(idUser) {
    const noIdEl      = document.getElementById('noIdDisplay');
    const instansiEl  = document.getElementById('selectInstansi');

    if (!idUser || !dataPegawai[idUser]) {
        noIdEl.value = '';
        instansiEl.value = '';
        return;
    }

    const p = dataPegawai[idUser];
    noIdEl.value       = p.no_id       ?? '—';
    instansiEl.value   = p.id_instansi ?? '';
}

// Jalankan saat load jika ada old input
window.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('selectPegawai');
    if (sel && sel.value) autofillPegawai(sel.value);
});

// Jam realtime
function updateWaktu() {
    const el = document.getElementById('waktuSekarang');
    if (el) {
        const now = new Date();
        el.textContent = now.toLocaleString('id-ID', {
            weekday:'long', year:'numeric', month:'long',
            day:'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit'
        });
    }
}
updateWaktu();
setInterval(updateWaktu, 1000);

// Preview foto
document.getElementById('inputFoto').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('previewFoto').style.display = 'block';
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->extend('layout/mobile') ?> 
<?= $this->section('content') ?>

<h5 class="fw-bold mb-3">Tambah Pengajuan</h5>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<form action="/pengajuan/simpan" method="post" enctype="multipart/form-data">
    <?= csrf_field(); ?>

    <div class="mb-2">
        <label class="form-label">Mulai Tanggal</label>
        <input type="date" name="mulai" id="mulai" value="<?= old('mulai') ?>"
               class="form-control rounded-pill" required>
    </div>

    <div class="mb-2">
        <label class="form-label">Selesai Tanggal</label>
        <input type="date" name="selesai" id="selesai" value="<?= old('selesai') ?>"
               class="form-control rounded-pill" required>
    </div>

    <div class="mb-2">
        <label class="form-label">Jenis</label>
        <select name="jenis" class="form-select rounded-pill" required>
            <option value="">-- Pilih --</option>
            <option value="Izin"  <?= old('jenis')=='Izin'  ?'selected':'' ?>>Izin</option>
        </select>
    </div>

    <div class="mb-2">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control rounded-3"
                  rows="3"><?= old('keterangan') ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">
            File Bukti <span class="text-danger">*</span>
        </label>
        <input type="file" name="file_bukti" class="form-control"
               accept=".jpg,.jpeg,.png,.pdf" required id="inputBukti">
        <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
            <i class="bi bi-info-circle me-1"></i>
            Format: JPG, PNG, atau PDF. Contoh: surat dokter, surat izin instansi, dsb.
        </div>
        <div id="previewBukti" style="display:none;margin-top:8px;">
            <img id="imgBukti" style="max-width:100%;max-height:200px;border-radius:10px;border:1px solid #e5e7eb;">
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 rounded-pill">
        Kirim Pengajuan
    </button>
</form>

<script>
// SET MIN DATE = HARI INI (tidak bisa pilih tanggal kemarin dst)
const today    = new Date();
const yyyy     = today.getFullYear();
const mm       = String(today.getMonth() + 1).padStart(2, '0');
const dd       = String(today.getDate()).padStart(2, '0');
const todayStr = yyyy + '-' + mm + '-' + dd;

const inputMulai   = document.getElementById('mulai');
const inputSelesai = document.getElementById('selesai');

inputMulai.setAttribute('min', todayStr);
inputSelesai.setAttribute('min', todayStr);

if (!inputMulai.value)   inputMulai.value   = todayStr;
if (!inputSelesai.value) inputSelesai.value = todayStr;

inputMulai.addEventListener('change', function () {
    inputSelesai.setAttribute('min', this.value);
    if (inputSelesai.value && inputSelesai.value < this.value) {
        inputSelesai.value = this.value;
    }
});

// PREVIEW FILE BUKTI
document.getElementById('inputBukti').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('imgBukti').src = e.target.result;
            document.getElementById('previewBukti').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('previewBukti').style.display = 'none';
    }
});
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/mobile') ?>
<?= $this->section('content') ?>

<div class="container mt-3">

<h5 class="fw-bold mb-3">Edit Pengajuan</h5>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<form action="/pengajuan/update/<?= $data['id_pengajuan'] ?>" method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label>Jenis</label>
        <select name="jenis" class="form-control">
            <option value="izin" <?= $data['jenis']=='izin'?'selected':'' ?>>Izin</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="date" name="mulai" class="form-control"
            value="<?= esc($data['tanggal_mulai']) ?>">
    </div>

    <div class="mb-3">
        <label>Tanggal Selesai</label>
        <input type="date" name="selesai" class="form-control"
            value="<?= esc($data['tanggal_selesai']) ?>">
    </div>

    <div class="mb-3">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"><?= esc($data['keterangan']) ?></textarea>
    </div>

    
    <!-- FILE BUKTI -->
    <div class="mb-3">
        <label class="form-label fw-semibold">File Bukti</label>
        <?php if(!empty($data['file_bukti'])): ?>
        <div style="margin-bottom:8px;">
            <?php
            $ext = strtolower(pathinfo($data['file_bukti'], PATHINFO_EXTENSION));
            if(in_array($ext, ['jpg','jpeg','png'])): ?>
            <img src="/uploads/pengajuan/<?= $data['file_bukti'] ?>"
                 style="max-width:100%;max-height:180px;border-radius:10px;border:1px solid #e5e7eb;">
            <?php else: ?>
            <a href="/uploads/pengajuan/<?= $data['file_bukti'] ?>" target="_blank"
               style="font-size:13px;color:#6366f1;">
                <i class="bi bi-file-earmark-pdf me-1"></i>Lihat File Bukti
            </a>
            <?php endif; ?>
            <div style="font-size:11px;color:#9ca3af;margin-top:4px;">Upload baru untuk mengganti file lama</div>
        </div>
        <?php endif; ?>
        <input type="file" name="file_bukti" class="form-control"
               accept=".jpg,.jpeg,.png,.pdf">
        <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
            Format: JPG, PNG, atau PDF. Kosongkan jika tidak ingin mengganti.
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        Simpan Perubahan
    </button>

</form>

</div>

<?= $this->endSection() ?>
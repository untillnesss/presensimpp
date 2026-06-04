<?= $this->extend('layout/mobile') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold">Pengajuan</h5>
    <a href="/pengajuan/tambah" class="btn btn-sm btn-primary rounded-pill">
        + Tambah
    </a>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<?php if(empty($pengajuan)): ?>
<div class="text-center mt-5">
    <p class="text-muted">Belum ada pengajuan</p>
</div>
<?php endif; ?>

<?php foreach($pengajuan as $p): ?>

<?php
$status = strtolower(trim($p['status_pengajuan'] ?? ''));

switch ($status) {
    case 'disetujui':
        $badge = 'bg-success';
        $text  = 'Disetujui';
        break;
    case 'ditolak':
        $badge = 'bg-danger';
        $text  = 'Ditolak';
        break;
    default:
        $badge = 'bg-warning text-dark';
        $text  = 'Menunggu';
        $status = 'menunggu';
}
?>

<div class="card mb-3 shadow-sm border-0">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <strong><?= esc($p['jenis']) ?></strong>
                <p class="mb-1 text-muted mt-1">
                    <?= esc($p['tanggal_mulai']) ?> s.d <?= esc($p['tanggal_selesai']) ?>
                </p>
            </div>

            <span class="badge <?= $badge ?> px-3 py-2">
                <?= $text ?>
            </span>

        </div>

        <p class="mb-2 mt-2">
            <?= esc($p['keterangan']) ?>
        </p>

        <!-- 🔥 TOMBOL HAPUS & EDIT -->
        <?php if($status === 'menunggu'): ?>
        <div class="d-flex gap-2 mt-2">

            <a href="/pengajuan/edit/<?= $p['id_pengajuan'] ?>" 
               class="btn btn-warning btn-sm w-100">
               Edit
            </a>

            <a href="/pengajuan/delete/<?= $p['id_pengajuan'] ?>" 
               class="btn btn-danger btn-sm w-100"
               onclick="return confirm('Yakin hapus pengajuan ini?')">
               Hapus
            </a>

        </div>
        <?php endif; ?>

    </div>
</div>

<?php endforeach ?>

<?= $this->endSection() ?>
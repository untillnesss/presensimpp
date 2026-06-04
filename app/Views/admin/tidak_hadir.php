<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4>Instansi Tidak Hadir</h4>
        <p>Daftar instansi yang belum hadir hari ini</p>
    </div>
    <a href="/admin/dashboard" class="btn-secondary-outline">
    </a>
</div>

<?php if(empty($data)): ?>
<div class="card">
    <div class="card-body empty-state">
        <div style="font-size:56px;">🎉</div>
        <p style="font-weight:700;font-size:15px;color:#059669;margin-top:16px;">Semua Instansi Hadir!</p>
        <p>Tidak ada instansi yang absen hari ini</p>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Instansi Tidak Hadir</div>
            <div class="card-header-sub"><?= count($data) ?> instansi belum hadir</div>
        </div>
        <span style="background:#fee2e2;color:#dc2626;padding:6px 16px;border-radius:99px;font-size:12px;font-weight:700;">
            <i class="bi bi-x-circle-fill me-1"></i><?= count($data) ?> Absen
        </span>
    </div>
    <div class="card-body p-0">
        <?php foreach($data as $d): ?>
        <div class="instansi-item">
            <div style="display:flex;align-items:center;gap:14px;">
                <div class="instansi-avatar" style="background:#fee2e2;">
                    <i class="bi bi-building" style="color:#dc2626;"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:14px;color:#111827;"><?= esc($d['nama_instansi']) ?></div>
                    <div style="font-size:12px;color:#9ca3af;">Belum melakukan presensi</div>
                </div>
            </div>
            <span class="badge-status badge-terlambat"><i class="bi bi-x-circle-fill me-1"></i>Tidak Hadir</span>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
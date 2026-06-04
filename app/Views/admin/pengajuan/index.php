<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4>Pengajuan</h4>
    <p>Kelola pengajuan izin dan sakit pegawai</p>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<?php if(empty($data)): ?>
<div class="card">
    <div class="card-body empty-state">
        <div style="font-size:64px;">📋</div>
        <p style="font-weight:700;font-size:16px;color:#374151;margin-top:16px;">Tidak ada pengajuan</p>
        <p>Semua pengajuan sudah ditindaklanjuti</p>
    </div>
</div>
<?php endif; ?>

<div class="row g-3">
<?php foreach($data as $d):
    $status = strtolower($d['status_pengajuan']);
    if ($status == 'disetujui') {
        $badgeClass = 'badge-disetujui'; $text = 'Disetujui'; $icon = 'bi-check-circle-fill';
        $headerColor = 'linear-gradient(135deg,#d1fae5,#a7f3d0)'; $headerText = '#065f46';
    } elseif ($status == 'ditolak') {
        $badgeClass = 'badge-ditolak'; $text = 'Ditolak'; $icon = 'bi-x-circle-fill';
        $headerColor = 'linear-gradient(135deg,#fee2e2,#fecaca)'; $headerText = '#991b1b';
    } else {
        $badgeClass = 'badge-menunggu'; $text = 'Menunggu'; $icon = 'bi-hourglass-split';
        $headerColor = 'linear-gradient(135deg,#fef3c7,#fde68a)'; $headerText = '#92400e';
    }
    $jenisEmoji = match($d['jenis']) { 'sakit' => '💊', default => '📅' };
?>
<div class="col-md-6 col-xl-4">
    <div class="pengajuan-card">
        <div style="background:<?= $headerColor ?>;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;">
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:20px;"><?= $jenisEmoji ?></span>
                <span style="font-size:13px;font-weight:800;color:<?= $headerText ?>;text-transform:capitalize;"><?= $d['jenis'] ?></span>
            </div>
            <span class="badge-status <?= $badgeClass ?>"><i class="bi <?= $icon ?> me-1"></i><?= $text ?></span>
        </div>
        <div class="card-body p-4">
            <div style="margin-bottom:16px;">
                <div style="font-weight:800;font-size:14px;color:#111827;"><?= esc($d['nama']) ?></div>
                <div style="font-size:12px;color:#9ca3af;margin-top:2px;"><?= esc($d['nama_instansi']) ?></div>
            </div>
            <div style="background:#f7f8ff;border-radius:14px;padding:14px;margin-bottom:14px;border:1px solid #eef0fb;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                    <span style="font-size:12px;color:#9ca3af;font-weight:600;">📅 Periode</span>
                    <span style="font-size:13px;font-weight:700;color:#374151;">
                        <?= date('d M', strtotime($d['tanggal_mulai'])) ?> — <?= date('d M Y', strtotime($d['tanggal_selesai'])) ?>
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                    <span style="font-size:12px;color:#9ca3af;font-weight:600;">📝 Keterangan</span>
                    <span style="font-size:13px;color:#374151;max-width:60%;text-align:right;line-height:1.4;"><?= esc($d['keterangan']) ?></span>
                </div>
            </div>
            <?php if($status == 'menunggu'): ?>
            <div class="d-flex gap-2">
                <a href="/admin/pengajuan/acc/<?= $d['id_pengajuan'] ?>" class="btn-sm-action btn-setujui w-100 text-center py-2 justify-content-center">
                    <i class="bi bi-check-lg"></i> Setujui
                </a>
                <a href="/admin/pengajuan/tolak/<?= $d['id_pengajuan'] ?>" class="btn-sm-action btn-tolak w-100 text-center py-2 justify-content-center">
                    <i class="bi bi-x-lg"></i> Tolak
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<?= $this->endSection() ?>
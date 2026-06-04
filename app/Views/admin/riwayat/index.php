<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4>Riwayat Aktivitas</h4>
    <p>Log semua aktivitas yang dilakukan admin</p>
</div>

<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Log Aktivitas</div>
            <div class="card-header-sub">Semua tindakan tersimpan otomatis</div>
        </div>
        <span style="background:rgba(255,255,255,0.15);color:#fff;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;border:1px solid rgba(255,255,255,0.2);">
            <i class="bi bi-shield-check me-1"></i>Secured Log
        </span>
    </div>
    <div class="card-body p-0">
        <?php if(!empty($log)): ?>
            <?php foreach($log as $l):
                $aksiConfig = match(true) {
                    str_contains($l['aksi'], 'Tambah')  => ['bg'=>'#d1fae5','color'=>'#059669','icon'=>'bi-plus-circle-fill'],
                    str_contains($l['aksi'], 'Hapus')   => ['bg'=>'#fee2e2','color'=>'#dc2626','icon'=>'bi-trash-fill'],
                    str_contains($l['aksi'], 'Edit')    => ['bg'=>'#fef3c7','color'=>'#d97706','icon'=>'bi-pencil-fill'],
                    str_contains($l['aksi'], 'Setting') => ['bg'=>'#eef2ff','color'=>'#6366f1','icon'=>'bi-sliders'],
                    default                              => ['bg'=>'#f3f4f6','color'=>'#6b7280','icon'=>'bi-activity']
                };
            ?>
            <div class="log-row">
                <div class="log-dot" style="background:<?= $aksiConfig['bg'] ?>;"><i class="bi <?= $aksiConfig['icon'] ?>" style="color:<?= $aksiConfig['color'] ?>;"></i></div>
                <div style="flex:1;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:6px;">
                        <div>
                            <span style="font-weight:700;font-size:14px;color:#111827;"><?= esc($l['aksi']) ?></span>
                            <?php if($l['role']=='admin'): ?>
                                <span class="badge-status badge-admin ms-2" style="font-size:10.5px;padding:3px 10px;">Admin</span>
                            <?php elseif($l['role']=='sekretariat'): ?>
                                <span class="badge-status badge-sekretariat ms-2" style="font-size:10.5px;padding:3px 10px;">Sekretariat</span>
                            <?php else: ?>
                                <span class="badge-status badge-pegawai ms-2" style="font-size:10.5px;padding:3px 10px;">Pegawai</span>
                            <?php endif; ?>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div style="font-size:12.5px;font-weight:700;color:#374151;"><?= date('d M Y', strtotime($l['created_at'])) ?></div>
                            <div style="font-size:11px;color:#9ca3af;"><?= date('H:i', strtotime($l['created_at'])) ?> WIB</div>
                        </div>
                    </div>
                    <div style="font-size:13px;color:#6b7280;margin-top:4px;"><?= esc($l['keterangan']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state">
                <div style="font-size:56px;">📂</div>
                <p style="font-weight:700;font-size:15px;color:#374151;margin-top:16px;">Belum ada aktivitas</p>
                <p>Log aktivitas akan muncul setelah admin melakukan tindakan</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
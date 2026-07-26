<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4><?= esc($judul) ?></h4>
        <p>Data hari ini — <?= date('l, d F Y', strtotime($hariIni)) ?></p>
    </div>
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title"><?= esc($judul) ?></div>
            <div class="card-header-sub"><?= count($data) ?> data ditemukan</div>
        </div>
        <span style="background:rgba(255,255,255,0.15);color:#fff;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;border:1px solid rgba(255,255,255,0.2);">
            <?= count($data) ?> Record
        </span>
    </div>
    <div class="card-body p-0">

        <?php if(empty($data)): ?>
        <div style="text-align:center;padding:60px 20px;">
            <div style="font-size:52px;margin-bottom:12px;">📭</div>
            <p style="font-weight:700;color:#374151;">Tidak ada data</p>
            <p style="color:#9ca3af;font-size:13px;">Tidak ada pegawai dengan status ini hari ini</p>
        </div>
        <?php else: ?>

        <?php if($kategori == 'tidak-hadir'): ?>
        <!-- Tidak hadir: hanya nama instansi -->
        <div class="card-body p-0">
            <?php $no = 1; foreach($data as $d): ?>
            <div class="instansi-item">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div style="width:36px;height:36px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-building" style="color:#dc2626;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:#111827;"><?= esc($d['nama_instansi']) ?></div>
                        <div style="font-size:12px;color:#9ca3af;">Belum melakukan presensi</div>
                    </div>
                </div>
                <span class="badge-status badge-terlambat">Tidak Hadir</span>
            </div>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th style="width:36px;">No</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <?php if($kategori == 'terlambat'): ?>
                        <th>Keterlambatan</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; foreach($data as $d): ?>
                <tr>
                    <td style="font-weight:700;color:#6366f1;font-size:12px;"><?= $no++ ?></td>
                    <td style="font-weight:700;color:#111827;"><?= esc($d['nama'] ?? '-') ?></td>
                    <td style="font-size:12.5px;color:#6b7280;"><?= esc($d['nama_instansi'] ?? '-') ?></td>
                    <td>
                        <?php if(!empty($d['jam_masuk']) && $d['jam_masuk'] != '00:00:00'): ?>
                            <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:7px;font-size:12px;font-weight:700;">
                                <?= date('H:i', strtotime($d['jam_masuk'])) ?>
                            </span>
                        <?php else: ?><span style="color:#c9d0e8;">—</span><?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($d['jam_pulang']) && $d['jam_pulang'] != '00:00:00'): ?>
                            <span style="background:#cffafe;color:#164e63;padding:3px 10px;border-radius:7px;font-size:12px;font-weight:700;">
                                <?= date('H:i', strtotime($d['jam_pulang'])) ?>
                            </span>
                        <?php else: ?><span style="color:#c9d0e8;">—</span><?php endif; ?>
                    </td>
                    <td>
                        <?php $st = strtolower($d['status'] ?? ''); ?>
                        <?php if($st=='hadir'): ?><span class="badge-status badge-hadir">Hadir</span>
                        <?php elseif($st=='terlambat'): ?><span class="badge-status badge-terlambat">Terlambat</span>
                        <?php elseif($st=='izin'): ?><span class="badge-status badge-izin">Izin</span>
                        <?php else: ?><span style="color:#6b7280;"><?= ucfirst($st) ?></span><?php endif; ?>
                    </td>
                    <?php if($kategori == 'terlambat'): ?>
                    <td style="font-weight:700;color:#dc2626;">
                        <?= !empty($d['keterlambatan']) ? $d['keterlambatan'].' mnt' : '—' ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
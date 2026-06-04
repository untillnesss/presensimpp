<?= $this->extend('layout/sekretariat') ?>
<?= $this->section('content') ?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><?= esc($judul) ?></h4>
        <p>Data hari ini — <?= date('l, d F Y', strtotime($hariIni)) ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title"><?= esc($judul) ?></div>
            <div class="card-header-sub"><?= count($data) ?> data ditemukan</div>
        </div>
    </div>
    <div class="card-body p-0">

        <?php if(empty($data)): ?>
        <div style="text-align:center;padding:50px;color:#9ca3af;">
            <i class="bi bi-inbox" style="font-size:40px;display:block;margin-bottom:12px;"></i>
            Tidak ada data untuk kategori ini hari ini
        </div>
        <?php else: ?>

        <?php if($kategori == 'tidak-hadir'): ?>
        <!-- TIDAK HADIR: tampilkan daftar instansi -->
        <?php foreach($data as $d): ?>
        <div class="instansi-item">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-building" style="color:#dc2626;font-size:18px;"></i>
                </div>
                <div style="font-weight:700;font-size:14px;color:#1e1b4b;"><?= esc($d['nama_instansi']) ?></div>
            </div>
            <span class="badge-pill bp-terlambat">Tidak Hadir</span>
        </div>
        <?php endforeach; ?>

        <?php else: ?>
        <!-- HADIR/TERLAMBAT/IZIN/SAKIT/CUTI: tampilkan daftar pegawai -->
        <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Instansi</th>
                    <?php if(in_array($kategori, ['hadir','terlambat'])): ?>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <?php endif; ?>
                    <?php if($kategori == 'terlambat'): ?>
                    <th>Terlambat</th>
                    <?php endif; ?>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; foreach($data as $d): ?>
            <tr>
                <td style="color:#9ca3af;font-weight:600;"><?= $no++ ?></td>
                <td style="font-weight:700;color:#1e1b4b;"><?= esc($d['nama'] ?? '-') ?></td>
                <td style="font-size:13px;color:#6b7280;"><?= esc($d['nama_instansi'] ?? '-') ?></td>
                <?php if(in_array($kategori, ['hadir','terlambat'])): ?>
                <td style="font-weight:600;"><?= ($d['jam_masuk'] != '00:00:00') ? date('H:i', strtotime($d['jam_masuk'])) : '-' ?></td>
                <td style="font-weight:600;"><?= (!empty($d['jam_pulang']) && $d['jam_pulang'] != '00:00:00') ? date('H:i', strtotime($d['jam_pulang'])) : '-' ?></td>
                <?php endif; ?>
                <?php if($kategori == 'terlambat'): ?>
                <td style="color:#dc2626;font-weight:700;"><?= !empty($d['keterlambatan']) ? $d['keterlambatan'].' menit' : '-' ?></td>
                <?php endif; ?>
                <td>
                    <?php $st = strtolower($d['status'] ?? ''); ?>
                    <?php if($st=='hadir'): ?><span class="badge-pill bp-hadir">Hadir</span>
                    <?php elseif($st=='terlambat'): ?><span class="badge-pill bp-terlambat">Terlambat</span>
                    <?php elseif($st=='izin'): ?><span class="badge-pill bp-izin">Izin</span>
                    <?php elseif($st=='sakit'): ?><span class="badge-pill bp-sakit">Sakit</span>
                    <?php else: ?><span class="badge-pill" style="background:#f3f4f6;color:#6b7280;"><?= ucfirst($st) ?></span>
                    <?php endif; ?>
                </td>
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
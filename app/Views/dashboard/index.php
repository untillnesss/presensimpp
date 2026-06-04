<?php setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian'); ?>
<?= $this->extend('layout/mobile') ?>
<?= $this->section('content') ?>

<!-- HEADER PROFIL -->
<div class="text-center text-white p-4 rounded-4 mb-3" style="background:#0d6efd">
    <img src="<?= base_url('uploads/profil/' . ($profil['foto'] ?? 'default.png')) ?>"
         class="rounded-circle mb-2"
         width="80" height="80">
    <h5 class="fw-bold mb-0"><?= $profil['nama'] ?? '-' ?></h5>
    <small><?= $profil['jabatan'] ?? '-' ?></small><br>
    <small><?= $profil['nama_instansi'] ?? '-' ?></small>
</div>

<!-- TANGGAL & JAM -->
<div class="card p-3 mb-3 text-center">
    <strong><?= strftime('%A, %d %B %Y') ?></strong>
    <span class="text-muted" id="clock"><?= date('H:i:s') ?></span>
</div>

<!-- CARD ABSEN -->
<div class="row g-2 mb-3 align-items-stretch">

    <!-- ABSEN MASUK -->
    <div class="col-6">
        <a href="/presensi?type=masuk" class="text-decoration-none text-dark">
            <div class="card p-3 bg-success-subtle h-100">
                <div class="d-flex align-items-center h-100">
                    <i class="bi bi-box-arrow-in-right fs-2 text-success me-3"></i>
                    <div>
                        <div class="fw-bold">Masuk</div>
                        <div class="fw-semibold">
                            <?= isset($presensiHariIni['jam_masuk']) 
                                ? date('H:i', strtotime($presensiHariIni['jam_masuk'])) . ' WIB'
                                : '--:--' ?>
                        </div>
                        <?php if(isset($presensiHariIni['status'])): ?>
                            <?php if($presensiHariIni['status'] === 'terlambat'): ?>
                                <span class="badge bg-danger">Terlambat</span>
                            <?php elseif($presensiHariIni['status'] === 'hadir'): ?>
                                <span class="badge bg-success">Tepat Waktu</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <small class="text-muted d-block">
                            <?= date('H:i', strtotime($setting['jam_masuk_mulai'])) ?> - 
                            <?= date('H:i', strtotime($setting['jam_masuk_selesai'])) ?> WIB
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- ABSEN PULANG -->
    <div class="col-6">
        <a href="/presensi?type=pulang" class="text-decoration-none text-dark">
            <div class="card p-3 bg-warning-subtle h-100">
                <div class="d-flex align-items-center h-100">
                    <i class="bi bi-box-arrow-right fs-2 text-warning me-3"></i>
                    <div>
                        <div class="fw-bold">Pulang</div>
                        <div class="fw-semibold">
                            <?= (!empty($presensiHariIni['jam_pulang']) && $presensiHariIni['jam_pulang'] !== '00:00:00')
                                ? date('H:i', strtotime($presensiHariIni['jam_pulang'])) . ' WIB'
                                : '--:--' ?>
                        </div>
                        <?php if(!empty($presensiHariIni['jam_pulang']) && $presensiHariIni['jam_pulang'] !== '00:00:00'): ?>
                            <?php if($presensiHariIni['jam_pulang'] >= $setting['jam_pulang_mulai']): ?>
                                <span class="badge bg-success">Sesuai Waktu</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Pulang Cepat</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <small class="text-muted d-block">
                            <?= date('H:i', strtotime($setting['jam_pulang_mulai'])) ?> - 
                            <?= date('H:i', strtotime($setting['jam_pulang_selesai'])) ?> WIB
                        </small>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>

<!-- TABEL PRESENSI HARI INI -->
<div class="card p-3">
    <h6 class="fw-bold mb-2">Presensi Hari Ini</h6>

    <table class="table table-sm mb-0">
        <tr>
            <td width="40%">Masuk</td>
            <td>
                <?= (!empty($presensiHariIni['jam_masuk']) && $presensiHariIni['jam_masuk'] !== '00:00:00')
                    ? date('H:i', strtotime($presensiHariIni['jam_masuk'])) . ' WIB'
                    : '-' ?>
            </td>
        </tr>
        <tr>
            <td>Pulang</td>
            <td>
                <?= (!empty($presensiHariIni['jam_pulang']) && $presensiHariIni['jam_pulang'] !== '00:00:00')
                    ? date('H:i', strtotime($presensiHariIni['jam_pulang'])) . ' WIB'
                    : '-' ?>
            </td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <?php
                $status = $presensiHariIni['status'] ?? '';
                if (!$presensiHariIni || empty($status)):
                ?>
                    <span class="badge bg-secondary">Belum Absen</span>
                <?php elseif ($status === 'terlambat'): ?>
                    <span class="badge bg-danger">Terlambat</span>
                <?php elseif ($status === 'hadir'): ?>
                    <span class="badge bg-success">Hadir</span>
                <?php elseif ($status === 'izin'): ?>
                    <span class="badge bg-info text-dark">Izin</span>
                <?php elseif ($status === 'sakit'): ?>
                    <span class="badge bg-warning text-dark">Sakit</span>
                <?php else: ?>
                    <span class="badge bg-secondary"><?= ucfirst($status) ?></span>
                <?php endif; ?>
            </td>
        </tr>

        <?php if (!empty($presensiHariIni) && $presensiHariIni['status'] === 'terlambat' && $menitTerlambat > 0): ?>
        <tr>
            <td>Terlambat</td>
            <td>
                <span class="text-danger fw-semibold">
                    <i class="bi bi-clock-history me-1"></i>
                    <?= $menitTerlambat ?> menit
                </span>
                <small class="text-muted d-block">
                    Batas masuk: <?= date('H:i', strtotime($setting['jam_masuk_selesai'])) ?> WIB
                </small>
            </td>
        </tr>
        <?php endif; ?>

    </table>
</div>

<script>
setInterval(() => {
    document.getElementById('clock').innerText =
        new Date().toLocaleTimeString('id-ID');
}, 1000);
</script>

<?= $this->endSection() ?>
<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4>Dashboard</h4>
    <p>Ringkasan kehadiran instansi hari ini — <?= date('l, d F Y') ?></p>
</div>

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <a href="/admin/dashboard/hadir" class="text-decoration-none">
            <div class="stat-card" style="background:linear-gradient(135deg,#1e1b4b,#3730a3);color:white;box-shadow:0 8px 30px rgba(55,48,163,0.35);">
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-lbl">Instansi Hadir</div>
                    <div class="stat-num"><?= $jumlahHadir ?></div>
                    <div class="stat-sub">instansi sudah hadir</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/dashboard/tidak-hadir" class="text-decoration-none">
            <div class="stat-card" style="background:linear-gradient(135deg,#2d2a6e,#4f46e5);color:white;box-shadow:0 8px 30px rgba(79,70,229,0.35);">
                <div class="stat-icon"><i class="bi bi-person-x-fill"></i></div>
                <div>
                    <div class="stat-lbl">Tidak Hadir</div>
                    <div class="stat-num"><?= $jumlahTidakHadir ?></div>
                    <div class="stat-sub">instansi tidak hadir</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/pengajuan" class="text-decoration-none">
            <div class="stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);color:white;box-shadow:0 8px 30px rgba(59,130,246,0.35);">
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-lbl">Pengajuan Pending</div>
                    <div class="stat-num"><?= $jumlahPending ?></div>
                    <div class="stat-sub">menunggu persetujuan</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- RINGKASAN HARI INI — 1 box, 3 kiri + 3 kanan (sama dengan sekretariat) -->
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Ringkasan Hari Ini</div>
            <div class="card-header-sub"><?= date('l, d F Y') ?></div>
        </div>
        <span style="background:rgba(255,255,255,0.15);color:#fff;padding:5px 14px;border-radius:99px;font-size:11px;font-weight:700;border:1px solid rgba(255,255,255,0.2);">
            <i class="bi bi-circle-fill me-1" style="font-size:7px;color:#10b981;"></i> Live
        </span>
    </div>
    <div class="card-body p-0">
        <div class="row g-0">

            <!-- KOLOM KIRI: Hadir, Tidak Hadir, Terlambat -->
            <div class="col-lg-6" style="border-right:1px solid #eef0fb;">

                <a href="/admin/dashboard/hadir" class="instansi-item text-decoration-none" style="color:inherit;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:44px;height:44px;border-radius:13px;background:#d1fae5;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-check-circle-fill" style="color:#059669;font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1e1b4b;">Instansi Hadir</div>
                            <div style="font-size:12px;color:#9ca3af;">Sudah melakukan presensi</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:26px;font-weight:800;color:#059669;"><?= $jumlahHadir ?></div>
                        <div style="font-size:11px;color:#9ca3af;">instansi</div>
                    </div>
                </a>

                <a href="/admin/dashboard/tidak-hadir" class="instansi-item text-decoration-none" style="color:inherit;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:44px;height:44px;border-radius:13px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-x-circle-fill" style="color:#dc2626;font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1e1b4b;">Instansi Tidak Hadir</div>
                            <div style="font-size:12px;color:#9ca3af;">Belum melakukan presensi</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:26px;font-weight:800;color:#dc2626;"><?= $jumlahTidakHadir ?></div>
                        <div style="font-size:11px;color:#9ca3af;">instansi</div>
                    </div>
                </a>

                <a href="/admin/dashboard/detail/terlambat" class="instansi-item text-decoration-none" style="color:inherit;border-bottom:none;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:44px;height:44px;border-radius:13px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-clock-history" style="color:#dc2626;font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1e1b4b;">Terlambat</div>
                            <div style="font-size:12px;color:#9ca3af;">Masuk melewati jam masuk</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:26px;font-weight:800;color:#dc2626;"><?= $jumlahTerlambat ?></div>
                        <div style="font-size:11px;color:#9ca3af;">pegawai</div>
                    </div>
                </a>

            </div>

            <!-- KOLOM KANAN: Izin, Sakit -->
            <div class="col-lg-6">

                <a href="/admin/dashboard/detail/izin" class="instansi-item text-decoration-none" style="color:inherit;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:44px;height:44px;border-radius:13px;background:#fef9c3;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-person-check" style="color:#b45309;font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1e1b4b;">Izin</div>
                            <div style="font-size:12px;color:#9ca3af;">Pegawai izin hari ini</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:26px;font-weight:800;color:#b45309;"><?= $jumlahIzin ?></div>
                        <div style="font-size:11px;color:#9ca3af;">pegawai</div>
                    </div>
                </a>

                <a href="/admin/dashboard/detail/sakit" class="instansi-item text-decoration-none" style="color:inherit;">
                    <div style="display:flex;align-items:center;gap:16px;">
                        <div style="width:44px;height:44px;border-radius:13px;background:#dbeafe;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-heart-pulse" style="color:#2563eb;font-size:20px;"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#1e1b4b;">Sakit</div>
                            <div style="font-size:12px;color:#9ca3af;">Pegawai sakit hari ini</div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:26px;font-weight:800;color:#2563eb;"><?= $jumlahSakit ?></div>
                        <div style="font-size:11px;color:#9ca3af;">pegawai</div>
                    </div>
                </a>

                </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

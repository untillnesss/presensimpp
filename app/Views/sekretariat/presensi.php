<?= $this->extend('layout/sekretariat') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <h4>Data Presensi</h4>
    <p>Lihat dan unduh rekap laporan presensi pegawai</p>
</div>

<!-- FILTER & REKAP -->
<div class="card mb-4">
    <div class="card-body p-4">
        <div style="font-size:13px;font-weight:800;color:#6366f1;text-transform:uppercase;letter-spacing:1px;margin-bottom:16px;">
            <i class="bi bi-funnel me-2"></i>Filter & Unduh Rekap
        </div>

        <form method="get" action="/sekretariat/presensi" id="formFilter">
            <div class="row g-3 align-items-end">

                <!-- Pilih Periode -->
                <div class="col-md-2">
                    <label class="form-label">Periode</label>
                    <select name="periode" id="selectPeriode" class="form-select" onchange="toggleFilter()">
                        <option value="bulanan"  <?= ($periode=='bulanan') ?'selected':'' ?>>Bulanan</option>
                        <option value="mingguan" <?= ($periode=='mingguan')?'selected':'' ?>>Mingguan</option>
                        <option value="harian"   <?= ($periode=='harian')  ?'selected':'' ?>>Harian</option>
                    </select>
                </div>

                <!-- BULANAN: Bulan + Tahun -->
                <div class="col-md-2 filter-bulanan">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        <?php for($i=1;$i<=12;$i++): ?>
                        <option value="<?= $i ?>" <?= ($bulan==$i)?'selected':'' ?>>
                            <?= date('F', mktime(0,0,0,$i,1)) ?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2 filter-bulanan filter-mingguan">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        <?php for($t=2024;$t<=date('Y');$t++): ?>
                        <option value="<?= $t ?>" <?= ($tahun==$t)?'selected':'' ?>><?= $t ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- MINGGUAN: Nomor Minggu -->
                <div class="col-md-2 filter-mingguan">
                    <label class="form-label">Minggu ke-</label>
                    <select name="minggu" class="form-select">
                        <?php for($w=1;$w<=$totalMinggu;$w++):
                            $senin  = date('d M', strtotime("{$tahun}-W{$w}-1"));
                            $mingguAkhir = date('d M', strtotime("{$tahun}-W{$w}-7"));
                        ?>
                        <option value="<?= $w ?>" <?= ($minggu==$w)?'selected':'' ?>>
                            Minggu <?= $w ?> (<?= $senin ?> – <?= $mingguAkhir ?>)
                        </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- HARIAN: Tanggal -->
                <div class="col-md-3 filter-harian">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control"
                           value="<?= $tanggal ?>" max="<?= date('Y-m-d') ?>">
                </div>

                <!-- Instansi -->
                <div class="col-md-3">
                    <label class="form-label">Instansi</label>
                    <select name="instansi" class="form-select">
                        <option value="">Semua Instansi</option>
                        <?php foreach($instansi as $ins): ?>
                        <option value="<?= $ins['id_instansi'] ?>" <?= ($selectedInstansi==$ins['id_instansi'])?'selected':'' ?>>
                            <?= esc($ins['nama_instansi']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tombol Tampilkan -->
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>

            </div>
        </form>

        <!-- TOMBOL UNDUH EXCEL -->
        <div class="mt-3">
            <a href="/sekretariat/presensi/rekap?periode=<?= $periode ?>&bulan=<?= $bulan ?>&tahun=<?= $tahun ?>&minggu=<?= $minggu ?>&tanggal=<?= $tanggal ?>&instansi=<?= $selectedInstansi ?>"
               class="btn-rekap btn-rekap-excel">
                <i class="bi bi-file-earmark-excel"></i> Unduh Laporan Excel
            </a>
        </div>
    </div>
</div>

<!-- LABEL PERIODE AKTIF -->
<div class="mb-3" style="font-size:13px;color:#6b7280;">
    <?php
        if ($periode === 'harian') {
            echo '<i class="bi bi-calendar-day me-1 text-primary"></i>Menampilkan data: <strong>' . date('l, d F Y', strtotime($tanggal)) . '</strong>';
        } elseif ($periode === 'mingguan') {
            $senin_  = date('d F Y', strtotime("{$tahun}-W{$minggu}-1"));
            $minggu_ = date('d F Y', strtotime("{$tahun}-W{$minggu}-7"));
            echo "<i class=\"bi bi-calendar-week me-1 text-primary\"></i>Menampilkan data: <strong>Minggu ke-{$minggu} ({$senin_} – {$minggu_})</strong>";
        } else {
            echo '<i class="bi bi-calendar-month me-1 text-primary"></i>Menampilkan data: <strong>' . date('F', mktime(0,0,0,$bulan,1)) . ' ' . $tahun . '</strong>';
        }
        if (!empty($namaInstansiFilter)) echo ' &nbsp;|&nbsp; Instansi: <strong>' . esc($namaInstansiFilter) . '</strong>';
    ?>
</div>

<!-- TABEL DATA -->
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Data Presensi</div>
            <div class="card-header-sub">
                <?= !empty($namaInstansiFilter) ? esc($namaInstansiFilter) : 'Semua Instansi' ?>
            </div>
        </div>
        <span style="background:rgba(255,255,255,0.15);color:#fff;padding:6px 16px;border-radius:99px;font-size:12px;font-weight:700;">
            <?= count($presensi) ?> data
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Instansi</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($presensi)): ?>
                <tr>
                    <td colspan="9" style="text-align:center;padding:50px;color:#9ca3af;">
                        <i class="bi bi-inbox" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                        Tidak ada data presensi untuk filter ini
                    </td>
                </tr>
                <?php else: ?>
                <?php $no=1; foreach($presensi as $p): ?>
                <tr>
                    <td style="color:#9ca3af;font-weight:600;"><?= $no++ ?></td>
                    <td style="font-weight:700;color:#1e1b4b;"><?= esc($p['nama']) ?></td>
                    <td style="font-size:13px;color:#6b7280;"><?= esc($p['nama_instansi']??'-') ?></td>
                    <td><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                    <td style="font-weight:600;"><?= ($p['jam_masuk']!='00:00:00') ? date('H:i',strtotime($p['jam_masuk'])) : '-' ?></td>
                    <td style="font-weight:600;"><?= (!empty($p['jam_pulang'])&&$p['jam_pulang']!='00:00:00') ? date('H:i',strtotime($p['jam_pulang'])) : '-' ?></td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;">
                            <?php if(!empty($p['foto_masuk'])): ?>
                                <img src="/uploads/presensi/<?= esc($p['foto_masuk']) ?>"
                                     width="38" height="38"
                                     style="border-radius:8px;object-fit:cover;cursor:pointer;border:2px solid #c7d2fe;"
                                     title="Foto Masuk"
                                     onclick="zoomFoto(this.src, 'Foto Masuk — <?= esc($p['nama']) ?>')">
                            <?php else: ?>
                                <span style="font-size:11px;color:#d1d5db;">—</span>
                            <?php endif; ?>
                            <?php if(!empty($p['foto_pulang'])): ?>
                                <img src="/uploads/presensi/<?= esc($p['foto_pulang']) ?>"
                                     width="38" height="38"
                                     style="border-radius:8px;object-fit:cover;cursor:pointer;border:2px solid #a7f3d0;"
                                     title="Foto Pulang"
                                     onclick="zoomFoto(this.src, 'Foto Pulang — <?= esc($p['nama']) ?>')">
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php $st=strtolower($p['status']??''); ?>
                        <?php if($st=='hadir'): ?><span class="badge-status badge-hadir">Hadir</span>
                        <?php elseif($st=='terlambat'): ?><span class="badge-status badge-terlambat">Terlambat</span>
                        <?php elseif($st=='izin'): ?><span class="badge-status badge-izin">Izin</span>
                        <?php elseif($st=='sakit'): ?><span class="badge-status badge-sakit">Sakit</span>
                        <?php else: ?><span class="badge-status" style="background:#f3f4f6;color:#6b7280;"><?= ucfirst($st) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($p['keterlambatan'])): ?>
                            <span style="color:#dc2626;font-weight:700;font-size:13px;"><?= $p['keterlambatan'] ?> menit</span>
                        <?php else: ?>
                            <span style="color:#d1d5db;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalFotoSekretariat" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.85);z-index:9999;cursor:pointer;" onclick="this.style.display='none'">
    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;">
        <div id="modalFotoLabel" style="color:#fff;font-size:14px;font-weight:700;margin-bottom:12px;"></div>
        <img id="modalFotoImg" style="max-width:90%;max-height:80vh;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,0.5);">
        <div style="color:#9ca3af;font-size:12px;margin-top:12px;">Klik di mana saja untuk menutup</div>
    </div>
</div>
<script>
function zoomFoto(src, label) {
    document.getElementById('modalFotoImg').src = src;
    document.getElementById('modalFotoLabel').textContent = label || '';
    document.getElementById('modalFotoSekretariat').style.display = 'flex';
    event.stopPropagation();
}
function toggleFilter() {

    var p = document.getElementById('selectPeriode').value;
    document.querySelectorAll('.filter-bulanan').forEach(function(el){ el.style.display = (p==='bulanan') ? '' : 'none'; });
    document.querySelectorAll('.filter-mingguan').forEach(function(el){ el.style.display = (p==='mingguan') ? '' : 'none'; });
    document.querySelectorAll('.filter-harian').forEach(function(el){ el.style.display = (p==='harian') ? '' : 'none'; });
    // tahun untuk mingguan
    var tahunEl = document.querySelector('.filter-mingguan.filter-bulanan');
    if(tahunEl) tahunEl.style.display = (p==='bulanan'||p==='mingguan') ? '' : 'none';
}
// Jalankan saat halaman load
toggleFilter();
</script>

<?= $this->endSection() ?>

<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4>Data Presensi</h4>
        <p>Kelola data presensi seluruh pegawai</p>
    </div>
    <a href="/admin/presensi/tambah" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah
    </a>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<!-- ── FILTER BAR ─────────────────────────────────────────── -->
<div style="background:#fff;border-radius:16px;padding:16px 20px;margin-bottom:20px;box-shadow:0 4px 20px rgba(99,102,241,0.08);border:1px solid #eef0fb;">
    <form method="get" action="/admin/presensi" id="filterForm">
        <div class="d-flex flex-wrap gap-2 align-items-end">

            <!-- FILTER JENIS -->
            <div>
                <div style="font-size:11px;font-weight:700;color:#6b7280;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">Periode</div>
                <select name="filter" id="inputFilter"
                    onchange="setFilter(this.value)"
                    style="padding:7px 14px;border-radius:10px;border:1.5px solid #e5e7eb;font-family:inherit;font-size:13px;font-weight:700;color:#374151;background:#f9fafb;cursor:pointer;">
                    <option value="harian"   <?= $filter=='harian'   ? 'selected' : '' ?>>Harian</option>
                    <option value="mingguan" <?= $filter=='mingguan' ? 'selected' : '' ?>>Mingguan</option>
                    <option value="bulanan"  <?= $filter=='bulanan'  ? 'selected' : '' ?>>Bulanan</option>
                </select>
            </div>

            <!-- FILTER HARIAN: pilih tanggal -->
            <div id="wrapHarian" style="<?= $filter=='harian' ? '' : 'display:none;' ?>">
                <div style="font-size:11px;font-weight:700;color:#6b7280;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">Tanggal</div>
                <input type="date" name="tanggal" id="inputTanggal"
                       value="<?= $tanggal ?? date('Y-m-d') ?>"
                       max="<?= date('Y-m-d') ?>"
                       style="padding:7px 12px;border-radius:10px;border:1.5px solid #e5e7eb;font-family:inherit;font-size:13px;color:#374151;background:#f9fafb;">
            </div>

            <!-- FILTER MINGGUAN: pilih minggu -->
            <div id="wrapMinggu" style="<?= $filter=='mingguan' ? '' : 'display:none;' ?>">
                <div style="font-size:11px;font-weight:700;color:#6b7280;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">Minggu ke-</div>
                <input type="week" name="minggu_picker" id="mingguPicker"
                       value="<?= $tahun ?>-W<?= str_pad($minggu,2,'0',STR_PAD_LEFT) ?>"
                       style="padding:7px 12px;border-radius:10px;border:1.5px solid #e5e7eb;font-family:inherit;font-size:13px;color:#374151;background:#f9fafb;"
                       onchange="updateMinggu(this.value)">
                <input type="hidden" name="minggu" id="inputMinggu" value="<?= $minggu ?>">
                <input type="hidden" name="tahun"  id="inputTahunMinggu" value="<?= $tahun ?>">
            </div>

            <!-- FILTER BULANAN: bulan + tahun -->
            <div id="wrapBulan" style="<?= $filter=='bulanan' ? '' : 'display:none;' ?> display:flex;gap:8px;align-items:flex-end;">
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">Bulan</div>
                    <select name="bulan" style="padding:7px 12px;border-radius:10px;border:1.5px solid #e5e7eb;font-family:inherit;font-size:13px;color:#374151;background:#f9fafb;">
                        <?php
                        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        for($m=1;$m<=12;$m++):
                        ?>
                        <option value="<?= $m ?>" <?= $bulan==$m?'selected':'' ?>><?= $namaBulan[$m] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <div style="font-size:11px;font-weight:700;color:#6b7280;letter-spacing:0.8px;text-transform:uppercase;margin-bottom:6px;">Tahun</div>
                    <select name="tahun" style="padding:7px 12px;border-radius:10px;border:1.5px solid #e5e7eb;font-family:inherit;font-size:13px;color:#374151;background:#f9fafb;">
                        <?php foreach($daftarTahun as $y): ?>
                        <option value="<?= $y ?>" <?= $tahun==$y?'selected':'' ?>><?= $y ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- TOMBOL TERAPKAN -->
            <div style="margin-top:auto;">
                <button type="submit" style="padding:8px 20px;border-radius:10px;background:linear-gradient(135deg,#1e1b4b,#2d2a6e);color:#fff;border:none;font-size:13px;font-weight:700;cursor:pointer;">
                    <i class="bi bi-funnel-fill me-1"></i> Terapkan
                </button>
            </div>

        </div>
    </form>
</div>

<!-- ── TABEL ────────────────────────────────────────────────── -->
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">
                <?php
                if($filter=='harian') echo 'Presensi — '.date('d M Y', strtotime($tanggal));
                elseif($filter=='mingguan') echo 'Presensi Minggu ke-'.$minggu.' Tahun '.$tahun;
                else {
                    $nb=['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    echo 'Presensi '.$nb[$bulan].' '.$tahun;
                }
                ?>
            </div>
            <div class="card-header-sub"><?= count($presensi) ?> data ditemukan</div>
        </div>
        <span style="background:rgba(255,255,255,0.15);color:#fff;padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;border:1px solid rgba(255,255,255,0.2);">
            <?= count($presensi) ?> Record
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th style="width:36px;">No</th>
                        <th>Nama</th>
                        <th>No. ID</th>
                        <th>Instansi</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Foto</th>
                        <th>Status</th>
                        <th style="width:50px;">Telat</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($presensi)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;padding:48px;color:#9ca3af;">
                        <div style="font-size:40px;margin-bottom:10px;">📭</div>
                        Tidak ada data untuk periode ini
                    </td>
                </tr>
                <?php else: $no=1; foreach($presensi as $p): ?>
                <tr>
                    <td style="font-weight:700;color:#6366f1;font-size:12px;"><?= $no++ ?></td>
                    <td style="font-weight:700;color:#111827;white-space:nowrap;"><?= esc($p['nama']) ?></td>
                    <td><span style="font-family:monospace;font-size:11px;background:#f3f4f6;padding:2px 7px;border-radius:6px;color:#374151;white-space:nowrap;"><?= esc($p['no_id_pegawai'] ?? '—') ?></span></td>
                    <td style="font-size:12px;color:#6b7280;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($p['nama_instansi'] ?? '-') ?></td>
                    <td style="font-size:12.5px;font-weight:600;color:#374151;white-space:nowrap;"><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                    <td>
                        <?php if($p['jam_masuk'] != '00:00:00'): ?>
                            <span style="background:#d1fae5;color:#065f46;padding:3px 9px;border-radius:7px;font-size:12px;font-weight:700;"><?= date('H:i', strtotime($p['jam_masuk'])) ?></span>
                        <?php else: ?><span style="color:#c9d0e8;font-size:12px;">—</span><?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($p['jam_pulang']) && $p['jam_pulang'] != '00:00:00'): ?>
                            <span style="background:#cffafe;color:#164e63;padding:3px 9px;border-radius:7px;font-size:12px;font-weight:700;"><?= date('H:i', strtotime($p['jam_pulang'])) ?></span>
                        <?php else: ?><span style="color:#c9d0e8;font-size:12px;">—</span><?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;">
                        <?php if(!empty($p['foto_masuk'])): ?>
                            <img src="/uploads/presensi/<?= $p['foto_masuk'] ?>" width="36" height="36"
                                 style="border-radius:8px;object-fit:cover;cursor:pointer;border:1.5px solid #e5e7eb;"
                                 onclick="previewFoto(this.src,'Foto Masuk')"
                                 title="Foto Masuk">
                        <?php endif; ?>
                        <?php if(!empty($p['foto_pulang'])): ?>
                            <img src="/uploads/presensi/<?= $p['foto_pulang'] ?>" width="36" height="36"
                                 style="border-radius:8px;object-fit:cover;cursor:pointer;border:1.5px solid #e5e7eb;"
                                 onclick="previewFoto(this.src,'Foto Pulang')"
                                 title="Foto Pulang">
                        <?php endif; ?>
                        <?php if(empty($p['foto_masuk']) && empty($p['foto_pulang'])): ?>
                            <span style="color:#c9d0e8;font-size:12px;">—</span>
                        <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php $st = strtolower($p['status'] ?? ''); ?>
                        <?php if($st=='hadir'): ?>
                            <span class="badge-status badge-hadir">Hadir</span>
                        <?php elseif($st=='terlambat'): ?>
                            <span class="badge-status badge-terlambat">Terlambat</span>
                        <?php elseif($st=='izin'): ?>
                            <span class="badge-status badge-izin">Izin</span>
                        <?php elseif($st=='sakit'): ?>
                            <span class="badge-status badge-sakit">Sakit</span>
                        <?php else: ?>
                            <span style="color:#6b7280;font-size:12px;"><?= ucfirst($st) ?></span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:12.5px;font-weight:700;color:<?= !empty($p['keterlambatan']) ? '#dc2626' : '#c9d0e8' ?>;">
                        <?= !empty($p['keterlambatan']) ? $p['keterlambatan'].' mnt' : '—' ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL FOTO -->
<div class="modal fade" id="modalFoto" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius:20px;overflow:hidden;background:#1e1b4b;">
      <div class="modal-header border-0 px-4 pt-3 pb-0">
        <span id="modalFotoLabel" style="color:#fff;font-weight:700;font-size:14px;"></span>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-3 text-center">
        <img id="imgPreview" src="" class="img-fluid" style="border-radius:12px;max-height:75vh;">
      </div>
    </div>
  </div>
</div>

<script>
function previewFoto(src, label) {
    document.getElementById('imgPreview').src = src;
    document.getElementById('modalFotoLabel').textContent = label;
    new bootstrap.Modal(document.getElementById('modalFoto')).show();
}

function setFilter(val) {
    document.getElementById('wrapHarian').style.display = val === 'harian'   ? '' : 'none';
    document.getElementById('wrapMinggu').style.display = val === 'mingguan' ? '' : 'none';
    document.getElementById('wrapBulan').style.display  = val === 'bulanan'  ? 'flex' : 'none';
} else {
                btn.style.background = '#f9fafb';
                btn.style.color = '#6b7280';
                btn.style.borderColor = '#e5e7eb';
            }
        });
    });
}

function updateMinggu(val) {
    // val = "2026-W15"
    const parts = val.split('-W');
    document.getElementById('inputTahunMinggu').value = parts[0];
    document.getElementById('inputMinggu').value = parts[1];
}
</script>

<?= $this->endSection() ?>
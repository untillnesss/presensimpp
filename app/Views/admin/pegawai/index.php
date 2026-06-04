<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<?php $tab = $_GET['tab'] ?? 'daftar'; ?>

<div class="page-header page-header-line">
    <div>
        <h4>Kelola Pegawai</h4>
        <p>Manajemen akun dan persetujuan pegawai</p>
    </div>
    <?php if(!empty($menunggu)): ?>
    <span style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d;padding:6px 14px;border-radius:99px;font-size:13px;font-weight:700;">
        ⏳ <?= count($menunggu) ?> Menunggu Persetujuan
    </span>
    <?php endif; ?>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i><span><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i><?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<!-- ===== TAB NAVIGATION ===== -->
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <button onclick="switchTab('daftar')" id="tabDaftar"
        class="tab-btn <?= $tab=='daftar' ? 'tab-active' : '' ?>">
        <i class="bi bi-people-fill me-1"></i> Daftar Pegawai
        <span style="background:rgba(255,255,255,0.25);padding:1px 8px;border-radius:99px;margin-left:4px;font-size:11px;"><?= count($pegawai) ?></span>
    </button>
    <button onclick="switchTab('menunggu')" id="tabMenunggu"
        class="tab-btn <?= $tab=='menunggu' ? 'tab-active' : '' ?>">
        <i class="bi bi-hourglass-split me-1"></i> Menunggu Persetujuan
        <?php if(!empty($menunggu)): ?>
        <span style="background:#ef4444;color:#fff;padding:1px 8px;border-radius:99px;margin-left:4px;font-size:11px;"><?= count($menunggu) ?></span>
        <?php endif; ?>
    </button>
    <button onclick="switchTab('tambah')" id="tabTambah"
        class="tab-btn <?= $tab=='tambah' ? 'tab-active' : '' ?>">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Manual
    </button>
</div>

<style>
.tab-btn {
    padding:10px 18px;border-radius:10px;font-weight:700;font-size:13px;
    cursor:pointer;border:2px solid #e5e7eb;background:#fff;color:#374151;
    font-family:'Plus Jakarta Sans',sans-serif;transition:all 0.15s;
}
.tab-active {
    background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;border-color:#6366f1;
    box-shadow:0 4px 14px rgba(99,102,241,0.35);
}
</style>

<!-- ===== PANEL: DAFTAR PEGAWAI ===== -->
<div id="panelDaftar" style="display:<?= $tab=='daftar' ? 'block' : 'none' ?>;">
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Daftar Pegawai Aktif</div>
            <div class="card-header-sub"><?= count($pegawai) ?> pegawai terdaftar</div>
        </div>
        <i class="bi bi-people-fill" style="color:#fff;font-size:22px;"></i>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th style="width:36px;">No</th>
                        <th>Nama</th>
                        <th>No. ID</th>
                        <th>Email</th>
                        <th>Instansi</th>
                        <th>Jabatan</th>
                        <th>Status Akun</th>
                        <th>Perangkat</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($pegawai)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:48px;color:#9ca3af;">
                        <div style="font-size:40px;margin-bottom:10px;">👤</div>
                        Belum ada pegawai aktif
                    </td>
                </tr>
                <?php else: $no=1; foreach($pegawai as $p): ?>
                <tr>
                    <td style="font-weight:700;color:#6366f1;font-size:12px;"><?= $no++ ?></td>
                    <td style="font-weight:700;color:#111827;"><?= esc($p['nama']) ?></td>
                    <td><span style="font-family:monospace;font-size:12px;background:#f3f4f6;padding:3px 8px;border-radius:6px;"><?= esc($p['no_id']) ?></span></td>
                    <td style="font-size:12px;color:#6b7280;"><?= esc($p['email'] ?? '-') ?></td>
                    <td style="font-size:12px;color:#6b7280;"><?= esc($p['nama_instansi'] ?? '-') ?></td>
                    <td style="font-size:12px;color:#6b7280;"><?= esc($p['jabatan'] ?? '-') ?></td>
                    <td>
                        <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;">✅ Aktif</span>
                    </td>
                    <td>
                        <?php if(!empty($p['device_token'])): ?>
                        <span style="background:#d1fae5;color:#065f46;padding:3px 8px;border-radius:99px;font-size:11px;font-weight:700;display:inline-block;margin-bottom:4px;"> Terdaftar</span><br>
                        <a href="/admin/pegawai/reset-device/<?= $p['id_user'] ?>"
                           onclick="return confirm('Reset perangkat <?= esc($p['nama'] ?? '') ?>?')"
                           style="font-size:11px;font-weight:700;color:#92400e;background:#fef3c7;padding:3px 8px;border-radius:6px;text-decoration:none;">
                            Reset HP
                        </a>
                        <?php else: ?>
                        <span style="font-size:11px;color:#9ca3af;">— Belum Login</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;flex-direction:column;gap:3px;">
                            <a href="/admin/pegawai/toggle-aktif/<?= $p['id_user'] ?>"
                               style="font-size:11px;font-weight:700;color:#1d4ed8;background:#dbeafe;padding:3px 8px;border-radius:6px;text-decoration:none;text-align:center;">
                               Nonaktifkan
                            </a>
                            <a href="/admin/pegawai/delete/<?= $p['id_user'] ?>"
                               onclick="return confirm('Hapus akun <?= esc($p['nama']) ?>?')"
                               style="font-size:11px;font-weight:700;color:#dc2626;background:#fee2e2;padding:3px 8px;border-radius:6px;text-decoration:none;text-align:center;">
                               Hapus
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<!-- ===== PANEL: MENUNGGU PERSETUJUAN ===== -->
<div id="panelMenunggu" style="display:<?= $tab=='menunggu' ? 'block' : 'none' ?>;">
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Akun Menunggu Persetujuan</div>
            <div class="card-header-sub">Pegawai yang mendaftar sendiri lewat aplikasi</div>
        </div>
        <i class="bi bi-person-check" style="color:#fff;font-size:22px;"></i>
    </div>
    <div class="card-body p-0">
        <?php if(empty($menunggu)): ?>
        <div style="text-align:center;padding:60px;color:#9ca3af;">
            <div style="font-size:48px;margin-bottom:12px;">✅</div>
            <div style="font-weight:700;font-size:15px;margin-bottom:4px;">Tidak ada akun yang menunggu</div>
            <div style="font-size:13px;">Semua pendaftaran sudah diproses</div>
        </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No. ID</th>
                        <th>Instansi</th>
                        <th>Email</th>
                        <th>Waktu Daftar</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($menunggu as $m): ?>
                <tr>
                    <td style="font-weight:700;color:#111827;"><?= esc($m['nama'] ?? '—') ?></td>
                    <td><span style="font-family:monospace;font-size:11px;background:#f3f4f6;padding:2px 7px;border-radius:6px;"><?= esc($m['no_id'] ?? '—') ?></span></td>
                    <td style="font-size:12px;color:#6b7280;"><?= esc($m['nama_instansi'] ?? '—') ?></td>
                    <td style="font-size:12px;color:#6b7280;"><?= esc($m['email']) ?></td>
                    <td style="font-size:11px;color:#9ca3af;white-space:nowrap;"><?= date('d M Y H:i', strtotime($m['created_at'])) ?></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <a href="/admin/pegawai/setujui/<?= $m['id_user'] ?>"
                               onclick="return confirm('Setujui akun <?= esc($m['nama'] ?? $m['email']) ?>?')"
                               style="flex:1;text-align:center;font-size:12px;font-weight:700;color:#065f46;background:#d1fae5;padding:6px 8px;border-radius:8px;text-decoration:none;">
                               ✅ Setujui
                            </a>
                            <a href="/admin/pegawai/tolak/<?= $m['id_user'] ?>"
                               onclick="return confirm('Tolak dan hapus akun <?= esc($m['nama'] ?? $m['email']) ?>?')"
                               style="flex:1;text-align:center;font-size:12px;font-weight:700;color:#991b1b;background:#fee2e2;padding:6px 8px;border-radius:8px;text-decoration:none;">
                               ❌ Tolak
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
</div>

<!-- ===== PANEL: TAMBAH MANUAL ===== -->
<div id="panelTambah" style="display:<?= $tab=='tambah' ? 'block' : 'none' ?>;">
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Tambah Akun Pegawai Manual</div>
            <div class="card-header-sub">Untuk pegawai baru atau HP rusak yang tidak bisa daftar sendiri</div>
        </div>
        <i class="bi bi-person-plus-fill" style="color:#fff;font-size:22px;"></i>
    </div>
    <div class="card-body p-4">
        <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #86efac;border-radius:12px;padding:14px 18px;margin-bottom:24px;">
            <div style="font-weight:700;color:#166534;font-size:13px;margin-bottom:4px;">
                <i class="bi bi-lightbulb-fill me-1"></i>Gunakan form ini ketika:
            </div>
            <div style="font-size:12px;color:#15803d;">
                Pegawai baru pertama kali bertugas di MPP dan belum punya akun, atau HP-nya rusak.
                Setelah akun dibuat, catat presensinya lewat menu <strong>Data Presensi → Tambah</strong>.
            </div>
        </div>

        <form method="post" action="/admin/pegawai/simpan">

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="bi bi-person me-1" style="color:#6366f1;"></i>Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" value="<?= old('nama') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="bi bi-card-text me-1" style="color:#6366f1;"></i>No. ID / NIP / NIK <span class="text-danger">*</span></label>
                <input type="text" name="no_id" class="form-control" placeholder="Contoh: 199001012020011001" value="<?= old('no_id') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="bi bi-briefcase me-1" style="color:#6366f1;"></i>Jabatan <span class="text-danger">*</span></label>
                <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Petugas Pelayanan BPJS" value="<?= old('jabatan') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="bi bi-building me-1" style="color:#6366f1;"></i>Instansi <span class="text-danger">*</span></label>
                <select name="id_instansi" class="form-select" required>
                    <option value="">-- Pilih Instansi --</option>
                    <?php foreach($instansi as $i): ?>
                    <option value="<?= $i['id_instansi'] ?>" <?= old('id_instansi') == $i['id_instansi'] ? 'selected' : '' ?>>
                        <?= esc($i['nama_instansi']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <hr style="border-color:#e5e7eb;margin:20px 0;">
            <div style="font-size:12px;font-weight:700;color:#6b7280;letter-spacing:1px;text-transform:uppercase;margin-bottom:14px;">
                <i class="bi bi-shield-lock me-1"></i>Data Login
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold"><i class="bi bi-envelope me-1" style="color:#6366f1;"></i>Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="Contoh: budi@bpjs.go.id" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold"><i class="bi bi-key me-1" style="color:#6366f1;"></i>Password <span class="text-danger">*</span></label>
                <div style="position:relative;">
                    <input type="password" name="password" id="inputPassword" class="form-control"
                           placeholder="Minimal 6 karakter" value="pegawai123" required minlength="6"
                           style="padding-right:44px;">
                    <button type="button" onclick="togglePw()" tabindex="-1"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;font-size:16px;">
                        <i class="bi bi-eye" id="iconEye"></i>
                    </button>
                </div>
                <div style="font-size:11px;color:#9ca3af;margin-top:4px;">
                    <i class="bi bi-info-circle me-1"></i>Default: <strong>pegawai123</strong>. Sampaikan ke pegawai untuk login.
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding:13px;justify-content:center;">
                <i class="bi bi-person-check-fill me-2"></i> Buat Akun Pegawai
            </button>
        </form>
    </div>
</div>
</div>
</div>
</div>

<script>
function switchTab(tab) {
    ['daftar','menunggu','tambah'].forEach(function(t) {
        document.getElementById('panel' + t.charAt(0).toUpperCase() + t.slice(1)).style.display = 'none';
        var btn = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
        btn.classList.remove('tab-active');
    });
    document.getElementById('panel' + tab.charAt(0).toUpperCase() + tab.slice(1)).style.display = 'block';
    document.getElementById('tab'   + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('tab-active');
    history.replaceState(null, '', '?tab=' + tab);
}

function togglePw() {
    var inp = document.getElementById('inputPassword');
    var ico = document.getElementById('iconEye');
    if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
    else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
}
</script>

<?= $this->endSection() ?>

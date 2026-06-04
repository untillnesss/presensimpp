<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4>Kelola Akun Pegawai</h4>
        <p>Persetujuan pendaftaran & manajemen perangkat</p>
    </div>
    <?php if(!empty($menunggu)): ?>
    <span style="background:#fef3c7;color:#92400e;border:1px solid #fcd34d;padding:6px 14px;border-radius:99px;font-size:13px;font-weight:700;">
        ⏳ <?= count($menunggu) ?> Menunggu
    </span>
    <?php endif; ?>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i><?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<!-- Tab -->
<div style="display:flex;gap:8px;margin-bottom:20px;">
    <button onclick="switchTab('menunggu')" id="tabMenunggu"
        style="padding:10px 20px;border-radius:10px;font-weight:700;font-size:13px;cursor:pointer;border:2px solid #6366f1;background:#6366f1;color:#fff;">
        ⏳ Menunggu Persetujuan
        <?php if(!empty($menunggu)): ?>
        <span style="background:#fff;color:#6366f1;border-radius:99px;padding:1px 8px;margin-left:4px;font-size:11px;"><?= count($menunggu) ?></span>
        <?php endif; ?>
    </button>
    <button onclick="switchTab('perangkat')" id="tabPerangkat"
        style="padding:10px 20px;border-radius:10px;font-weight:700;font-size:13px;cursor:pointer;border:2px solid #e5e7eb;background:#fff;color:#374151;">
        📱 Manajemen Perangkat
    </button>
</div>

<!-- TAB MENUNGGU -->
<div id="panelMenunggu">
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Akun Menunggu Persetujuan</div>
            <div class="card-header-sub">Periksa data pegawai sebelum menyetujui</div>
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
                        <th>Daftar</th>
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
                            <a href="/admin/akun/setujui/<?= $m['id_user'] ?>"
                               onclick="return confirm('Setujui akun <?= esc($m['nama'] ?? $m['email']) ?>?')"
                               style="flex:1;text-align:center;font-size:12px;font-weight:700;color:#065f46;background:#d1fae5;padding:6px 8px;border-radius:8px;text-decoration:none;">
                               ✅ Setujui
                            </a>
                            <a href="/admin/akun/tolak/<?= $m['id_user'] ?>"
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

<!-- TAB PERANGKAT -->
<div id="panelPerangkat" style="display:none;">
<div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fcd34d;border-radius:14px;padding:14px 18px;margin-bottom:16px;display:flex;gap:12px;align-items:flex-start;">
    <i class="bi bi-info-circle-fill" style="color:#f59e0b;font-size:20px;flex-shrink:0;margin-top:2px;"></i>
    <div style="font-size:12px;color:#92400e;">
        <strong>Reset Perangkat</strong> digunakan jika pegawai berganti HP atau tidak bisa login karena perangkat berbeda. 
        Setelah direset, pegawai bisa login dari HP baru dan perangkat baru tersebut akan terdaftar otomatis.
    </div>
</div>
<div class="card">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Manajemen Perangkat Pegawai</div>
            <div class="card-header-sub">Reset jika pegawai ganti HP atau tidak bisa login</div>
        </div>
        <i class="bi bi-phone" style="color:#fff;font-size:22px;"></i>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>No. ID</th>
                        <th>Instansi</th>
                        <th>Status Perangkat</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($aktif)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:#9ca3af;">Belum ada pegawai aktif</td>
                </tr>
                <?php else: foreach($aktif as $a): ?>
                <tr>
                    <td style="font-weight:700;color:#111827;"><?= esc($a['nama'] ?? '—') ?></td>
                    <td><span style="font-family:monospace;font-size:11px;background:#f3f4f6;padding:2px 7px;border-radius:6px;"><?= esc($a['no_id'] ?? '—') ?></span></td>
                    <td style="font-size:12px;color:#6b7280;"><?= esc($a['nama_instansi'] ?? '—') ?></td>
                    <td>
                        <?php if(!empty($a['device_token'])): ?>
                        <span style="background:#d1fae5;color:#065f46;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;">
                            📱 Terdaftar
                        </span>
                        <?php else: ?>
                        <span style="background:#f3f4f6;color:#9ca3af;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:700;">
                            — Belum Login
                        </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(!empty($a['device_token'])): ?>
                        <a href="/admin/akun/reset-device/<?= $a['id_user'] ?>"
                           onclick="return confirm('Reset perangkat <?= esc($a['nama'] ?? '') ?>? Pegawai bisa login dari HP baru.')"
                           style="display:block;text-align:center;font-size:12px;font-weight:700;color:#92400e;background:#fef3c7;padding:6px 10px;border-radius:8px;text-decoration:none;">
                           🔄 Reset HP
                        </a>
                        <?php else: ?>
                        <span style="font-size:11px;color:#d1d5db;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
function switchTab(tab) {
    document.getElementById('panelMenunggu').style.display  = tab === 'menunggu'  ? 'block' : 'none';
    document.getElementById('panelPerangkat').style.display = tab === 'perangkat' ? 'block' : 'none';

    document.getElementById('tabMenunggu').style.background  = tab === 'menunggu'  ? '#6366f1' : '#fff';
    document.getElementById('tabMenunggu').style.color       = tab === 'menunggu'  ? '#fff' : '#374151';
    document.getElementById('tabMenunggu').style.borderColor = tab === 'menunggu'  ? '#6366f1' : '#e5e7eb';

    document.getElementById('tabPerangkat').style.background  = tab === 'perangkat' ? '#6366f1' : '#fff';
    document.getElementById('tabPerangkat').style.color       = tab === 'perangkat' ? '#fff' : '#374151';
    document.getElementById('tabPerangkat').style.borderColor = tab === 'perangkat' ? '#6366f1' : '#e5e7eb';
}
</script>

<?= $this->endSection() ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Presensi MPP Tuban' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5;
            --sidebar-bg: #1e1b4b; --topbar-bg: #1e1b4b; --body-bg: #eef0fb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); color: #1e1b4b; }

        /* SIDEBAR */
        .sidebar { position:fixed;top:0;left:0;width:260px;height:100vh;background:var(--sidebar-bg);display:flex;flex-direction:column;z-index:200;overflow:hidden; }
        .sidebar::before { content:'';position:absolute;top:-80px;left:-80px;width:260px;height:260px;background:radial-gradient(circle,rgba(99,102,241,0.35) 0%,transparent 70%);pointer-events:none; }
        .sidebar-brand { padding:24px 20px 20px;display:flex;flex-direction:column;align-items:center;border-bottom:1px solid rgba(255,255,255,0.07);position:relative;z-index:1; }
        .sidebar-logo-wrap { width:72px;height:72px;background:linear-gradient(135deg,rgba(99,102,241,0.4),rgba(139,92,246,0.4));border-radius:20px;display:flex;align-items:center;justify-content:center;margin-bottom:12px;border:1.5px solid rgba(99,102,241,0.5);box-shadow:0 8px 24px rgba(99,102,241,0.3); }
        .sidebar-logo-wrap img { width:52px;height:52px;object-fit:contain; }
        .sidebar-brand .brand-name { color:#fff;font-weight:800;font-size:15px;text-align:center; }
        .sidebar-brand .brand-sub { color:rgba(165,180,252,0.7);font-size:11px;margin-top:3px;letter-spacing:1px;text-transform:uppercase; }
        .sidebar-menu { padding:18px 14px;flex:1;overflow-y:auto;position:relative;z-index:1; }
        .sidebar-menu::-webkit-scrollbar { width:4px; }
        .sidebar-menu::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.1);border-radius:99px; }
        .sidebar-label { color:rgba(165,180,252,0.5);font-size:9.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;padding:0 10px 10px; }
        .sidebar-menu a { display:flex;align-items:center;gap:11px;padding:11px 14px;border-radius:12px;color:rgba(199,210,254,0.7);text-decoration:none;font-size:13.5px;font-weight:500;margin-bottom:3px;transition:all 0.2s;position:relative; }
        .sidebar-menu a:hover { background:rgba(99,102,241,0.15);color:#c7d2fe; }
        .sidebar-menu a.active { background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:700;box-shadow:0 6px 20px rgba(99,102,241,0.45); }
        .sidebar-menu a.active::before { content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:60%;background:#fff;border-radius:99px; }
        .sidebar-menu a i { font-size:17px;width:22px;text-align:center;flex-shrink:0; }
        .sidebar-footer { padding:14px;border-top:1px solid rgba(255,255,255,0.07);position:relative;z-index:1; }
        .sidebar-footer a { display:flex;align-items:center;gap:11px;padding:11px 14px;border-radius:12px;color:rgba(199,210,254,0.5);text-decoration:none;font-size:13.5px;transition:all 0.2s; }
        .sidebar-footer a:hover { background:rgba(239,68,68,0.15);color:#fca5a5; }

        /* TOPBAR — warna gelap seperti sidebar */
        .topbar { position:fixed;top:0;left:260px;right:0;height:66px;background:var(--topbar-bg);border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between;padding:0 28px;z-index:99;box-shadow:0 2px 16px rgba(30,27,75,0.3); }
        .topbar-left .topbar-greeting { font-size:13px;color:rgba(165,180,252,0.7);font-weight:500; }
        .topbar-left .topbar-greeting span { color:#fff;font-weight:700; }
        .topbar-profile-btn { display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.12);padding:8px 16px 8px 8px;border-radius:99px;text-decoration:none;transition:all 0.2s; }
        .topbar-profile-btn:hover { background:rgba(255,255,255,0.14);border-color:rgba(99,102,241,0.6); }
        .topbar-avatar-img { width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid rgba(99,102,241,0.6);box-shadow:0 2px 8px rgba(99,102,241,0.3); }
        .topbar-avatar-fallback { width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:800;font-size:16px;display:flex;align-items:center;justify-content:center; }
        .topbar-profile-name { font-size:13px;font-weight:700;color:#fff;display:block; }
        .topbar-profile-role { font-size:11px;color:rgba(165,180,252,0.7);display:block; }

        /* MAIN */
        .main-content { margin-left:260px;margin-top:66px;padding:28px;min-height:calc(100vh - 66px); }
        .page-header { margin-bottom:24px; }
        .page-header h4 { font-weight:800;font-size:22px;color:#1e1b4b; }
        .page-header p { color:#9ca3af;font-size:13px;margin-top:4px; }

        /* CARD */
        .card { border:none;border-radius:18px;box-shadow:0 4px 20px rgba(99,102,241,0.08);background:#fff; }
        .card-header-inner { padding:18px 22px;border-bottom:2px solid #eef0fb;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#1e1b4b,#2d2a6e);border-radius:18px 18px 0 0; }
        .card-header-title { font-weight:800;font-size:15px;color:#fff; }
        .card-header-sub { font-size:12px;color:rgba(165,180,252,0.75);margin-top:2px; }

        /* STAT CARD */
        .stat-card { border-radius:18px;padding:22px 24px;display:flex;align-items:center;gap:18px;transition:transform 0.2s,box-shadow 0.2s;cursor:pointer; }
        .stat-card:hover { transform:translateY(-4px); }
        .stat-icon { width:58px;height:58px;border-radius:16px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;color:white; }
        .stat-lbl { font-size:12px;font-weight:600;opacity:0.85;margin-bottom:4px; }
        .stat-num { font-size:38px;font-weight:800;line-height:1; }
        .stat-sub { font-size:11px;opacity:0.7;margin-top:4px; }

        /* TABLE */
        .table-modern { border-collapse:separate;border-spacing:0;width:100%; }
        .table-modern thead th { background:#f5f6ff;color:#6366f1;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;padding:14px 18px;border-bottom:2px solid #e5e7eb;white-space:nowrap; }
        .table-modern tbody td { padding:14px 18px;font-size:13.5px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle; }
        .table-modern tbody tr:last-child td { border-bottom:none; }
        .table-modern tbody tr:hover td { background:#fafbff; }

        /* BADGE */
        .badge-pill { padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;white-space:nowrap;display:inline-block; }
        .bp-hadir     { background:#d1fae5;color:#059669; }
        .bp-terlambat { background:#fee2e2;color:#dc2626; }
        .bp-izin      { background:#fef9c3;color:#b45309; }
        .bp-sakit     { background:#dbeafe;color:#2563eb; }

        /* BUTTON */
        .btn-primary { background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;border-radius:10px;font-weight:700;padding:9px 22px;font-size:13.5px;box-shadow:0 4px 14px rgba(99,102,241,0.35);font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-primary:hover { background:linear-gradient(135deg,#4f46e5,#4338ca); }
        .btn-sm-action { padding:5px 14px;border-radius:8px;font-size:12px;font-weight:700;border:none;cursor:pointer;text-decoration:none;display:inline-block;transition:all 0.15s;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-edit { background:#fef9c3;color:#92400e; } .btn-edit:hover { background:#fde68a; }
        .btn-hapus { background:#fee2e2;color:#b91c1c; } .btn-hapus:hover { background:#fecaca; }
        .btn-setujui { background:#d1fae5;color:#065f46; } .btn-setujui:hover { background:#a7f3d0; }
        .btn-tolak { background:#fee2e2;color:#b91c1c; } .btn-tolak:hover { background:#fecaca; }

        /* FORM */
        .form-control,.form-select { border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:13.5px;transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif; }
        .form-control:focus,.form-select:focus { border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
        .form-label { font-weight:700;font-size:12.5px;color:#4b5563;margin-bottom:6px; }
        .alert { border-radius:12px;border:none;font-size:13.5px; }

        /* INSTANSI ITEM */
        .instansi-item { display:flex;justify-content:space-between;align-items:center;padding:14px 22px;border-bottom:1px solid #eef0fb;transition:background 0.15s; }
        .instansi-item:last-child { border-bottom:none; }
        .instansi-item:hover { background:#f7f8ff; }

        /* PENGAJUAN CARD */
        .pengajuan-card { background:#fff;border-radius:18px;border:1.5px solid #dde1f7;box-shadow:0 4px 20px rgba(99,102,241,0.1);transition:transform 0.2s,box-shadow 0.2s;overflow:hidden; }
        .pengajuan-card:hover { transform:translateY(-3px);box-shadow:0 8px 30px rgba(99,102,241,0.18); }

        /* SETTING BLOCK */
        .setting-block { border-radius:16px;padding:20px;margin-bottom:16px; }
        .setting-block-title { font-size:11px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:16px;display:flex;align-items:center;gap:7px; }
        .empty-state { text-align:center;padding:60px 20px; }
        .empty-state p { color:#9ca3af;margin-top:14px;font-size:14px; }

        /* BADGE STATUS */
        .badge-status { display:inline-block;padding:4px 12px;border-radius:99px;font-size:11.5px;font-weight:700;white-space:nowrap; }
        .badge-hadir     { background:#d1fae5;color:#059669; }
        .badge-terlambat { background:#fee2e2;color:#dc2626; }
        .badge-izin      { background:#fef9c3;color:#b45309; }
        .badge-sakit     { background:#dbeafe;color:#2563eb; }
        .badge-admin     { background:#eef2ff;color:#4f46e5; }
        .badge-sekretariat { background:#f0fdf4;color:#166534; }
        .badge-pegawai   { background:#f3f4f6;color:#374151; }

        /* LOG ROW (Riwayat) */
        .log-row { display:flex;align-items:flex-start;gap:16px;padding:16px 22px;border-bottom:1px solid #eef0fb;transition:background 0.15s; }
        .log-row:last-child { border-bottom:none; }
        .log-row:hover { background:#f8f9ff; }
        .log-dot { width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0; }

        /* BTN SECONDARY OUTLINE */
        .btn-secondary-outline { display:inline-flex;align-items:center;gap:8px;padding:9px 20px;border-radius:10px;font-size:13.5px;font-weight:700;text-decoration:none;border:1.5px solid #e5e7eb;color:#374151;background:#fff;transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-secondary-outline:hover { border-color:#6366f1;color:#6366f1;background:#f5f6ff; }

        /* BTN REKAP */
        .btn-rekap { display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:10px;font-size:13.5px;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-rekap-excel { background:linear-gradient(135deg,#059669,#10b981);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,0.35); }
        .btn-rekap-excel:hover { background:linear-gradient(135deg,#047857,#059669);color:#fff; }
        .btn-rekap-pdf   { background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;box-shadow:0 4px 14px rgba(239,68,68,0.35); }
        .btn-rekap-pdf:hover { background:linear-gradient(135deg,#b91c1c,#dc2626);color:#fff; }

        /* PAGE HEADER WITH BUTTON */
        .page-header-line { display:flex;justify-content:space-between;align-items:center;margin-bottom:24px; }

        /* ===== MOBILE RESPONSIVE ===== */
        .hamburger { display:none;position:fixed;top:14px;left:16px;z-index:300;background:rgba(255,255,255,0.1);border:1.5px solid rgba(255,255,255,0.2);border-radius:10px;width:42px;height:42px;align-items:center;justify-content:center;cursor:pointer;color:#fff;font-size:20px; }
        .sidebar-overlay { display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:199;backdrop-filter:blur(2px); }

        @media (max-width: 768px) {
            .hamburger { display:flex; }
            .sidebar { transform:translateX(-100%);transition:transform 0.3s ease; }
            .sidebar.open { transform:translateX(0); }
            .sidebar-overlay.open { display:block; }
            .topbar { left:0;padding:0 16px 0 70px; }
            .topbar-left .topbar-greeting { font-size:12px; }
            .topbar-profile-btn { padding:6px 10px 6px 6px;gap:8px; }
            .topbar-profile-name { font-size:12px; }
            .topbar-profile-role { display:none; }
            .main-content { margin-left:0;padding:16px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
<button class="hamburger" id="hamburgerBtn" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo-wrap">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
        </div>
        <div class="brand-name">Presensi MPP</div>
        <div class="brand-sub">Tuban</div>
    </div>
    <div class="sidebar-menu">
        <?php $uri = service('uri'); $seg2 = $uri->getSegment(2); ?>
        <div class="sidebar-label">Menu Utama</div>
        <a href="/admin/dashboard" class="<?= ($seg2=='dashboard')?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="/admin/presensi"  class="<?= ($seg2=='presensi') ?'active':'' ?>"><i class="bi bi-calendar-check"></i> Data Presensi</a>
        <a href="/admin/pegawai"   class="<?= ($seg2=='pegawai')  ?'active':'' ?>" style="position:relative;">
            <i class="bi bi-people-fill"></i> Kelola Pegawai
            <?php
            $pendingPegawai = (new \App\Models\UserModel())->where('is_active',0)->where('role','pegawai')->countAllResults();
            if($pendingPegawai > 0): ?>
            <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:99px;"><?= $pendingPegawai ?></span>
            <?php endif; ?>
        </a>
        <a href="/admin/pengajuan" class="<?= ($seg2=='pengajuan')?'active':'' ?>"><i class="bi bi-clipboard-check"></i> Pengajuan</a>
        <a href="/admin/setting"   class="<?= ($seg2=='setting')  ?'active':'' ?>"><i class="bi bi-sliders"></i> Setting</a>
        <a href="/admin/riwayat"   class="<?= ($seg2=='riwayat')  ?'active':'' ?>"><i class="bi bi-clock-history"></i> Riwayat</a>
        <a href="/admin/profil"    class="<?= ($seg2=='profil')   ?'active':'' ?>"><i class="bi bi-person-circle"></i> Profil</a>
        <a href="/logout" style="color:rgba(252,165,165,0.7);"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="topbar">
    <div class="topbar-left">
        <div class="topbar-greeting">
            Selamat datang, <span><?= session()->get('nama') ?? ucfirst(session()->get('role') ?? 'Admin') ?></span>
        </div>
    </div>
    <div class="topbar-right">
        <?php
            $fotoAdmin   = session()->get('foto_profil');
            $namaAdmin   = session()->get('nama') ?? ucfirst(session()->get('role') ?? 'Admin');
            $jabatanAdmin = session()->get('jabatan') ?? ucfirst(session()->get('role') ?? 'Admin');
        ?>
        <a href="/admin/profil" class="topbar-profile-btn">
            <?php if (!empty($fotoAdmin)): ?>
                <img src="<?= base_url('uploads/profil/' . $fotoAdmin) ?>" class="topbar-avatar-img" alt="Foto">
            <?php else: ?>
                <div class="topbar-avatar-fallback"><?= strtoupper(substr($namaAdmin, 0, 1)) ?></div>
            <?php endif; ?>
            <div>
                <span class="topbar-profile-name"><?= esc($namaAdmin) ?></span>
                <span class="topbar-profile-role"><?= esc($jabatanAdmin) ?></span>
            </div>
        </a>
    </div>
</div>

<main class="main-content">
    <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('open');
}
</script>
</body>
</html>
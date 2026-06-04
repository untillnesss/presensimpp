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
        /* Sama persis dengan layout admin */
        :root {
            --primary: #6366f1; --primary-dark: #4f46e5;
            --sidebar-bg: #1e1b4b; --body-bg: #eef0fb;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--body-bg); color: #1e1b4b; }
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

        .topbar { position:fixed;top:0;left:260px;right:0;height:66px;background:#1e1b4b;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between;padding:0 28px;z-index:99;box-shadow:0 1px 10px rgba(99,102,241,0.07); }
        .topbar-left .topbar-greeting { font-size:13px;color:rgba(165,180,252,0.7);font-weight:500; }
        .topbar-left .topbar-greeting span { color:#fff;font-weight:700; }
        .topbar-profile-btn { display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.08);border:1.5px solid rgba(255,255,255,0.12);padding:8px 16px 8px 8px;border-radius:99px;text-decoration:none;transition:all 0.2s; }
        .topbar-profile-btn:hover { background:rgba(255,255,255,0.14);border-color:rgba(99,102,241,0.6); }
        .topbar-avatar-img { width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid #6366f1;box-shadow:0 2px 8px rgba(99,102,241,0.25); }
        .topbar-avatar-fallback { width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;font-weight:800;font-size:16px;display:flex;align-items:center;justify-content:center;border:2px solid rgba(99,102,241,0.4); }
        .topbar-profile-name { font-size:13px;font-weight:700;color:#fff;display:block; }
        .topbar-profile-role { font-size:11px;color:rgba(165,180,252,0.7);display:block; }

        .main-content { margin-left:260px;margin-top:66px;padding:28px;min-height:calc(100vh - 66px); }
        .page-header { margin-bottom:24px; }
        .page-header h4 { font-weight:800;font-size:22px;color:#1e1b4b; }
        .page-header p { color:#9ca3af;font-size:13px;margin-top:4px; }
        .card { border:none;border-radius:18px;box-shadow:0 4px 20px rgba(99,102,241,0.08);background:#fff; }
        .stat-card { border-radius:18px;padding:22px 24px;display:flex;align-items:center;gap:18px;transition:transform 0.2s,box-shadow 0.2s;cursor:pointer; }
        .stat-card:hover { transform:translateY(-4px); }
        .stat-icon { width:58px;height:58px;border-radius:16px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;color:white; }
        .stat-lbl { font-size:12px;font-weight:600;opacity:0.85;margin-bottom:4px; }
        .stat-num { font-size:38px;font-weight:800;line-height:1; }
        .stat-sub { font-size:11px;opacity:0.7;margin-top:4px; }
        .card-header-inner { padding:18px 22px;border-bottom:2px solid #eef0fb;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#1e1b4b,#2d2a6e);border-radius:18px 18px 0 0; }
        .card-header-title { font-weight:800;font-size:15px;color:#fff; }
        .card-header-sub { font-size:12px;color:rgba(165,180,252,0.75);margin-top:2px; }
        .table-modern { border-collapse:separate;border-spacing:0;width:100%; }
        .table-modern thead th { background:#f5f6ff;color:#6366f1;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.8px;padding:14px 18px;border-bottom:2px solid #e5e7eb;white-space:nowrap; }
        .table-modern tbody td { padding:14px 18px;font-size:13.5px;color:#374151;border-bottom:1px solid #f3f4f6;vertical-align:middle; }
        .table-modern tbody tr:last-child td { border-bottom:none; }
        .table-modern tbody tr:hover td { background:#fafbff; }
        .badge-pill { padding:5px 14px;border-radius:99px;font-size:12px;font-weight:700;white-space:nowrap;display:inline-block; }
        .bp-hadir     { background:#d1fae5;color:#059669; }
        .bp-terlambat { background:#fee2e2;color:#dc2626; }
        .bp-izin      { background:#fef9c3;color:#b45309; }
        .bp-sakit     { background:#dbeafe;color:#2563eb; }
        .instansi-item { display:flex;justify-content:space-between;align-items:center;padding:14px 22px;border-bottom:1px solid #eef0fb;transition:background 0.15s; }
        .instansi-item:last-child { border-bottom:none; }
        .instansi-item:hover { background:#f7f8ff; }
        .btn-primary { background:linear-gradient(135deg,#6366f1,#4f46e5);border:none;border-radius:10px;font-weight:700;padding:9px 22px;font-size:13.5px;box-shadow:0 4px 14px rgba(99,102,241,0.35);font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-primary:hover { background:linear-gradient(135deg,#4f46e5,#4338ca); }
        .btn-rekap { display:inline-flex;align-items:center;gap:8px;padding:9px 22px;border-radius:10px;font-size:13.5px;font-weight:700;text-decoration:none;border:none;cursor:pointer;transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-rekap-excel { background:linear-gradient(135deg,#059669,#10b981);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,0.35); }
        .btn-rekap-excel:hover { background:linear-gradient(135deg,#047857,#059669);color:#fff; }
        .btn-rekap-pdf { background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;box-shadow:0 4px 14px rgba(239,68,68,0.35); }
        .btn-rekap-pdf:hover { background:linear-gradient(135deg,#b91c1c,#dc2626);color:#fff; }
        .form-control,.form-select { border-radius:10px;border:1.5px solid #e5e7eb;padding:10px 14px;font-size:13.5px;transition:all 0.2s;font-family:'Plus Jakarta Sans',sans-serif; }
        .form-control:focus,.form-select:focus { border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.12); }
        .form-label { font-weight:700;font-size:12.5px;color:#4b5563;margin-bottom:6px; }
        .alert { border-radius:12px;border:none;font-size:13.5px; }

        /* BADGE STATUS */
        .badge-status { display:inline-block;padding:4px 12px;border-radius:99px;font-size:11.5px;font-weight:700;white-space:nowrap; }
        .badge-hadir     { background:#d1fae5;color:#059669; }
        .badge-terlambat { background:#fee2e2;color:#dc2626; }
        .badge-izin      { background:#fef9c3;color:#b45309; }
        .badge-sakit     { background:#dbeafe;color:#2563eb; }
    </style>
</head>
<body>

<div class="sidebar">
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
        <a href="/sekretariat/dashboard" class="<?= ($seg2=='dashboard')?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="/sekretariat/presensi"  class="<?= ($seg2=='presensi') ?'active':'' ?>"><i class="bi bi-calendar-check"></i> Data Presensi</a>
        <a href="/sekretariat/profil"    class="<?= ($seg2=='profil')   ?'active':'' ?>"><i class="bi bi-person-circle"></i> Profil</a>
    </div>
    <div class="sidebar-footer">
        <a href="/logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="topbar">
    <div class="topbar-left">
        <div class="topbar-greeting">
            Selamat datang, <span><?= session()->get('nama') ?? 'Sekretariat' ?></span>
        </div>
    </div>
    <div class="topbar-right">
        <?php
            $foto    = session()->get('foto_profil');
            $nama    = session()->get('nama') ?? 'Sekretariat';
            $jabatan = session()->get('jabatan') ?? 'Sekretariat';
        ?>
        <a href="/sekretariat/profil" class="topbar-profile-btn">
            <?php if (!empty($foto)): ?>
                <img src="<?= base_url('uploads/profil/' . $foto) ?>" class="topbar-avatar-img" alt="Foto">
            <?php else: ?>
                <div class="topbar-avatar-fallback"><?= strtoupper(substr($nama, 0, 1)) ?></div>
            <?php endif; ?>
            <div>
                <span class="topbar-profile-name"><?= esc($nama) ?></span>
                <span class="topbar-profile-role">Lihat Profil →</span>
            </div>
        </a>
    </div>
</div>

<main class="main-content">
    <?= $this->renderSection('content') ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
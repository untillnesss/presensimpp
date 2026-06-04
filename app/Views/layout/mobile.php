<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Presensi MPP Tuban' ?></title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/cosmo/bootstrap.min.css" rel="stylesheet">

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===== GLOBAL ===== */
        body.bg-soft {
            background-color: #e9eef5;
            font-family: 'Segoe UI', sans-serif;
            color: #1e293b;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body.bg-soft-auth {
            background: linear-gradient(180deg, #eaf1ff, #f8fbff);
        }

        /* ===== CONTENT ===== */
        .content {
            padding: 56px 20px 96px;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            border-bottom: 1px solid #e5edff;
        }

        /* ===== CARD ===== */
        .card {
            border-radius: 18px;
            border: none;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(59,130,246,.08);
        }

        /* ===== FORM ===== */
        .form-control,
        .form-select {
            border-radius: 14px;
            border: 1.5px solid #dbeafe;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.15);
        }

        /* ===== BUTTON ===== */
        .btn-primary {
            background-color: #3b82f6;
            border: none;
            border-radius: 14px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        /* ===== LIST GROUP ===== */
        .list-group-item {
            border: none;
            border-radius: 12px;
            margin-bottom: 6px;
        }

        /* ===== BOTTOM NAV ===== */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #ffffff;
            display: flex;
            justify-content: space-around;
            padding: 8px 0;
            border-top: 1px solid #dbe1ea;
            z-index: 1000;
        }

        .bottom-nav .nav-item {
            text-align: center;
            color: #64748b;
            font-size: 12px;
            text-decoration: none;
        }

        .bottom-nav .nav-item i {
            font-size: 20px;
            display: block;
        }

        .bottom-nav .nav-item.active,
        .bottom-nav .nav-item:hover {
            color: #0d6efd;
        }

        /* ===== PROFILE PAGE ===== */
        .profile-header {
            background: linear-gradient(180deg, #eef4ff, #ffffff);
            border-radius: 20px;
            padding: 20px 16px;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 6px 16px rgba(59,130,246,.15);
        }

        .edit-avatar {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 34px;
            height: 34px;
            background: #3b82f6;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(59,130,246,.3);
        }

        .edit-avatar i {
            font-size: 16px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        a.text-primary {
            font-weight: 500;
            text-decoration: none;
        }

        a.text-primary:hover {
            text-decoration: underline;
        }

        .btn-primary {
            border-radius: 999px;
            padding: 12px;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(59,130,246,.25);
        }

        /* ===== OFFCANVAS MENU ===== */
        .offcanvas {
            width: 260px !important;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .menu-item {
            border-radius: 12px;
            margin-bottom: 10px;
            padding: 12px 14px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: 0.2s;
        }

        .menu-item i {
            font-size: 18px;
            margin-right: 10px;
        }

        .menu-item:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .active-menu {
            background: #0d6efd !important;
            color: white !important;
        }

        .active-menu i {
            color: white !important;
        }
    </style>
</head>

<body class="bg-soft">

<!-- HEADER -->
<nav class="navbar navbar-dark bg-primary shadow-sm fixed-top">
    <div class="container-fluid">

        <button class="btn text-white" data-bs-toggle="offcanvas" data-bs-target="#menuDrawer">
            <i class="bi bi-list fs-3"></i>
        </button>

        <span class="fw-semibold text-white">
            Presensi MPP Tuban
        </span>

        <a href="/profil" class="text-white">
            <i class="bi bi-person-circle fs-3"></i>
        </a>

    </div>
</nav>

<!-- SIDEBAR -->
<div class="offcanvas offcanvas-start" id="menuDrawer">
    <div class="offcanvas-header border-bottom">
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">

        <?php $role = session()->get('role'); ?>
        <?php $uri = service('uri'); ?>

        <!-- ================= ADMIN ================= -->
        <?php if($role == 'admin'): ?>

            <a href="/admin/dashboard" class="menu-item <?= ($uri->getSegment(2) == 'dashboard') ? 'active-menu' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <a href="/admin/presensi" class="menu-item <?= ($uri->getSegment(2) == 'presensi') ? 'active-menu' : '' ?>">
                <i class="bi bi-calendar-check"></i> Data Presensi
            </a>

            <a href="/admin/pengajuan" class="menu-item <?= ($uri->getSegment(2) == 'pengajuan') ? 'active-menu' : '' ?>">
                <i class="bi bi-clipboard-check"></i> Pengajuan
            </a>

            <a href="/admin/setting" class="menu-item <?= ($uri->getSegment(2) == 'setting') ? 'active-menu' : '' ?>">
                <i class="bi bi-gear"></i> Setting
            </a>

            <a href="/admin/riwayat" class="menu-item <?= ($uri->getSegment(2) == 'riwayat') ? 'active-menu' : '' ?>">
                <i class="bi bi-clock-history"></i> Riwayat
            </a>

        <!-- ================= PEGAWAI ================= -->
        <?php else: ?>

            <a href="/dashboard" class="menu-item <?= ($uri->getSegment(1) == 'dashboard') ? 'active-menu' : '' ?>">
                <i class="bi bi-house"></i> Dashboard
            </a>

            <a href="/presensi" class="menu-item <?= ($uri->getSegment(1) == 'presensi') ? 'active-menu' : '' ?>">
                <i class="bi bi-camera"></i> Presensi
            </a>

            <a href="/pengajuan" class="menu-item <?= ($uri->getSegment(1) == 'pengajuan') ? 'active-menu' : '' ?>">
                <i class="bi bi-clipboard-check"></i> Pengajuan
            </a>

            <a href="/riwayat" class="menu-item <?= ($uri->getSegment(1) == 'riwayat') ? 'active-menu' : '' ?>">
                <i class="bi bi-clock-history"></i> Riwayat
            </a>

            <a href="/profil" class="menu-item <?= ($uri->getSegment(1) == 'profil') ? 'active-menu' : '' ?>">
                <i class="bi bi-person"></i> Profil
            </a>

        <?php endif; ?>

        <hr>

        <a href="/logout" class="menu-item text-danger">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>

    </div>
</div>

<!-- CONTENT -->
<main class="content" style="margin-top:70px;">
    <?= $this->renderSection('content') ?>
</main>

<!-- BOTTOM NAV (HANYA PEGAWAI) -->
<?php if(session()->get('role') != 'admin'): ?>
<nav class="bottom-nav shadow-lg">

    <a href="/dashboard" class="nav-item">
        <i class="bi bi-house"></i>
        <span>Home</span>
    </a>

    <a href="/pengajuan" class="nav-item">
        <i class="bi bi-clipboard-check"></i>
        <span>Pengajuan</span>
    </a>

    <a href="/riwayat" class="nav-item">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat</span>
    </a>

</nav>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
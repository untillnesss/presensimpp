<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Presensi MPP Tuban' ?></title>

    <!-- BOOTSWATCH LUX (STABIL & NETRAL) -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.2/dist/cosmo/bootstrap.min.css" rel="stylesheet">

    <style>
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

        .content {
            padding: 56px 20px 96px;
        }

        .navbar {
            border-bottom: 1px solid #e5edff;
        }

        .card {
            border-radius: 18px;
            border: none;
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(59,130,246,.08);
        }

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

        .btn-primary {
            background-color: #3b82f6;
            border: none;
            border-radius: 14px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .list-group-item {
            border: none;
            border-radius: 12px;
            margin-bottom: 6px;
        }

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
    </style>
</head>

<body class="bg-soft-auth">

<main class="d-flex align-items-center justify-content-center" style="min-height:100vh">
    <div style="width:100%; max-width:420px">
        <?= $this->renderSection('content') ?>
    </div>
</main>

</body>
</html>

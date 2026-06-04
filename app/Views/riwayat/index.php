<?= $this->extend('layout/mobile') ?>
<?= $this->section('content') ?>

<style>

/* CONTAINER MOBILE FIX */
.container {
    padding-left: 10px !important;
    padding-right: 10px !important;
}

/* CARD */
.card-custom {
    background: #ffffff;
    border-radius: 12px;
    padding: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
}

/* TABLE */
.table {
    margin-bottom: 0;
}

.table thead th {
    background: #505357 !important;
    color: white !important;
    font-size: 12px;
    border: none;
    padding: 8px;
}

.table tbody td {
    font-size: 12px;
    padding: 8px 6px;
    border-color: #f1f1f1;
}

/* STATUS ROW */
.hadir-row td { background: #e7f9f0; }
.izin-row td { background: #fff4e5; }
.sakit-row td { background: #e8f0fe; }
.libur-row td {
    background: #e65261 !important;
    color: white;
}
.tidak-row td { background: #f5f5f5; }

/* BADGE */
.badge-status {
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 10px;
}

.badge-hadir { background: #28c76f; color: white; }
.badge-izin { background: #ff9f43; color: white; }
.badge-sakit { background: #4dabf7; color: white; }
.badge-libur { background: #ea5455; color: white; }
.badge-tidak { background: #6c757d; color: white; }

/* BIAR TABLE FULL WIDTH TANPA KEPOTONG */
.table-responsive {
    overflow-x: auto;
}

/* FILTER BIAR RAPET */
.filter-box {
    margin-bottom: 10px;
}

</style>

<div class="container mt-0">

<h5 class="mb-2 fw-bold">Riwayat Presensi</h5>

<!-- FILTER -->
<div class="card-custom filter-box">
<form method="get" class="row g-1">

    <div class="col-4">
        <select name="bulan" class="form-control form-control-sm">
            <?php for ($i=1; $i<=12; $i++): ?>
                <option value="<?= $i ?>" <?= ($bulan == $i ? 'selected' : '') ?>>
                    <?= date('M', mktime(0,0,0,$i,1)) ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="col-4">
        <select name="tahun" class="form-control form-control-sm">
            <?php for ($t=2023; $t<=date('Y'); $t++): ?>
                <option value="<?= $t ?>" <?= ($tahun == $t ? 'selected' : '') ?>>
                    <?= $t ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>

    <div class="col-4">
        <button class="btn btn-primary btn-sm w-100">Filter</button>
    </div>

</form>
</div>

<!-- TABLE -->
<div class="card-custom">
<div class="table-responsive">

<table class="table text-center">

    <thead>
        <tr>
            <th>Tgl</th>
            <th>Masuk</th>
            <th>Pulang</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach($riwayat as $r): ?>

        <?php
            switch ($r['status']) {
                case 'hadir':
                    $ket = 'Hadir'; $class='hadir-row'; $badge='badge-hadir'; break;
                case 'terlambat':
                    $ket = 'Terlambat'; $class='izin-row'; $badge='badge-izin'; break;
                case 'izin':
                    $ket = 'Izin'; $class='izin-row'; $badge='badge-izin'; break;
                case 'sakit':
                    $ket = 'Sakit'; $class='sakit-row'; $badge='badge-sakit'; break;
                case 'libur':
                    $ket = 'Libur'; $class='libur-row'; $badge='badge-libur'; break;
                default:
                    $ket = 'Tidak'; $class='tidak-row'; $badge='badge-tidak'; break;
            }
        ?>

        <tr class="<?= $class ?>">
            <td><?= date('d/m', strtotime($r['tanggal'])) ?></td>
            <td><?= $r['jam_masuk'] ?: '-' ?></td>
            <td><?= $r['jam_pulang'] ?: '-' ?></td>
            <td>
                <span class="badge-status <?= $badge ?>">
                    <?= $ket ?>
                </span>
            </td>
        </tr>

    <?php endforeach; ?>
    </tbody>

</table>

</div>
</div>

</div>

<?= $this->endSection() ?>
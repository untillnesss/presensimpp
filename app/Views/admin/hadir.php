<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<h5 class="mb-3">Instansi Hadir Hari Ini</h5>

<?php if(empty($data)): ?>
    <div class="alert alert-info">Belum ada yang hadir</div>
<?php endif; ?>

<?php foreach($data as $d): ?>
    <div class="card mb-2 shadow-sm border-0 rounded-3">
        <div class="card-body d-flex justify-content-between">
            <span><?= $d['nama_instansi'] ?></span>
            <span class="badge bg-success">Hadir</span>
        </div>
    </div>
<?php endforeach; ?>

<?= $this->endSection() ?>
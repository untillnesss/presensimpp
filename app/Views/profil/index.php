<?= $this->extend('layout/mobile') ?>
<?= $this->section('content') ?>

<form action="/profil/save" method="post" enctype="multipart/form-data">
<?= csrf_field() ?>

<!-- ===== PROFILE HEADER ===== -->
<div class="profile-header text-center mb-4">

    <?php
        $foto = !empty($profil['foto'])
            ? base_url('uploads/profil/' . $profil['foto'])
            : base_url('assets/img/default-profile.png');
    ?>

    <div class="avatar-wrapper">
        <img
            src="<?= $foto ?>"
            id="previewFoto"
            alt="Foto Profil"
            class="profile-avatar"
        >
        <label class="edit-avatar">
            <i class="bi bi-camera-fill"></i>
            <input type="file" name="foto" accept="image/*" hidden onchange="previewFoto(this)">
        </label>
    </div>

    <div class="mt-2">
        <div class="fw-semibold"><?= esc($profil['nama'] ?? '') ?></div>
        <small class="text-muted">
            <?= esc($profil['jabatan'] ?? '') ?> • <?= esc($profil['nama_instansi'] ?? '') ?>
        </small>
    </div>

</div>

<div class="mb-2">
    <h6 class="fw-bold mb-0">Profil</h6>
</div>

<div class="card shadow-sm p-3 mb-3">

    <div class="mb-3">
        <label class="form-label">No. ID Pegawai</label>
        <input type="text" name="no_id" class="form-control"
               value="<?= $profil['no_id'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control"
               value="<?= $profil['nama'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Jabatan</label>
        <input type="text" name="jabatan" class="form-control"
               value="<?= $profil['jabatan'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Instansi</label>
        <select name="id_instansi" class="form-select" required>
            <option value="">-- Pilih Instansi --</option>
            <?php foreach ($instansi as $i): ?>
                <option value="<?= $i['id_instansi'] ?>"
                    <?= ($profil['id_instansi'] ?? '') == $i['id_instansi'] ? 'selected' : '' ?>>
                    <?= $i['nama_instansi'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="text-start">
        <a href="<?= base_url('/lupa-password?mode=change') ?>" class="text-primary">
            Ubah password?
        </a>
    </div>

</div>

<button class="btn btn-primary w-100 rounded-pill">Simpan Profil</button>

</form>

<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('previewFoto').src = e.target.result; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>

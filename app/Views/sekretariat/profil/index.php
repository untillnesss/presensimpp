<?= $this->extend('layout/sekretariat') ?>
<?= $this->section('content') ?>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert" style="background:#d1fae5;color:#065f46;border-radius:14px;padding:14px 18px;margin-bottom:20px;font-weight:600;display:flex;align-items:center;gap:8px;">
    <i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<div class="row justify-content-center">
<div class="col-lg-7">

    <!-- PROFILE HERO -->
    <div style="background:linear-gradient(135deg,#1e1b4b 0%,#2d2a6e 50%,#4338ca 100%);border-radius:22px;padding:40px 28px;text-align:center;margin-bottom:20px;position:relative;overflow:hidden;box-shadow:0 8px 32px rgba(99,102,241,0.35);">
        <div style="position:absolute;top:-50px;right:-50px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.04);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-40px;left:-40px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,0.04);pointer-events:none;"></div>

        <?php
            $foto = !empty($profil['foto'])
                ? base_url('uploads/profil/' . $profil['foto'])
                : base_url('assets/img/default-profile.png');
        ?>

        <div style="position:relative;display:inline-block;margin-bottom:18px;">
            <img src="<?= $foto ?>" id="previewFoto" alt="Foto Profil"
                 style="width:108px;height:108px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,0.25);box-shadow:0 8px 28px rgba(0,0,0,0.35);">
            <label for="fotoInput" style="position:absolute;bottom:3px;right:3px;width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 3px 10px rgba(0,0,0,0.3);border:2px solid rgba(255,255,255,0.3);">
                <i class="bi bi-camera-fill" style="color:#fff;font-size:13px;"></i>
            </label>
        </div>

        <div style="color:#fff;font-weight:800;font-size:21px;letter-spacing:-0.3px;margin-bottom:5px;">
            <?= esc($profil['nama'] ?? 'Nama Belum Diisi') ?>
        </div>
        <div style="color:rgba(165,180,252,0.85);font-size:13px;margin-bottom:14px;">
            <?= esc($profil['jabatan'] ?? 'Jabatan belum diisi') ?>
        </div>

        <div style="display:inline-flex;align-items:center;gap:8px;flex-wrap:wrap;justify-content:center;">
            <span style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);color:rgba(199,210,254,0.9);padding:5px 14px;border-radius:99px;font-size:12px;font-weight:600;">
                <i class="bi bi-building me-1"></i><?= esc($profil['nama_instansi'] ?? 'Instansi belum diatur') ?>
            </span>
            <span style="background:linear-gradient(135deg,#0891b2,#06b6d4);color:#fff;padding:5px 16px;border-radius:99px;font-size:12px;font-weight:700;box-shadow:0 2px 10px rgba(6,182,212,0.4);">
                <i class="bi bi-person-badge-fill me-1"></i>Sekretariat
            </span>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card">
        <div class="card-header-inner">
            <div>
                <div class="card-header-title">Edit Data Profil</div>
                <div class="card-header-sub">Update informasi akun sekretariat</div>
            </div>
            <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill" style="color:#fff;font-size:17px;"></i>
            </div>
        </div>
        <div class="card-body p-4">

            <form action="/sekretariat/profil/save" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="file" name="foto" id="fotoInput" accept="image/*" hidden>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-card-text me-1" style="color:#6366f1;"></i>No. ID Pegawai</label>
                    <input type="text" name="no_id" class="form-control" value="<?= esc($profil['no_id'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person me-1" style="color:#6366f1;"></i>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc($profil['nama'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-briefcase me-1" style="color:#6366f1;"></i>Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="<?= esc($profil['jabatan'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-building me-1" style="color:#6366f1;"></i>Instansi</label>
                    <select name="id_instansi" class="form-select" required>
                        <option value="">-- Pilih Instansi --</option>
                        <?php foreach ($instansi as $i): ?>
                        <option value="<?= $i['id_instansi'] ?>"
                            <?= ($profil['id_instansi'] ?? '') == $i['id_instansi'] ? 'selected' : '' ?>>
                            <?= esc($i['nama_instansi']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="background:#f7f8ff;border-radius:12px;padding:14px 18px;margin-bottom:24px;border:1px solid #eef0fb;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#374151;">Password Akun</div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Ganti password login</div>
                    </div>
                    <a href="<?= base_url('/lupa-password?mode=change') ?>"
                       style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;padding:7px 16px;border-radius:10px;font-size:12.5px;font-weight:700;text-decoration:none;">
                        Ubah Password
                    </a>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding:13px;">
                    <i class="bi bi-save me-2"></i> Simpan Profil
                </button>
            </form>

        </div>
    </div>

</div>
</div>

<script>
document.getElementById('fotoInput').addEventListener('change', function() {
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewFoto').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<?= $this->endSection() ?>
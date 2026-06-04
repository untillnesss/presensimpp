<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>

<div class="row justify-content-center">
<div class="col-lg-7">

    <!-- PROFILE HEADER CARD -->
    <div style="background:linear-gradient(135deg,#1e1b4b,#2d2a6e,#4338ca);border-radius:22px;padding:36px 28px;text-align:center;margin-bottom:20px;position:relative;overflow:hidden;box-shadow:0 8px 32px rgba(99,102,241,0.35);">
        <!-- decorative circles -->
        <div style="position:absolute;top:-40px;right:-40px;width:150px;height:150px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
        <div style="position:absolute;bottom:-30px;left:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>

        <?php
            $foto = !empty($profil['foto'])
                ? base_url('uploads/profil/' . $profil['foto'])
                : base_url('assets/img/default-profile.png');
        ?>

        <!-- FOTO -->
        <div style="position:relative;display:inline-block;margin-bottom:16px;">
            <img src="<?= $foto ?>" id="previewFoto" alt="Foto Profil"
                 style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid rgba(255,255,255,0.3);box-shadow:0 8px 24px rgba(0,0,0,0.3);">
            <label style="position:absolute;bottom:2px;right:2px;width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
                <i class="bi bi-camera-fill" style="color:#fff;font-size:13px;"></i>
                <input type="file" name="foto_preview" accept="image/*" hidden onchange="previewFotoChange(this)">
            </label>
        </div>

        <div style="color:#fff;font-weight:800;font-size:20px;letter-spacing:-0.3px;"><?= esc($profil['nama'] ?? 'Nama Admin') ?></div>
        <div style="color:rgba(165,180,252,0.85);font-size:13px;margin-top:4px;"><?= esc($profil['jabatan'] ?? '') ?></div>
        <div style="margin-top:10px;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);padding:5px 16px;border-radius:99px;border:1px solid rgba(255,255,255,0.15);">
            <i class="bi bi-building" style="color:rgba(165,180,252,0.8);font-size:12px;"></i>
            <span style="color:rgba(199,210,254,0.9);font-size:12px;font-weight:600;"><?= esc($profil['nama_instansi'] ?? 'Belum diatur') ?></span>
        </div>

        <!-- ROLE BADGE -->
        <div style="margin-top:12px;">
            <span style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;padding:5px 18px;border-radius:99px;font-size:12px;font-weight:700;box-shadow:0 2px 10px rgba(99,102,241,0.4);">
                <i class="bi bi-shield-fill-check me-1"></i>Admin
            </span>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="card">
        <div class="card-header-inner">
            <div>
                <div class="card-header-title">Edit Data Profil</div>
                <div class="card-header-sub">Perubahan akan langsung tersimpan</div>
            </div>
            <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill" style="color:#fff;font-size:17px;"></i>
            </div>
        </div>
        <div class="card-body p-4">

            <form action="/admin/profil/save" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- INPUT FOTO TERSEMBUNYI (dikirim bareng form) -->
                <input type="file" name="foto" id="fotoInput" accept="image/*" hidden onchange="previewFotoChange(this)">

                <!-- FOTO KLIK DARI HEADER -->
                <script>
                function previewFotoChange(input) {
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            document.getElementById('previewFoto').src = e.target.result;
                        };
                        reader.readAsDataURL(input.files[0]);
                        // sync ke input form utama
                        const dt = new DataTransfer();
                        dt.items.add(input.files[0]);
                        document.getElementById('fotoInput').files = dt.files;
                    }
                }
                </script>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-card-text me-1" style="color:#6366f1;"></i>No. ID Pegawai</label>
                    <input type="text" name="no_id" class="form-control"
                           value="<?= esc($profil['no_id'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-person me-1" style="color:#6366f1;"></i>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= esc($profil['nama'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label"><i class="bi bi-briefcase me-1" style="color:#6366f1;"></i>Jabatan</label>
                    <input type="text" name="jabatan" class="form-control"
                           value="<?= esc($profil['jabatan'] ?? '') ?>" required>
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



                <div style="background:#f7f8ff;border-radius:12px;padding:14px;margin-bottom:24px;border:1px solid #eef0fb;display:flex;justify-content:space-between;align-items:center;">
                    <div>
                        <div style="font-size:13px;font-weight:700;color:#374151;">Password</div>
                        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Ubah password akun admin</div>
                    </div>
                    <a href="<?= base_url('/lupa-password?mode=change') ?>"
                       style="background:linear-gradient(135deg,#6366f1,#4f46e5);color:#fff;padding:7px 16px;border-radius:10px;font-size:12.5px;font-weight:700;text-decoration:none;">
                        Ubah Password
                    </a>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="padding:13px;justify-content:center;">
                    <i class="bi bi-save me-2"></i> Simpan Profil
                </button>

            </form>
        </div>
    </div>

</div>
</div>

<?= $this->endSection() ?>
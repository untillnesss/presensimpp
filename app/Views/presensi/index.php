<?php setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian'); ?>
<?= $this->extend('layout/mobile') ?>
<?= $this->section('content') ?>

<?php
$now             = date('H:i:s');
$bolehMasuk      = $setting && $now >= $setting['jam_masuk_mulai'];
$bolehPulang     = $setting && $now >= $setting['jam_pulang_mulai'] && $now <= $setting['jam_pulang_selesai'];
$batasTepat      = date('H:i:s', strtotime($setting['jam_masuk_mulai']) + (20 * 60));
$sedangTerlambat = $setting && $now > $batasTepat;
$belumMasuk      = !$presensi;
$belumPulang     = $presensi && (empty($presensi['jam_pulang']) || $presensi['jam_pulang'] == '00:00:00');
$sudahPulang     = $presensi && !empty($presensi['jam_pulang']) && $presensi['jam_pulang'] != '00:00:00';
$adaLokasi       = !empty($setting['latitude']) && !empty($setting['longitude']);
?>

<div class="container mt-3">

<h5 class="mb-3">Presensi</h5>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Kartu jam & nama -->
<div class="card mb-3">
    <div class="card-body text-center">
        <p><?= strftime('%A, %d %B %Y') ?></p>
        <h3 id="clock"><?= date('H:i:s') ?></h3>
        <p><?= $profil['nama'] ?? '' ?></p>
    </div>
</div>

<?php if($adaLokasi): ?>
<!-- INDIKATOR RADIUS LOKASI -->
<div id="radiusCard" class="card mb-3">
    <div class="card-body py-2 px-3">
        <div style="font-size:12px;font-weight:700;color:#374151;margin-bottom:4px;">
            <i class="bi bi-geo-alt-fill me-1" style="color:#f59e0b;"></i>
            Status Lokasi
            <span style="font-weight:400;color:#9ca3af;">(radius <?= (int)$setting['radius'] ?> m)</span>
        </div>
        <div id="lokasiStatus" style="font-size:13px;color:#9ca3af;">
            ⏳ Mengambil lokasi GPS...
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ================= MASUK ================= -->
<?php if($belumMasuk): ?>
    <?php if(!$bolehMasuk): ?>
        <div class="alert alert-warning text-center">Belum waktunya atau sudah lewat jam absen masuk</div>
    <?php else: ?>

        <?php if($sedangTerlambat): ?>
        <div class="alert mb-3" style="background:#fff8ec;border:1px solid #ffc107;border-radius:12px;padding:12px 14px;">
            <strong style="color:#856404;">Kamu Terlambat</strong><br>
            <small style="color:#856404;">Batas keterlambatan pukul <?= date('H:i', strtotime($setting['batas_terlambat'])) ?> WIB. Presensi akan tercatat sebagai <strong>Terlambat</strong>.</small>
        </div>
        <?php endif; ?>

        <form action="/presensi/masuk" method="post" onsubmit="return validasiMasuk()">
            <input type="hidden" name="latitude"    id="lat">
            <input type="hidden" name="longitude"   id="lng">
            <input type="hidden" name="foto_base64" id="foto_base64_masuk">

            <div class="card">
                <div class="card-body text-center">
                    <label>Foto Absen Masuk</label>
                    <video id="videoMasuk" width="100%" autoplay class="rounded"></video>
                    <canvas id="canvasMasuk" style="display:none;"></canvas>
                    <img id="previewMasuk" class="img-fluid mt-2 rounded d-block mx-auto" style="display:none;max-width:80%;"/>
                    <button type="button" class="btn btn-primary mt-3 w-100" onclick="ambilFotoMasuk()">📸 Ambil Foto</button>
                    <?php if($sedangTerlambat): ?>
                    <button type="submit" class="btn btn-primary mt-2 w-100" style="background:linear-gradient(135deg,#d97706,#f59e0b);box-shadow:0 4px 14px rgba(245,158,11,0.4);">Presensi Masuk Terlambat</button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-success w-100 mt-2">Presensi Masuk</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>

    <?php endif; ?>
<?php endif; ?>

<!-- ================= PULANG ================= -->
<?php if($belumPulang): ?>
    <?php if(!$bolehPulang): ?>
        <div class="alert alert-warning text-center">Belum waktunya atau sudah lewat jam absen pulang</div>
    <?php else: ?>

        <form action="/presensi/pulang" method="post" onsubmit="return validasiPulang()">
            <input type="hidden" name="foto_base64" id="foto_base64_pulang">

            <div class="card mt-3">
                <div class="card-body text-center">
                    <label>Foto Presensi Pulang</label>
                    <video id="videoPulang" width="100%" autoplay class="rounded"></video>
                    <canvas id="canvasPulang" style="display:none;"></canvas>
                    <img id="previewPulang" class="img-fluid mt-2 rounded d-block mx-auto" style="display:none;max-width:80%;"/>
                    <button type="button" class="btn btn-primary mt-3 w-100" onclick="ambilFotoPulang()">📸 Ambil Foto</button>
                    <button type="submit" class="btn btn-warning w-100 mt-2">Presensi Pulang</button>
                </div>
            </div>
        </form>

    <?php endif; ?>
<?php endif; ?>

<?php if($sudahPulang): ?>
<div class="alert alert-info mt-3 text-center">Kamu sudah absen pulang hari ini</div>
<?php endif; ?>

<?php if($presensi): ?>
<div class="card mt-3">
    <div class="card-body">
        <p>Jam Masuk : <?= $presensi['jam_masuk'] ?></p>
        <p>Jam Pulang : <?= (!empty($presensi['jam_pulang']) && $presensi['jam_pulang'] != '00:00:00') ? $presensi['jam_pulang'] : '-' ?></p>
    </div>
</div>
<?php endif; ?>

</div>

<script>
setInterval(() => {
    document.getElementById("clock").innerHTML = new Date().toLocaleTimeString('id-ID');
}, 1000);

// Data lokasi dari setting
var latKantor  = <?= $adaLokasi ? (float)$setting['latitude']  : 0 ?>;
var lngKantor  = <?= $adaLokasi ? (float)$setting['longitude'] : 0 ?>;
var radiusMaks = <?= $adaLokasi ? (int)$setting['radius']      : 0 ?>;
var adaLokasi  = <?= $adaLokasi ? 'true' : 'false' ?>;

var latUser = 0, lngUser = 0;

function hitungJarakM(lat1, lng1, lat2, lng2) {
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLng = (lng2 - lng1) * Math.PI / 180;
    var a = Math.sin(dLat/2) * Math.sin(dLat/2)
          + Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180)
          * Math.sin(dLng/2) * Math.sin(dLng/2);
    return 6371000 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

function updateStatusLokasi() {
    var el = document.getElementById('lokasiStatus');
    if (!el || !adaLokasi) return;
    if (latUser === 0 && lngUser === 0) {
        el.innerHTML = '⏳ Mengambil lokasi GPS...';
        el.style.color = '#9ca3af';
        return;
    }
    var jarak = hitungJarakM(latUser, lngUser, latKantor, lngKantor);
    var j = Math.round(jarak);
    if (jarak <= radiusMaks) {
        el.innerHTML = '✅ <strong style="color:#059669;">Dalam radius</strong> — ' + j + ' m dari kantor (maks ' + radiusMaks + ' m)';
    } else {
        el.innerHTML = '❌ <strong style="color:#dc2626;">Di luar radius</strong> — ' + j + ' m dari kantor (maks ' + radiusMaks + ' m). Pindah lebih dekat ke kantor.';
    }
}

if (navigator.geolocation) {
    navigator.geolocation.watchPosition(function(pos) {
        latUser = pos.coords.latitude;
        lngUser = pos.coords.longitude;
        document.getElementById("lat").value = latUser;
        document.getElementById("lng").value = lngUser;
        updateStatusLokasi();
    }, function(err) {
        var el = document.getElementById('lokasiStatus');
        if (el) el.innerHTML = '❌ GPS tidak bisa diakses: ' + err.message;
    }, { enableHighAccuracy: true, maximumAge: 10000, timeout: 15000 });
}

function validasiMasuk() {
    if (!document.getElementById('foto_base64_masuk').value) { alert('Ambil foto dulu!'); return false; }
    if (adaLokasi) {
        if (latUser === 0 && lngUser === 0) { alert('Lokasi GPS belum terdeteksi.\nTunggu sebentar atau pastikan GPS aktif.'); return false; }
        var jarak = hitungJarakM(latUser, lngUser, latKantor, lngKantor);
        if (jarak > radiusMaks) { alert('Kamu berada ' + Math.round(jarak) + ' meter dari kantor.\nMaksimum radius adalah ' + radiusMaks + ' meter.'); return false; }
    }
    return true;
}

function validasiPulang() {
    if (!document.getElementById('foto_base64_pulang').value) { alert('Ambil foto dulu!'); return false; }
    return true;
}

// Kamera masuk
let streamMasuk = null;
async function ambilFotoMasuk() {
    try {
        if (!streamMasuk) {
            streamMasuk = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            document.getElementById('videoMasuk').srcObject = streamMasuk;
            return;
        }
        let video = document.getElementById('videoMasuk');
        let canvas = document.getElementById('canvasMasuk');
        canvas.width = video.videoWidth; canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        let data = canvas.toDataURL('image/png');
        document.getElementById('foto_base64_masuk').value = data;
        let preview = document.getElementById('previewMasuk');
        preview.src = data; preview.style.display = 'block';
        streamMasuk.getTracks().forEach(t => t.stop()); streamMasuk = null;
        video.style.display = 'none';
    } catch(err) { alert("Kamera error: " + err.message); }
}

// Kamera pulang
let streamPulang = null;
async function ambilFotoPulang() {
    try {
        if (!streamPulang) {
            streamPulang = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
            document.getElementById('videoPulang').srcObject = streamPulang;
            return;
        }
        let video = document.getElementById('videoPulang');
        let canvas = document.getElementById('canvasPulang');
        canvas.width = video.videoWidth; canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        let data = canvas.toDataURL('image/png');
        document.getElementById('foto_base64_pulang').value = data;
        let preview = document.getElementById('previewPulang');
        preview.src = data; preview.style.display = 'block';
        streamPulang.getTracks().forEach(t => t.stop()); streamPulang = null;
        video.style.display = 'none';
    } catch(err) { alert("Kamera error: " + err.message); }
}
</script>

<?= $this->endSection() ?>

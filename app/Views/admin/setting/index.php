<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="page-header page-header-line">
    <div>
        <h4>Setting Presensi</h4>
        <p>Konfigurasi jam masuk dan lokasi kantor</p>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
<div class="alert alert-success mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill"></i><?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
<div class="alert alert-danger mb-3 d-flex align-items-center gap-2">
    <i class="bi bi-exclamation-triangle-fill"></i><?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<div class="row justify-content-center">
<div class="col-lg-7">

<?php
$jamMasuk         = $setting['jam_masuk_mulai'] ?? '08:00:00';
$base             = strtotime($jamMasuk);
$batasTerlambat   = date('H:i', $base + (20 * 60));
$jamPulangMulai   = date('H:i', $base + (8 * 3600));
$jamPulangSelesai = date('H:i', $base + (9 * 3600));
$latitude         = $setting['latitude']  ?? '';
$longitude        = $setting['longitude'] ?? '';
$radius           = $setting['radius']    ?? 100;
?>

<!-- Card info konfigurasi aktif -->
<div class="card mb-3">
    <div class="card-header-inner">
        <div>
            <div class="card-header-title">Konfigurasi Aktif</div>
            <div class="card-header-sub">Terakhir diperbarui: <?= date('d M Y H:i', strtotime($setting['update_at'] ?? 'now')) ?></div>
        </div>
        <div style="width:40px;height:40px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-sliders" style="color:#fff;font-size:17px;"></i>
        </div>
    </div>
    <div class="card-body p-3">
        <div class="row g-2 text-center">
            <div class="col-6 col-md-3">
                <div style="background:#eef2ff;border-radius:12px;padding:12px 8px;">
                    <div style="font-size:11px;color:#6366f1;font-weight:700;margin-bottom:4px;">⏰ Jam Masuk</div>
                    <div style="font-size:18px;font-weight:800;color:#4338ca;"><?= date('H:i', strtotime($jamMasuk)) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div style="background:#fff1f2;border-radius:12px;padding:12px 8px;">
                    <div style="font-size:11px;color:#ef4444;font-weight:700;margin-bottom:4px;">🚨 Batas Terlambat</div>
                    <div style="font-size:18px;font-weight:800;color:#dc2626;"><?= $batasTerlambat ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div style="background:#f0fdf4;border-radius:12px;padding:12px 8px;">
                    <div style="font-size:11px;color:#10b981;font-weight:700;margin-bottom:4px;">🏠 Jam Pulang</div>
                    <div style="font-size:18px;font-weight:800;color:#059669;"><?= $jamPulangMulai ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div style="background:#fffbeb;border-radius:12px;padding:12px 8px;">
                    <div style="font-size:11px;color:#f59e0b;font-weight:700;margin-bottom:4px;">⏳ Batas Pulang</div>
                    <div style="font-size:18px;font-weight:800;color:#d97706;"><?= $jamPulangSelesai ?></div>
                </div>
            </div>
        </div>
        <?php if($latitude && $longitude): ?>
        <div class="mt-2 text-center" style="font-size:12px;color:#64748b;">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i>
            <?= $latitude ?>, <?= $longitude ?> &nbsp;|&nbsp;
            <i class="bi bi-circle me-1" style="color:#3b82f6;"></i>Radius: <strong><?= $radius ?> m</strong>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Form setting -->
<div class="card">
    <div class="card-body p-4">
        <form method="post" action="/admin/setting/update">

            <!-- ===== JAM MASUK ===== -->
            <div class="setting-block" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);margin-bottom:16px;">
                <div class="setting-block-title" style="color:#4338ca;">
                    <div style="width:30px;height:30px;border-radius:9px;background:#6366f1;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clock-fill" style="color:#fff;font-size:14px;"></i>
                    </div>
                    Jam Masuk
                </div>
                <input type="time" name="jam_masuk" id="inputJamMasuk"
                       value="<?= date('H:i', strtotime($jamMasuk)) ?>"
                       class="form-control" style="border-color:rgba(99,102,241,0.3);"
                       onchange="updatePreview(this.value)" required>
                <div style="font-size:11.5px;color:#6366f1;margin-top:6px;">
                    <i class="bi bi-info-circle me-1"></i>Waktu terlambat, pulang, dan batas pulang dihitung otomatis dari jam ini.
                </div>
            </div>

            <!-- Preview otomatis -->
            <div style="background:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:16px;margin-bottom:20px;">
                <div style="font-size:12px;font-weight:700;color:#64748b;margin-bottom:10px;"><i class="bi bi-magic me-1"></i>Otomatis dihitung oleh sistem:</div>
                <div class="row g-2">
                    <div class="col-4 text-center">
                        <div style="font-size:11px;color:#9ca3af;">🚨 Batas Terlambat</div>
                        <div id="prevTerlambat" style="font-size:16px;font-weight:800;color:#ef4444;"><?= $batasTerlambat ?></div>
                        <div style="font-size:10px;color:#9ca3af;">+20 menit</div>
                    </div>
                    <div class="col-4 text-center">
                        <div style="font-size:11px;color:#9ca3af;">🏠 Jam Pulang</div>
                        <div id="prevPulang" style="font-size:16px;font-weight:800;color:#10b981;"><?= $jamPulangMulai ?></div>
                        <div style="font-size:10px;color:#9ca3af;">+8 jam kerja</div>
                    </div>
                    <div class="col-4 text-center">
                        <div style="font-size:11px;color:#9ca3af;">⏳ Batas Pulang</div>
                        <div id="prevBatasPulang" style="font-size:16px;font-weight:800;color:#f59e0b;"><?= $jamPulangSelesai ?></div>
                        <div style="font-size:10px;color:#9ca3af;">+9 jam</div>
                    </div>
                </div>
            </div>

            <hr style="border-color:#e2e8f0;margin-bottom:20px;">

            <!-- ===== KOORDINAT KANTOR ===== -->
            <div style="margin-bottom:16px;">
                <div style="font-size:13px;font-weight:700;color:#1e293b;margin-bottom:12px;display:flex;align-items:center;">
                    <div style="width:30px;height:30px;border-radius:9px;background:#ef4444;display:inline-flex;align-items:center;justify-content:center;margin-right:8px;">
                        <i class="bi bi-geo-alt-fill" style="color:#fff;font-size:14px;"></i>
                    </div>
                    Lokasi Kantor
                </div>

                <button type="button" onclick="deteksiLokasi()"
                    style="background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;border:none;border-radius:10px;padding:8px 16px;font-size:12.5px;font-weight:600;display:inline-flex;align-items:center;gap:6px;margin-bottom:12px;cursor:pointer;">
                    <i class="bi bi-crosshair2"></i> Deteksi Lokasi Saya Sekarang
                </button>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label style="font-size:12px;color:#64748b;font-weight:600;">Latitude</label>
                        <input type="text" name="latitude" id="inputLat"
                               value="<?= $latitude ?>"
                               class="form-control" placeholder="-6.90603300"
                               style="font-size:13px;" required
                               oninput="updateMarker()">
                    </div>
                    <div class="col-6">
                        <label style="font-size:12px;color:#64748b;font-weight:600;">Longitude</label>
                        <input type="text" name="longitude" id="inputLng"
                               value="<?= $longitude ?>"
                               class="form-control" placeholder="112.08051600"
                               style="font-size:13px;" required
                               oninput="updateMarker()">
                    </div>
                </div>

                <div id="map" style="width:100%;height:260px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:8px;background:#f1f5f9;"></div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:14px;">
                    <i class="bi bi-info-circle me-1"></i>Klik pada peta atau drag marker untuk tentukan titik kantor. Lingkaran biru = area radius absen.
                </div>
            </div>

            <!-- ===== RADIUS ===== -->
            <div class="setting-block" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);margin-bottom:20px;">
                <div class="setting-block-title" style="color:#1d4ed8;">
                    <div style="width:30px;height:30px;border-radius:9px;background:#3b82f6;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-circle" style="color:#fff;font-size:14px;"></i>
                    </div>
                    Radius Absen
                </div>
                <div class="d-flex align-items-center gap-2">
                    <input type="number" name="radius" id="inputRadius"
                           value="<?= $radius ?>" min="10" max="5000"
                           class="form-control" style="border-color:rgba(59,130,246,0.3);width:120px;"
                           oninput="syncSlider(this.value); updateRadius(this.value)" required>
                    <span style="font-size:14px;font-weight:600;color:#1d4ed8;">meter</span>
                </div>
                <input type="range" id="sliderRadius" min="10" max="1000" step="10"
                       value="<?= $radius ?>"
                       style="width:100%;margin-top:10px;accent-color:#3b82f6;"
                       oninput="document.getElementById('inputRadius').value=this.value; updateRadius(this.value);">
                <div style="display:flex;justify-content:space-between;font-size:10px;color:#94a3b8;margin-top:2px;">
                    <span>10 m</span><span>250 m</span><span>500 m</span><span>1000 m</span>
                </div>
                <div style="font-size:11.5px;color:#3b82f6;margin-top:8px;">
                    <i class="bi bi-info-circle me-1"></i>Pegawai hanya bisa absen jika berada dalam radius ini dari kantor. Minimal 10 m.
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding:13px;justify-content:center;">
                <i class="bi bi-save2 me-2"></i>Simpan Setting
            </button>
        </form>
    </div>
</div>

</div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ── Preview jam otomatis ─────────────────────────────────────
function updatePreview(val) {
    if (!val) return;
    const [h, m] = val.split(':').map(Number);
    const base   = h * 60 + m;
    document.getElementById('prevTerlambat').textContent   = toTime(base + 20);
    document.getElementById('prevPulang').textContent      = toTime(base + 480);
    document.getElementById('prevBatasPulang').textContent = toTime(base + 540);
}
function toTime(t) {
    const h = Math.floor(t / 60) % 24, m = t % 60;
    return String(h).padStart(2,'0') + ':' + String(m).padStart(2,'0');
}

// ── Peta Leaflet ─────────────────────────────────────────────
const initLat = <?= $latitude  ?: -6.906033 ?>;
const initLng = <?= $longitude ?: 112.080516 ?>;
const initR   = <?= $radius ?: 100 ?>;

const map = L.map('map').setView([initLat, initLng], 16);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors', maxZoom: 19
}).addTo(map);

let marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);
let circle = L.circle([initLat, initLng], {
    radius: initR, color: '#3b82f6', fillColor: '#93c5fd', fillOpacity: 0.2, weight: 2
}).addTo(map);

map.on('click', e => setKoordinat(e.latlng.lat, e.latlng.lng));
marker.on('dragend', () => {
    const p = marker.getLatLng();
    setKoordinat(p.lat, p.lng);
});

function setKoordinat(lat, lng) {
    marker.setLatLng([lat, lng]);
    circle.setLatLng([lat, lng]);
    document.getElementById('inputLat').value = lat.toFixed(8);
    document.getElementById('inputLng').value = lng.toFixed(8);
}

function updateMarker() {
    const lat = parseFloat(document.getElementById('inputLat').value);
    const lng = parseFloat(document.getElementById('inputLng').value);
    if (!isNaN(lat) && !isNaN(lng)) {
        marker.setLatLng([lat, lng]);
        circle.setLatLng([lat, lng]);
        map.setView([lat, lng], map.getZoom());
    }
}

function updateRadius(val) {
    circle.setRadius(parseInt(val) || 100);
}

function syncSlider(val) {
    document.getElementById('sliderRadius').value = val;
}

function deteksiLokasi() {
    if (!navigator.geolocation) { alert('Browser tidak mendukung geolokasi.'); return; }
    navigator.geolocation.getCurrentPosition(pos => {
        setKoordinat(pos.coords.latitude, pos.coords.longitude);
        map.setView([pos.coords.latitude, pos.coords.longitude], 17);
    }, () => alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.'));
}
</script>

<?= $this->endSection() ?>

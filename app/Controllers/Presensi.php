<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use App\Models\ProfilModel;
use App\Models\SettingAbsenModel;

class Presensi extends BaseController
{
    protected $presensiModel;
    protected $profilModel;
    protected $settingModel;

    public function __construct()
    {
        helper('image');
        $this->presensiModel = new PresensiModel();
        $this->profilModel   = new ProfilModel();
        $this->settingModel  = new SettingAbsenModel();
    }

    // Rumus Haversine — hitung jarak dua koordinat GPS dalam meter
    private function hitungJarak(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R    = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a    = sin($dLat/2) * sin($dLat/2)
              + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
              * sin($dLng/2) * sin($dLng/2);
        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    public function index()
    {
        $idUser   = session()->get('id_user');
        $profil   = $this->profilModel->where('id_user', $idUser)->first();
        $today    = date('Y-m-d');
        $presensi = $this->presensiModel->where('id_user', $idUser)->where('tanggal', $today)->first();
        $setting  = $this->settingModel->first();

        return view('presensi/index', [
            'profil'   => $profil,
            'presensi' => $presensi,
            'setting'  => $setting,
        ]);
    }

    // ================= MASUK =================
    public function masuk()
    {
        $idUser  = session()->get('id_user');
        $today   = date('Y-m-d');
        $now     = date('H:i:s');
        $setting = $this->settingModel->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'Setting absen belum dikonfigurasi');
        }

        if ($now < $setting['jam_masuk_mulai']) {
            return redirect()->back()->with('error', 'Belum waktunya absen masuk');
        }

        // ── VALIDASI RADIUS ──────────────────────────────────────────
        if (!empty($setting['latitude']) && !empty($setting['longitude'])) {
            $latUser = (float) $this->request->getPost('latitude');
            $lngUser = (float) $this->request->getPost('longitude');

            if ($latUser == 0 && $lngUser == 0) {
                return redirect()->back()->with('error',
                    'Lokasi GPS tidak terdeteksi. Pastikan GPS aktif dan izinkan akses lokasi di browser.'
                );
            }

            $jarak = $this->hitungJarak(
                $latUser, $lngUser,
                (float) $setting['latitude'],
                (float) $setting['longitude']
            );

            $radius = (int) ($setting['radius'] ?? 100);
            if ($jarak > $radius) {
                $jarakBulat = round($jarak);
                return redirect()->back()->with('error',
                    "Lokasi kamu terlalu jauh dari kantor ({$jarakBulat} meter). "
                    . "Maksimum radius adalah {$radius} meter."
                );
            }
        }
        // ── END VALIDASI RADIUS ───────────────────────────────────────

        $status         = 'hadir';
        $menitTerlambat = null;
        $batasTerlambat = date('H:i:s', strtotime($setting['jam_masuk_mulai']) + (20 * 60));

        if ($now > $batasTerlambat) {
            $status         = 'terlambat';
            $menitTerlambat = (int) ceil((strtotime($now) - strtotime($setting['jam_masuk_mulai'])) / 60);
        }

        $cek = $this->presensiModel->where('id_user', $idUser)->where('tanggal', $today)->first();
        if ($cek && !empty($cek['jam_masuk'])) {
            return redirect()->back()->with('error', 'Kamu sudah absen masuk hari ini');
        }

        $fotoBase64 = $this->request->getPost('foto_base64');
        if (!$fotoBase64) {
            return redirect()->back()->with('error', 'Foto belum diambil');
        }

        $profil   = $this->profilModel->where('id_user', $idUser)->first();
        $namaFoto = simpan_foto_base64($fotoBase64, 'uploads/presensi', 'masuk_' . time());
        if (!$namaFoto) {
            return redirect()->back()->with('error', 'Foto tidak valid atau gagal disimpan, coba lagi');
        }

        $dataPresensi = [
            'jam_masuk'     => $now,
            'foto_masuk'    => $namaFoto,
            'latitude'      => $this->request->getPost('latitude') ?? 0,
            'longitude'     => $this->request->getPost('longitude') ?? 0,
            'status'        => $status,
            'keterlambatan' => $menitTerlambat,
        ];

        if ($cek) {
            $this->presensiModel->update($cek['id_presensi'], $dataPresensi);
        } else {
            $this->presensiModel->insert(array_merge($dataPresensi, [
                'id_user'     => $idUser,
                'id_instansi' => $profil['id_instansi'] ?? null,
                'tanggal'     => $today,
                'jam_pulang'  => '00:00:00',
                'foto_pulang' => '',
            ]));
        }

        $pesan = ($status === 'terlambat')
            ? "Absen masuk berhasil — Terlambat {$menitTerlambat} menit"
            : 'Absen masuk berhasil — Tepat Waktu';

        return redirect()->to('/dashboard')->with('success', $pesan);
    }

    // ================= PULANG =================
    public function pulang()
    {
        $idUser  = session()->get('id_user');
        $today   = date('Y-m-d');
        $now     = date('H:i:s');
        $setting = $this->settingModel->first();

        if (!$setting) {
            return redirect()->back()->with('error', 'Setting absen belum dikonfigurasi');
        }

        if ($now < $setting['jam_pulang_mulai']) {
            return redirect()->back()->with('error', 'Belum waktunya absen pulang');
        }

        if ($now > $setting['jam_pulang_selesai']) {
            return redirect()->back()->with('error', 'Sudah melewati batas absen pulang');
        }

        $cek = $this->presensiModel->where('id_user', $idUser)->where('tanggal', $today)->first();

        if (!$cek || empty($cek['jam_masuk'])) {
            return redirect()->back()->with('error', 'Silakan absen masuk dulu');
        }

        if (!empty($cek['jam_pulang']) && $cek['jam_pulang'] != '00:00:00') {
            return redirect()->back()->with('error', 'Kamu sudah absen pulang');
        }

        $fotoBase64 = $this->request->getPost('foto_base64');
        if (!$fotoBase64) {
            return redirect()->back()->with('error', 'Foto belum diambil');
        }

        $namaFoto = simpan_foto_base64($fotoBase64, 'uploads/presensi', 'pulang_' . time());
        if (!$namaFoto) {
            return redirect()->back()->with('error', 'Foto tidak valid atau gagal disimpan, coba lagi');
        }

        $jamMasuk      = strtotime($cek['jam_masuk']);
        $jamPulangNow  = strtotime($now);
        $selisihDetik  = $jamPulangNow - $jamMasuk;
        $jamKerjaWajib = 8 * 3600;

        $statusPulang = 'pulang_normal';
        $pesanPulang  = 'Absen pulang berhasil';

        if ($selisihDetik < $jamKerjaWajib) {
            $statusPulang = 'pulang_cepat';
            $kurangMenit  = (int) ceil(($jamKerjaWajib - $selisihDetik) / 60);
            $pesanPulang  = "Absen pulang berhasil — Pulang Cepat ({$kurangMenit} menit lebih awal dari 8 jam kerja)";
        }

        $this->presensiModel->update($cek['id_presensi'], [
            'jam_pulang'    => $now,
            'foto_pulang'   => $namaFoto,
            'status_pulang' => $statusPulang,
        ]);

        return redirect()->to('/dashboard')->with('success', $pesanPulang);
    }
}

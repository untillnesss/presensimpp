<?php

/**
 * Helper: Kompresi & Simpan Foto
 * ─────────────────────────────────────────────────────────────────────────────
 * Dua fungsi utama:
 *   - simpan_foto_base64()  → untuk foto presensi (dari webcam / base64)
 *   - simpan_foto_upload()  → untuk foto profil (dari <input type="file">)
 *
 * Hasil kompresi:
 *   - Resolusi maksimum  : 800 × 800 px (otomatis di-scale-down jika lebih besar)
 *   - Format output      : JPEG dengan quality 70%
 *   - Estimasi ukuran    : 30–80 KB per foto (sebelumnya bisa 500 KB–2 MB)
 */

// ─────────────────────────────────────────────────────────────────────────────
// 1. Simpan foto dari base64 (presensi masuk / pulang)
// ─────────────────────────────────────────────────────────────────────────────

if (!function_exists('simpan_foto_base64')) {
    /**
     * Decode base64, kompres, lalu simpan sebagai JPEG.
     *
     * @param  string  $base64String  String base64 lengkap (dengan header "data:image/...;base64,")
     * @param  string  $direktori     Path direktori tujuan (tanpa trailing slash), relatif ke FCPATH
     * @param  string  $namaFile      Nama file output TANPA ekstensi (ekstensi otomatis .jpg)
     * @param  int     $maxPx         Ukuran sisi maksimum (px). Default 800.
     * @param  int     $quality       Kualitas JPEG 0–100. Default 70.
     * @return string|false           Nama file tersimpan (misal "masuk_xxx.jpg"), atau false jika gagal
     */
    function simpan_foto_base64(
        string $base64String,
        string $direktori,
        string $namaFile,
        int    $maxPx   = 800,
        int    $quality = 70
    ): string|false {

        // Pisahkan header dan data
        $parts = explode(';base64,', $base64String);
        if (count($parts) < 2) {
            return false;
        }

        $imageData = base64_decode($parts[1]);
        if ($imageData === false) {
            return false;
        }

        // Buat resource GD dari raw bytes
        $src = @imagecreatefromstring($imageData);
        if (!$src) {
            return false;
        }

        return _kompres_dan_simpan($src, $direktori, $namaFile, $maxPx, $quality);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. Simpan foto dari file upload (profil pegawai)
// ─────────────────────────────────────────────────────────────────────────────

if (!function_exists('simpan_foto_upload')) {
    /**
     * Terima objek UploadedFile CI4, kompres, lalu simpan sebagai JPEG.
     *
     * @param  \CodeIgniter\HTTP\Files\UploadedFile $file       Objek file dari $this->request->getFile()
     * @param  string                               $direktori  Path direktori tujuan, relatif ke FCPATH
     * @param  string|null                          $namaFile   Nama file output (tanpa ekstensi).
     *                                                          Jika null, dibuat acak.
     * @param  int                                  $maxPx      Ukuran sisi maksimum (px). Default 800.
     * @param  int                                  $quality    Kualitas JPEG 0–100. Default 70.
     * @return string|false                                     Nama file tersimpan, atau false jika gagal
     */
    function simpan_foto_upload(
        $file,
        string  $direktori,
        ?string $namaFile = null,
        int     $maxPx    = 800,
        int     $quality  = 70
    ): string|false {

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return false;
        }

        $tmpPath = $file->getTempName();
        $mime    = $file->getMimeType();

        $src = _buat_resource_gd($tmpPath, $mime);
        if (!$src) {
            return false;
        }

        if ($namaFile === null) {
            $namaFile = bin2hex(random_bytes(8));
        }

        return _kompres_dan_simpan($src, $direktori, $namaFile, $maxPx, $quality);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Fungsi internal — tidak untuk dipanggil langsung dari luar
// ─────────────────────────────────────────────────────────────────────────────

if (!function_exists('_buat_resource_gd')) {
    /** Buat resource GD berdasarkan path file dan MIME type. */
    function _buat_resource_gd(string $path, string $mime)
    {
        return match (true) {
            str_contains($mime, 'jpeg') || str_contains($mime, 'jpg') => @imagecreatefromjpeg($path),
            str_contains($mime, 'png')                                 => @imagecreatefrompng($path),
            str_contains($mime, 'gif')                                 => @imagecreatefromgif($path),
            str_contains($mime, 'webp')                                => @imagecreatefromwebp($path),
            default                                                    => @imagecreatefromstring(file_get_contents($path)),
        };
    }
}

if (!function_exists('_kompres_dan_simpan')) {
    /**
     * Scale-down gambar jika melebihi $maxPx, lalu simpan sebagai JPEG.
     *
     * @param  resource $src        GD image resource (akan di-destroy setelah selesai)
     * @param  string   $direktori  Direktori relatif terhadap FCPATH
     * @param  string   $namaFile   Nama file tanpa ekstensi
     * @param  int      $maxPx      Sisi maksimum (width atau height)
     * @param  int      $quality    Kualitas JPEG
     * @return string|false         Nama file ".jpg" yang tersimpan, atau false jika gagal
     */
    function _kompres_dan_simpan($src, string $direktori, string $namaFile, int $maxPx, int $quality): string|false
    {
        $origW = imagesx($src);
        $origH = imagesy($src);

        // Hitung dimensi baru (hanya scale-down, tidak pernah scale-up)
        if ($origW > $maxPx || $origH > $maxPx) {
            if ($origW >= $origH) {
                $newW = $maxPx;
                $newH = (int) round($origH * $maxPx / $origW);
            } else {
                $newH = $maxPx;
                $newW = (int) round($origW * $maxPx / $origH);
            }
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        // Buat canvas baru dan salin gambar (resample = bicubic, lebih halus)
        $dst = imagecreatetruecolor($newW, $newH);
        if (!$dst) {
            imagedestroy($src);
            return false;
        }

        // Preserve transparency untuk PNG (jika ada)
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparan = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparan);
        imagealphablending($dst, true);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        // Pastikan direktori ada
        $fullDir = rtrim(FCPATH, '/') . '/' . trim($direktori, '/');
        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        $namaOutput = $namaFile . '.jpg';
        $fullPath   = $fullDir . '/' . $namaOutput;

        $berhasil = imagejpeg($dst, $fullPath, $quality);
        imagedestroy($dst);

        return $berhasil ? $namaOutput : false;
    }
}

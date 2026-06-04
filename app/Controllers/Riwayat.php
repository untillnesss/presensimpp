<?php

namespace App\Controllers;

use App\Models\PresensiModel;

class Riwayat extends BaseController
{
    public function index()
    {
        $presensiModel = new PresensiModel();

        // ambil id user dari session
        $userId = session()->get('id_user');

        // filter bulan & tahun
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // ambil data presensi
        $dataPresensi = $presensiModel
            ->where('id_user', $userId)
            ->where('MONTH(tanggal)', (int)$bulan)
            ->where('YEAR(tanggal)', (int)$tahun)
            ->findAll();

        // mapping berdasarkan tanggal
        $presensiByTanggal = [];
        foreach ($dataPresensi as $row) {
            $presensiByTanggal[$row['tanggal']] = $row;
        }

        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, (int)$bulan, (int)$tahun);

        $riwayat = [];
        $today = date('Y-m-d');

        for ($i = 1; $i <= $jumlahHari; $i++) {

            $tanggal = date('Y-m-d', strtotime("$tahun-$bulan-$i"));

            // stop tanggal masa depan
            if ($tanggal > $today) {
                break;
            }

            $hari = date('D', strtotime($tanggal));

            $data = [
                'tanggal' => $tanggal,
                'jam_masuk' => '-',
                'jam_pulang' => '-',
                'status' => 'tidak hadir'
            ];

            // libur (Jumat, Sabtu, Minggu)
            if ($hari == 'Fri' || $hari == 'Sat' || $hari == 'Sun') {
                $data['status'] = 'libur';
            }

            // kalau ada data presensi
            if (isset($presensiByTanggal[$tanggal])) {
                $row = $presensiByTanggal[$tanggal];

                $data['jam_masuk'] = (!empty($row['jam_masuk']) && $row['jam_masuk'] != '00:00:00') 
                                    ? $row['jam_masuk'] : '-';

                $data['jam_pulang'] = (!empty($row['jam_pulang']) && $row['jam_pulang'] != '00:00:00') 
                                    ? $row['jam_pulang'] : '-';

                $data['status'] = $row['status'] ?? 'hadir';
            }

            $riwayat[] = $data;
        }

        // 🔥 BALIK URUTAN (TERBARU DI ATAS)
        $riwayat = array_reverse($riwayat);

        return view('riwayat/index', [
            'riwayat' => $riwayat,
            'bulan' => (int)$bulan,
            'tahun' => (int)$tahun
        ]);
    }
}
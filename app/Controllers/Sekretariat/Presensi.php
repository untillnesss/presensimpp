<?php

namespace App\Controllers\Sekretariat;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use App\Models\InstansiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Presensi extends BaseController
{
    public function index()
    {
        $presensiModel = new PresensiModel();
        $instansiModel = new InstansiModel();

        $periode          = $this->request->getGet('periode') ?? 'bulanan';
        $bulan            = (int)($this->request->getGet('bulan') ?? date('m'));
        $tahun            = (int)($this->request->getGet('tahun') ?? date('Y'));
        $minggu           = (int)($this->request->getGet('minggu') ?? date('W'));
        $tanggal          = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $selectedInstansi = $this->request->getGet('instansi') ?? '';

        $query = $presensiModel
            ->select('data_presensi.*, profil.nama, instansi.nama_instansi')
            ->join('profil', 'profil.id_user = data_presensi.id_user', 'left')
            ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi', 'left');

        // Filter berdasarkan periode
        if ($periode === 'harian') {
            $query->where('tanggal', $tanggal);
        } elseif ($periode === 'mingguan') {
            $query->where('YEAR(tanggal)', $tahun)
                  ->where('WEEK(tanggal, 1)', $minggu);
        } else {
            // bulanan (default)
            $query->where('MONTH(tanggal)', $bulan)
                  ->where('YEAR(tanggal)', $tahun);
        }

        $namaInstansiFilter = '';
        if (!empty($selectedInstansi)) {
            $query->where('data_presensi.id_instansi', $selectedInstansi);
            $inst = $instansiModel->find($selectedInstansi);
            $namaInstansiFilter = $inst['nama_instansi'] ?? '';
        }

        $presensi = $query->orderBy('tanggal', 'DESC')->findAll();
        $instansi = $instansiModel->where('status_aktif', 1)->findAll();

        // Hitung minggu-minggu dalam tahun untuk dropdown
        $totalMinggu = (int)date('W', mktime(0,0,0,12,28,$tahun));

        return view('sekretariat/presensi', [
            'presensi'           => $presensi,
            'instansi'           => $instansi,
            'periode'            => $periode,
            'bulan'              => $bulan,
            'tahun'              => $tahun,
            'minggu'             => $minggu,
            'tanggal'            => $tanggal,
            'totalMinggu'        => $totalMinggu,
            'selectedInstansi'   => $selectedInstansi,
            'namaInstansiFilter' => $namaInstansiFilter,
        ]);
    }

    public function rekap()
    {
        $presensiModel = new PresensiModel();
        $instansiModel = new InstansiModel();

        $periode          = $this->request->getGet('periode') ?? 'bulanan';
        $bulan            = (int)($this->request->getGet('bulan') ?? date('m'));
        $tahun            = (int)($this->request->getGet('tahun') ?? date('Y'));
        $minggu           = (int)($this->request->getGet('minggu') ?? date('W'));
        $tanggal          = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $selectedInstansi = $this->request->getGet('instansi') ?? '';

        $query = $presensiModel
            ->select('data_presensi.*, profil.nama, instansi.nama_instansi')
            ->join('profil', 'profil.id_user = data_presensi.id_user', 'left')
            ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi', 'left');

        if ($periode === 'harian') {
            $query->where('tanggal', $tanggal);
            $labelPeriode = 'Harian ' . date('d F Y', strtotime($tanggal));
            $fileLabel    = date('d-m-Y', strtotime($tanggal));
        } elseif ($periode === 'mingguan') {
            $query->where('YEAR(tanggal)', $tahun)->where('WEEK(tanggal, 1)', $minggu);
            // Hitung tanggal Senin-Minggu dari nomor minggu
            $senin  = date('d M', strtotime("{$tahun}-W{$minggu}-1"));
            $minggu_ = date('d M Y', strtotime("{$tahun}-W{$minggu}-7"));
            $labelPeriode = "Mingguan {$senin} – {$minggu_}";
            $fileLabel    = "Minggu{$minggu}_{$tahun}";
        } else {
            $query->where('MONTH(tanggal)', $bulan)->where('YEAR(tanggal)', $tahun);
            $labelPeriode = date('F', mktime(0,0,0,$bulan,1)) . ' ' . $tahun;
            $fileLabel    = date('F', mktime(0,0,0,$bulan,1)) . "_$tahun";
        }

        $namaInstansiFilter = 'Semua Instansi';
        if (!empty($selectedInstansi)) {
            $query->where('data_presensi.id_instansi', $selectedInstansi);
            $inst = $instansiModel->find($selectedInstansi);
            $namaInstansiFilter = $inst['nama_instansi'] ?? 'Semua Instansi';
        }

        $presensi = $query->orderBy('tanggal', 'ASC')->findAll();

        // ======== BUILD EXCEL ========
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Presensi');

        // --- Header Judul ---
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'REKAP PRESENSI PEGAWAI MPP TUBAN');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', "Periode: {$labelPeriode} | Instansi: {$namaInstansiFilter}");
        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', 'Dicetak: ' . date('d F Y H:i') . ' WIB');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        foreach (['A1','A2','A3'] as $cell) {
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        $sheet->getStyle('A1:H3')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('1e1b4b');
        $sheet->getStyle('A1:H3')->getFont()->getColor()->setRGB('FFFFFF');

        // --- Row kosong ---
        $sheet->getRowDimension(4)->setRowHeight(6);

        // --- Header Tabel ---
        $headers = ['No', 'Nama Pegawai', 'Instansi', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterlambatan'];
        $cols    = ['A','B','C','D','E','F','G','H'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($cols[$i].'5', $h);
        }
        $sheet->getStyle('A5:H5')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4f46e5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(22);

        // --- Data ---
        $row = 6;
        $no  = 1;
        $statusColors = [
            'hadir'     => ['bg' => 'D1FAE5', 'fg' => '065F46'],
            'terlambat' => ['bg' => 'FEE2E2', 'fg' => '991B1B'],
            'izin'      => ['bg' => 'FEF9C3', 'fg' => '92400E'],
            'sakit'     => ['bg' => 'DBEAFE', 'fg' => '1E40AF'],
            'cuti'      => ['bg' => 'EDE9FE', 'fg' => '5B21B6'],
        ];

        if (empty($presensi)) {
            $sheet->mergeCells("A6:H6");
            $sheet->setCellValue("A6", 'Tidak ada data untuk periode ini');
            $sheet->getStyle('A6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A6')->getFont()->setItalic(true)->getColor()->setRGB('9CA3AF');
        } else {
            foreach ($presensi as $p) {
                $masuk     = (!empty($p['jam_masuk']) && $p['jam_masuk'] != '00:00:00') ? date('H:i', strtotime($p['jam_masuk'])) : '-';
                $pulang    = (!empty($p['jam_pulang']) && $p['jam_pulang'] != '00:00:00') ? date('H:i', strtotime($p['jam_pulang'])) : '-';
                $terlambat = !empty($p['keterlambatan']) ? $p['keterlambatan'].' menit' : '-';

                $sheet->setCellValue("A{$row}", $no++);
                $sheet->setCellValue("B{$row}", $p['nama'] ?? '-');
                $sheet->setCellValue("C{$row}", $p['nama_instansi'] ?? '-');
                $sheet->setCellValue("D{$row}", date('d/m/Y', strtotime($p['tanggal'])));
                $sheet->setCellValue("E{$row}", $masuk);
                $sheet->setCellValue("F{$row}", $pulang);
                $sheet->setCellValue("G{$row}", ucfirst($p['status'] ?? ''));
                $sheet->setCellValue("H{$row}", $terlambat);

                $bgColor = ($row % 2 === 0) ? 'F5F6FF' : 'FFFFFF';
                $sheet->getStyle("A{$row}:H{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);

                $stKey = strtolower($p['status'] ?? '');
                if (isset($statusColors[$stKey])) {
                    $sheet->getStyle("G{$row}")->getFill()->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB($statusColors[$stKey]['bg']);
                    $sheet->getStyle("G{$row}")->getFont()->setBold(true)
                        ->getColor()->setRGB($statusColors[$stKey]['fg']);
                }

                $sheet->getStyle("A{$row}:H{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E5E7EB');
                $sheet->getRowDimension($row)->setRowHeight(18);
                $row++;
            }
        }

        // Lebar kolom
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('C')->setWidth(32);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(14);
        $sheet->getColumnDimension('H')->setWidth(16);

        $sheet->getStyle("A6:A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D6:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("G6:H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $filename = "Rekap_Presensi_{$fileLabel}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}

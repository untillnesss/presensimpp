<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PresensiModel;
use App\Models\ProfilModel;
use App\Models\UserModel;
use App\Models\InstansiModel;
use App\Models\SettingAbsenModel;

class Presensi extends BaseController
{
    protected $presensiModel;
    protected $profilModel;
    protected $userModel;
    protected $instansiModel;
    protected $settingModel;

    public function __construct()
    {
        helper('log');
        $this->presensiModel = new PresensiModel();
        $this->profilModel   = new ProfilModel();
        $this->userModel     = new UserModel();
        $this->instansiModel = new InstansiModel();
        $this->settingModel  = new SettingAbsenModel();
    }

    public function index()
    {
        $filter  = $this->request->getGet('filter')  ?? 'harian';
        $tahun   = $this->request->getGet('tahun')   ?? date('Y');
        $bulan   = $this->request->getGet('bulan')   ?? date('m');
        $minggu  = $this->request->getGet('minggu')  ?? date('W');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $builder = $this->presensiModel
            ->select('data_presensi.*, profil.nama AS nama, profil.no_id AS no_id_pegawai, instansi.nama_instansi')
            ->join('profil', 'profil.id_user = data_presensi.id_user', 'left')
            ->join('instansi', 'instansi.id_instansi = data_presensi.id_instansi', 'left');

        if ($filter === 'harian') {
            $builder->where('data_presensi.tanggal', $tanggal);
        } elseif ($filter === 'mingguan') {
            $dto = new \DateTime();
            $dto->setISODate((int)$tahun, (int)$minggu);
            $senin       = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $mingguAkhir = $dto->format('Y-m-d');
            $builder->where('data_presensi.tanggal >=', $senin)
                    ->where('data_presensi.tanggal <=', $mingguAkhir);
        } elseif ($filter === 'bulanan') {
            $builder->where('YEAR(data_presensi.tanggal)', $tahun)
                    ->where('MONTH(data_presensi.tanggal)', $bulan);
        }

        $data = $builder->orderBy('data_presensi.tanggal', 'DESC')->findAll();

        $daftarTahun = [];
        for ($y = date('Y'); $y >= date('Y') - 4; $y--) {
            $daftarTahun[] = $y;
        }

        return view('admin/presensi/index', [
            'title'       => 'Data Presensi',
            'presensi'    => $data,
            'filter'      => $filter,
            'tahun'       => $tahun,
            'bulan'       => $bulan,
            'minggu'      => $minggu,
            'tanggal'     => $tanggal,
            'daftarTahun' => $daftarTahun,
        ]);
    }

    public function tambah()
    {
        $jenis = $this->request->getGet('jenis');

        // Ambil semua pegawai beserta no_id dan instansinya untuk dropdown
        $pegawai = $this->profilModel
            ->select('profil.id_user, profil.nama, profil.no_id, profil.id_instansi, instansi.nama_instansi')
            ->join('instansi', 'instansi.id_instansi = profil.id_instansi', 'left')
            ->join('user', 'user.id_user = profil.id_user', 'left')
            ->where('user.is_active', 1)
            ->where('user.role', 'pegawai')
            ->orderBy('profil.nama', 'ASC')
            ->findAll();

        return view('admin/presensi/tambah', [
            'instansi' => $this->instansiModel->where('status_aktif', 1)->findAll(),
            'pegawai'  => $pegawai,
            'jenis'    => $jenis,
        ]);
    }

    public function simpan()
    {
        $jenis      = $this->request->getPost('jenis');
        $idUser     = $this->request->getPost('id_user');
        $idInstansi = $this->request->getPost('id_instansi');
        $status     = $this->request->getPost('status');

        // Validasi field wajib
        if (empty($idUser) || empty($idInstansi) || empty($status) || empty($jenis)) {
            return redirect()->back()->withInput()
                ->with('error', 'Semua field wajib diisi!');
        }

        // Ambil profil pegawai berdasarkan id_user
        $profil = $this->profilModel->where('id_user', $idUser)->first();
        if (!$profil) {
            return redirect()->back()->withInput()
                ->with('error', 'Pegawai tidak ditemukan!');
        }

        $fotoField = ($jenis === 'masuk') ? 'foto_masuk' : 'foto_pulang';
        $fotoFile  = $this->request->getFile($fotoField);
        if (!$fotoFile || !$fotoFile->isValid() || $fotoFile->hasMoved()) {
            return redirect()->back()->withInput()
                ->with('error', 'Foto absen wajib diupload!');
        }

        // Tanggal & jam otomatis
        $tanggalSekarang = date('Y-m-d');
        $jamSekarang     = date('H:i:s');

        // Upload foto
        if (!is_dir(FCPATH . 'uploads/presensi/')) {
            mkdir(FCPATH . 'uploads/presensi/', 0775, true);
        }
        $namaFoto = $jenis . '_' . time() . '.' . $fotoFile->getExtension();
        $fotoFile->move(FCPATH . 'uploads/presensi/', $namaFoto);

        if ($jenis === 'masuk') {
            // Cek sudah absen masuk hari ini?
            $sudahMasuk = $this->presensiModel
                ->where('id_user', $idUser)
                ->where('tanggal', $tanggalSekarang)
                ->first();

            if ($sudahMasuk) {
                return redirect()->back()->withInput()
                    ->with('error', 'Pegawai "' . $profil['nama'] . '" sudah absen masuk hari ini!');
            }

            // Hitung status otomatis
            $setting       = $this->settingModel->first();
            $statusFinal   = $status;
            $keterlambatan = 0;

            if (in_array($status, ['hadir', 'terlambat']) && $setting) {
                $statusFinal = 'hadir';
                if ($jamSekarang > $setting['jam_masuk_selesai']) {
                    if ($jamSekarang <= $setting['batas_terlambat']) {
                        $statusFinal   = 'terlambat';
                        $telat         = strtotime($jamSekarang) - strtotime($setting['jam_masuk_selesai']);
                        $keterlambatan = floor($telat / 60);
                    } else {
                        $statusFinal = 'alpha';
                    }
                }
            }

            $this->presensiModel->insert([
                'id_user'       => $idUser,
                'id_instansi'   => $idInstansi,
                'tanggal'       => $tanggalSekarang,
                'jam_masuk'     => $jamSekarang,
                'jam_pulang'    => '00:00:00',
                'foto_masuk'    => $namaFoto,
                'foto_pulang'   => '',
                'latitude'      => 0,
                'longitude'     => 0,
                'status'        => $statusFinal,
                'keterlambatan' => $keterlambatan,
                'sumber'        => 'manual',
            ]);

            logAktivitas('Tambah Presensi Masuk', "Absen masuk manual: {$profil['nama']} (No.ID: {$profil['no_id']})");
            return redirect()->to('/admin/presensi')
                ->with('success', 'Absen masuk "' . $profil['nama'] . '" berhasil disimpan');

        } else {
            // Absen pulang
            $presensiHariIni = $this->presensiModel
                ->where('id_user', $idUser)
                ->where('tanggal', $tanggalSekarang)
                ->first();

            if (!$presensiHariIni) {
                return redirect()->back()->withInput()
                    ->with('error', 'Pegawai "' . $profil['nama'] . '" belum absen masuk hari ini. Tidak bisa absen pulang!');
            }

            if (!empty($presensiHariIni['jam_pulang']) && $presensiHariIni['jam_pulang'] !== '00:00:00') {
                return redirect()->back()->withInput()
                    ->with('error', 'Pegawai "' . $profil['nama'] . '" sudah absen pulang hari ini!');
            }

            $this->presensiModel->update($presensiHariIni['id_presensi'], [
                'jam_pulang'  => $jamSekarang,
                'foto_pulang' => $namaFoto,
            ]);

            logAktivitas('Tambah Presensi Pulang', "Absen pulang manual: {$profil['nama']} (No.ID: {$profil['no_id']})");
            return redirect()->to('/admin/presensi')
                ->with('success', 'Absen pulang "' . $profil['nama'] . '" berhasil disimpan');
        }
    }

    public function edit($id)
    {
        $presensi = $this->presensiModel->find($id);

        if (!$presensi) {
            return redirect()->to('/admin/presensi')->with('error', 'Data presensi tidak ditemukan');
        }

        $users = $this->profilModel->select('id_user, nama, no_id')->findAll();

        return view('admin/presensi/edit', [
            'presensi' => $presensi,
            'instansi' => $this->instansiModel->where('status_aktif', 1)->findAll(),
            'users'    => $users,
        ]);
    }

    public function update($id)
    {
        $data = $this->presensiModel->find($id);
        if (!$data) {
            return redirect()->to('/admin/presensi')->with('error', 'Data tidak ditemukan');
        }

        $idUser     = $this->request->getPost('id_user');
        $idInstansi = $this->request->getPost('id_instansi');
        $tanggal    = $this->request->getPost('tanggal');
        $jamMasuk   = $this->request->getPost('jam_masuk');
        $statusPost = $this->request->getPost('status');

        if (empty($idUser) || empty($idInstansi) || empty($tanggal) || empty($jamMasuk) || empty($statusPost)) {
            return redirect()->back()->withInput()
                ->with('error', 'Semua field wajib diisi!');
        }

        $setting       = $this->settingModel->first();
        $status        = $statusPost;
        $keterlambatan = 0;

        if (in_array($statusPost, ['hadir', 'terlambat']) && $jamMasuk && $setting) {
            $status = 'hadir';
            if ($jamMasuk > $setting['jam_masuk_selesai']) {
                if ($jamMasuk <= $setting['batas_terlambat']) {
                    $status        = 'terlambat';
                    $telat         = strtotime($jamMasuk) - strtotime($setting['jam_masuk_selesai']);
                    $keterlambatan = floor($telat / 60);
                } else {
                    $status = 'alpha';
                }
            }
        }

        $updateData = [
            'id_user'       => $idUser,
            'id_instansi'   => $idInstansi,
            'tanggal'       => $tanggal,
            'jam_masuk'     => $jamMasuk,
            'jam_pulang'    => $this->request->getPost('jam_pulang') ?: '00:00:00',
            'status'        => $status,
            'keterlambatan' => $keterlambatan,
            'sumber'        => 'manual',
        ];

        $fotoMasuk = $this->request->getFile('foto_masuk');
        if ($fotoMasuk && $fotoMasuk->isValid() && !$fotoMasuk->hasMoved()) {
            $namaFoto = 'masuk_' . time() . '.' . $fotoMasuk->getExtension();
            $fotoMasuk->move(FCPATH . 'uploads/presensi/', $namaFoto);
            $updateData['foto_masuk'] = $namaFoto;
        }

        $fotoPulang = $this->request->getFile('foto_pulang');
        if ($fotoPulang && $fotoPulang->isValid() && !$fotoPulang->hasMoved()) {
            $namaFoto = 'pulang_' . time() . '.' . $fotoPulang->getExtension();
            $fotoPulang->move(FCPATH . 'uploads/presensi/', $namaFoto);
            $updateData['foto_pulang'] = $namaFoto;
        }

        $this->presensiModel->update($id, $updateData);

        logAktivitas('Edit Presensi', 'Edit presensi ID: ' . $id);
        return redirect()->to('/admin/presensi')->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $this->presensiModel->delete($id);
        logAktivitas('Hapus Presensi', 'Hapus presensi ID: ' . $id);
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}

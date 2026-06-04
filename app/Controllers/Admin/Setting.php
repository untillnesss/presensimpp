<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingAbsenModel;

class Setting extends BaseController
{
    protected $model;

    public function __construct()
    {
        helper('log');
        $this->model = new SettingAbsenModel();
    }

    public function index()
    {
        $data['setting'] = $this->model->first();
        return view('admin/setting/index', $data);
    }

    public function update()
    {
        $jamMasuk  = $this->request->getPost('jam_masuk');
        $latitude  = $this->request->getPost('latitude');
        $longitude = $this->request->getPost('longitude');
        $radius    = $this->request->getPost('radius');

        if (empty($jamMasuk)) {
            return redirect()->back()->with('error', 'Jam masuk wajib diisi!');
        }

        if (empty($latitude) || empty($longitude)) {
            return redirect()->back()->with('error', 'Koordinat kantor wajib diisi!');
        }

        if (empty($radius) || $radius < 10) {
            return redirect()->back()->with('error', 'Radius minimal 10 meter!');
        }

        $baseTime         = strtotime($jamMasuk);
        $batasTerlambat   = date('H:i:s', $baseTime + (20 * 60));
        $jamPulangMulai   = date('H:i:s', $baseTime + (8 * 3600));
        $jamPulangSelesai = date('H:i:s', $baseTime + (9 * 3600));

        $this->model->update(1, [
            'jam_masuk_mulai'    => $jamMasuk,
            'jam_masuk_selesai'  => $jamMasuk,
            'batas_terlambat'    => $batasTerlambat,
            'jam_pulang_mulai'   => $jamPulangMulai,
            'jam_pulang_selesai' => $jamPulangSelesai,
            'latitude'           => $latitude,
            'longitude'          => $longitude,
            'radius'             => (int) $radius,
            'update_at'          => date('Y-m-d H:i:s'),
        ]);

        logAktivitas('Ubah Setting', 'Jam masuk: ' . $jamMasuk . ' | Koordinat: (' . $latitude . ',' . $longitude . ') | Radius: ' . $radius . 'm');

        return redirect()->back()->with('success',
            'Setting berhasil disimpan! Jam masuk: ' . $jamMasuk
            . ' | Batas terlambat: ' . $batasTerlambat
            . ' | Jam pulang: ' . $jamPulangMulai
            . ' | Radius: ' . $radius . ' m'
        );
    }
}

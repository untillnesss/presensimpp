<?php

namespace App\Controllers\Sekretariat;

use App\Controllers\BaseController;
use App\Models\ProfilModel;
use App\Models\InstansiModel;

class Profil extends BaseController
{
    public function index()
    {
        $idUser = session()->get('id_user');

        $profilModel   = new ProfilModel();
        $instansiModel = new InstansiModel();

        $profil = $profilModel
            ->select('profil.*, instansi.nama_instansi')
            ->join('instansi', 'instansi.id_instansi = profil.id_instansi', 'left')
            ->where('profil.id_user', $idUser)
            ->first();

        $instansi = $instansiModel->where('status_aktif', 1)->findAll();

        return view('sekretariat/profil/index', [
            'title'    => 'Profil Sekretariat',
            'profil'   => $profil,
            'instansi' => $instansi,
        ]);
    }

    public function save()
    {
        helper('image');
        $idUser      = session()->get('id_user');
        $profilModel = new ProfilModel();

        $profilLama = $profilModel->where('id_user', $idUser)->first();

        $noId = trim($this->request->getPost('no_id'));

        if ($profilModel->isNoIdTaken($noId, (int)$idUser)) {
            return redirect()->back()->withInput()
                ->with('error', 'No. ID "' . $noId . '" sudah digunakan oleh pengguna lain. Gunakan No. ID yang berbeda.');
        }

        $data = [
            'id_user'     => $idUser,
            'no_id'       => $noId,
            'nama'        => $this->request->getPost('nama'),
            'jabatan'     => $this->request->getPost('jabatan'),
            'id_instansi' => $this->request->getPost('id_instansi'),
            'update_at'   => date('Y-m-d H:i:s'),
        ];

        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaFoto = simpan_foto_upload($file, 'uploads/profil', bin2hex(random_bytes(8)));
            if ($namaFoto) {
                $data['foto'] = $namaFoto;
            }
        }

        if ($profilLama) {
            $profilModel->update($profilLama['id_profil'], $data);
        } else {
            $profilModel->insert($data);
        }

        session()->set('nama', $data['nama']);
        session()->set('jabatan', $data['jabatan']);
        if (isset($data['foto'])) {
            session()->set('foto_profil', $data['foto']);
        }

        return redirect()->to('/sekretariat/profil')->with('success', 'Profil berhasil disimpan');
    }
}

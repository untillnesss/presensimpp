<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogModel;

class Riwayat extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new LogModel();
    }

    public function index()
    {
        $data = $this->logModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('admin/riwayat/index', [
            'log' => $data
        ]);
    }
}
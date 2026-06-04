<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ================= DEFAULT =================
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setAutoRoute(false); // 🔥 penting biar gak ambigu

// ================= AUTH =================
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::processLogin');
$routes->get('logout', 'Auth::logout');

// ================= REGISTER =================
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::processRegister');

// ================= VERIFIKASI OTP =================
$routes->get('verifikasi-otp', 'Auth::verifikasiOtp');
$routes->post('verifikasi-otp', 'Auth::processVerifikasiOtp');

// ================= DASHBOARD USER =================
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

// ================= PROFIL =================
$routes->get('profil', 'Profil::index', ['filter' => 'auth']);
$routes->post('profil/save', 'Profil::save', ['filter' => 'auth']);

// ================= PENGAJUAN =================
$routes->get('pengajuan', 'Pengajuan::index', ['filter' => 'auth']);
$routes->get('pengajuan/tambah', 'Pengajuan::tambah', ['filter' => 'auth']);
$routes->post('pengajuan/simpan', 'Pengajuan::simpan', ['filter' => 'auth']);
$routes->get('/pengajuan/delete/(:num)', 'Pengajuan::delete/$1');
$routes->get('/pengajuan/edit/(:num)', 'Pengajuan::edit/$1');
$routes->post('/pengajuan/update/(:num)', 'Pengajuan::update/$1');

// ================= PRESENSI =================
$routes->get('presensi', 'Presensi::index', ['filter' => 'auth']);
$routes->post('presensi/masuk', 'Presensi::masuk', ['filter' => 'auth']);
$routes->post('presensi/pulang', 'Presensi::pulang', ['filter' => 'auth']);

// ================= RIWAYAT =================
$routes->get('riwayat', 'Riwayat::index', ['filter' => 'auth']);

// ================= OTP & PASSWORD =================
$routes->get('lupa-password', 'Auth::forgotPassword');
$routes->post('lupa-password', 'Auth::sendOtp');

$routes->get('reset-password', 'Auth::resetPassword');
$routes->post('reset-password', 'Auth::processResetPassword');

// ================= ADMIN =================
$routes->group('admin', ['filter' => 'auth:admin'], function($routes){

    // ===== DASHBOARD =====
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('dashboard/hadir', 'Admin\Dashboard::hadir');
    $routes->get('dashboard/tidak-hadir', 'Admin\Dashboard::tidakHadir');
    $routes->get('dashboard/pengajuan', 'Admin\Dashboard::pengajuan');
    $routes->get('dashboard/detail/(:segment)', 'Admin\Dashboard::detail/$1');

    // ===== PRESENSI =====
    $routes->get('presensi', 'Admin\Presensi::index');
    $routes->get('presensi/tambah', 'Admin\Presensi::tambah');
    $routes->post('presensi/simpan', 'Admin\Presensi::simpan');

    // ===== PENGAJUAN =====
    $routes->get('pengajuan', 'Admin\Pengajuan::index');
    $routes->get('pengajuan/acc/(:num)', 'Admin\Pengajuan::acc/$1');
    $routes->get('pengajuan/tolak/(:num)', 'Admin\Pengajuan::tolak/$1');

    // ===== SETTING =====
    $routes->get('setting', 'Admin\Setting::index');
    $routes->post('setting/update', 'Admin\Setting::update');

    // ===== RIWAYAT =====
    $routes->get('riwayat', 'Admin\Riwayat::index');


    // ===== KELOLA PEGAWAI =====
    $routes->get('pegawai', 'Admin\Pegawai::index');
    $routes->post('pegawai/simpan', 'Admin\Pegawai::simpan');
    $routes->get('pegawai/setujui/(:num)', 'Admin\Pegawai::setujui/$1');
    $routes->get('pegawai/tolak/(:num)', 'Admin\Pegawai::tolakPendaftar/$1');
    $routes->get('pegawai/toggle-aktif/(:num)', 'Admin\Pegawai::toggleAktif/$1');
    $routes->get('pegawai/reset-password/(:num)', 'Admin\Pegawai::resetPassword/$1');
    $routes->get('pegawai/reset-device/(:num)', 'Admin\Pegawai::resetDevice/$1');
    $routes->get('pegawai/delete/(:num)', 'Admin\Pegawai::delete/$1');

    // ===== PROFIL ADMIN =====
    $routes->get('profil', 'Admin\Profil::index');
    $routes->post('profil/save', 'Admin\Profil::save');

});
// ================= SEKRETARIAT =================
// Tambahkan ini di dalam file Routes.php SETELAH blok admin

$routes->group('sekretariat', ['filter' => 'auth:sekretariat'], function($routes){
    $routes->get('dashboard', 'Sekretariat\Dashboard::index');
    $routes->get('dashboard/detail/(:segment)', 'Sekretariat\Dashboard::detail/$1');
    $routes->get('presensi',  'Sekretariat\Presensi::index');
    $routes->get('presensi/rekap', 'Sekretariat\Presensi::rekap');
    // ===== PROFIL SEKRETARIAT =====
    $routes->get('profil', 'Sekretariat\Profil::index');
    $routes->post('profil/save', 'Sekretariat\Profil::save');
});

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DinasLuarController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Route auth 
Route::get('/', [AuthController::class, 'get_login_page'])->name('login-page');
Route::get('/registrasi', [AuthController::class, 'get_registrasi_page'])->name('registrasi');
Route::post('/registrasi', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Route guru 
Route::group(['middleware' => ['guru', 'no-cache']], function () {
    Route::get('/dashboard-guru', [GuruController::class, 'get_dashboard_guru_page'])->name('dashboard-guru');
});

// Route kepala sekolah 
Route::group(['middleware' => ['kepsek', 'no-cache']], function () {
    Route::get('/dashboard-kepsek', [KepsekController::class, 'get_dashboard_kepsek_page'])->name('dashboard-kepsek');
    Route::get('/pengajuan-dinas-luar', [KepsekController::class, 'get_pengajuan_dinas_luar_page'])->name('pengajuan-dinas-luar');
});

// Route admin
Route::group(['middleware' => ['admin', 'no-cache']], function () {
    Route::get('/dashboard-admin', [AdminController::class, 'get_dashboard_admin_page'])->name('dashboard-admin');
    Route::get('/data-presensi', [AdminController::class, 'get_data_presensi_page'])->name('data-presensi');
});

// Route hanya untuk user yang sudah login (authenticated)
Route::group(['middleware' => ['auth', 'no-cache']], function () {
    Route::get('/profil', [PublicController::class, 'get_profil_page'])->name('profil');

    Route::get('/presensi-guru', [GuruController::class, 'get_presensi_page'])->name('presensi-guru');
    Route::get('/riwayat-presensi-guru', [GuruController::class, 'get_riwayat_presensi_guru_page'])->name('riwayat-presensi-guru');
    Route::get('/presensi-guru/face-detection', [GuruController::class, 'get_face_detection_page'])->name('face-detection-page');
    Route::get('/rekapitulasi-presensi', [PublicController::class, 'get_rekapitulasi_presensi_page'])->name('rekapitulasi-presensi');
    Route::get('/data-pengajar', [PublicController::class, 'get_data_pengajar_page'])->name('data-pengajar');
    Route::prefix('api')->group(function () {
        Route::post('/process-presensi', [PresensiController::class, 'processPresensi']);
        Route::post('/process-pengajuan-dinas-luar', [DinasLuarController::class, 'pengajuan_dinas_luar']);
    });
});


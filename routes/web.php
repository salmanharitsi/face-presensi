<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DinasLuarController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

// Route auth page
Route::get('/', [AuthController::class, 'get_login_page'])->name('login-page');
Route::get('/registrasi', [AuthController::class, 'get_registrasi_page'])->name('registrasi');
Route::post('/registrasi', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


// Route guru page
Route::group(['middleware' => ['guru', 'no-cache']], function () {
    Route::get('/dashboard-guru', [GuruController::class, 'get_dashboard_guru_page'])->name('dashboard-guru');
    Route::get('/presensi-guru', [GuruController::class, 'get_presensi_page'])->name('presensi-guru');
    Route::get('/riwayat-presensi-guru', [GuruController::class, 'get_riwayat_presensi_guru_page'])->name('riwayat-presensi-guru');
    Route::get('/presensi-guru/face-detection', [GuruController::class, 'get_face_detection_page'])->name('face-detection-page');

    Route::prefix('api')->group(function () {
        Route::post('/process-presensi', [PresensiController::class, 'processPresensi']);
        Route::post('/process-pengajuan-dinas-luar', [DinasLuarController::class, 'pengajuan_dinas_luar']);
    });
});

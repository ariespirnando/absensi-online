<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SiswaDashboard;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DataGuruController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\DataPelajaranController;
use App\Http\Controllers\KonfigurasiDataTAController;
use App\Http\Controllers\RekapitulasiKelasController;
use App\Http\Controllers\KonfigurasiDataKelasController;
use App\Http\Controllers\KonfigurasiPelajaranController;
use App\Http\Controllers\KonfigurasiSiswaController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\PointTransactionController;
use App\Http\Controllers\RekapitulasiSiswaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class,'index'])->name('login');
Route::get('forgot_password', [AuthController::class,'forgot_password'])->name('forgot_password');

Route::post('proses_login', [AuthController::class,'proses_login'])->name('proses_login');
Route::post('proses_forgot_password', [AuthController::class,'proses_forgot_password'])->name('proses_forgot_password');

Route::get('logout', [AuthController::class,'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('profile', [AdminDashboard::class,'index'])->name('profile');

    Route::group(['middleware' => ['auth.check:ADMIN']], function () {
        Route::get('admin', [AdminDashboard::class,'index'])->name('admin');
        Route::get('data/guru', [DataGuruController::class,'index'])->name('data_guru');
        Route::get('data/pelajaran', [DataPelajaranController::class,'index'])->name('data_pelajaran');
        Route::get('data/siswa', [DataSiswaController::class,'index'])->name('data_siswa');
        Route::get('konfigurasi/ta', [KonfigurasiDataTAController::class,'index'])->name('konfigurasi_ta');
        Route::get('konfigurasi/kelas/{id}', [KonfigurasiDataKelasController::class,'index'])->name('konfigurasi_kelas');
        Route::get('konfigurasi/pelajaran/{id}', [KonfigurasiPelajaranController::class,'index'])->name('konfigurasi_pelajaran');
        Route::get('konfigurasi/siswa/{id}', [KonfigurasiSiswaController::class,'index'])->name('konfigurasi_siswa');
        Route::get('absensi', [AbsensiController::class,'index'])->name('absensi');
        Route::get('konfigurasi/point', [PointController::class,'index'])->name('konfigurasi_point');
        Route::get('point', [PointTransactionController::class,'index'])->name('point');
        Route::get('rekapitulasi/kelas', [RekapitulasiKelasController::class,'index'])->name('rekapitulasi_kelas');
        Route::get('rekapitulasi/siswa', [RekapitulasiSiswaController::class,'index'])->name('rekapitulasi_siswa');
    });
    Route::group(['middleware' => ['auth.check:SISWA']], function () {
        Route::resource('siswa', SiswaDashboard::class);
    });
});

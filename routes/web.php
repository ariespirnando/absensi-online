<?php

use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataGuruController;
use App\Http\Controllers\DataPelajaranController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\KonfigurasiDataKelas;
use App\Http\Controllers\KonfigurasiDataTA;
use App\Http\Controllers\RekapitulasiKelas;
use App\Http\Controllers\RekapitulasiSiswa;
use App\Http\Controllers\SiswaDashboard;

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

// Route::get('/', function () {
//     return view('contents.data.guru.index');
// });

Route::get('/', [AuthController::class,'index'])->name('login');
Route::get('forgot_password', [AuthController::class,'forgot_password'])->name('forgot_password');

Route::post('proses_login', [AuthController::class,'proses_login'])->name('proses_login');
Route::post('proses_forgot_password', [AuthController::class,'proses_forgot_password'])->name('proses_forgot_password');

Route::get('logout', [AuthController::class,'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['middleware' => ['auth.check:ADMIN']], function () {
        //View
        Route::get('admin', [AdminDashboard::class,'index'])->name('admin');
        Route::get('data/guru', [DataGuruController::class,'index'])->name('data_guru');
        Route::get('data/pelajaran', [DataPelajaranController::class,'index'])->name('data_pelajaran');
        Route::get('data/siswa', [DataSiswaController::class,'index'])->name('data_siswa');
        Route::get('konfigurasi/ta', [KonfigurasiDataTA::class,'index'])->name('konfigurasi_ta');
        Route::get('konfigurasi/kelas', [KonfigurasiDataKelas::class,'index'])->name('konfigurasi_kelas');
        Route::get('absensi', [AbsensiController::class,'index'])->name('absensi');
        Route::get('rekapitulasi/kelas', [RekapitulasiKelas::class,'index'])->name('rekapitulasi_kelas');
        Route::get('rekapitulasi/siswa', [RekapitulasiSiswa::class,'index'])->name('rekapitulasi_siswa');
    });
    Route::group(['middleware' => ['auth.check:SISWA']], function () {
        Route::resource('siswa', SiswaDashboard::class);
    });
});

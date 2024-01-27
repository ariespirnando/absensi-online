<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


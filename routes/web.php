<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pcr', function () {
    return 'Selamat Datang di Website Kampus PCR!';
});

Route::get('/mahasiswa', function () {
    return 'Halo Mahasiswa';
})->name('mahasiswa.show');

Route::get('/nama/{param1}', function ($param1) {
    return 'Nama saya: ' . $param1;
});

Route::get('/nim/{param1?}', function ($param1 = '') {
    return 'NIM saya: ' . $param1;
});

Route::get('/mahasiswa/{param1}', [MahasiswaController::class, 'show']);

Route::get('/about', function () {
    return view('halaman-about');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/pegawai', [PegawaiController::class, 'index']);

Route::post('question/store', [QuestionController::class, 'store'])
    ->name('question.store');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('pelanggan', PelangganController::class);

Route::resource('user', UserController::class);

Route::delete('/user/{id}/delete-photo', [UserController::class, 'deletePhoto'])
    ->name('user.deletePhoto');

Route::delete('/pelanggan/{id}/foto', [PelangganController::class, 'destroyFoto'])
    ->name('pelanggan.foto.destroy');

Route::post('/pelanggan/{id}/file', [PelangganController::class, 'uploadFilePendukung'])
    ->name('pelanggan.file.upload');

// hapus file pendukung pelanggan
Route::delete('/pelanggan/{id}/file/{fileId}', [PelangganController::class, 'destroyFilePendukung'])
    ->name('pelanggan.file.destroy');

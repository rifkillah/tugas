<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
Route::get('/buku/{kode}', [BukuController::class, 'show'])->name('buku.show');

Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');

Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
Route::get('/peminjaman/baru', [PeminjamanController::class, 'create'])->name('peminjaman.create');
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::get('/peminjaman/{kode}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
Route::post('/peminjaman/{kode}/kembali', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

Route::get('/laporan-pengujian', [LaporanController::class, 'index'])->name('laporan');

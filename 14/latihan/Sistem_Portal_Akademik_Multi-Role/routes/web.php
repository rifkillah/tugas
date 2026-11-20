<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/register', [AuthController::class, 'tampilRegister']);
Route::post('/register', [AuthController::class, 'prosesRegister']);

Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/panel-admin', function () {
        return "<h1>Panel Ruang Server Admin</h1><p>Halo Admin! Kamu memiliki hak akses penuh di halaman ini.</p><p><small style='color:#666;'>WM: Rifkillah muliya al-faris</small></p><a href='/dashboard'>Kembali ke Dashboard</a>";
    })->middleware('role:admin');

    Route::get('/krs-mahasiswa', function () {
        return "<h1>Halaman Pengisian KRS Mahasiswa</h1><p>Silakan pilih mata kuliah sesuai peminatan kamu semester ini.</p><p><small style='color:#666;'>WM: Rifkillah muliya al-faris</small></p><a href='/dashboard'>Kembali ke Dashboard</a>";
    })->middleware('role:mahasiswa');
});

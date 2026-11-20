<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerpusAuthController;

Route::get('/', function () {
    return redirect('/perpus/login');
});

Route::prefix('perpus')->group(function () {
    // Autentikasi: Registrasi
    Route::get('/register', [PerpusAuthController::class, 'register']);
    Route::post('/register', [PerpusAuthController::class, 'simpanRegister']);

    // Autentikasi: Login
    Route::get('/login', [PerpusAuthController::class, 'login'])->name('login');
    Route::post('/login', [PerpusAuthController::class, 'cekLogin']);

    // Rute yang membutuhkan login (auth)
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [PerpusAuthController::class, 'dashboard']);
        Route::post('/logout', [PerpusAuthController::class, 'logout']);

        // Otorisasi: Khusus Pustakawan
        Route::get('/kelola-buku', [PerpusAuthController::class, 'kelolaBuku'])
            ->middleware('role:pustakawan');

        // Otorisasi: Khusus Anggota
        Route::get('/pinjam-buku', [PerpusAuthController::class, 'pinjamBuku'])
            ->middleware('role:anggota');
    });
});

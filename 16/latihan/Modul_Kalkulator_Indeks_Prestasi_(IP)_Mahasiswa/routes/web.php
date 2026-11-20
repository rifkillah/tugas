<?php

use App\Http\Controllers\NilaiAkademikController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Modul Kalkulator Indeks Prestasi (IP) Mahasiswa
|--------------------------------------------------------------------------
| Dibuat untuk latihan Pertemuan 16: Debugging, Error Handling, dan
| Automated Testing. Watermark: Rifkillah Muliya Al-Faris.
*/

Route::redirect('/', '/uji-nilai');

Route::prefix('uji-nilai')->name('nilai.')->group(function () {
    Route::get('/', [NilaiAkademikController::class, 'index'])->name('index');
    Route::get('/hitung/{nim}', [NilaiAkademikController::class, 'hitungIp'])->name('hitung');
});

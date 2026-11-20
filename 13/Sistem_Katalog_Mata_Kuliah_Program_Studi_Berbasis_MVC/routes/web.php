<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MataKuliahController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('matakuliah')->group(function () {
    Route::get('/', [MataKuliahController::class, 'index']);
    Route::get('/{kode}', [MataKuliahController::class, 'detail']);
});

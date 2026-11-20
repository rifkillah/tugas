<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuApiController;

// Endpoint RESTful API untuk Katalog Buku E-Perpustakaan Kampus
Route::apiResource('buku', BukuApiController::class);

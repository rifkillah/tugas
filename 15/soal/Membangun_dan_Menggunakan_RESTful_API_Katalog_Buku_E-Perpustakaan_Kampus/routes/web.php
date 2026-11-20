<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/perpus/katalog-api');
});

Route::get('/perpus/katalog-api', function () {
    return view('perpus_katalog_api');
});

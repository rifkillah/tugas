<?php

namespace App\Providers;

use App\Models\Pengaturan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Watermark global: dibaca dari tabel pengaturans (MySQL) dengan
        // fallback ke config agar halaman error tetap aman ditampilkan.
        View::composer('*', function ($view) {
            $view->with('watermark', Pengaturan::watermark());
        });
    }
}

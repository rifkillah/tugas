<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan watermark pemilik proyek pada data mahasiswa
     * sehingga ikut terlihat di phpMyAdmin.
     */
    public function up(): void
    {
        if (Schema::hasColumn('mahasiswas', 'watermark')) {
            return;
        }

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('watermark', 100)
                ->default('Rifkillah Muliya Al-Faris')
                ->after('prodi');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('mahasiswas', 'watermark')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->dropColumn('watermark');
            });
        }
    }
};

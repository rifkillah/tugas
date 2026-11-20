<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Watermark pemilik sistem pada metadata tabel MySQL.
     * Terlihat di phpMyAdmin melalui tab Operations / Structure.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        $komentar = 'Pustaka Cendekia - dikelola oleh Rifkillah Muliya Al-Faris';

        foreach (['buku', 'anggota', 'peminjaman', 'pengaturan'] as $tabel) {
            DB::statement("ALTER TABLE `{$tabel}` COMMENT = '{$komentar}'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return;
        }

        foreach (['buku', 'anggota', 'peminjaman', 'pengaturan'] as $tabel) {
            DB::statement("ALTER TABLE `{$tabel}` COMMENT = ''");
        }
    }
};

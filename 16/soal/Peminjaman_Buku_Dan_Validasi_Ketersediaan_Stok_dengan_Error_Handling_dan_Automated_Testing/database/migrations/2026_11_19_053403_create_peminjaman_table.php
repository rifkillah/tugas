<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman', 30)->unique();
            $table->foreignId('anggota_id')->constrained('anggota')->restrictOnDelete();
            $table->foreignId('buku_id')->constrained('buku')->restrictOnDelete();
            $table->unsignedTinyInteger('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_jatuh_tempo')->index();
            $table->date('tanggal_kembali')->nullable();
            $table->string('status', 15)->default('dipinjam')->index();
            $table->unsignedInteger('denda')->default(0);
            $table->string('catatan', 255)->nullable();
            $table->string('dicatat_oleh', 100)
                ->default('Rifkillah Muliya Al-Faris')
                ->comment('Watermark pemilik dan pengelola sistem');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};

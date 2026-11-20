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
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_anggota', 30)->unique();
            $table->string('nama', 120);
            $table->string('program_studi', 100);
            $table->string('angkatan', 4);
            $table->string('telepon', 25)->nullable();
            $table->string('status', 15)->default('aktif')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};

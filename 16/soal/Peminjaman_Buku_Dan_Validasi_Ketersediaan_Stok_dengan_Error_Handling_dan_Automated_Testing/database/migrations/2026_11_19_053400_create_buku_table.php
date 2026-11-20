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
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku', 20)->unique();
            $table->string('judul', 180);
            $table->string('penulis', 120);
            $table->string('penerbit', 120)->nullable();
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->string('kategori', 60)->index();
            $table->string('rak', 20);
            $table->unsignedSmallInteger('stok_total')->default(1);
            $table->unsignedSmallInteger('stok_tersedia')->default(1);
            $table->text('sinopsis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 100)->unique();
            $table->text('nilai');
            $table->timestamps();

            $table->comment('Watermark & identitas proyek: Rifkillah Muliya Al-Faris');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};

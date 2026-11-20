<?php

namespace App\Models;

use Database\Factories\PengaturanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Table('pengaturan')]
#[Fillable(['kunci', 'nilai', 'keterangan'])]
class Pengaturan extends Model
{
    /** @use HasFactory<PengaturanFactory> */
    use HasFactory;

    public static function nilai(string $kunci, ?string $bawaan = null): ?string
    {
        return static::query()->where('kunci', $kunci)->value('nilai') ?? $bawaan;
    }
}

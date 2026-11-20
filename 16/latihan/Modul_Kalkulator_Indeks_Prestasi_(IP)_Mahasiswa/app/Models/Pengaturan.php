<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Menyimpan identitas/watermark aplikasi di database MySQL
 * agar dapat dipantau langsung melalui phpMyAdmin.
 */
class Pengaturan extends Model
{
    protected $table = 'pengaturans';

    protected $fillable = [
        'kunci',
        'nilai',
    ];

    /**
     * Ambil satu nilai pengaturan dengan fallback yang aman
     * ketika database belum siap (misalnya saat migrasi awal).
     */
    public static function nilai(string $kunci, ?string $default = null): ?string
    {
        try {
            return static::query()->where('kunci', $kunci)->value('nilai') ?? $default;
        } catch (Throwable) {
            return $default;
        }
    }

    public static function watermark(): string
    {
        return static::nilai('watermark', (string) config('app.watermark'));
    }
}

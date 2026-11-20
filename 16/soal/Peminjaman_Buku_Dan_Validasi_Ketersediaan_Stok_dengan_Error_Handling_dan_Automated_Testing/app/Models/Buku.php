<?php

namespace App\Models;

use Database\Factories\BukuFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('buku')]
#[Fillable([
    'kode_buku',
    'judul',
    'penulis',
    'penerbit',
    'tahun',
    'kategori',
    'rak',
    'stok_total',
    'stok_tersedia',
    'sinopsis',
])]
class Buku extends Model
{
    /** @use HasFactory<BukuFactory> */
    use HasFactory;

    /**
     * @return HasMany<Peminjaman, $this>
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tahun' => 'integer',
            'stok_total' => 'integer',
            'stok_tersedia' => 'integer',
        ];
    }

    public function stokRendah(): bool
    {
        return $this->stok_tersedia <= 1;
    }

    public function persentaseTersedia(): int
    {
        if ($this->stok_total <= 0) {
            return 0;
        }

        return (int) round(($this->stok_tersedia / $this->stok_total) * 100);
    }
}

<?php

namespace App\Models;

use Database\Factories\AnggotaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('anggota')]
#[Fillable([
    'nomor_anggota',
    'nama',
    'program_studi',
    'angkatan',
    'telepon',
    'status',
])]
class Anggota extends Model
{
    /** @use HasFactory<AnggotaFactory> */
    use HasFactory;

    /**
     * @return HasMany<Peminjaman, $this>
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * @return HasMany<Peminjaman, $this>
     */
    public function peminjamanAktif(): HasMany
    {
        return $this->peminjaman()->where('status', 'dipinjam');
    }

    public function aktif(): bool
    {
        return $this->status === 'aktif';
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Database\Factories\PeminjamanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table('peminjaman')]
#[Fillable([
    'kode_peminjaman',
    'anggota_id',
    'buku_id',
    'jumlah',
    'tanggal_pinjam',
    'tanggal_jatuh_tempo',
    'tanggal_kembali',
    'status',
    'denda',
    'catatan',
    'dicatat_oleh',
])]
class Peminjaman extends Model
{
    /** @use HasFactory<PeminjamanFactory> */
    use HasFactory;

    public const DENDA_PER_HARI = 1000;

    public const STATUS_DIPINJAM = 'dipinjam';

    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    /**
     * @return BelongsTo<Anggota, $this>
     */
    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    /**
     * @return BelongsTo<Buku, $this>
     */
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'tanggal_pinjam' => 'date',
            'tanggal_jatuh_tempo' => 'date',
            'tanggal_kembali' => 'date',
            'denda' => 'integer',
        ];
    }

    /**
     * @param  Builder<Peminjaman>  $query
     * @return Builder<Peminjaman>
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DIPINJAM);
    }

    /**
     * @param  Builder<Peminjaman>  $query
     * @return Builder<Peminjaman>
     */
    public function scopeTerlambat(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_DIPINJAM)
            ->whereDate('tanggal_jatuh_tempo', '<', today());
    }

    public function belumDikembalikan(): bool
    {
        return $this->status === self::STATUS_DIPINJAM;
    }

    public function isTerlambat(): bool
    {
        return $this->belumDikembalikan()
            && $this->tanggal_jatuh_tempo->lt(today());
    }

    public function hariTerlambat(?CarbonInterface $acuan = null): int
    {
        $acuan = $acuan ? Carbon::parse($acuan) : now();

        if (! $this->belumDikembalikan() && $this->tanggal_kembali !== null) {
            $acuan = $this->tanggal_kembali;
        }

        $acuan = $acuan->copy()->startOfDay();
        $jatuhTempo = $this->tanggal_jatuh_tempo->copy()->startOfDay();

        if ($jatuhTempo->greaterThanOrEqualTo($acuan)) {
            return 0;
        }

        return (int) $jatuhTempo->diffInDays($acuan);
    }

    public function hitungDenda(?CarbonInterface $acuan = null): int
    {
        return $this->hariTerlambat($acuan) * self::DENDA_PER_HARI;
    }
}

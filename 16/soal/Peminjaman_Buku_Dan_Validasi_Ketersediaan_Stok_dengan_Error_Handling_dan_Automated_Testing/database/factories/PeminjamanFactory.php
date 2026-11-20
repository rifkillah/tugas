<?php

namespace Database\Factories;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalPinjam = today()->subDays(4);

        return [
            'kode_peminjaman' => 'PJM-'.$tanggalPinjam->format('Ymd').'-'.fake()->unique()->numerify('####'),
            'anggota_id' => Anggota::factory(),
            'buku_id' => Buku::factory(),
            'jumlah' => 1,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_jatuh_tempo' => $tanggalPinjam->copy()->addDays(14),
            'tanggal_kembali' => null,
            'status' => Peminjaman::STATUS_DIPINJAM,
            'denda' => 0,
            'catatan' => null,
            'dicatat_oleh' => config('app.owner'),
        ];
    }

    public function dikembalikan(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'tanggal_kembali' => today()->subDay(),
        ]);
    }
}

<?php

namespace Tests\Unit;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Tests\TestCase;

class PeminjamanDendaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-11-19 09:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_denda_dihitung_seribu_rupiah_per_hari(): void
    {
        $peminjaman = $this->buatPeminjaman([
            'tanggal_jatuh_tempo' => '2026-11-15',
            'status' => Peminjaman::STATUS_DIPINJAM,
        ]);

        $this->assertSame(4, $peminjaman->hariTerlambat());
        $this->assertSame(4000, $peminjaman->hitungDenda());
    }

    public function test_tidak_ada_denda_saat_kembali_tepat_waktu(): void
    {
        $peminjaman = $this->buatPeminjaman([
            'tanggal_jatuh_tempo' => '2026-11-19',
            'status' => Peminjaman::STATUS_DIPINJAM,
        ]);

        $this->assertFalse($peminjaman->isTerlambat());
        $this->assertSame(0, $peminjaman->hitungDenda());
    }

    public function test_peminjaman_aktif_belum_jatuh_tempo_belum_terlambat(): void
    {
        $peminjaman = $this->buatPeminjaman([
            'tanggal_jatuh_tempo' => '2026-11-25',
            'status' => Peminjaman::STATUS_DIPINJAM,
        ]);

        $this->assertFalse($peminjaman->isTerlambat());
        $this->assertSame(0, $peminjaman->hariTerlambat());
    }

    public function test_denda_memakai_tanggal_kembali_bukan_tanggal_hari_ini(): void
    {
        $peminjaman = $this->buatPeminjaman([
            'tanggal_jatuh_tempo' => '2026-11-10',
            'tanggal_kembali' => '2026-11-12',
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
        ]);

        $this->assertSame(2, $peminjaman->hariTerlambat());
        $this->assertSame(2000, $peminjaman->hitungDenda());
    }

    /**
     * @param  array<string, mixed>  $atribut
     */
    private function buatPeminjaman(array $atribut): Peminjaman
    {
        return new Peminjaman(array_merge([
            'kode_peminjaman' => 'PJM-UNIT-0001',
            'jumlah' => 1,
            'tanggal_pinjam' => '2026-11-01',
            'denda' => 0,
        ], $atribut));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeminjamanBukuTest extends TestCase
{
    use RefreshDatabase;

    private Buku $buku;

    private Anggota $anggota;

    protected function setUp(): void
    {
        parent::setUp();

        $this->buku = Buku::factory()->create([
            'kode_buku' => 'BK-TEST-01',
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'penerbit' => 'Lentera Dipantara',
            'tahun' => 2005,
            'kategori' => 'Sastra',
            'rak' => 'A-01',
            'stok_total' => 3,
            'stok_tersedia' => 3,
        ]);

        $this->anggota = Anggota::factory()->create([
            'nomor_anggota' => 'AGT-TEST-01',
            'nama' => 'Rifqi Maulana',
            'program_studi' => 'Teknik Informatika',
            'angkatan' => '2024',
            'status' => 'aktif',
        ]);
    }

    public function test_halaman_dasbor_dan_koleksi_buku_dapat_diakses(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Ringkasan layanan hari ini');

        $this->get('/buku')
            ->assertOk()
            ->assertSee('Koleksi buku perpustakaan')
            ->assertSee('Bumi Manusia');
    }

    public function test_peminjaman_normal_mencatat_transaksi_dan_mengurangi_stok(): void
    {
        $response = $this->post(route('peminjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 2,
            'durasi' => 14,
            'catatan' => 'Kartu anggota sudah diverifikasi.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('peminjaman', [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 2,
            'status' => Peminjaman::STATUS_DIPINJAM,
            'dicatat_oleh' => config('app.owner'),
        ]);

        $this->assertSame(1, $this->buku->fresh()->stok_tersedia);
        $this->assertDatabaseCount('peminjaman', 1);
    }

    public function test_eksemplar_terakhir_tetap_dapat_dipinjam(): void
    {
        $this->buku->update(['stok_tersedia' => 1]);

        $response = $this->post(route('peminjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
            'durasi' => 7,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sukses');
        $this->assertSame(0, $this->buku->fresh()->stok_tersedia);
        $this->assertDatabaseCount('peminjaman', 1);
    }

    public function test_peminjaman_ditolak_saat_stok_habis(): void
    {
        $this->buku->update(['stok_tersedia' => 0]);

        $response = $this->followingRedirects()->post(route('peminjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
            'durasi' => 14,
        ]);

        $response->assertOk();
        $response->assertSee('Peminjaman tidak dapat diproses');
        $response->assertSee('sedang habis');

        $this->assertDatabaseCount('peminjaman', 0);
        $this->assertSame(0, $this->buku->fresh()->stok_tersedia);
    }

    public function test_peminjaman_ditolak_saat_jumlah_melebihi_stok(): void
    {
        $this->buku->update(['stok_tersedia' => 1]);

        $response = $this->followingRedirects()->post(route('peminjaman.store'), [
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 2,
            'durasi' => 14,
        ]);

        $response->assertOk();
        $response->assertSee('Peminjaman tidak dapat diproses');
        $response->assertSee('tidak mencukupi');

        $this->assertDatabaseCount('peminjaman', 0);
        $this->assertSame(1, $this->buku->fresh()->stok_tersedia);
    }

    public function test_peminjaman_ditolak_untuk_anggota_nonaktif(): void
    {
        $anggotaNonaktif = Anggota::factory()->nonaktif()->create([
            'nomor_anggota' => 'AGT-TEST-02',
            'nama' => 'Dimas Arya',
        ]);

        $response = $this->followingRedirects()->post(route('peminjaman.store'), [
            'anggota_id' => $anggotaNonaktif->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
            'durasi' => 14,
        ]);

        $response->assertOk();
        $response->assertSee('Peminjaman tidak dapat diproses');
        $response->assertSee('nonaktif');

        $this->assertDatabaseCount('peminjaman', 0);
        $this->assertSame(3, $this->buku->fresh()->stok_tersedia);
    }

    public function test_kode_buku_palsu_menampilkan_halaman_404(): void
    {
        $this->get('/buku/BK-TIDAK-ADA')
            ->assertNotFound()
            ->assertSee('BK-TIDAK-ADA')
            ->assertSee('tidak terdaftar');
    }

    public function test_kode_peminjaman_palsu_menampilkan_halaman_404(): void
    {
        $this->get('/peminjaman/PJM-PALSU')
            ->assertNotFound()
            ->assertSee('PJM-PALSU')
            ->assertSee('tidak ditemukan');
    }

    public function test_pengembalian_menambah_stok_dan_menghitung_denda(): void
    {
        $this->buku->update(['stok_tersedia' => 2]);

        $peminjaman = Peminjaman::factory()->create([
            'kode_peminjaman' => 'PJM-TEST-0001',
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
            'tanggal_pinjam' => today()->subDays(10),
            'tanggal_jatuh_tempo' => today()->subDays(3),
            'status' => Peminjaman::STATUS_DIPINJAM,
        ]);

        $response = $this->post(route('peminjaman.kembalikan', $peminjaman->kode_peminjaman));

        $response->assertRedirect(route('peminjaman.show', $peminjaman->kode_peminjaman));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'denda' => 3000,
            'tanggal_kembali' => today()->toDateString(),
        ]);

        $this->assertSame(3, $this->buku->fresh()->stok_tersedia);
    }

    public function test_pengembalian_ganda_tidak_menambah_stok_dua_kali(): void
    {
        $peminjaman = Peminjaman::factory()->dikembalikan()->create([
            'kode_peminjaman' => 'PJM-TEST-0002',
            'anggota_id' => $this->anggota->id,
            'buku_id' => $this->buku->id,
            'jumlah' => 1,
            'status' => Peminjaman::STATUS_DIKEMBALIKAN,
            'denda' => 0,
        ]);

        $response = $this->followingRedirects()->post(route('peminjaman.kembalikan', $peminjaman->kode_peminjaman));

        $response->assertOk();
        $response->assertSee('sudah dikembalikan');

        $this->assertSame(3, $this->buku->fresh()->stok_tersedia);
        $this->assertSame(Peminjaman::STATUS_DIKEMBALIKAN, $peminjaman->fresh()->status);
    }
}

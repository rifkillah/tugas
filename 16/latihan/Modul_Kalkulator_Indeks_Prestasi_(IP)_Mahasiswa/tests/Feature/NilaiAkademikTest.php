<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiAkademikTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mengisi data mahasiswa & watermark ke database testing MySQL.
        $this->seed();
    }

    /**
     * Positive Test: halaman utama berhasil diakses dan menampilkan data utama.
     */
    public function test_halaman_utama_uji_nilai_dapat_diakses(): void
    {
        $response = $this->get('/uji-nilai');

        $response->assertStatus(200);
        $response->assertSee('Panel Simulasi Error Handling & Debugging');
        $response->assertSee('Rizky Pratama');
        $response->assertSee('2410114001');
        $response->assertSee('Rifkillah Muliya Al-Faris');
        $response->assertSee('MySQL');
    }

    /**
     * Positive Test: perhitungan IP mahasiswa normal harus akurat.
     */
    public function test_perhitungan_ip_mahasiswa_normal_akurat(): void
    {
        $response = $this->get('/uji-nilai/hitung/2410114001');

        $response->assertStatus(200);
        $response->assertViewIs('nilai_hasil');
        $response->assertViewHas('nim', '2410114001');
        $response->assertSee('3.80');
        $response->assertSee('24 SKS');
        $response->assertViewHas('predikat', 'Dengan Pujian');
        $response->assertDontSee('Peringatan Sistem');
    }

    /**
     * Exception Test: total SKS 0 tidak membuat aplikasi crash,
     * melainkan menampilkan pesan dari blok try-catch.
     */
    public function test_error_handling_saat_total_sks_bernilai_nol(): void
    {
        $response = $this->get('/uji-nilai/hitung/2410114003');

        $response->assertStatus(200);
        $response->assertViewIs('nilai_hasil');
        $response->assertSee('Total SKS bernilai 0');
        $response->assertSee('Peringatan Sistem');
        $response->assertViewHas('pesan_error');
    }

    /**
     * Negative Test: NIM yang tidak terdaftar harus menghasilkan status 404
     * dan menampilkan halaman error kustom.
     */
    public function test_nim_tidak_terdaftar_menampilkan_error_404(): void
    {
        $response = $this->get('/uji-nilai/hitung/99999999');

        $response->assertStatus(404);
        $response->assertSee('Oops! Data atau Halaman Tidak Ditemukan');
        $response->assertSee('99999999');
        $response->assertSee('Rifkillah Muliya Al-Faris');
    }

    /**
     * Memastikan watermark tersimpan di database MySQL (tabel pengaturans
     * dan kolom watermark pada tabel mahasiswas).
     */
    public function test_watermark_tersimpan_di_database_mysql(): void
    {
        $this->assertDatabaseHas('pengaturans', [
            'kunci' => 'watermark',
            'nilai' => 'Rifkillah Muliya Al-Faris',
        ]);

        $this->assertDatabaseHas('mahasiswas', [
            'nim' => '2410114001',
            'watermark' => 'Rifkillah Muliya Al-Faris',
        ]);
    }
}

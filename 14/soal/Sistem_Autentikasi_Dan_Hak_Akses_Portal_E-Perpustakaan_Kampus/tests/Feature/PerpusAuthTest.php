<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PerpusAuthTest extends TestCase
{
    public function test_halaman_register_bisa_diakses_dan_memiliki_watermark(): void
    {
        $response = $this->get('/perpus/register');
        $response->assertStatus(200);
        $response->assertSee('Rifkillah muliya al-faris');
        $response->assertSee('Registrasi Akun Baru');
    }

    public function test_halaman_login_bisa_diakses_dan_memiliki_watermark(): void
    {
        $response = $this->get('/perpus/login');
        $response->assertStatus(200);
        $response->assertSee('Rifkillah muliya al-faris');
        $response->assertSee('Login Portal Perpustakaan');
    }

    public function test_registrasi_berhasil_menyimpan_user_dengan_password_terhash(): void
    {
        $email = 'mahasiswa_baru_' . time() . '@kampus.ac.id';
        $response = $this->post('/perpus/register', [
            'name' => 'Mahasiswa Baru Rifkillah',
            'email' => $email,
            'password' => 'secret123',
            'role' => 'anggota',
        ]);

        $response->assertRedirect('/perpus/login');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'anggota',
        ]);

        $user = User::where('email', $email)->first();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_guest_tidak_bisa_mengakses_dashboard(): void
    {
        $response = $this->get('/perpus/dashboard');
        $response->assertRedirect('/perpus/login');
    }

    public function test_pustakawan_bisa_akses_dashboard_dan_kelola_buku_tetapi_ditolak_ke_pinjam_buku(): void
    {
        $pustakawan = User::where('role', 'pustakawan')->first();
        $this->assertNotNull($pustakawan);

        // Dashboard
        $responseDash = $this->actingAs($pustakawan)->get('/perpus/dashboard');
        $responseDash->assertStatus(200);
        $responseDash->assertSee('Pustakawan');

        // Kelola Buku -> 200
        $responseKelola = $this->actingAs($pustakawan)->get('/perpus/kelola-buku');
        $responseKelola->assertStatus(200);
        $responseKelola->assertSee('Panel Pengelolaan Koleksi Buku Perpustakaan');

        // Pinjam Buku -> 403 Forbidden
        $responsePinjam = $this->actingAs($pustakawan)->get('/perpus/pinjam-buku');
        $responsePinjam->assertStatus(403);
    }

    public function test_anggota_bisa_akses_dashboard_dan_pinjam_buku_tetapi_ditolak_ke_kelola_buku(): void
    {
        $anggota = User::where('role', 'anggota')->first();
        $this->assertNotNull($anggota);

        // Dashboard
        $responseDash = $this->actingAs($anggota)->get('/perpus/dashboard');
        $responseDash->assertStatus(200);
        $responseDash->assertSee('Anggota');

        // Pinjam Buku -> 200
        $responsePinjam = $this->actingAs($anggota)->get('/perpus/pinjam-buku');
        $responsePinjam->assertStatus(200);
        $responsePinjam->assertSee('Form Peminjaman Buku Mahasiswa');

        // Kelola Buku -> 403 Forbidden
        $responseKelola = $this->actingAs($anggota)->get('/perpus/kelola-buku');
        $responseKelola->assertStatus(403);
    }

    public function test_logout_berhasil_menghapus_sesi(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->post('/perpus/logout');
        $response->assertRedirect('/perpus/login');
        $this->assertGuest();
    }
}

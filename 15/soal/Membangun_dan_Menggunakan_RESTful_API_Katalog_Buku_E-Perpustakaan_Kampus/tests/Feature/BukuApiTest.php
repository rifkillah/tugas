<?php

namespace Tests\Feature;

use App\Models\Buku;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BukuApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_bukus_via_api()
    {
        $response = $this->getJson('/api/buku');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'pesan',
                     'jumlah_data',
                     'data' => [
                         '*' => ['id', 'kode_buku', 'judul_buku', 'penulis', 'stok']
                     ]
                 ]);
    }

    public function test_can_create_buku_via_api()
    {
        $payload = [
            'kode_buku' => 'BK-TEST-' . rand(1000, 9999),
            'judul_buku' => 'Buku Uji Coba Otomatis',
            'penulis' => 'Tester Laravel',
            'stok' => 5,
        ];

        $response = $this->postJson('/api/buku', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => true,
                     'data' => [
                         'kode_buku' => $payload['kode_buku'],
                         'judul_buku' => $payload['judul_buku'],
                         'penulis' => $payload['penulis'],
                         'stok' => $payload['stok'],
                     ]
                 ]);
    }

    public function test_validation_fails_on_duplicate_kode_buku()
    {
        $existing = Buku::factory()->create([
            'kode_buku' => 'BK-UNIQUE-01'
        ]);

        $payload = [
            'kode_buku' => 'BK-UNIQUE-01',
            'judul_buku' => 'Buku Duplikat',
            'penulis' => 'Penulis',
            'stok' => 3,
        ];

        $response = $this->postJson('/api/buku', $payload);

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => false,
                 ])
                 ->assertJsonValidationErrors(['kode_buku']);
    }

    public function test_can_show_single_buku()
    {
        $buku = Buku::factory()->create();

        $response = $this->getJson("/api/buku/{$buku->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'data' => [
                         'id' => $buku->id,
                         'kode_buku' => $buku->kode_buku,
                     ]
                 ]);
    }

    public function test_returns_404_for_nonexistent_buku()
    {
        $response = $this->getJson('/api/buku/999999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => false,
                 ]);
    }

    public function test_can_update_buku()
    {
        $buku = Buku::factory()->create([
            'kode_buku' => 'BK-UPDATE-' . rand(1000, 9999),
            'judul_buku' => 'Buku Sebelum Update',
            'penulis' => 'Penulis Lama',
            'stok' => 2,
        ]);

        $response = $this->putJson("/api/buku/{$buku->id}", [
            'kode_buku' => $buku->kode_buku,
            'judul_buku' => 'Buku Sesudah Diperbarui',
            'penulis' => 'Penulis Baru',
            'stok' => 10,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                     'data' => [
                         'judul_buku' => 'Buku Sesudah Diperbarui',
                         'penulis' => 'Penulis Baru',
                         'stok' => 10,
                     ]
                 ]);
    }

    public function test_can_delete_buku()
    {
        $buku = Buku::factory()->create([
            'kode_buku' => 'BK-DEL-' . rand(1000, 9999),
            'judul_buku' => 'Buku Untuk Dihapus',
            'penulis' => 'Penulis',
            'stok' => 1,
        ]);

        $response = $this->deleteJson("/api/buku/{$buku->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => true,
                 ]);

        $this->assertDatabaseMissing('bukus', ['id' => $buku->id]);
    }

    public function test_web_client_contains_watermark()
    {
        $response = $this->get('/perpus/katalog-api');

        $response->assertStatus(200)
                 ->assertSee('Rifkillah muliya al-faris')
                 ->assertSee('Portal Konsumen RESTful API Katalog Buku')
                 ->assertSee('/api/buku');
    }
}

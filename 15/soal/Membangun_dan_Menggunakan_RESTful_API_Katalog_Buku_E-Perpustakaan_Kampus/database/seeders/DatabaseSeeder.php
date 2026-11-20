<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Buku;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $books = [
            [
                'kode_buku' => 'BK-001',
                'judul_buku' => 'Pemrograman Web Modern dengan Laravel & RESTful API',
                'penulis' => 'Taylor Otwell',
                'stok' => 15,
            ],
            [
                'kode_buku' => 'BK-002',
                'judul_buku' => 'Clean Code: Panduan Praktis Arsitektur Piranti Lunak',
                'penulis' => 'Robert C. Martin',
                'stok' => 8,
            ],
            [
                'kode_buku' => 'BK-003',
                'judul_buku' => 'Database System Concepts & SQL Architecture',
                'penulis' => 'Abraham Silberschatz',
                'stok' => 12,
            ],
        ];

        foreach ($books as $b) {
            Buku::firstOrCreate(['kode_buku' => $b['kode_buku']], $b);
        }
    }
}

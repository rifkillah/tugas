<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Seed data contoh untuk simulasi perhitungan IP.
     */
    public function run(): void
    {
        $watermark = 'Rifkillah Muliya Al-Faris';

        $data = [
            [
                'nim' => '2410114001',
                'nama' => 'Rizky Pratama',
                'prodi' => 'Teknik Informatika',
                'total_mutu' => 76,
                'total_sks' => 20,
                'watermark' => $watermark,
            ],
            [
                'nim' => '2410114002',
                'nama' => 'Siti Nabila',
                'prodi' => 'Teknik Informatika',
                'total_mutu' => 78,
                'total_sks' => 20,
                'watermark' => $watermark,
            ],
            [
                'nim' => '2410114003',
                'nama' => 'Budi Hartono (Mahasiswa Baru)',
                'prodi' => 'Teknik Informatika',
                'total_mutu' => 0,
                'total_sks' => 0,
                'watermark' => $watermark,
            ],
        ];

        foreach ($data as $item) {
            Mahasiswa::updateOrCreate(
                ['nim' => $item['nim']],
                $item
            );
        }
    }
}

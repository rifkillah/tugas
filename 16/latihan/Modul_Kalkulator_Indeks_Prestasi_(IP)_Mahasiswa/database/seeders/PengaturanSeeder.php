<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'watermark' => 'Rifkillah Muliya Al-Faris',
            'pemilik_proyek' => 'Rifkillah Muliya Al-Faris',
            'nama_aplikasi' => 'Kalkulator Indeks Prestasi (IP) Mahasiswa',
            'mata_kuliah' => 'Pemrograman Web - Pertemuan 16',
            'topik' => 'Debugging, Error Handling, dan Automated Testing',
        ];

        foreach ($data as $kunci => $nilai) {
            Pengaturan::updateOrCreate(
                ['kunci' => $kunci],
                ['nilai' => $nilai]
            );
        }
    }
}

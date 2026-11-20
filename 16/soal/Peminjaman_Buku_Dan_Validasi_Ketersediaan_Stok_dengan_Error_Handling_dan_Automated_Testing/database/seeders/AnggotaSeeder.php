<?php

namespace Database\Seeders;

use App\Models\Anggota;
use Illuminate\Database\Seeder;

class AnggotaSeeder extends Seeder
{
    /**
     * Seed registered library members.
     */
    public function run(): void
    {
        $daftarAnggota = [
            [
                'nomor_anggota' => 'AGT-2024-001',
                'nama' => 'Rifqi Maulana',
                'program_studi' => 'Teknik Informatika',
                'angkatan' => '2024',
                'telepon' => '0812-4410-7781',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2023-014',
                'nama' => 'Nabila Putri',
                'program_studi' => 'Manajemen',
                'angkatan' => '2023',
                'telepon' => '0857-2214-9032',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2022-009',
                'nama' => 'Fajar Nugroho',
                'program_studi' => 'Akuntansi',
                'angkatan' => '2022',
                'telepon' => '0813-7780-1245',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2024-032',
                'nama' => 'Salsabila Rahma',
                'program_studi' => 'Pendidikan Bahasa Indonesia',
                'angkatan' => '2024',
                'telepon' => '0821-3345-8890',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2023-021',
                'nama' => 'Dimas Arya',
                'program_studi' => 'Teknik Sipil',
                'angkatan' => '2023',
                'telepon' => '0852-9910-2214',
                'status' => 'nonaktif',
            ],
            [
                'nomor_anggota' => 'AGT-2021-004',
                'nama' => 'Intan Permata',
                'program_studi' => 'Ilmu Komunikasi',
                'angkatan' => '2021',
                'telepon' => '0812-6672-4501',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2024-018',
                'nama' => 'Bagas Pratama',
                'program_studi' => 'Sistem Informasi',
                'angkatan' => '2024',
                'telepon' => '0881-2240-7765',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2022-027',
                'nama' => 'Ayu Lestari',
                'program_studi' => 'Pendidikan Guru Sekolah Dasar',
                'angkatan' => '2022',
                'telepon' => '0822-8871-3409',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2023-040',
                'nama' => 'Rizky Hidayat',
                'program_studi' => 'Teknik Mesin',
                'angkatan' => '2023',
                'telepon' => '0856-4412-0978',
                'status' => 'aktif',
            ],
            [
                'nomor_anggota' => 'AGT-2024-011',
                'nama' => 'Melati Kusuma',
                'program_studi' => 'Farmasi',
                'angkatan' => '2024',
                'telepon' => '0819-3302-8814',
                'status' => 'aktif',
            ],
        ];

        foreach ($daftarAnggota as $data) {
            Anggota::query()->updateOrCreate(
                ['nomor_anggota' => $data['nomor_anggota']],
                $data,
            );
        }
    }
}

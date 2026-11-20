<?php

namespace Database\Seeders;

use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    /**
     * Seed application settings, including the system watermark.
     */
    public function run(): void
    {
        $pengaturan = [
            [
                'kunci' => 'nama_aplikasi',
                'nilai' => 'Pustaka Cendekia',
                'keterangan' => 'Nama sistem layanan sirkulasi perpustakaan',
            ],
            [
                'kunci' => 'unit_layanan',
                'nilai' => 'Unit Sirkulasi Perpustakaan',
                'keterangan' => 'Unit pengelola peminjaman dan pengembalian',
            ],
            [
                'kunci' => 'pemilik_sistem',
                'nilai' => 'Rifkillah Muliya Al-Faris',
                'keterangan' => 'Pengembang dan penguji sistem',
            ],
            [
                'kunci' => 'watermark',
                'nilai' => 'Rifkillah Muliya Al-Faris',
                'keterangan' => 'Watermark pemilik yang ditampilkan di situs dan dicatat pada transaksi',
            ],
            [
                'kunci' => 'versi',
                'nilai' => '1.0 - Pertemuan 16',
                'keterangan' => 'Versi aplikasi dan pertemuan tugas',
            ],
            [
                'kunci' => 'denda_per_hari',
                'nilai' => '1000',
                'keterangan' => 'Denda keterlambatan per hari per eksemplar (rupiah)',
            ],
            [
                'kunci' => 'masa_pinjam_default',
                'nilai' => '14',
                'keterangan' => 'Lama peminjaman standar dalam hari',
            ],
            [
                'kunci' => 'jam_layanan',
                'nilai' => 'Senin-Jumat, 08.00-16.00 WIB',
                'keterangan' => 'Jam layanan loket sirkulasi',
            ],
        ];

        foreach ($pengaturan as $data) {
            Pengaturan::query()->updateOrCreate(
                ['kunci' => $data['kunci']],
                $data,
            );
        }
    }
}

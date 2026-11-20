<?php

namespace App\Models;

class MataKuliah
{
    // Data sumber mata kuliah disimpan di dalam Model
    private static $daftar_matkul = [
        [
            'kode' => 'IF201',
            'nama' => 'Pemrograman Web Lanjut',
            'sks' => 4,
            'semester' => 4,
            'kategori' => 'Wajib',
            'dosen' => 'Septa, S.Kom., M.Kom.',
            'deskripsi' => 'Mempelajari konsep arsitektur MVC, penggunaan framework Laravel, pembuatan RESTful API, serta integrasi basis data modern.'
        ],
        [
            'kode' => 'IF202',
            'nama' => 'Kecerdasan Buatan (Artificial Intelligence)',
            'sks' => 3,
            'semester' => 4,
            'kategori' => 'Wajib',
            'dosen' => 'Dr. Budi Santoso, M.Kom.',
            'deskripsi' => 'Mempelajari algoritma pencarian, representasi pengetahuan, machine learning dasar, dan pengolahan bahasa alami.'
        ],
        [
            'kode' => 'IF305',
            'nama' => 'Pengembangan Game & Teknologi Imersif (AR/VR)',
            'sks' => 3,
            'semester' => 6,
            'kategori' => 'Peminatan',
            'dosen' => 'Septa, S.Kom., M.Kom.',
            'deskripsi' => 'Merancang dan membangun aplikasi interaktif 2D/3D serta simulasi Virtual Reality dan Augmented Reality menggunakan Game Engine.'
        ],
        [
            'kode' => 'IF308',
            'nama' => 'Keamanan Jaringan & Etika Profesi IT',
            'sks' => 2,
            'semester' => 6,
            'kategori' => 'Wajib',
            'dosen' => 'Rina Wijaya, S.Kom., M.T.',
            'deskripsi' => 'Menganalisis celah keamanan sistem jaringan komputer, kriptografi, serta standar kode etik profesional di bidang teknologi informasi.'
        ]
    ];

    // Method 1: Mengambil seluruh data mata kuliah
    public static function semuaData()
    {
        return self::$daftar_matkul;
    }

    // Method 2: Mencari satu mata kuliah berdasarkan Kode (misal: IF201)
    public static function cariKode($kode)
    {
        foreach (self::$daftar_matkul as $mk) {
            // Menggunakan strtoupper agar pencarian tidak peka huruf besar/kecil (case-insensitive)
            if (strtoupper($mk['kode']) === strtoupper($kode)) {
                return $mk;
            }
        }
        return null;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// PENTING: Panggil Model MataKuliah agar bisa digunakan di dalam Controller ini
use App\Models\MataKuliah;

class MataKuliahController extends Controller
{
    // Fungsi untuk halaman utama daftar mata kuliah (/matakuliah)
    public function index()
    {
        // 1. Meminta seluruh data dari Model MataKuliah
        $matkul = MataKuliah::semuaData();

        // 2. Menghitung total SKS dari seluruh mata kuliah di Controller
        $total_sks = 0;
        foreach ($matkul as $item) {
            $total_sks += $item['sks'];
        }

        // 3. Mengirimkan data ke View 'matakuliah_index.blade.php'
        return view('matakuliah_index', [
            'judul_halaman' => 'Katalog Mata Kuliah Teknik Informatika',
            'data_matkul' => $matkul,
            'total_sks' => $total_sks
        ]);
    }

    // Fungsi untuk halaman detail satu mata kuliah (/matakuliah/{kode})
    public function detail($kode)
    {
        // 1. Meminta Model mencari data berdasarkan kode yang dikirim dari URL
        $matkul_pilihan = MataKuliah::cariKode($kode);

        // 2. Jika kode mata kuliah tidak ditemukan, tampilkan halaman error 404
        if (!$matkul_pilihan) {
            abort(404, 'Data Mata Kuliah dengan kode tersebut tidak ditemukan.');
        }

        // 3. Jika ditemukan, kirim data ke View 'matakuliah_detail.blade.php'
        return view('matakuliah_detail', [
            'mk' => $matkul_pilihan
        ]);
    }
}

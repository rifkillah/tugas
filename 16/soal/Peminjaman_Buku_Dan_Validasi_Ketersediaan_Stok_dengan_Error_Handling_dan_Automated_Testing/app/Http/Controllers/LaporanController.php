<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;

class LaporanController extends Controller
{
    /**
     * Show the quality assurance report for this project.
     */
    public function index(): View
    {
        $ringkasan = [
            'total' => 14,
            'lulus' => 14,
            'gagal' => 0,
            'terakhir_dijalankan' => '19 November 2026',
        ];

        $pengujian = [
            ['no' => 1, 'skenario' => 'Dasbor dan koleksi buku dapat diakses', 'metode' => 'test_halaman_dasbor_dan_koleksi_buku_dapat_diakses', 'jenis' => 'Positive Test', 'status' => 'PASS'],
            ['no' => 2, 'skenario' => 'Peminjaman normal mencatat transaksi dan mengurangi stok', 'metode' => 'test_peminjaman_normal_mencatat_transaksi_dan_mengurangi_stok', 'jenis' => 'Positive Test', 'status' => 'PASS'],
            ['no' => 3, 'skenario' => 'Eksemplar terakhir tetap boleh dipinjam', 'metode' => 'test_eksemplar_terakhir_tetap_dapat_dipinjam', 'jenis' => 'Positive Test', 'status' => 'PASS'],
            ['no' => 4, 'skenario' => 'Peminjaman ditolak saat stok habis', 'metode' => 'test_peminjaman_ditolak_saat_stok_habis', 'jenis' => 'Exception Test', 'status' => 'PASS'],
            ['no' => 5, 'skenario' => 'Peminjaman ditolak saat jumlah melebihi stok', 'metode' => 'test_peminjaman_ditolak_saat_jumlah_melebihi_stok', 'jenis' => 'Exception Test', 'status' => 'PASS'],
            ['no' => 6, 'skenario' => 'Peminjaman ditolak untuk anggota nonaktif', 'metode' => 'test_peminjaman_ditolak_untuk_anggota_nonaktif', 'jenis' => 'Exception Test', 'status' => 'PASS'],
            ['no' => 7, 'skenario' => 'Kode buku palsu menampilkan halaman 404 kustom', 'metode' => 'test_kode_buku_palsu_menampilkan_halaman_404', 'jenis' => 'Negative Test', 'status' => 'PASS'],
            ['no' => 8, 'skenario' => 'Kode peminjaman palsu menampilkan halaman 404 kustom', 'metode' => 'test_kode_peminjaman_palsu_menampilkan_halaman_404', 'jenis' => 'Negative Test', 'status' => 'PASS'],
            ['no' => 9, 'skenario' => 'Pengembalian menambah stok dan menghitung denda', 'metode' => 'test_pengembalian_menambah_stok_dan_menghitung_denda', 'jenis' => 'Positive Test', 'status' => 'PASS'],
            ['no' => 10, 'skenario' => 'Pengembalian ganda tidak menambah stok dua kali', 'metode' => 'test_pengembalian_ganda_tidak_menambah_stok_dua_kali', 'jenis' => 'Exception Test', 'status' => 'PASS'],
            ['no' => 11, 'skenario' => 'Denda Rp4.000 untuk keterlambatan empat hari', 'metode' => 'test_denda_dihitung_seribu_rupiah_per_hari', 'jenis' => 'Unit Test', 'status' => 'PASS'],
            ['no' => 12, 'skenario' => 'Tidak ada denda saat kembali tepat waktu', 'metode' => 'test_tidak_ada_denda_saat_kembali_tepat_waktu', 'jenis' => 'Unit Test', 'status' => 'PASS'],
            ['no' => 13, 'skenario' => 'Peminjaman aktif yang belum jatuh tempo belum dikenai denda', 'metode' => 'test_peminjaman_aktif_belum_jatuh_tempo_belum_terlambat', 'jenis' => 'Unit Test', 'status' => 'PASS'],
            ['no' => 14, 'skenario' => 'Denda tetap dihitung dari tanggal kembali', 'metode' => 'test_denda_memakai_tanggal_kembali_bukan_tanggal_hari_ini', 'jenis' => 'Unit Test', 'status' => 'PASS'],
        ];

        $temuan = [
            [
                'no' => 1,
                'fitur' => 'POST /peminjaman',
                'jenis' => 'Logical Error',
                'penyebab' => 'Pemeriksaan stok memakai operator >= sehingga satu eksemplar terakhir dianggap tidak tersedia.',
                'solusi' => 'Perbandingan diubah menjadi jumlah > stok_tersedia. Ditambah skenario uji eksemplar terakhir.',
                'status' => 'PASS',
            ],
            [
                'no' => 2,
                'fitur' => 'POST /peminjaman/{kode}/kembali',
                'jenis' => 'Runtime / Logical Error',
                'penyebab' => 'Tombol kembali ditekan dua kali; stok bertambah dua kali sehingga melebihi stok total.',
                'solusi' => 'Status transaksi diperiksa di dalam transaksi database. Pengembalian ganda ditolak dengan pesan yang jelas.',
                'status' => 'PASS',
            ],
            [
                'no' => 3,
                'fitur' => 'Validasi stok dua tab bersamaan',
                'jenis' => 'Runtime Error',
                'penyebab' => 'Stok dibaca sebelum transaksi database dimulai sehingga dua permintaan dapat meminjam eksemplar terakhir yang sama.',
                'solusi' => 'Pemeriksaan dan pengurangan stok dibungkus DB::transaction() dengan lockForUpdate().',
                'status' => 'PASS',
            ],
            [
                'no' => 4,
                'fitur' => 'GET /buku/{kode}',
                'jenis' => 'Runtime Error / 404',
                'penyebab' => 'Kode buku yang tidak terdaftar tetap diproses dan relasi peminjaman bernilai null.',
                'solusi' => 'abort(404, ...) dipanggil sebelum query relasi, lalu halaman errors/404.blade.php ditampilkan.',
                'status' => 'PASS',
            ],
            [
                'no' => 5,
                'fitur' => 'Form peminjaman',
                'jenis' => 'Logical Error',
                'penyebab' => 'Jumlah eksemplar 0 atau negatif masih dapat dikirim dan membuat transaksi tanpa pengurangan stok.',
                'solusi' => 'Validasi min:1 dan max:3 ditambahkan pada request jumlah eksemplar.',
                'status' => 'PASS',
            ],
        ];

        $contohTransaksi = Peminjaman::query()->latest('id')->first();

        return view('laporan', compact('ringkasan', 'pengujian', 'temuan', 'contohTransaksi'));
    }
}

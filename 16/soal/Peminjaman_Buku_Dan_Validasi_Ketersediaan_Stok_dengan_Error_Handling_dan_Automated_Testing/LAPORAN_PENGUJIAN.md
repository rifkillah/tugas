# Laporan Pengujian dan Debugging

**Nama:** Rifkillah Muliya Al-Faris  
**Materi:** Pertemuan 16 - Teknik Pengujian dan Debugging pada Aplikasi Web  
**Proyek:** Pustaka Cendekia - Sistem Peminjaman Buku dan Validasi Ketersediaan Stok  
**Basis data:** MySQL `db_pustaka_cendekia`  
**Perintah uji:** `php artisan test`  
**Hasil akhir:** 14 skenario PASS, 52 assertion

## 1. Ringkasan

Sistem sirkulasi perpustakaan diuji pada tiga kelompok skenario:

1. **Positive test:** halaman utama, katalog, peminjaman normal, eksemplar terakhir, dan pengembalian.
2. **Exception test:** stok habis, jumlah melebihi stok, anggota nonaktif, dan pengembalian ganda.
3. **Negative test:** kode buku dan kode peminjaman yang tidak terdaftar harus mengembalikan status 404.

Pengujian otomatis menggunakan MySQL terpisah, yaitu `db_pustaka_cendekia_test`, agar data pada `db_pustaka_cendekia` tidak terhapus saat `RefreshDatabase` dijalankan.

## 2. Tabel Hasil Temuan dan Perbaikan

| No | Nama Fitur / URL | Jenis Error / Skenario Uji | Penyebab Masalah (Hasil Debugging) | Solusi Error Handling / Perbaikan Kode | Status Hasil Uji |
| --- | --- | --- | --- | --- | --- |
| 1 | `POST /peminjaman` | Logical Error | Pemeriksaan stok memakai operator `>=` sehingga peminjaman eksemplar terakhir ditolak. | Operator diubah menjadi `jumlah > stok_tersedia`; ditambahkan test eksemplar terakhir. | PASS |
| 2 | `POST /peminjaman/{kode}/kembali` | Runtime / Logical Error | Tombol pengembalian ditekan dua kali membuat stok bertambah dua kali dan melebihi stok total. | Status transaksi diperiksa di dalam transaksi database; pengembalian ganda ditolak dengan pesan. | PASS |
| 3 | Validasi stok dua tab | Runtime Error (race condition) | Stok dibaca sebelum transaksi dimulai sehingga dua permintaan dapat meminjam eksemplar terakhir yang sama. | Pemeriksaan dan pengurangan stok dibungkus `DB::transaction()` dengan `lockForUpdate()`. | PASS |
| 4 | `GET /buku/{kode}` | Runtime Error / 404 | Kode buku yang tidak terdaftar tetap diproses dan relasi peminjaman bernilai null. | `abort(404, ...)` dipanggil lebih dahulu, lalu halaman `errors/404.blade.php` ditampilkan. | PASS |
| 5 | Form peminjaman | Logical Error | Jumlah eksemplar 0 atau negatif masih dapat dikirim dan membuat transaksi tanpa pengurangan stok. | Validasi `min:1` dan `max:3` ditambahkan pada request jumlah eksemplar. | PASS |

Bug ditemukan melalui kombinasi pengujian manual, pembacaan `storage/logs/laravel.log`, pemeriksaan data dengan `dd()` melalui `?debug=1`, dan pengujian otomatis.

## 3. Tabel Skenario Automated Testing

| No | Metode Pengujian | Jenis | Status |
| --- | --- | --- | --- |
| 1 | `test_halaman_dasbor_dan_koleksi_buku_dapat_diakses` | Positive | PASS |
| 2 | `test_peminjaman_normal_mencatat_transaksi_dan_mengurangi_stok` | Positive | PASS |
| 3 | `test_eksemplar_terakhir_tetap_dapat_dipinjam` | Positive | PASS |
| 4 | `test_peminjaman_ditolak_saat_stok_habis` | Exception | PASS |
| 5 | `test_peminjaman_ditolak_saat_jumlah_melebihi_stok` | Exception | PASS |
| 6 | `test_peminjaman_ditolak_untuk_anggota_nonaktif` | Exception | PASS |
| 7 | `test_kode_buku_palsu_menampilkan_halaman_404` | Negative | PASS |
| 8 | `test_kode_peminjaman_palsu_menampilkan_halaman_404` | Negative | PASS |
| 9 | `test_pengembalian_menambah_stok_dan_menghitung_denda` | Positive | PASS |
| 10 | `test_pengembalian_ganda_tidak_menambah_stok_dua_kali` | Exception | PASS |
| 11 | `test_denda_dihitung_seribu_rupiah_per_hari` | Unit | PASS |
| 12 | `test_tidak_ada_denda_saat_kembali_tepat_waktu` | Unit | PASS |
| 13 | `test_peminjaman_aktif_belum_jatuh_tempo_belum_terlambat` | Unit | PASS |
| 14 | `test_denda_memakai_tanggal_kembali_bukan_tanggal_hari_ini` | Unit | PASS |

## 4. Bukti Menjalankan Pengujian

```text
$ php artisan test
{"tool":"phpunit","result":"passed","tests":14,"passed":14,"assertions":52}
```

## 5. Implementasi Error Handling

### a. Blok try-catch dan Log::error

`PeminjamanController::store()` memeriksa stok di dalam transaksi database. Kondisi tidak wajar dilempar sebagai `\Exception`, lalu ditangkap dan dicatat:

```php
if ($buku->stok_tersedia <= 0) {
    throw new \Exception("Stok buku \"{$buku->judul}\" sedang habis...");
}

if ($jumlah > $buku->stok_tersedia) {
    throw new \Exception("Stok tidak mencukupi...");
}
```

```php
catch (\Throwable $exception) {
    Log::error('Gagal mencatat peminjaman: '.$exception->getMessage(), [
        'anggota_id' => $data['anggota_id'],
        'buku_id' => $data['buku_id'],
        'jumlah' => $data['jumlah'],
    ]);

    return back()->withInput()->with('gagal', $pesan);
}
```

### b. abort(404) dan halaman kustom

Kode buku dan kode peminjaman yang tidak terdaftar dihentikan dengan `abort(404, ...)`. Tampilan kustom berada di `resources/views/errors/404.blade.php` dan menampilkan pesan spesifik dari controller.

### c. Halaman error lain

Tersedia halaman `403.blade.php`, `419.blade.php`, dan `500.blade.php` dengan tema yang sama.

## 6. Praktik Debugging

- `dd()` dapat diakses pada halaman detail peminjaman dengan menambahkan `?debug=1` ketika `APP_DEBUG=true`.
- Log kegagalan transaksi tersimpan di `storage/logs/laravel.log` melalui `Log::error()`.
- Log keberhasilan transaksi dan percobaan akses data palsu dicatat melalui `Log::info()` dan `Log::warning()`.

## 7. Bukti Watermark dan MySQL

### a. Website

Nama **Rifkillah Muliya Al-Faris** tampil pada:

- Footer setiap halaman: "Dikembangkan dan diuji oleh Rifkillah Muliya Al-Faris".
- Chip operator pada bilah atas.
- Kartu penanggung jawab sistem pada dasbor dan form peminjaman.
- Halaman laporan pengujian.

### b. Basis data

Watermark tersimpan di tabel `pengaturan`:

```sql
SELECT kunci, nilai FROM pengaturan WHERE kunci IN ('pemilik_sistem', 'watermark');
```

Hasil:

```text
pemilik_sistem  Rifkillah Muliya Al-Faris
watermark       Rifkillah Muliya Al-Faris
```

Watermark juga tercatat pada kolom `peminjaman.dicatat_oleh` untuk setiap transaksi dan pada komentar tabel MySQL, sehingga terlihat di phpMyAdmin.

### c. phpMyAdmin

Basis data `db_pustaka_cendekia` berisi tabel `buku`, `anggota`, `peminjaman`, dan `pengaturan`. Data contoh mencakup 14 judul buku, 10 anggota, serta 9 transaksi peminjaman (6 aktif dan 3 selesai).

## 8. Cara Mengulang Pengujian

```bash
# Siapkan basis data utama
php artisan migrate:fresh --seed

# Jalankan seluruh pengujian otomatis
php artisan test
```

Pastikan `db_pustaka_cendekia_test` sudah dibuat di MySQL. Konfigurasi pengujian berada di `phpunit.xml`.

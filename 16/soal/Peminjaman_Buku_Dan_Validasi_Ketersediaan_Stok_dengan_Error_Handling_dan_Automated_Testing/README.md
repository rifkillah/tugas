# Pustaka Cendekia

Sistem layanan sirkulasi perpustakaan untuk tugas **Pertemuan 16: Teknik Pengujian dan Debugging pada Aplikasi Web**. Proyek ini menangani pencatatan peminjaman buku, validasi ketersediaan stok, pengembalian, denda keterlambatan, error handling, halaman 404 kustom, dan automated testing.

- **Pemilik / penguji:** Rifkillah Muliya Al-Faris
- **Studi kasus:** Peminjaman buku dan validasi ketersediaan stok
- **Framework:** Laravel 13, PHP 8.5
- **Basis data:** MySQL (`db_pustaka_cendekia`)
- **Antarmuka:** Blade + CSS lokal, tanpa build tool frontend

## Fitur

- Dasbor ringkasan koleksi, stok tersedia, transaksi aktif, dan keterlambatan.
- Katalog buku dengan pencarian, filter kategori, dan filter ketersediaan.
- Detail buku beserta daftar eksemplar yang sedang dipinjam.
- Formulir peminjaman dengan validasi stok, anggota aktif, jumlah eksemplar, dan masa pinjam.
- Pengembalian buku dengan perhitungan denda otomatis Rp1.000 per hari per eksemplar.
- Daftar anggota dan jumlah pinjaman aktif.
- Halaman error kustom: 404, 403, 419, dan 500.
- Laporan pengujian dan temuan debugging di `/laporan-pengujian`.

## Persyaratan

- PHP 8.3 atau lebih baru (proyek ini diuji pada PHP 8.5).
- Composer.
- MySQL 8 (Laragon menyediakan MySQL dan phpMyAdmin).
- Node.js tidak diperlukan untuk menjalankan antarmuka karena CSS disimpan di `public/css/app.css`.

## Instalasi

1. Pasang dependensi PHP.

   ```bash
   composer install
   ```

2. Siapkan berkas lingkungan.

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Buat dua basis data melalui phpMyAdmin atau terminal MySQL.

   ```sql
   CREATE DATABASE db_pustaka_cendekia
       CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

   CREATE DATABASE db_pustaka_cendekia_test
       CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

4. Sesuaikan bagian berikut di `.env`.

   ```dotenv
   APP_NAME="Pustaka Cendekia"
   APP_OWNER="Rifkillah Muliya Al-Faris"

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_pustaka_cendekia
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Jalankan migrasi dan isi data contoh.

   ```bash
   php artisan migrate:fresh --seed
   ```

6. Jalankan server lokal.

   ```bash
   php artisan serve
   ```

7. Buka `http://127.0.0.1:8000`. Jika port 8000 sedang dipakai proyek lain, jalankan `php artisan serve --port=8080` lalu buka `http://127.0.0.1:8080`.

## Melihat Data di phpMyAdmin

Buka `http://localhost/phpmyadmin`, lalu pilih basis data **`db_pustaka_cendekia`**. Tabel yang tersedia:

| Tabel | Isi |
| --- | --- |
| `buku` | Koleksi dan jumlah stok |
| `anggota` | Data pemustaka |
| `peminjaman` | Transaksi pinjam dan kembali |
| `pengaturan` | Pengaturan aplikasi dan watermark |

Watermark **Rifkillah Muliya Al-Faris** dapat diperiksa pada:

1. Tabel `pengaturan`, kunci `watermark` dan `pemilik_sistem`.
2. Kolom `dicatat_oleh` pada setiap baris `peminjaman`.
3. Komentar tabel MySQL (tab **Operations** di phpMyAdmin).

## Automated Testing

Basis data pengujian sudah diatur di `phpunit.xml` menggunakan `db_pustaka_cendekia_test` agar data utama tidak terhapus.

```bash
php artisan test
```

Hasil terakhir:

```text
tests: 14 passed (52 assertions)
```

Skenario mencakup halaman normal, peminjaman berhasil, eksemplar terakhir, stok habis, jumlah melebihi stok, anggota nonaktif, kode buku palsu, kode peminjaman palsu, pengembalian berdenda, pengembalian ganda, serta empat unit test perhitungan denda.

## Error Handling dan Debugging

- `PeminjamanController::store()` membungkus validasi stok di dalam `DB::transaction()` dan `try-catch`.
- Kondisi stok habis, jumlah berlebih, dan anggota nonaktif dilempar sebagai exception dengan pesan yang ramah.
- Kegagalan dicatat melalui `Log::error()` ke `storage/logs/laravel.log`.
- `abort(404, ...)` dipakai untuk kode buku atau kode peminjaman yang tidak terdaftar.
- Mode debug `dd()` dapat dibuka melalui `?debug=1` pada halaman detail peminjaman saat `APP_DEBUG=true`.
- Halaman error kustom berada di `resources/views/errors/`.

## Rute Utama

| Metode | URL | Keterangan |
| --- | --- | --- |
| GET | `/` | Dasbor sirkulasi |
| GET | `/buku` | Katalog buku |
| GET | `/buku/{kode}` | Detail buku |
| GET | `/anggota` | Daftar anggota |
| GET | `/peminjaman` | Daftar transaksi |
| GET | `/peminjaman/baru` | Formulir peminjaman |
| POST | `/peminjaman` | Simpan peminjaman |
| GET | `/peminjaman/{kode}` | Detail transaksi |
| POST | `/peminjaman/{kode}/kembali` | Catat pengembalian |
| GET | `/laporan-pengujian` | Laporan QA |

## Struktur Penting

```text
app/Http/Controllers/PeminjamanController.php   logika pinjam, stok, denda
app/Models/Peminjaman.php                       relasi dan perhitungan denda
database/migrations/                            skema MySQL
database/seeders/                               data contoh perpustakaan
resources/views/errors/                         halaman error kustom
tests/Feature/PeminjamanBukuTest.php            10 feature test
tests/Unit/PeminjamanDendaTest.php               4 unit test
LAPORAN_PENGUJIAN.md                            laporan hasil temuan
```

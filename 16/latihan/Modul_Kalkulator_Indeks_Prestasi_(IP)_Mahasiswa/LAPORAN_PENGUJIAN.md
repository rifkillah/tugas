# Laporan Simulasi Debugging, Error Handling, dan Automated Testing

**Proyek:** Modul Kalkulator Indeks Prestasi (IP) Mahasiswa
**Watermark / Pemilik Proyek:** Rifkillah Muliya Al-Faris
**Mata Kuliah:** Pemrograman Web — Pertemuan 16
**Framework:** Laravel 13
**Database:** MySQL (`db_kalkulator_ip`) — dapat dipantau melalui phpMyAdmin

---

## 1. Cara Menjalankan Aplikasi

```bash
# dari folder proyek
php artisan migrate --seed
php artisan serve
```

Buka:

- Panel pengujian: <http://127.0.0.1:8000/uji-nilai>
- Perhitungan normal: <http://127.0.0.1:8000/uji-nilai/hitung/2410114001>
- Skenario exception: <http://127.0.0.1:8000/uji-nilai/hitung/2410114003>
- Skenario 404: <http://127.0.0.1:8000/uji-nilai/hitung/99999999>
- Debug `dd()`: <http://127.0.0.1:8000/uji-nilai/hitung/2410114001?debug=1>

Menjalankan seluruh pengujian otomatis:

```bash
php artisan test
```

Hasil terakhir: **13 tests passed, 43 assertions**.

> Tampilan halaman mengikuti kode Latihan pada modul Pertemuan 16
> (`nilai_index.blade.php`, `nilai_hasil.blade.php`, dan `errors/404.blade.php`),
> dengan tambahan watermark serta keterangan sumber data MySQL.

---

## 2. Akses Database melalui phpMyAdmin

1. Jalankan MySQL dari Laragon.
2. Buka <http://localhost/phpmyadmin>.
3. Pilih database **`db_kalkulator_ip`**.
4. Tabel yang dapat diperiksa:

| Tabel | Isi |
|---|---|
| `mahasiswas` | Data NIM, nama, prodi, total mutu, total SKS, dan kolom `watermark` |
| `pengaturans` | Watermark dan identitas proyek, termasuk baris `watermark = Rifkillah Muliya Al-Faris` |

Query untuk membuktikan watermark tersimpan di database:

```sql
SELECT nim, nama, watermark FROM mahasiswas;
SELECT kunci, nilai FROM pengaturans WHERE kunci = 'watermark';
```

---

## 3. Fitur Error Handling yang Diterapkan

| Komponen | Implementasi |
|---|---|
| Validasi pembagian nol | `throw new InvalidArgumentException(...)` di `KalkulatorIpService` saat `total_sks <= 0` |
| Validasi nilai tidak wajar | Menolak total mutu/SKS negatif dan IP di atas 4.00 |
| Penanganan error | Blok `try-catch (\Throwable $e)` di `NilaiAkademikController` |
| Pencatatan log | `Log::info()`, `Log::warning()`, dan `Log::error()` ke `storage/logs/laravel.log` |
| Data tidak ditemukan | `abort(404, 'Mahasiswa dengan NIM ... tidak ditemukan ...')` |
| Halaman error kustom | `resources/views/errors/404.blade.php` dan `500.blade.php` |
| Fallback database | Data modul digunakan sementara bila MySQL tidak tersedia, disertai log error |
| Fitur debugging | Parameter `?debug=1` menjalankan `dd()` untuk memeriksa data mentah |

---

## 4. Hasil Temuan Debugging dan Perbaikan Bug

| No | Nama Fitur / URL | Jenis Error / Skenario Uji | Penyebab Masalah (Hasil Debugging) | Solusi Error Handling / Perbaikan Kode | Status |
|---|---|---|---|---|---|
| 1 | `/uji-nilai/hitung/2410114001` | Logical Error — rumus dan kuota SKS | Perhitungan langsung di Controller tanpa validasi, sehingga rumus sulit diuji dan rawan salah ambang batas | Rumus dipindahkan ke `KalkulatorIpService`, kuota SKS `>= 3.00 ? 24 : 20`, ditambah predikat | PASS |
| 2 | `/uji-nilai/hitung/2410114003` | Runtime Error — pembagian dengan nol | `total_sks = 0` untuk mahasiswa baru menyebabkan `DivisionByZeroError` | `throw new InvalidArgumentException`, ditangkap `try-catch`, dicatat `Log::error()`, pengguna melihat pesan ramah | PASS |
| 3 | `/uji-nilai/hitung/99999999` | Negative Test — data tidak ditemukan (404) | NIM palsu diakses tanpa pemeriksaan keberadaan data | `abort(404, ...)` + halaman `errors/404.blade.php` kustom | PASS |
| 4 | `/uji-nilai/hitung/{nim}?debug=1` | Debugging — inspeksi variabel | Nilai variabel tidak terlihat saat alur perhitungan bermasalah | `dd()` menampilkan NIM, sumber data, dan data mentah sebelum dihitung | PASS (manual) |
| 5 | Perhitungan IP di atas 4.00 / nilai negatif | Logical Error — validasi data | Data ekstrem tetap diproses sehingga hasil IP tidak wajar | Validasi nilai negatif dan batas maksimal IP 4.00 di service | PASS |
| 6 | `pengaturans` & `mahasiswas` | Verifikasi watermark database | Watermark hanya tampil di halaman, belum tersimpan di database | Kolom `watermark` pada `mahasiswas` + tabel `pengaturans` yang di-seed | PASS |

---

## 5. Ringkasan Automated Testing

| File Pengujian | Skenario | Hasil |
|---|---|---|
| `tests/Feature/NilaiAkademikTest.php` | Halaman utama dapat diakses dan menampilkan data utama | PASS |
| `tests/Feature/NilaiAkademikTest.php` | Perhitungan normal 76 ÷ 20 = 3.80 dan kuota 24 SKS | PASS |
| `tests/Feature/NilaiAkademikTest.php` | Total SKS 0 ditangani tanpa crash | PASS |
| `tests/Feature/NilaiAkademikTest.php` | NIM tidak terdaftar menghasilkan status 404 | PASS |
| `tests/Feature/NilaiAkademikTest.php` | Watermark tersimpan di database MySQL | PASS |
| `tests/Unit/KalkulatorIpServiceTest.php` | Rumus IP, ambang kuota, predikat, dan validasi nilai ekstrem | PASS |
| `tests/Feature/ExampleTest.php` | Root URL diarahkan ke `/uji-nilai` | PASS |

---

## 6. Catatan Teknis

- Konfigurasi database aplikasi: `.env` → `DB_CONNECTION=mysql`, `DB_DATABASE=db_kalkulator_ip`.
- Konfigurasi database pengujian: `phpunit.xml` → `DB_DATABASE=db_kalkulator_ip_test`.
- Watermark website dibaca dari tabel `pengaturans` melalui `Pengaturan::watermark()` dan ditampilkan sebagai lapisan diagonal serta footer pada setiap halaman.
- Watermark juga tersimpan per baris pada kolom `mahasiswas.watermark` agar langsung terlihat di phpMyAdmin.

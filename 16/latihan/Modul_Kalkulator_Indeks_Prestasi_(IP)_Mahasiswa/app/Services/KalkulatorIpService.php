<?php

namespace App\Services;

use InvalidArgumentException;

/**
 * Service perhitungan Indeks Prestasi (IP) Semester.
 *
 * Logika rumus dipisahkan dari Controller agar mudah diuji secara
 * terisolasi melalui Unit Test (tests/Unit/KalkulatorIpServiceTest.php).
 */
class KalkulatorIpService
{
    /**
     * Hitung IP Semester beserta kuota SKS semester depan.
     *
     * @return array{ip: float, ip_format: string, kuota_sks: int, predikat: string}
     *
     * @throws InvalidArgumentException
     */
    public function hitung(float $totalMutu, int $totalSks): array
    {
        // BUG #1 (Runtime Error) yang diperbaiki:
        // Pembagian dengan nol terjadi saat mahasiswa baru belum memiliki SKS.
        if ($totalSks <= 0) {
            throw new InvalidArgumentException(
                'Total SKS bernilai 0. Tidak dapat menghitung IP Semester karena belum ada mata kuliah yang diambil.'
            );
        }

        // BUG #2 (Logical Error) yang diperbaiki:
        // Nilai mutu/SKS negatif tidak boleh dianggap sebagai nilai sah.
        if ($totalMutu < 0 || $totalSks < 0) {
            throw new InvalidArgumentException(
                'Total mutu dan total SKS tidak boleh bernilai negatif.'
            );
        }

        $ip = $totalMutu / $totalSks;

        // Batas maksimal 4.00 untuk mengantisipasi data mutu yang tidak wajar.
        if ($ip > 4.00) {
            throw new InvalidArgumentException(
                'Hasil perhitungan IP melebihi 4.00. Periksa kembali total mutu dan total SKS.'
            );
        }

        return [
            'ip' => $ip,
            'ip_format' => number_format($ip, 2),
            'kuota_sks' => $ip >= 3.00 ? 24 : 20,
            'predikat' => $this->predikat($ip),
        ];
    }

    private function predikat(float $ip): string
    {
        return match (true) {
            $ip >= 3.50 => 'Dengan Pujian',
            $ip >= 3.00 => 'Sangat Memuaskan',
            $ip >= 2.50 => 'Memuaskan',
            $ip >= 2.00 => 'Cukup',
            default => 'Perlu Perbaikan',
        };
    }
}

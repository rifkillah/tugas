<?php

namespace Tests\Unit;

use App\Services\KalkulatorIpService;
use InvalidArgumentException;
use Tests\TestCase;

class KalkulatorIpServiceTest extends TestCase
{
    private KalkulatorIpService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new KalkulatorIpService();
    }

    public function test_menghitung_ip_normal_dengan_benar(): void
    {
        $hasil = $this->service->hitung(76, 20);

        $this->assertSame(3.8, $hasil['ip']);
        $this->assertSame('3.80', $hasil['ip_format']);
        $this->assertSame(24, $hasil['kuota_sks']);
        $this->assertSame('Dengan Pujian', $hasil['predikat']);
    }

    public function test_ip_tepat_tiga_mendapat_kuota_24_sks(): void
    {
        $hasil = $this->service->hitung(60, 20);

        $this->assertSame('3.00', $hasil['ip_format']);
        $this->assertSame(24, $hasil['kuota_sks']);
        $this->assertSame('Sangat Memuaskan', $hasil['predikat']);
    }

    public function test_ip_di_bawah_tiga_mendapat_kuota_20_sks(): void
    {
        $hasil = $this->service->hitung(50, 20);

        $this->assertSame('2.50', $hasil['ip_format']);
        $this->assertSame(20, $hasil['kuota_sks']);
        $this->assertSame('Memuaskan', $hasil['predikat']);
    }

    public function test_total_sks_nol_melempar_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Total SKS bernilai 0');

        $this->service->hitung(0, 0);
    }

    public function test_nilai_negatif_melempar_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('tidak boleh bernilai negatif');

        $this->service->hitung(-10, 20);
    }

    public function test_ip_melebihi_empat_melempar_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('melebihi 4.00');

        $this->service->hitung(100, 20);
    }
}

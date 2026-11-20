<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Services\KalkulatorIpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class NilaiAkademikController extends Controller
{
    /**
     * Data cadangan (fallback) dari modul praktikum.
     * Data utama tetap dibaca dari database MySQL (tabel mahasiswas).
     */
    private array $data_mahasiswa = [
        '2410114001' => [
            'nama' => 'Rizky Pratama',
            'prodi' => 'Teknik Informatika',
            'total_mutu' => 76,
            'total_sks' => 20,
        ],
        '2410114002' => [
            'nama' => 'Siti Nabila',
            'prodi' => 'Teknik Informatika',
            'total_mutu' => 78,
            'total_sks' => 20,
        ],
        '2410114003' => [
            'nama' => 'Budi Hartono (Mahasiswa Baru)',
            'prodi' => 'Teknik Informatika',
            'total_mutu' => 0,
            'total_sks' => 0,
        ],
    ];

    public function __construct(private readonly KalkulatorIpService $kalkulatorIp)
    {
    }

    /**
     * Halaman panel simulasi error handling & debugging.
     */
    public function index()
    {
        $sumberData = 'MySQL (tabel mahasiswas)';

        try {
            $records = Mahasiswa::query()->orderBy('nim')->get();

            if ($records->isNotEmpty()) {
                $mahasiswa = $records->mapWithKeys(fn (Mahasiswa $item) => [
                    $item->nim => [
                        'nama' => $item->nama,
                        'prodi' => $item->prodi,
                        'total_mutu' => (float) $item->total_mutu,
                        'total_sks' => (int) $item->total_sks,
                        'watermark' => $item->watermark,
                    ],
                ])->all();
            } else {
                $mahasiswa = $this->data_mahasiswa;
                $sumberData = 'Data fallback modul (tabel masih kosong)';
            }
        } catch (Throwable $e) {
            Log::error('Gagal membaca data mahasiswa dari MySQL: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'baris' => $e->getLine(),
            ]);

            $mahasiswa = $this->data_mahasiswa;
            $sumberData = 'Data fallback modul (database tidak tersedia)';
        }

        return view('nilai_index', [
            'mahasiswa' => $mahasiswa,
            'sumberData' => $sumberData,
        ]);
    }

    /**
     * Menghitung IP Semester untuk NIM tertentu.
     */
    public function hitungIp(Request $request, string $nim)
    {
        $mhs = null;
        $sumberData = 'MySQL (tabel mahasiswas)';

        try {
            $record = Mahasiswa::query()->where('nim', $nim)->first();

            if ($record !== null) {
                $mhs = [
                    'nama' => $record->nama,
                    'prodi' => $record->prodi,
                    'total_mutu' => (float) $record->total_mutu,
                    'total_sks' => (int) $record->total_sks,
                    'watermark' => $record->watermark,
                ];
            }
        } catch (Throwable $e) {
            Log::error("Gagal mengambil data NIM {$nim} dari MySQL: ".$e->getMessage(), [
                'file' => $e->getFile(),
                'baris' => $e->getLine(),
            ]);
        }

        // Fallback data modul bila database tidak tersedia.
        if ($mhs === null && isset($this->data_mahasiswa[$nim])) {
            $mhs = $this->data_mahasiswa[$nim];
            $sumberData = 'Data fallback modul';
        }

        // Skenario Negative Test: NIM tidak terdaftar -> halaman 404 kustom.
        if ($mhs === null) {
            Log::warning('Percobaan akses NIM tidak terdaftar: '.$nim);

            abort(404, "Mahasiswa dengan NIM {$nim} tidak ditemukan di database akademik.");
        }

        // Fitur debugging: buka /uji-nilai/hitung/{nim}?debug=1
        if ($request->query('debug') === '1') {
            dd([
                'INFO_DEBUG' => 'Memeriksa isi data mentah sebelum dihitung',
                'nim' => $nim,
                'sumber_data' => $sumberData,
                'data_mahasiswa' => $mhs,
            ]);
        }

        try {
            $hasil = $this->kalkulatorIp->hitung(
                (float) $mhs['total_mutu'],
                (int) $mhs['total_sks']
            );

            Log::info("Berhasil menghitung IP untuk NIM: {$nim}", [
                'ip' => $hasil['ip'],
                'kuota_sks' => $hasil['kuota_sks'],
            ]);

            return view('nilai_hasil', [
                'nim' => $nim,
                'mhs' => $mhs,
                'ip' => $hasil['ip_format'],
                'kuota_sks' => $hasil['kuota_sks'],
                'predikat' => $hasil['predikat'],
                'sumberData' => $sumberData,
                'pesan_error' => null,
            ]);
        } catch (Throwable $e) {
            // Exception Test: aplikasi tidak crash, error dicatat, dan
            // pengguna menerima pesan yang ramah.
            Log::error("Error Perhitungan IP pada NIM {$nim}: ".$e->getMessage(), [
                'file' => $e->getFile(),
                'baris' => $e->getLine(),
            ]);

            return view('nilai_hasil', [
                'nim' => $nim,
                'mhs' => $mhs,
                'ip' => '0.00',
                'kuota_sks' => 0,
                'predikat' => '-',
                'sumberData' => $sumberData,
                'pesan_error' => $e->getMessage(),
            ]);
        }
    }
}

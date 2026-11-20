<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeminjamanSeeder extends Seeder
{
    /**
     * Seed circulation records and adjust the available stock.
     */
    public function run(): void
    {
        if (Peminjaman::query()->exists()) {
            return;
        }

        $dicatatOleh = Pengaturan::nilai('watermark', (string) config('app.owner'));

        $sedangDipinjam = [
            ['AGT-2024-001', 'BK-001', 1, 14, 14],
            ['AGT-2021-004', 'BK-001', 1, 17, 14],
            ['AGT-2023-014', 'BK-003', 1, 7, 14],
            ['AGT-2024-018', 'BK-005', 1, 11, 14],
            ['AGT-2022-027', 'BK-013', 1, 9, 14],
            ['AGT-2022-009', 'BK-006', 1, 4, 14],
        ];

        $sudahDikembalikan = [
            ['AGT-2024-001', 'BK-010', 1, 30, 14, 18],
            ['AGT-2024-032', 'BK-011', 1, 38, 14, 20],
            ['AGT-2024-011', 'BK-004', 1, 50, 14, 32],
        ];

        DB::transaction(function () use ($dicatatOleh, $sedangDipinjam, $sudahDikembalikan): void {
            $nomor = 1;

            foreach ($sedangDipinjam as [$nomorAnggota, $kodeBuku, $jumlah, $lewatHari, $durasi]) {
                $anggota = Anggota::query()->where('nomor_anggota', $nomorAnggota)->firstOrFail();
                $buku = Buku::query()->where('kode_buku', $kodeBuku)->firstOrFail();
                $tanggalPinjam = today()->subDays($lewatHari);
                $jatuhTempo = $tanggalPinjam->copy()->addDays($durasi);

                Peminjaman::query()->create([
                    'kode_peminjaman' => $this->kodePeminjaman($tanggalPinjam, $nomor++),
                    'anggota_id' => $anggota->id,
                    'buku_id' => $buku->id,
                    'jumlah' => $jumlah,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'tanggal_kembali' => null,
                    'status' => Peminjaman::STATUS_DIPINJAM,
                    'denda' => 0,
                    'catatan' => null,
                    'dicatat_oleh' => $dicatatOleh,
                ]);

                $buku->update([
                    'stok_tersedia' => max(0, $buku->stok_tersedia - $jumlah),
                ]);
            }

            foreach ($sudahDikembalikan as [$nomorAnggota, $kodeBuku, $jumlah, $lewatHari, $durasi, $kembaliHari]) {
                $anggota = Anggota::query()->where('nomor_anggota', $nomorAnggota)->firstOrFail();
                $buku = Buku::query()->where('kode_buku', $kodeBuku)->firstOrFail();
                $tanggalPinjam = today()->subDays($lewatHari);
                $jatuhTempo = $tanggalPinjam->copy()->addDays($durasi);
                $tanggalKembali = today()->subDays($kembaliHari);
                $hariTerlambat = $jatuhTempo->greaterThan($tanggalKembali)
                    ? 0
                    : (int) $jatuhTempo->diffInDays($tanggalKembali);

                Peminjaman::query()->create([
                    'kode_peminjaman' => $this->kodePeminjaman($tanggalPinjam, $nomor++),
                    'anggota_id' => $anggota->id,
                    'buku_id' => $buku->id,
                    'jumlah' => $jumlah,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'tanggal_kembali' => $tanggalKembali,
                    'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                    'denda' => $hariTerlambat * Peminjaman::DENDA_PER_HARI,
                    'catatan' => $hariTerlambat > 0 ? 'Denda dibayar di loket.' : null,
                    'dicatat_oleh' => $dicatatOleh,
                ]);
            }
        });
    }

    private function kodePeminjaman(Carbon $tanggal, int $nomor): string
    {
        return 'PJM-'.$tanggal->format('Ymd').'-'.str_pad((string) $nomor, 4, '0', STR_PAD_LEFT);
    }
}

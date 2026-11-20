<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengaturan;
use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class PeminjamanController extends Controller
{
    /**
     * Show circulation records with filters and summary numbers.
     */
    public function index(Request $request): View
    {
        $kataKunci = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $peminjaman = Peminjaman::query()
            ->with(['anggota', 'buku'])
            ->when($kataKunci !== '', function (Builder $query) use ($kataKunci): void {
                $query->where(function (Builder $sub) use ($kataKunci): void {
                    $sub->where('kode_peminjaman', 'like', "%{$kataKunci}%")
                        ->orWhereHas('anggota', function (Builder $anggota) use ($kataKunci): void {
                            $anggota->where('nama', 'like', "%{$kataKunci}%")
                                ->orWhere('nomor_anggota', 'like', "%{$kataKunci}%");
                        })
                        ->orWhereHas('buku', function (Builder $buku) use ($kataKunci): void {
                            $buku->where('judul', 'like', "%{$kataKunci}%")
                                ->orWhere('kode_buku', 'like', "%{$kataKunci}%");
                        });
                });
            })
            ->when($status === 'dipinjam', fn (Builder $query) => $query->aktif())
            ->when($status === 'terlambat', fn (Builder $query) => $query->terlambat())
            ->when($status === 'dikembalikan', fn (Builder $query) => $query->where('status', Peminjaman::STATUS_DIKEMBALIKAN))
            ->orderByDesc('status')
            ->orderByDesc('tanggal_pinjam')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $statistik = [
            'aktif' => Peminjaman::query()->aktif()->count(),
            'terlambat' => Peminjaman::query()->terlambat()->count(),
            'selesai' => Peminjaman::query()->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->count(),
            'denda' => (int) Peminjaman::query()->where('status', Peminjaman::STATUS_DIKEMBALIKAN)->sum('denda'),
        ];

        return view('peminjaman.index', compact(
            'peminjaman',
            'statistik',
            'kataKunci',
            'status',
        ));
    }

    /**
     * Show the borrowing form.
     */
    public function create(Request $request): View
    {
        $daftarBuku = Buku::query()->orderBy('judul')->get();
        $daftarAnggota = Anggota::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $bukuTerpilih = null;
        $kodeBuku = trim((string) $request->query('buku', ''));

        if ($kodeBuku !== '') {
            $bukuTerpilih = Buku::query()->where('kode_buku', $kodeBuku)->first();

            if (! $bukuTerpilih) {
                Log::warning('Form peminjaman dibuka dengan kode buku yang tidak terdaftar.', [
                    'kode_buku' => $kodeBuku,
                    'ip' => $request->ip(),
                ]);

                abort(404, "Buku dengan kode {$kodeBuku} tidak terdaftar pada koleksi Pustaka Cendekia.");
            }
        }

        return view('peminjaman.create', compact(
            'daftarBuku',
            'daftarAnggota',
            'bukuTerpilih',
        ));
    }

    /**
     * Validate stock and record a new loan.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'anggota_id' => ['required', 'integer', 'exists:anggota,id'],
            'buku_id' => ['required', 'integer', 'exists:buku,id'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:3'],
            'durasi' => ['required', 'integer', 'in:7,14,21'],
            'catatan' => ['nullable', 'string', 'max:200'],
        ], [
            'anggota_id.required' => 'Anggota peminjam wajib dipilih.',
            'anggota_id.exists' => 'Anggota yang dipilih tidak terdaftar.',
            'buku_id.required' => 'Buku yang akan dipinjam wajib dipilih.',
            'buku_id.exists' => 'Buku yang dipilih tidak terdaftar.',
            'jumlah.required' => 'Jumlah eksemplar wajib diisi.',
            'jumlah.min' => 'Jumlah eksemplar minimal 1.',
            'jumlah.max' => 'Maksimal 3 eksemplar untuk satu transaksi.',
            'durasi.in' => 'Durasi peminjaman harus 7, 14, atau 21 hari.',
        ]);

        try {
            $peminjaman = DB::transaction(function () use ($data): Peminjaman {
                $anggota = Anggota::query()->lockForUpdate()->find($data['anggota_id']);
                $buku = Buku::query()->lockForUpdate()->find($data['buku_id']);

                if (! $anggota) {
                    abort(404, 'Anggota yang dipilih tidak ditemukan pada basis data.');
                }

                if (! $buku) {
                    abort(404, 'Buku yang dipilih tidak ditemukan pada basis data.');
                }

                if (! $anggota->aktif()) {
                    throw new \Exception("Anggota {$anggota->nama} berstatus nonaktif dan belum dapat meminjam buku.");
                }

                if ($buku->stok_tersedia <= 0) {
                    throw new \Exception("Stok buku \"{$buku->judul}\" sedang habis. Silakan pilih judul lain atau tunggu hingga buku dikembalikan.");
                }

                $jumlah = (int) $data['jumlah'];

                if ($jumlah > $buku->stok_tersedia) {
                    throw new \Exception("Stok tidak mencukupi. \"{$buku->judul}\" hanya tersedia {$buku->stok_tersedia} eksemplar.");
                }

                $tanggalPinjam = today();
                $tanggalJatuhTempo = today()->addDays((int) $data['durasi']);

                $peminjaman = Peminjaman::query()->create([
                    'kode_peminjaman' => $this->buatKodePeminjaman($tanggalPinjam),
                    'anggota_id' => $anggota->id,
                    'buku_id' => $buku->id,
                    'jumlah' => $jumlah,
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                    'status' => Peminjaman::STATUS_DIPINJAM,
                    'denda' => 0,
                    'catatan' => $data['catatan'] ?? null,
                    'dicatat_oleh' => Pengaturan::nilai('watermark', (string) config('app.owner')),
                ]);

                $buku->update([
                    'stok_tersedia' => $buku->stok_tersedia - $jumlah,
                ]);

                return $peminjaman;
            });
        } catch (HttpExceptionInterface $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Gagal mencatat peminjaman: '.$exception->getMessage(), [
                'anggota_id' => $data['anggota_id'],
                'buku_id' => $data['buku_id'],
                'jumlah' => $data['jumlah'],
                'jenis' => $exception::class,
                'petugas' => config('app.owner'),
            ]);

            $pesan = $exception::class === \Exception::class
                ? $exception->getMessage()
                : 'Peminjaman gagal diproses karena kendala teknis. Silakan hubungi petugas sirkulasi.';

            return back()->withInput()->with('gagal', $pesan);
        }

        Log::info('Peminjaman berhasil dicatat.', [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'anggota_id' => $peminjaman->anggota_id,
            'buku_id' => $peminjaman->buku_id,
            'jumlah' => $peminjaman->jumlah,
            'petugas' => $peminjaman->dicatat_oleh,
        ]);

        return redirect()
            ->route('peminjaman.show', $peminjaman->kode_peminjaman)
            ->with('sukses', "Peminjaman {$peminjaman->kode_peminjaman} tercatat. Buku wajib dikembalikan paling lambat {$peminjaman->tanggal_jatuh_tempo->format('d M Y')}.");
    }

    /**
     * Show one loan receipt. Add ?debug=1 in local mode to inspect the raw data with dd().
     */
    public function show(Request $request, string $kode): View
    {
        $peminjaman = Peminjaman::query()
            ->with(['anggota', 'buku'])
            ->where('kode_peminjaman', $kode)
            ->first();

        if (! $peminjaman) {
            Log::warning('Percobaan membuka transaksi peminjaman yang tidak terdaftar.', [
                'kode_peminjaman' => $kode,
                'ip' => $request->ip(),
            ]);

            abort(404, "Transaksi peminjaman dengan kode {$kode} tidak ditemukan.");
        }

        if ($request->boolean('debug') && config('app.debug')) {
            dd([
                'INFO_DEBUG' => 'Pemeriksaan data peminjaman sebelum ditampilkan',
                'kode_peminjaman' => $kode,
                'data_peminjaman' => $peminjaman->toArray(),
                'hari_terlambat' => $peminjaman->hariTerlambat(),
                'denda_berjalan' => $peminjaman->hitungDenda(),
            ]);
        }

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Receive a returned book and calculate the late fee.
     */
    public function kembalikan(string $kode): RedirectResponse
    {
        try {
            $peminjaman = DB::transaction(function () use ($kode): Peminjaman {
                $peminjaman = Peminjaman::query()
                    ->where('kode_peminjaman', $kode)
                    ->lockForUpdate()
                    ->first();

                if (! $peminjaman) {
                    abort(404, "Transaksi peminjaman dengan kode {$kode} tidak ditemukan.");
                }

                if (! $peminjaman->belumDikembalikan()) {
                    throw new \Exception("Peminjaman {$peminjaman->kode_peminjaman} sudah dikembalikan pada {$peminjaman->tanggal_kembali->format('d M Y')}.");
                }

                $buku = Buku::query()->lockForUpdate()->find($peminjaman->buku_id);

                if (! $buku) {
                    abort(404, 'Buku pada transaksi ini tidak ditemukan pada basis data.');
                }

                $tanggalKembali = today();
                $denda = $peminjaman->hitungDenda($tanggalKembali);

                $peminjaman->update([
                    'status' => Peminjaman::STATUS_DIKEMBALIKAN,
                    'tanggal_kembali' => $tanggalKembali,
                    'denda' => $denda,
                ]);

                $buku->update([
                    'stok_tersedia' => min($buku->stok_total, $buku->stok_tersedia + $peminjaman->jumlah),
                ]);

                return $peminjaman->load(['anggota', 'buku']);
            });
        } catch (HttpExceptionInterface $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Gagal memproses pengembalian: '.$exception->getMessage(), [
                'kode_peminjaman' => $kode,
                'jenis' => $exception::class,
                'petugas' => config('app.owner'),
            ]);

            $pesan = $exception::class === \Exception::class
                ? $exception->getMessage()
                : 'Pengembalian gagal diproses karena kendala teknis. Silakan hubungi petugas sirkulasi.';

            return back()->with('gagal', $pesan);
        }

        Log::info('Buku diterima kembali.', [
            'kode_peminjaman' => $peminjaman->kode_peminjaman,
            'denda' => $peminjaman->denda,
            'petugas' => config('app.owner'),
        ]);

        $pesan = "Buku \"{$peminjaman->buku->judul}\" telah diterima kembali.";

        if ($peminjaman->denda > 0) {
            $pesan .= ' Denda keterlambatan: Rp'.number_format($peminjaman->denda, 0, ',', '.').'.';
        }

        return redirect()
            ->route('peminjaman.show', $peminjaman->kode_peminjaman)
            ->with('sukses', $pesan);
    }

    private function buatKodePeminjaman(CarbonInterface $tanggal): string
    {
        $prefix = 'PJM-'.$tanggal->format('Ymd').'-';
        $kodeTerakhir = Peminjaman::query()
            ->where('kode_peminjaman', 'like', $prefix.'%')
            ->orderByDesc('kode_peminjaman')
            ->value('kode_peminjaman');

        $urutan = $kodeTerakhir ? ((int) substr((string) $kodeTerakhir, -4)) + 1 : 1;

        return $prefix.str_pad((string) $urutan, 4, '0', STR_PAD_LEFT);
    }
}

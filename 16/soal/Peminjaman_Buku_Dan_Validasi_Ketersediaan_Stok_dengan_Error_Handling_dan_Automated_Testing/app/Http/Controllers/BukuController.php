<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BukuController extends Controller
{
    /**
     * Show the book collection with search and filters.
     */
    public function index(Request $request): View
    {
        $kataKunci = trim((string) $request->query('q', ''));
        $kategori = trim((string) $request->query('kategori', ''));
        $status = (string) $request->query('status', '');

        $buku = Buku::query()
            ->when($kataKunci !== '', function (Builder $query) use ($kataKunci): void {
                $query->where(function (Builder $sub) use ($kataKunci): void {
                    $sub->where('judul', 'like', "%{$kataKunci}%")
                        ->orWhere('penulis', 'like', "%{$kataKunci}%")
                        ->orWhere('kode_buku', 'like', "%{$kataKunci}%");
                });
            })
            ->when($kategori !== '', fn (Builder $query) => $query->where('kategori', $kategori))
            ->when($status === 'tersedia', fn (Builder $query) => $query->where('stok_tersedia', '>', 0))
            ->when($status === 'habis', fn (Builder $query) => $query->where('stok_tersedia', 0))
            ->orderBy('judul')
            ->paginate(8)
            ->withQueryString();

        $daftarKategori = Buku::query()
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('buku.index', compact(
            'buku',
            'daftarKategori',
            'kataKunci',
            'kategori',
            'status',
        ));
    }

    /**
     * Show one book and its active loans.
     */
    public function show(string $kode): View
    {
        $buku = Buku::query()->where('kode_buku', $kode)->first();

        if (! $buku) {
            Log::warning('Percobaan membuka buku yang tidak terdaftar.', [
                'kode_buku' => $kode,
                'ip' => request()->ip(),
            ]);

            abort(404, "Buku dengan kode {$kode} tidak terdaftar pada koleksi Pustaka Cendekia.");
        }

        $peminjamanAktif = $buku->peminjaman()
            ->with('anggota')
            ->where('status', 'dipinjam')
            ->orderBy('tanggal_jatuh_tempo')
            ->get();

        return view('buku.show', compact('buku', 'peminjamanAktif'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Show the circulation dashboard.
     */
    public function index(): View
    {
        $ringkasan = [
            'judul' => Buku::query()->count(),
            'eksemplar' => (int) Buku::query()->sum('stok_total'),
            'tersedia' => (int) Buku::query()->sum('stok_tersedia'),
            'dipinjam' => Peminjaman::query()->aktif()->count(),
            'terlambat' => Peminjaman::query()->terlambat()->count(),
        ];

        $peminjamanTerbaru = Peminjaman::query()
            ->with(['anggota', 'buku'])
            ->orderByDesc('tanggal_pinjam')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $stokMenipis = Buku::query()
            ->whereColumn('stok_tersedia', '<=', 'stok_total')
            ->where('stok_tersedia', '<=', 1)
            ->orderBy('stok_tersedia')
            ->orderBy('judul')
            ->limit(5)
            ->get();

        $jatuhTempoTerdekat = Peminjaman::query()
            ->aktif()
            ->with(['anggota', 'buku'])
            ->orderBy('tanggal_jatuh_tempo')
            ->limit(4)
            ->get();

        return view('dashboard', compact(
            'ringkasan',
            'peminjamanTerbaru',
            'stokMenipis',
            'jatuhTempoTerdekat',
        ));
    }
}

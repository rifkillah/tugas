<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Show registered members and their active loan counts.
     */
    public function index(Request $request): View
    {
        $kataKunci = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $anggota = Anggota::query()
            ->withCount(['peminjamanAktif as pinjaman_aktif_count'])
            ->when($kataKunci !== '', function (Builder $query) use ($kataKunci): void {
                $query->where(function (Builder $sub) use ($kataKunci): void {
                    $sub->where('nama', 'like', "%{$kataKunci}%")
                        ->orWhere('nomor_anggota', 'like', "%{$kataKunci}%")
                        ->orWhere('program_studi', 'like', "%{$kataKunci}%");
                });
            })
            ->when($status === 'aktif', fn (Builder $query) => $query->where('status', 'aktif'))
            ->when($status === 'nonaktif', fn (Builder $query) => $query->where('status', 'nonaktif'))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('anggota.index', compact('anggota', 'kataKunci', 'status'));
    }
}

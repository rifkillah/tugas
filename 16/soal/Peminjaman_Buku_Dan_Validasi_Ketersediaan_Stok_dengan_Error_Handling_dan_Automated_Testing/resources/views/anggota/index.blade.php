@extends('layouts.app')

@section('title', 'Anggota Perpustakaan')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Data keanggotaan</span>
            <h1 class="page-title">Anggota perpustakaan</h1>
            <p class="page-subtitle">Daftar pemustaka terdaftar beserta jumlah peminjaman yang masih berjalan. Anggota nonaktif tidak muncul pada formulir peminjaman.</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('peminjaman.create') }}" class="btn btn--utama">Catat peminjaman</a>
        </div>
    </div>

    <form method="GET" action="{{ route('anggota.index') }}" class="filter-bar">
        <div class="field field--cari">
            <label for="q">Cari nama, nomor anggota, atau program studi</label>
            <input type="search" id="q" name="q" class="input" value="{{ $kataKunci }}" placeholder="Contoh: Nabila atau AGT-2023">
        </div>

        <div class="field">
            <label for="status">Status keanggotaan</label>
            <select id="status" name="status" class="select">
                <option value="">Semua status</option>
                <option value="aktif" @selected($status === 'aktif')>Aktif</option>
                <option value="nonaktif" @selected($status === 'nonaktif')>Nonaktif</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn--utama">Terapkan</button>
            <a href="{{ route('anggota.index') }}" class="btn btn--garis">Reset</a>
        </div>
    </form>

    <section class="panel">
        <div class="panel__head">
            <div>
                <h2 class="panel__title">Daftar anggota</h2>
                <p class="panel__sub">{{ $anggota->total() }} anggota sesuai filter yang dipilih.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nomor anggota</th>
                        <th>Nama dan program studi</th>
                        <th>Angkatan</th>
                        <th>Kontak</th>
                        <th>Pinjaman aktif</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($anggota as $item)
                        <tr>
                            <td class="nowrap"><span class="table__judul">{{ $item->nomor_anggota }}</span></td>
                            <td>
                                <span class="table__judul">{{ $item->nama }}</span>
                                <span class="table__meta">{{ $item->program_studi }}</span>
                            </td>
                            <td class="nowrap">{{ $item->angkatan }}</td>
                            <td class="nowrap">{{ $item->telepon ?? '-' }}</td>
                            <td>
                                @if ($item->pinjaman_aktif_count > 0)
                                    <span class="badge badge--emas">{{ $item->pinjaman_aktif_count }} transaksi</span>
                                @else
                                    <span class="badge badge--abu">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $item->status === 'aktif' ? 'badge--hijau' : 'badge--merah' }}">
                                    {{ $item->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty">
                                    <div class="empty__title">Anggota tidak ditemukan</div>
                                    <p class="empty__text">Coba kata kunci lain atau ubah filter status keanggotaan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @include('partials.paginasi', ['paginator' => $anggota])
@endsection

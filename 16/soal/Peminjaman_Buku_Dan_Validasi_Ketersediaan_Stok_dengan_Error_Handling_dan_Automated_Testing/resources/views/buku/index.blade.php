@extends('layouts.app')

@section('title', 'Koleksi Buku')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Katalog koleksi</span>
            <h1 class="page-title">Koleksi buku perpustakaan</h1>
            <p class="page-subtitle">Cari judul, periksa jumlah eksemplar di rak, lalu catat peminjaman tanpa meninggalkan halaman ini.</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('peminjaman.create') }}" class="btn btn--utama">Catat peminjaman</a>
        </div>
    </div>

    <form method="GET" action="{{ route('buku.index') }}" class="filter-bar">
        <div class="field field--cari">
            <label for="q">Cari judul, penulis, atau kode</label>
            <input type="search" id="q" name="q" class="input" value="{{ $kataKunci }}" placeholder="Contoh: Bumi Manusia atau BK-001">
        </div>

        <div class="field">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" class="select">
                <option value="">Semua kategori</option>
                @foreach ($daftarKategori as $item)
                    <option value="{{ $item }}" @selected($kategori === $item)>{{ $item }}</option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label for="status">Ketersediaan</label>
            <select id="status" name="status" class="select">
                <option value="">Semua stok</option>
                <option value="tersedia" @selected($status === 'tersedia')>Tersedia</option>
                <option value="habis" @selected($status === 'habis')>Stok habis</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn--utama">Terapkan</button>
            <a href="{{ route('buku.index') }}" class="btn btn--garis">Reset</a>
        </div>
    </form>

    <section class="panel">
        <div class="panel__head">
            <div>
                <h2 class="panel__title">Daftar koleksi</h2>
                <p class="panel__sub">{{ $buku->total() }} judul sesuai filter yang dipilih.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Judul dan penulis</th>
                        <th>Kategori</th>
                        <th>Rak</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($buku as $item)
                        <tr>
                            <td class="nowrap"><span class="table__judul">{{ $item->kode_buku }}</span></td>
                            <td>
                                <a href="{{ route('buku.show', $item->kode_buku) }}" class="table__judul">{{ $item->judul }}</a>
                                <span class="table__meta">{{ $item->penulis }} &middot; {{ $item->penerbit ?? 'Tanpa penerbit' }}, {{ $item->tahun ?? '-' }}</span>
                            </td>
                            <td><span class="badge badge--abu">{{ $item->kategori }}</span></td>
                            <td class="nowrap">{{ $item->rak }}</td>
                            <td>
                                <div class="stok">
                                    <span class="stok__angka {{ $item->stok_tersedia === 0 ? 'is-habis' : '' }}">{{ $item->stok_tersedia }}</span>
                                    <span class="stok__total">/ {{ $item->stok_total }}</span>
                                    <span class="stok__bar" aria-hidden="true">
                                        <span class="stok__fill {{ $item->stok_tersedia === 0 ? 'is-habis' : ($item->stok_tersedia === 1 ? 'is-rendah' : '') }}" style="width: {{ $item->persentaseTersedia() }}%"></span>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="table__actions">
                                    <a href="{{ route('buku.show', $item->kode_buku) }}" class="btn btn--garis btn--kecil">Detail</a>
                                    @if ($item->stok_tersedia > 0)
                                        <a href="{{ route('peminjaman.create', ['buku' => $item->kode_buku]) }}" class="btn btn--utama btn--kecil">Pinjam</a>
                                    @else
                                        <span class="btn btn--kecil is-disabled">Stok habis</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty">
                                    <div class="empty__title">Tidak ada judul yang cocok</div>
                                    <p class="empty__text">Ubah kata kunci atau filter kategori untuk menemukan koleksi lain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @include('partials.paginasi', ['paginator' => $buku])
@endsection

@extends('layouts.app')

@section('title', $buku->judul)

@section('content')
    <nav class="crumbs" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}">Dasbor</a>
        <span>/</span>
        <a href="{{ route('buku.index') }}">Koleksi buku</a>
        <span>/</span>
        <span>{{ $buku->kode_buku }}</span>
    </nav>

    <div class="detail-grid">
        <div class="stack">
            <section class="panel">
                <div class="book-hero">
                    <div class="sampul" aria-hidden="true">
                        <span class="sampul__kode">{{ $buku->kode_buku }}</span>
                        <span class="sampul__judul">{{ $buku->judul }}</span>
                        <span class="sampul__penulis">{{ $buku->penulis }}</span>
                    </div>

                    <div class="book-info">
                        <span class="eyebrow">{{ $buku->kategori }} &middot; Rak {{ $buku->rak }}</span>
                        <h1>{{ $buku->judul }}</h1>
                        <p class="book-info__penulis">oleh {{ $buku->penulis }}</p>

                        <dl class="meta-list">
                            <div>
                                <dt>Penerbit</dt>
                                <dd>{{ $buku->penerbit ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt>Tahun terbit</dt>
                                <dd>{{ $buku->tahun ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt>Kode koleksi</dt>
                                <dd>{{ $buku->kode_buku }}</dd>
                            </div>
                            <div>
                                <dt>Jumlah eksemplar</dt>
                                <dd>{{ $buku->stok_total }} eksemplar</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="sinopsis">
                    <h3>Sinopsis</h3>
                    <p>{{ $buku->sinopsis ?? 'Sinopsis untuk judul ini belum dilengkapi oleh petugas.' }}</p>
                </div>
            </section>

            <section class="panel">
                <div class="panel__head">
                    <div>
                        <h2 class="panel__title">Sedang dipinjam</h2>
                        <p class="panel__sub">Daftar anggota yang masih memegang eksemplar judul ini.</p>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Jumlah</th>
                                <th>Tanggal pinjam</th>
                                <th>Jatuh tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjamanAktif as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('peminjaman.show', $item->kode_peminjaman) }}" class="table__judul">{{ $item->anggota->nama }}</a>
                                        <span class="table__meta">{{ $item->anggota->nomor_anggota }}</span>
                                    </td>
                                    <td>{{ $item->jumlah }} eksemplar</td>
                                    <td class="nowrap">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                    <td class="nowrap">
                                        {{ $item->tanggal_jatuh_tempo->format('d/m/Y') }}
                                        @if ($item->isTerlambat())
                                            <span class="table__meta hint--merah">{{ $item->hariTerlambat() }} hari lewat</span>
                                        @endif
                                    </td>
                                    <td><x-status-peminjaman :peminjaman="$item" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty">
                                            <div class="empty__title">Semua eksemplar ada di rak</div>
                                            <p class="empty__text">Belum ada anggota yang meminjam judul ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="stack">
            <section class="aside-card stok-panel">
                <span class="stok-panel__label">Ketersediaan saat ini</span>
                <strong class="stok-panel__angka {{ $buku->stok_tersedia === 0 ? 'is-habis' : '' }}">{{ $buku->stok_tersedia }}</strong>
                <span class="stok-panel__dari">dari {{ $buku->stok_total }} eksemplar</span>

                <div class="stok-panel__bar" aria-hidden="true">
                    <span class="stok-panel__fill {{ $buku->stok_tersedia === 0 ? 'is-habis' : ($buku->stok_tersedia === 1 ? 'is-rendah' : '') }}" style="width: {{ $buku->persentaseTersedia() }}%"></span>
                </div>

                <p class="stok-panel__note">
                    @if ($buku->stok_tersedia === 0)
                        Seluruh eksemplar sedang dipinjam. Tunggu hingga ada pengembalian.
                    @else
                        {{ $buku->stok_tersedia }} eksemplar dapat dipinjam di loket sirkulasi.
                    @endif
                </p>

                <div style="margin-top: 16px; display: grid; gap: 8px;">
                    @if ($buku->stok_tersedia > 0)
                        <a href="{{ route('peminjaman.create', ['buku' => $buku->kode_buku]) }}" class="btn btn--utama">Catat peminjaman</a>
                    @else
                        <span class="btn is-disabled">Stok sedang habis</span>
                    @endif
                    <a href="{{ route('buku.index') }}" class="btn btn--garis">Kembali ke katalog</a>
                </div>
            </section>

            <section class="aside-card">
                <h2 class="aside-card__title">Ketentuan peminjaman</h2>
                <ol class="rules">
                    <li>Maksimal 3 eksemplar untuk setiap transaksi.</li>
                    <li>Masa pinjam 7, 14, atau 21 hari sejak tanggal pencatatan.</li>
                    <li>Denda keterlambatan Rp1.000 per hari per eksemplar.</li>
                    <li>Buku yang rusak atau hilang wajib diganti sesuai ketentuan perpustakaan.</li>
                </ol>
            </section>
        </aside>
    </div>
@endsection

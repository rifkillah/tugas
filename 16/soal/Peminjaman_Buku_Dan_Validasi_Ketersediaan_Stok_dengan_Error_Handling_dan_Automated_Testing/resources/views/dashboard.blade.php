@extends('layouts.app')

@section('title', 'Dasbor Sirkulasi')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Sirkulasi &middot; {{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
            <h1 class="page-title">Ringkasan layanan hari ini</h1>
            <p class="page-subtitle">Pantau ketersediaan koleksi, transaksi yang sedang berjalan, dan buku yang perlu segera ditindaklanjuti.</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('peminjaman.create') }}" class="btn btn--utama">Catat peminjaman</a>
            <a href="{{ route('laporan') }}" class="btn btn--garis">Lihat laporan uji</a>
        </div>
    </div>

    <div class="stat-grid">
        <article class="stat stat--hijau">
            <span class="stat__label">Judul koleksi</span>
            <strong class="stat__value">{{ $ringkasan['judul'] }}</strong>
            <span class="stat__note">{{ number_format($ringkasan['eksemplar'], 0, ',', '.') }} eksemplar terdata</span>
        </article>
        <article class="stat stat--emas">
            <span class="stat__label">Tersedia di rak</span>
            <strong class="stat__value">{{ $ringkasan['tersedia'] }}</strong>
            <span class="stat__note">Siap dipinjam hari ini</span>
        </article>
        <article class="stat stat--biru">
            <span class="stat__label">Sedang dipinjam</span>
            <strong class="stat__value">{{ $ringkasan['dipinjam'] }}</strong>
            <span class="stat__note">Transaksi aktif di loket</span>
        </article>
        <article class="stat stat--merah">
            <span class="stat__label">Terlambat</span>
            <strong class="stat__value">{{ $ringkasan['terlambat'] }}</strong>
            <span class="stat__note">Perlu penagihan anggota</span>
        </article>
    </div>

    <div class="dashboard-grid">
        <section class="panel">
            <div class="panel__head">
                <div>
                    <h2 class="panel__title">Peminjaman terbaru</h2>
                    <p class="panel__sub">Enam transaksi terakhir yang dicatat di loket sirkulasi.</p>
                </div>
                <a href="{{ route('peminjaman.index') }}" class="link-kecil">Semua transaksi &rarr;</a>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Jatuh tempo</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjamanTerbaru as $item)
                            <tr>
                                <td class="nowrap">
                                    <a href="{{ route('peminjaman.show', $item->kode_peminjaman) }}"><strong>{{ $item->kode_peminjaman }}</strong></a>
                                </td>
                                <td>
                                    <span class="table__judul">{{ $item->anggota->nama }}</span>
                                    <span class="table__meta">{{ $item->anggota->nomor_anggota }}</span>
                                </td>
                                <td>
                                    <span class="table__judul">{{ $item->buku->judul }}</span>
                                    <span class="table__meta">{{ $item->jumlah }} eksemplar</span>
                                </td>
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
                                        <div class="empty__title">Belum ada transaksi</div>
                                        <p class="empty__text">Peminjaman yang dicatat akan tampil di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="stack">
            <section class="panel">
                <div class="panel__head">
                    <div>
                        <h2 class="panel__title">Jatuh tempo terdekat</h2>
                        <p class="panel__sub">Empat transaksi paling mendesak.</p>
                    </div>
                </div>

                <ul class="agenda">
                    @forelse ($jatuhTempoTerdekat as $item)
                        @php
                            $sisaHari = (int) today()->startOfDay()->diffInDays($item->tanggal_jatuh_tempo->startOfDay(), false);
                        @endphp
                        <li>
                            <span class="agenda__date">
                                {{ $item->tanggal_jatuh_tempo->format('d') }}
                                <small>{{ $item->tanggal_jatuh_tempo->locale('id')->translatedFormat('M') }}</small>
                            </span>
                            <span class="agenda__body">
                                <a href="{{ route('peminjaman.show', $item->kode_peminjaman) }}" class="agenda__title">{{ $item->buku->judul }}</a>
                                <span class="agenda__meta">
                                    {{ $item->anggota->nama }} &middot;
                                    @if ($sisaHari < 0)
                                        <span class="hint--merah">lewat {{ abs($sisaHari) }} hari</span>
                                    @elseif ($sisaHari === 0)
                                        <span class="hint--merah">jatuh tempo hari ini</span>
                                    @else
                                        {{ $sisaHari }} hari lagi
                                    @endif
                                </span>
                            </span>
                        </li>
                    @empty
                        <li>
                            <span class="agenda__body">
                                <span class="agenda__title">Tidak ada transaksi aktif</span>
                                <span class="agenda__meta">Semua buku sudah kembali ke rak.</span>
                            </span>
                        </li>
                    @endforelse
                </ul>
            </section>

            <section class="panel">
                <div class="panel__head">
                    <div>
                        <h2 class="panel__title">Stok perlu perhatian</h2>
                        <p class="panel__sub">Tersisa satu eksemplar atau kurang.</p>
                    </div>
                </div>

                <ul class="agenda">
                    @forelse ($stokMenipis as $buku)
                        <li>
                            <span class="agenda__body">
                                <a href="{{ route('buku.show', $buku->kode_buku) }}" class="agenda__title">{{ $buku->judul }}</a>
                                <span class="agenda__meta">{{ $buku->kode_buku }} &middot; rak {{ $buku->rak }}</span>
                            </span>
                            <span class="badge {{ $buku->stok_tersedia === 0 ? 'badge--merah' : 'badge--emas' }}">
                                {{ $buku->stok_tersedia === 0 ? 'Habis' : 'Sisa 1' }}
                            </span>
                        </li>
                    @empty
                        <li>
                            <span class="agenda__body">
                                <span class="agenda__title">Stok koleksi aman</span>
                                <span class="agenda__meta">Tidak ada judul yang menipis.</span>
                            </span>
                        </li>
                    @endforelse
                </ul>
            </section>

            <section class="petugas-card">
                <div class="petugas-card__row">
                    <span class="petugas-card__avatar">RM</span>
                    <span>
                        <span class="petugas-card__name">{{ config('app.owner') }}</span>
                        <span class="petugas-card__role">Penanggung jawab sistem</span>
                    </span>
                </div>
                <p class="petugas-card__note">Nama petugas ini tercatat sebagai watermark pada setiap transaksi dan tersimpan di tabel pengaturan basis data.</p>
            </section>
        </aside>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Transaksi '.$peminjaman->kode_peminjaman)

@section('content')
    <nav class="crumbs" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}">Dasbor</a>
        <span>/</span>
        <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
        <span>/</span>
        <span>{{ $peminjaman->kode_peminjaman }}</span>
    </nav>

    <div class="page-head">
        <div>
            <span class="eyebrow">Kuitansi sirkulasi</span>
            <h1 class="page-title">Detail transaksi peminjaman</h1>
            <p class="page-subtitle">Periksa identitas peminjam, kondisi stok, dan status pengembalian sebelum menutup transaksi.</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('peminjaman.index') }}" class="btn btn--garis">Kembali ke daftar</a>
        </div>
    </div>

    <div class="receipt-grid">
        <section class="panel">
            <div class="receipt">
                <div class="receipt__head">
                    <div>
                        <span class="receipt__kode">{{ $peminjaman->kode_peminjaman }}</span>
                        <span class="receipt__tanggal">Dicatat {{ $peminjaman->created_at->locale('id')->translatedFormat('d F Y, H.i') }} WIB</span>
                    </div>

                    @if ($peminjaman->status === 'dikembalikan')
                        <span class="stamp stamp--kembali">Selesai</span>
                    @elseif ($peminjaman->isTerlambat())
                        <span class="stamp">Terlambat</span>
                    @else
                        <span class="stamp stamp--aktif">Dipinjam</span>
                    @endif
                </div>

                <div class="receipt__grid">
                    <div class="receipt__box">
                        <span class="receipt__box-label">Anggota peminjam</span>
                        <span class="receipt__box-value">{{ $peminjaman->anggota->nama }}</span>
                        <span class="receipt__box-meta">{{ $peminjaman->anggota->nomor_anggota }} &middot; {{ $peminjaman->anggota->program_studi }}</span>
                    </div>

                    <div class="receipt__box">
                        <span class="receipt__box-label">Buku yang dipinjam</span>
                        <a href="{{ route('buku.show', $peminjaman->buku->kode_buku) }}" class="receipt__box-value">{{ $peminjaman->buku->judul }}</a>
                        <span class="receipt__box-meta">{{ $peminjaman->buku->kode_buku }} &middot; rak {{ $peminjaman->buku->rak }} &middot; {{ $peminjaman->jumlah }} eksemplar</span>
                    </div>
                </div>

                <dl class="receipt__rows">
                    <div class="receipt__row">
                        <dt>Tanggal pinjam</dt>
                        <dd>{{ $peminjaman->tanggal_pinjam->locale('id')->translatedFormat('l, d F Y') }}</dd>
                    </div>
                    <div class="receipt__row">
                        <dt>Jatuh tempo</dt>
                        <dd>
                            {{ $peminjaman->tanggal_jatuh_tempo->locale('id')->translatedFormat('l, d F Y') }}
                            @if ($peminjaman->isTerlambat())
                                <span class="badge badge--merah">{{ $peminjaman->hariTerlambat() }} hari lewat</span>
                            @endif
                        </dd>
                    </div>
                    <div class="receipt__row">
                        <dt>Jumlah eksemplar</dt>
                        <dd>{{ $peminjaman->jumlah }} eksemplar</dd>
                    </div>
                    <div class="receipt__row">
                        <dt>Petugas pencatat</dt>
                        <dd>{{ $peminjaman->dicatat_oleh }}</dd>
                    </div>

                    @if ($peminjaman->catatan)
                        <div class="receipt__row">
                            <dt>Catatan</dt>
                            <dd>{{ $peminjaman->catatan }}</dd>
                        </div>
                    @endif

                    @if ($peminjaman->status === 'dikembalikan')
                        <div class="receipt__row">
                            <dt>Tanggal kembali</dt>
                            <dd>{{ $peminjaman->tanggal_kembali->locale('id')->translatedFormat('l, d F Y') }}</dd>
                        </div>
                        <div class="receipt__row">
                            <dt>Denda</dt>
                            <dd>Rp{{ number_format($peminjaman->denda, 0, ',', '.') }}</dd>
                        </div>
                    @endif
                </dl>

                @if ($peminjaman->status === 'dikembalikan' && $peminjaman->denda > 0)
                    <div class="denda-box">
                        <strong>Rp{{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
                        Denda keterlambatan {{ $peminjaman->hariTerlambat() }} hari sudah tercatat pada transaksi ini.
                    </div>
                @endif

                @if ($peminjaman->isTerlambat())
                    <div class="denda-box">
                        <strong>Rp{{ number_format($peminjaman->hitungDenda(), 0, ',', '.') }}</strong>
                        Perkiraan denda jika buku dikembalikan hari ini ({{ $peminjaman->hariTerlambat() }} hari keterlambatan).
                    </div>
                @endif

                @if ($peminjaman->belumDikembalikan())
                    <div class="form-actions">
                        <form method="POST" action="{{ route('peminjaman.kembalikan', $peminjaman->kode_peminjaman) }}"
                              data-confirm="Catat pengembalian buku ini? Pastikan fisik buku sudah diterima petugas.">
                            @csrf
                            <button type="submit" class="btn btn--utama">Catat pengembalian</button>
                        </form>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn--garis">Tunda dulu</a>
                    </div>
                @else
                    <div class="form-actions">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn--utama">Kembali ke daftar transaksi</a>
                        <a href="{{ route('buku.show', $peminjaman->buku->kode_buku) }}" class="btn btn--garis">Lihat buku</a>
                    </div>
                @endif
            </div>
        </section>

        <aside class="stack">
            <section class="aside-card">
                <h2 class="aside-card__title">Alur transaksi</h2>
                <ul class="timeline">
                    <li class="is-done">
                        <span class="timeline__label">Peminjaman dicatat</span>
                        <span class="timeline__value">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span>
                        <span class="timeline__meta">Oleh {{ $peminjaman->dicatat_oleh }}</span>
                    </li>
                    <li class="{{ $peminjaman->isTerlambat() ? 'is-alert' : '' }}">
                        <span class="timeline__label">Batas pengembalian</span>
                        <span class="timeline__value">{{ $peminjaman->tanggal_jatuh_tempo->format('d/m/Y') }}</span>
                        <span class="timeline__meta">
                            @if ($peminjaman->status === 'dikembalikan')
                                {{ $peminjaman->tanggal_kembali->lte($peminjaman->tanggal_jatuh_tempo) ? 'Dikembalikan tepat waktu' : 'Dikembalikan setelah jatuh tempo' }}
                            @elseif ($peminjaman->isTerlambat())
                                Sudah melewati batas {{ $peminjaman->hariTerlambat() }} hari
                            @else
                                Masih dalam masa pinjam
                            @endif
                        </span>
                    </li>
                    <li class="{{ $peminjaman->status === 'dikembalikan' ? 'is-done' : '' }}">
                        <span class="timeline__label">Pengembalian</span>
                        <span class="timeline__value">
                            {{ $peminjaman->status === 'dikembalikan' ? $peminjaman->tanggal_kembali->format('d/m/Y') : 'Belum dikembalikan' }}
                        </span>
                        <span class="timeline__meta">
                            {{ $peminjaman->status === 'dikembalikan' ? 'Stok sudah kembali ke rak' : 'Menunggu buku diterima petugas' }}
                        </span>
                    </li>
                </ul>
            </section>

            <section class="aside-card">
                <h2 class="aside-card__title">Ketentuan denda</h2>
                <p class="hint">
                    Denda dihitung Rp1.000 per hari per eksemplar, dimulai sehari setelah tanggal jatuh tempo.
                    Perhitungan dilakukan otomatis saat tombol pengembalian ditekan.
                </p>
                <p class="hint" style="margin-top: 9px;">
                    Setiap kegagalan transaksi dicatat melalui <span class="code">Log::error()</span> ke
                    <span class="code">storage/logs/laravel.log</span>.
                </p>

                @if (config('app.debug'))
                    <details class="dev-note">
                        <summary>Mode pengembangan</summary>
                        <p>Gunakan tautan ini untuk memeriksa data mentah transaksi dengan <span class="code">dd()</span>.</p>
                        <p>
                            <a href="{{ route('peminjaman.show', ['kode' => $peminjaman->kode_peminjaman, 'debug' => 1]) }}">Periksa data (dd) &rarr;</a>
                        </p>
                    </details>
                @endif
            </section>
        </aside>
    </div>
@endsection

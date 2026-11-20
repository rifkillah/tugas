@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Loket sirkulasi</span>
            <h1 class="page-title">Peminjaman dan pengembalian</h1>
            <p class="page-subtitle">Semua transaksi peminjaman tercatat di sini, termasuk status keterlambatan dan denda yang sudah dibayarkan.</p>
        </div>
        <div class="page-head__actions">
            <a href="{{ route('peminjaman.create') }}" class="btn btn--utama">Catat peminjaman</a>
        </div>
    </div>

    <div class="stat-grid">
        <article class="stat stat--biru">
            <span class="stat__label">Transaksi aktif</span>
            <strong class="stat__value">{{ $statistik['aktif'] }}</strong>
            <span class="stat__note">Buku belum dikembalikan</span>
        </article>
        <article class="stat stat--merah">
            <span class="stat__label">Terlambat</span>
            <strong class="stat__value">{{ $statistik['terlambat'] }}</strong>
            <span class="stat__note">Melewati tanggal jatuh tempo</span>
        </article>
        <article class="stat stat--hijau">
            <span class="stat__label">Selesai</span>
            <strong class="stat__value">{{ $statistik['selesai'] }}</strong>
            <span class="stat__note">Buku sudah kembali</span>
        </article>
        <article class="stat stat--emas">
            <span class="stat__label">Denda tercatat</span>
            <strong class="stat__value" style="font-size: 24px;">Rp{{ number_format($statistik['denda'], 0, ',', '.') }}</strong>
            <span class="stat__note">Akumulasi denda diterima</span>
        </article>
    </div>

    <form method="GET" action="{{ route('peminjaman.index') }}" class="filter-bar">
        <div class="field field--cari">
            <label for="q">Cari kode transaksi, anggota, atau buku</label>
            <input type="search" id="q" name="q" class="input" value="{{ $kataKunci }}" placeholder="Contoh: PJM-2026 atau Rifqi">
        </div>

        <div class="field">
            <label for="status">Status transaksi</label>
            <select id="status" name="status" class="select">
                <option value="">Semua status</option>
                <option value="dipinjam" @selected($status === 'dipinjam')>Sedang dipinjam</option>
                <option value="terlambat" @selected($status === 'terlambat')>Terlambat</option>
                <option value="dikembalikan" @selected($status === 'dikembalikan')>Selesai</option>
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn--utama">Terapkan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn--garis">Reset</a>
        </div>
    </form>

    <section class="panel">
        <div class="panel__head">
            <div>
                <h2 class="panel__title">Daftar transaksi</h2>
                <p class="panel__sub">{{ $peminjaman->total() }} transaksi sesuai filter yang dipilih.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Anggota</th>
                        <th>Buku</th>
                        <th>Pinjam</th>
                        <th>Jatuh tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($peminjaman as $item)
                        <tr>
                            <td class="nowrap">
                                <a href="{{ route('peminjaman.show', $item->kode_peminjaman) }}" class="table__judul">{{ $item->kode_peminjaman }}</a>
                                @if ($item->denda > 0)
                                    <span class="table__meta">Denda Rp{{ number_format($item->denda, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="table__judul">{{ $item->anggota->nama }}</span>
                                <span class="table__meta">{{ $item->anggota->nomor_anggota }}</span>
                            </td>
                            <td>
                                <a href="{{ route('buku.show', $item->buku->kode_buku) }}" class="table__judul">{{ $item->buku->judul }}</a>
                                <span class="table__meta">{{ $item->jumlah }} eksemplar</span>
                            </td>
                            <td class="nowrap">{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="nowrap">
                                {{ $item->tanggal_jatuh_tempo->format('d/m/Y') }}
                                @if ($item->isTerlambat())
                                    <span class="table__meta hint--merah">{{ $item->hariTerlambat() }} hari lewat</span>
                                @elseif ($item->status === 'dikembalikan' && $item->tanggal_kembali)
                                    <span class="table__meta">Kembali {{ $item->tanggal_kembali->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td><x-status-peminjaman :peminjaman="$item" /></td>
                            <td>
                                <a href="{{ route('peminjaman.show', $item->kode_peminjaman) }}" class="btn btn--garis btn--kecil">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty">
                                    <div class="empty__title">Tidak ada transaksi</div>
                                    <p class="empty__text">Ubah filter atau catat peminjaman baru melalui tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @include('partials.paginasi', ['paginator' => $peminjaman])
@endsection

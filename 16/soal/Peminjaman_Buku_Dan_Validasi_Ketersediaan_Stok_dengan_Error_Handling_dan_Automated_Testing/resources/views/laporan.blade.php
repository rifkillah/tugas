@extends('layouts.app')

@section('title', 'Laporan Pengujian')

@section('content')
    <div class="page-head">
        <div>
            <span class="eyebrow">Quality assurance &middot; Pertemuan 16</span>
            <h1 class="page-title">Laporan pengujian dan debugging</h1>
            <p class="page-subtitle">Ringkasan hasil pengujian otomatis, temuan bug, dan perbaikan pada sistem sirkulasi Pustaka Cendekia.</p>
        </div>
        <div class="page-head__actions">
            <button type="button" class="btn btn--garis" data-print>Cetak halaman</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn--utama">Buka data peminjaman</a>
        </div>
    </div>

    <section class="qa-hero">
        <div>
            <h2 class="qa-hero__title">Seluruh skenario pengujian lulus</h2>
            <p class="qa-hero__text">
                Diuji dengan <span class="code">php artisan test</span> pada basis data MySQL
                <span class="code">db_pustaka_cendekia_test</span>. Setiap kegagalan yang ditemukan selama
                pengembangan sudah diperbaiki dan diuji ulang.
            </p>
        </div>
        <div class="qa-stamp">Lulus</div>
    </section>

    <div class="qa-grid">
        <div class="qa-stat">
            <span class="qa-stat__label">Total skenario</span>
            <strong class="qa-stat__value">{{ $ringkasan['total'] }}</strong>
        </div>
        <div class="qa-stat">
            <span class="qa-stat__label">Lulus</span>
            <strong class="qa-stat__value" style="color: var(--hijau);">{{ $ringkasan['lulus'] }}</strong>
        </div>
        <div class="qa-stat">
            <span class="qa-stat__label">Gagal</span>
            <strong class="qa-stat__value" style="color: var(--merah);">{{ $ringkasan['gagal'] }}</strong>
        </div>
        <div class="qa-stat">
            <span class="qa-stat__label">Terakhir dijalankan</span>
            <strong class="qa-stat__value" style="font-size: 16px;">{{ $ringkasan['terakhir_dijalankan'] }}</strong>
        </div>
    </div>

    <section class="panel" style="margin-bottom: 18px;">
        <div class="panel__head">
            <div>
                <h2 class="panel__title">Skenario pengujian otomatis</h2>
                <p class="panel__sub">Feature test dan unit test pada folder <span class="code">tests/</span>.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 44px;">No</th>
                        <th>Skenario</th>
                        <th>Metode uji</th>
                        <th>Jenis</th>
                        <th>Hasil</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengujian as $uji)
                        <tr>
                            <td class="num">{{ $uji['no'] }}</td>
                            <td><span class="table__judul">{{ $uji['skenario'] }}</span></td>
                            <td><span class="code">{{ $uji['metode'] }}</span></td>
                            <td class="nowrap">{{ $uji['jenis'] }}</td>
                            <td><span class="status-pass">{{ $uji['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel" style="margin-bottom: 18px;">
        <div class="panel__head">
            <div>
                <h2 class="panel__title">Temuan debugging dan perbaikan</h2>
                <p class="panel__sub">Masalah yang ditemukan saat pengujian manual maupun otomatis.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 44px;">No</th>
                        <th>Nama fitur / URL</th>
                        <th>Jenis error / skenario uji</th>
                        <th>Penyebab masalah (hasil debugging)</th>
                        <th>Solusi error handling / perbaikan kode</th>
                        <th>Status hasil uji</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($temuan as $item)
                        <tr>
                            <td class="num">{{ $item['no'] }}</td>
                            <td class="nowrap"><span class="table__judul">{{ $item['fitur'] }}</span></td>
                            <td class="nowrap"><span class="badge badge--emas">{{ $item['jenis'] }}</span></td>
                            <td><span class="table__meta" style="font-size: 12px;">{{ $item['penyebab'] }}</span></td>
                            <td><span class="table__meta" style="font-size: 12px;">{{ $item['solusi'] }}</span></td>
                            <td><span class="status-pass">{{ $item['status'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <div class="dashboard-grid">
        <section class="panel">
            <div class="panel__head">
                <div>
                    <h2 class="panel__title">Catatan implementasi</h2>
                    <p class="panel__sub">Bukti pemenuhan spesifikasi tugas pertemuan 16.</p>
                </div>
            </div>
            <div class="panel__body">
                <ol class="rules">
                    <li><strong>Error handling:</strong> blok try-catch pada <span class="code">PeminjamanController</span> menangani stok habis, jumlah melebihi stok, dan anggota nonaktif. Setiap kegagalan dicatat dengan <span class="code">Log::error()</span>.</li>
                    <li><strong>Halaman 404 kustom:</strong> tersedia di <span class="code">resources/views/errors/404.blade.php</span> dan digunakan saat kode buku atau kode peminjaman tidak ditemukan.</li>
                    <li><strong>Debugging:</strong> data transaksi dapat diperiksa dengan <span class="code">dd()</span> melalui parameter <span class="code">?debug=1</span> pada halaman detail saat <span class="code">APP_DEBUG=true</span>.</li>
                    <li><strong>Basis data MySQL:</strong> seluruh data koleksi, anggota, peminjaman, dan pengaturan tersimpan di <span class="code">db_pustaka_cendekia</span> sehingga dapat dipantau melalui phpMyAdmin.</li>
                    <li><strong>Watermark:</strong> nama <span class="code">Rifkillah Muliya Al-Faris</span> tampil di footer situs, tersimpan pada tabel <span class="code">pengaturan</span>, pada kolom <span class="code">dicatat_oleh</span>, dan pada komentar tabel MySQL.</li>
                </ol>

                <div class="callout">
                    Perintah pengujian:
                    <span class="code">php artisan test</span>.
                    Perintah penyiapan data:
                    <span class="code">php artisan migrate:fresh --seed</span>.
                </div>
            </div>
        </section>

        <aside class="stack">
            <section class="aside-card">
                <h2 class="aside-card__title">Jejak watermark</h2>
                <p class="hint">Nama pemilik sistem dapat diperiksa di tiga tempat:</p>
                <dl class="summary-list" style="margin-top: 11px;">
                    <div>
                        <dt>Tabel pengaturan</dt>
                        <dd>kunci <span class="code">watermark</span></dd>
                    </div>
                    <div>
                        <dt>Tabel peminjaman</dt>
                        <dd>kolom <span class="code">dicatat_oleh</span></dd>
                    </div>
                    <div>
                        <dt>Metadata MySQL</dt>
                        <dd>komentar tabel</dd>
                    </div>
                </dl>
                @if ($contohTransaksi)
                    <p class="hint" style="margin-top: 11px;">
                        Transaksi terakhir <span class="code">{{ $contohTransaksi->kode_peminjaman }}</span>
                        dicatat oleh <strong>{{ $contohTransaksi->dicatat_oleh }}</strong>.
                    </p>
                @endif
            </section>

            <section class="petugas-card">
                <div class="petugas-card__row">
                    <span class="petugas-card__avatar">RM</span>
                    <span>
                        <span class="petugas-card__name">{{ config('app.owner') }}</span>
                        <span class="petugas-card__role">Pengembang &amp; penguji</span>
                    </span>
                </div>
                <p class="petugas-card__note">Laporan ini disusun sebagai bagian dari tugas pertemuan 16: teknik pengujian dan debugging aplikasi web.</p>
            </section>
        </aside>
    </div>
@endsection

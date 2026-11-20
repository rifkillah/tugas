<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Pustaka Cendekia - sistem layanan sirkulasi, peminjaman, dan pengembalian buku perpustakaan.">
    <title>@yield('title', 'Dasbor') &middot; Pustaka Cendekia</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="topbar__inner container">
            <a class="brand" href="{{ route('dashboard') }}" aria-label="Pustaka Cendekia - Dasbor">
                <span class="brand__mark">
                    <svg width="23" height="23" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 5.5c0-.83.67-1.5 1.5-1.5H11v15H5.5A1.5 1.5 0 0 1 4 17.5v-12Z" stroke="#f4e7cf" stroke-width="1.4" stroke-linejoin="round"/>
                        <path d="M20 5.5c0-.83-.67-1.5-1.5-1.5H13v15h5.5a1.5 1.5 0 0 0 1.5-1.5v-12Z" stroke="#f4e7cf" stroke-width="1.4" stroke-linejoin="round"/>
                        <path d="M12 4v15" stroke="#b47b2b" stroke-width="1.2"/>
                    </svg>
                </span>
                <span>
                    <span class="brand__name">Pustaka Cendekia</span>
                    <span class="brand__sub">Unit Sirkulasi Perpustakaan</span>
                </span>
            </a>

            <nav class="nav" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}" class="nav__link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}">Dasbor</a>
                <a href="{{ route('buku.index') }}" class="nav__link {{ request()->routeIs('buku.*') ? 'is-active' : '' }}">Koleksi Buku</a>
                <a href="{{ route('peminjaman.index') }}" class="nav__link {{ request()->routeIs('peminjaman.*') ? 'is-active' : '' }}">Peminjaman</a>
                <a href="{{ route('anggota.index') }}" class="nav__link {{ request()->routeIs('anggota.*') ? 'is-active' : '' }}">Anggota</a>
            </nav>

            <div class="topbar__actions">
                <span class="operator">
                    <span class="operator__dot"></span>
                    Operator &middot; {{ config('app.owner') }}
                </span>
                <a href="{{ route('peminjaman.create') }}" class="btn btn--emas btn--kecil">Catat Peminjaman</a>
            </div>
        </div>
    </header>

    <main class="page">
        <div class="container">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="footer__inner container">
            <div>
                <div class="footer__brand">Pustaka Cendekia</div>
                <div class="footer__meta">Layanan sirkulasi &middot; Senin&ndash;Jumat, 08.00&ndash;16.00 WIB &middot; Rak lantai 1</div>
            </div>

            <div class="footer__watermark">
                <span class="footer__label">Dikembangkan dan diuji oleh</span>
                <strong>{{ config('app.owner') }}</strong>
            </div>

            <div class="footer__links">
                <a href="{{ route('laporan') }}">Laporan pengujian</a>
                <span>Versi 1.0 &middot; Pertemuan 16</span>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

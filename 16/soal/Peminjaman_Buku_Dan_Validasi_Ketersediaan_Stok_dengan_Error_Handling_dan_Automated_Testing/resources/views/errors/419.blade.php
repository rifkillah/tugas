@extends('layouts.app')

@section('title', '419 - Sesi Berakhir')

@section('content')
    <div class="error-page error-page--403">
        <div class="error-page__code">419</div>
        <div class="error-page__body">
            <span class="eyebrow">Sesi formulir berakhir</span>
            <h1>Halaman terlalu lama dibiarkan terbuka.</h1>
            <p class="page-subtitle">Demi keamanan transaksi, token formulir sudah kedaluwarsa. Silakan kembali dan isi formulir sekali lagi.</p>

            <div class="error-page__actions">
                <a href="{{ route('peminjaman.create') }}" class="btn btn--utama">Buka formulir peminjaman</a>
                <a href="{{ route('dashboard') }}" class="btn btn--garis">Kembali ke dasbor</a>
            </div>
        </div>
    </div>
@endsection

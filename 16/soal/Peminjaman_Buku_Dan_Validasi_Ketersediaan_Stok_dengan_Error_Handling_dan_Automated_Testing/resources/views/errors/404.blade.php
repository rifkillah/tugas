@extends('layouts.app')

@section('title', '404 - Data Tidak Ditemukan')

@section('content')
    <div class="error-page">
        <div class="error-page__code">404</div>
        <div class="error-page__body">
            <span class="eyebrow">Rak tidak ditemukan</span>
            <h1>Halaman atau data yang Anda cari tidak tersedia.</h1>

            @if (isset($exception) && $exception->getMessage())
                <p class="error-page__detail">{{ $exception->getMessage() }}</p>
            @else
                <p class="page-subtitle">Alamat yang Anda tuju mungkin sudah dipindahkan, dihapus, atau salah diketik.</p>
            @endif

            <p class="hint" style="margin-top: 12px;">Kode galat: PC-404 &middot; Periksa kembali kode buku atau kode peminjaman Anda.</p>

            <div class="error-page__actions">
                <a href="{{ route('dashboard') }}" class="btn btn--utama">Kembali ke dasbor</a>
                <a href="{{ route('buku.index') }}" class="btn btn--garis">Telusuri koleksi</a>
                <a href="{{ route('peminjaman.index') }}" class="btn btn--garis">Daftar peminjaman</a>
            </div>
        </div>
    </div>
@endsection

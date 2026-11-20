@extends('layouts.app')

@section('title', '403 - Akses Ditolak')

@section('content')
    <div class="error-page error-page--403">
        <div class="error-page__code">403</div>
        <div class="error-page__body">
            <span class="eyebrow">Akses dibatasi</span>
            <h1>Anda tidak memiliki izin untuk membuka halaman ini.</h1>

            @if (isset($exception) && $exception->getMessage())
                <p class="error-page__detail">{{ $exception->getMessage() }}</p>
            @else
                <p class="page-subtitle">Halaman ini hanya dapat diakses oleh petugas sirkulasi yang berwenang.</p>
            @endif

            <div class="error-page__actions">
                <a href="{{ route('dashboard') }}" class="btn btn--utama">Kembali ke dasbor</a>
                <a href="{{ route('buku.index') }}" class="btn btn--garis">Buka koleksi buku</a>
            </div>
        </div>
    </div>
@endsection

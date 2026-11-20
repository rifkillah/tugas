@extends('layouts.app')

@section('title', '500 - Kendala Layanan')

@section('content')
    <div class="error-page error-page--500">
        <div class="error-page__code">500</div>
        <div class="error-page__body">
            <span class="eyebrow">Kendala pada layanan</span>
            <h1>Permintaan Anda belum dapat kami selesaikan.</h1>
            <p class="page-subtitle">Terjadi gangguan internal pada sistem sirkulasi. Kejadian ini sudah dicatat di berkas log untuk diperiksa oleh petugas.</p>

            <div class="error-page__actions">
                <a href="{{ route('dashboard') }}" class="btn btn--utama">Muat ulang dasbor</a>
                <a href="{{ route('buku.index') }}" class="btn btn--garis">Kembali ke koleksi</a>
            </div>
        </div>
    </div>
@endsection

@if (session('sukses'))
    <div class="flash flash--sukses" role="status">
        <span class="flash__icon" aria-hidden="true">&#10003;</span>
        <div>
            <span class="flash__title">Transaksi berhasil</span>
            <span class="flash__text">{{ session('sukses') }}</span>
        </div>
        <button type="button" class="flash__close" data-dismiss aria-label="Tutup pesan">&times;</button>
    </div>
@endif

@if (session('gagal'))
    <div class="flash flash--gagal" role="alert">
        <span class="flash__icon" aria-hidden="true">!</span>
        <div>
            <span class="flash__title">Peminjaman tidak dapat diproses</span>
            <span class="flash__text">{{ session('gagal') }}</span>
        </div>
        <button type="button" class="flash__close" data-dismiss aria-label="Tutup pesan">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div class="flash flash--gagal" role="alert">
        <span class="flash__icon" aria-hidden="true">!</span>
        <div>
            <span class="flash__title">Periksa kembali isian formulir</span>
            <ul class="flash__list">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="flash__close" data-dismiss aria-label="Tutup pesan">&times;</button>
    </div>
@endif

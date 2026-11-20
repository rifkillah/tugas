@extends('layouts.app')

@section('title', 'Catat Peminjaman')

@section('content')
    <nav class="crumbs" aria-label="Breadcrumb">
        <a href="{{ route('dashboard') }}">Dasbor</a>
        <span>/</span>
        <a href="{{ route('peminjaman.index') }}">Peminjaman</a>
        <span>/</span>
        <span>Catat baru</span>
    </nav>

    <div class="page-head">
        <div>
            <span class="eyebrow">Loket sirkulasi</span>
            <h1 class="page-title">Catat peminjaman baru</h1>
            <p class="page-subtitle">Pastikan kartu anggota sudah terverifikasi. Stok akan diperiksa ulang saat tombol simpan ditekan.</p>
        </div>
    </div>

    @if ($bukuTerpilih)
        <div class="form-note">
            Buku dipilih dari halaman detail: <strong>{{ $bukuTerpilih->judul }}</strong>.
            Stok tersedia saat ini {{ $bukuTerpilih->stok_tersedia }} dari {{ $bukuTerpilih->stok_total }} eksemplar.
        </div>
    @endif

    <div class="detail-grid">
        <section class="panel">
            <div class="panel__head">
                <div>
                    <h2 class="panel__title">Formulir transaksi</h2>
                    <p class="panel__sub">Isian bertanda bintang wajib dilengkapi.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('peminjaman.store') }}" class="panel__body">
                @csrf

                <div class="form-grid">
                    <div class="field field--full">
                        <label for="anggota_id">Anggota peminjam *</label>
                        <select id="anggota_id" name="anggota_id" class="select" required>
                            <option value="">Pilih anggota aktif</option>
                            @foreach ($daftarAnggota as $orang)
                                <option value="{{ $orang->id }}" @selected((string) old('anggota_id') === (string) $orang->id)>
                                    {{ $orang->nama }} &mdash; {{ $orang->nomor_anggota }} ({{ $orang->program_studi }})
                                </option>
                            @endforeach
                        </select>
                        @error('anggota_id')
                            <span class="hint hint--merah">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field field--full">
                        <label for="buku_id">Buku yang dipinjam *</label>
                        <select id="buku_id" name="buku_id" class="select" required
                                data-stok="{{ $bukuTerpilih?->stok_tersedia ?? 0 }}"
                                data-judul="{{ $bukuTerpilih?->judul ?? '' }}">
                            <option value="">Pilih buku dari koleksi</option>
                            @foreach ($daftarBuku as $buku)
                                <option value="{{ $buku->id }}"
                                        data-stok="{{ $buku->stok_tersedia }}"
                                        data-judul="{{ $buku->judul }}"
                                        @disabled($buku->stok_tersedia === 0)
                                        @selected((string) old('buku_id', $bukuTerpilih?->id) === (string) $buku->id)>
                                    {{ $buku->judul }} &mdash; {{ $buku->kode_buku }} &middot;
                                    {{ $buku->stok_tersedia > 0 ? 'stok '.$buku->stok_tersedia : 'stok habis' }}
                                </option>
                            @endforeach
                        </select>
                        <span class="hint" id="catatan-stok">Stok tersedia. Maksimal 3 eksemplar per transaksi.</span>
                        @error('buku_id')
                            <span class="hint hint--merah">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="jumlah">Jumlah eksemplar *</label>
                        <input type="number" id="jumlah" name="jumlah" class="input" min="1" max="3"
                               value="{{ old('jumlah', 1) }}" required>
                        <span class="hint">Maksimal 3 eksemplar atau sesuai stok tersedia.</span>
                        @error('jumlah')
                            <span class="hint hint--merah">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="durasi">Masa pinjam *</label>
                        <select id="durasi" name="durasi" class="select" required>
                            <option value="7" @selected((string) old('durasi') === '7')>7 hari</option>
                            <option value="14" @selected((string) old('durasi', 14) === '14')>14 hari</option>
                            <option value="21" @selected((string) old('durasi') === '21')>21 hari</option>
                        </select>
                        <span class="hint">Jatuh tempo dihitung dari tanggal pencatatan hari ini.</span>
                        @error('durasi')
                            <span class="hint hint--merah">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field field--full">
                        <label for="catatan">Catatan petugas</label>
                        <textarea id="catatan" name="catatan" class="textarea" maxlength="200"
                                  placeholder="Contoh: kartu anggota diverifikasi di loket, buku kondisi baik.">{{ old('catatan') }}</textarea>
                        <span class="hint">Opsional, maksimal 200 karakter.</span>
                        @error('catatan')
                            <span class="hint hint--merah">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--utama">Simpan peminjaman</button>
                    <a href="{{ route('peminjaman.index') }}" class="btn btn--garis">Batal</a>
                </div>
            </form>
        </section>

        <aside class="stack">
            <section class="aside-card">
                <h2 class="aside-card__title">Ringkasan pilihan</h2>
                <dl class="summary-list">
                    <div>
                        <dt>Buku</dt>
                        <dd id="ringkasan-buku">&mdash;</dd>
                    </div>
                    <div>
                        <dt>Stok tersedia</dt>
                        <dd id="ringkasan-stok">&mdash;</dd>
                    </div>
                    <div>
                        <dt>Perkiraan jatuh tempo</dt>
                        <dd id="ringkasan-jatuh-tempo">&mdash;</dd>
                    </div>
                </dl>
            </section>

            <section class="aside-card">
                <h2 class="aside-card__title">Ketentuan layanan</h2>
                <ol class="rules">
                    <li>Satu anggota dapat meminjam maksimal 3 eksemplar per transaksi.</li>
                    <li>Masa pinjam dipilih 7, 14, atau 21 hari.</li>
                    <li>Denda keterlambatan Rp1.000 per hari per eksemplar.</li>
                    <li>Peminjaman ditolak bila stok habis atau anggota berstatus nonaktif.</li>
                </ol>
            </section>

            <section class="petugas-card">
                <div class="petugas-card__row">
                    <span class="petugas-card__avatar">RM</span>
                    <span>
                        <span class="petugas-card__name">{{ config('app.owner') }}</span>
                        <span class="petugas-card__role">Operator yang bertugas</span>
                    </span>
                </div>
                <p class="petugas-card__note">Nama operator otomatis disimpan pada kolom <em>dicatat_oleh</em> di setiap transaksi.</p>
            </section>
        </aside>
    </div>
@endsection

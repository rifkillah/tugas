<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Perhitungan IP - {{ $mhs['nama'] }}</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; padding: 40px; }
        .card { max-width: 550px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); position: relative; z-index: 1; }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-sukses { background: #d4edda; color: #155724; border-left: 5px solid #28a745; padding: 15px; border-radius: 5px; margin-top: 15px; }
        .btn { display: inline-block; margin-top: 20px; padding: 8px 16px; background: #2c3e50; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    @include('partials.watermark')

    <div class="card">
        <h2>Hasil Evaluasi Indeks Prestasi (IP)</h2>

        @if ($pesan_error)
            <div class="alert-error">
                <b>Peringatan Sistem (Tertangkap Blok Try-Catch):</b><br>
                {{ $pesan_error }}
            </div>
        @endif

        <p><b>NIM:</b> {{ $nim }}</p>
        <p><b>Nama Mahasiswa:</b> {{ $mhs['nama'] }}</p>
        <p><b>Program Studi:</b> {{ $mhs['prodi'] }}</p>
        <p><b>Total SKS Diambil:</b> {{ $mhs['total_sks'] }} SKS</p>

        @if (!$pesan_error)
            <div class="alert-sukses">
                <b>IP Semester: {{ $ip }}</b> <br>
                Maksimal SKS Semester Depan: <b>{{ $kuota_sks }} SKS</b>
            </div>
        @endif

        <a href="{{ url('/uji-nilai') }}" class="btn">&laquo; Kembali ke Daftar Pengujian</a>

        <p class="wm-credit">Watermark: {{ $watermark }}</p>
    </div>
</body>
</html>

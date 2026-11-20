<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman atau Data Tidak Ditemukan</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; text-align: center; padding-top: 80px; color: #333; }
        .error-card { max-width: 500px; margin: auto; background: white; padding: 35px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-top: 5px solid #e74c3c; position: relative; z-index: 1; }
        h1 { font-size: 64px; margin: 0; color: #e74c3c; }
        h3 { margin-top: 10px; color: #2c3e50; }
        p { color: #666; line-height: 1.6; }
        .pesan-khusus { background: #fdf2e9; color: #d35400; padding: 10px; border-radius: 6px; font-weight: bold; margin: 15px 0; }
        .btn { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #2c3e50; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    @include('partials.watermark')

    <div class="error-card">
        <h1>404</h1>
        <h3>Oops! Data atau Halaman Tidak Ditemukan</h3>

        @if ($exception->getMessage())
            <div class="pesan-khusus">
                Detail: {{ $exception->getMessage() }}
            </div>
        @else
            <p>Alamat URL yang kamu tuju tidak tersedia di dalam sistem kami.</p>
        @endif

        <a href="{{ url('/uji-nilai') }}" class="btn">&laquo; Kembali ke Halaman Kalkulator Nilai</a>

        <p class="wm-credit">Watermark: {{ $watermark }}</p>
    </div>
</body>
</html>

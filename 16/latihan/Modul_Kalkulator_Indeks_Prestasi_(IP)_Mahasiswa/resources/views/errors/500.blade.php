<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; text-align: center; padding-top: 80px; color: #333; }
        .error-card { max-width: 500px; margin: auto; background: white; padding: 35px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-top: 5px solid #e74c3c; position: relative; z-index: 1; }
        h1 { font-size: 64px; margin: 0; color: #e74c3c; }
        h3 { margin-top: 10px; color: #2c3e50; }
        p { color: #666; line-height: 1.6; }
        .btn { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #2c3e50; color: white; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    @include('partials.watermark')

    <div class="error-card">
        <h1>500</h1>
        <h3>Oops! Terjadi Kesalahan pada Server</h3>
        <p>Kesalahan telah dicatat ke dalam log aplikasi agar dapat diperiksa oleh developer.</p>

        <a href="{{ url('/uji-nilai') }}" class="btn">&laquo; Kembali ke Halaman Kalkulator Nilai</a>

        <p class="wm-credit">Watermark: {{ $watermark }}</p>
    </div>
</body>
</html>

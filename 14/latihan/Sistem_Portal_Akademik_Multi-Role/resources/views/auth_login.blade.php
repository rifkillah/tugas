<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Portal Akademik</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; padding-top: 60px; }
        .card { background: white; width: 360px; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        label { display: block; margin-top: 12px; font-weight: bold; font-size: 14px; }
        input { width: 100%; padding: 9px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; margin-top: 20px; padding: 10px; background: #3498db; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .alert-sukses { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; font-size: 13px; margin-bottom: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; font-size: 13px; margin-bottom: 10px; }
        .link { text-align: center; margin-top: 15px; font-size: 14px; }
        .watermark { text-align: center; margin-top: 18px; padding-top: 12px; border-top: 1px dashed #e2e8f0; font-size: 12px; color: #64748b; }
        .watermark span { background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-weight: bold; color: #1e293b; display: inline-block; border: 1px solid #cbd5e1; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login Portal Akademik</h2>

        @if (session('sukses'))
            <div class="alert-sukses">{{ session('sukses') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <label>Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Masuk (Login)</button>
        </form>

        <div class="link">
            Belum punya akun? <a href="{{ url('/register') }}">Daftar Akun Baru</a>
        </div>

        <div class="watermark">
            <span>WM: Rifkillah muliya al-faris</span>
        </div>
    </div>
</body>
</html>

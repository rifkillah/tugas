<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Portal Akademik</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 40px; }
        .box { max-width: 650px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .badge { padding: 4px 10px; border-radius: 12px; color: white; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .bg-admin { background: #e67e22; }
        .bg-mhs { background: #27ae60; }
        .menu-box { margin-top: 20px; padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 6px; }
        .btn { display: inline-block; padding: 8px 14px; background: #2c3e50; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; font-size: 14px; }
        .btn-logout { background: #e74c3c; color: white; border: none; padding: 9px 16px; border-radius: 5px; cursor: pointer; font-weight: bold; margin-top: 20px; }
        .watermark { text-align: center; margin-top: 24px; padding-top: 14px; border-top: 1px dashed #e2e8f0; font-size: 13px; color: #64748b; }
        .watermark span { background: #f1f5f9; padding: 5px 12px; border-radius: 20px; font-weight: bold; color: #1e293b; display: inline-block; border: 1px solid #cbd5e1; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p>
            Email Aktif: <b>{{ Auth::user()->email }}</b> <br>
            Status Role Kamu:
            @if (Auth::user()->role === 'admin')
                <span class="badge bg-admin">Administrator</span>
            @else
                <span class="badge bg-mhs">Mahasiswa</span>
            @endif
        </p>

        <div class="menu-box">
            <h4>Menu Pengujian Otorisasi Role:</h4>
            <a href="{{ url('/panel-admin') }}" class="btn">Buka Halaman Khusus Admin</a>
            <a href="{{ url('/krs-mahasiswa') }}" class="btn">Buka Halaman KRS Mahasiswa</a>
        </div>

        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar (Logout)</button>
        </form>

        <div class="watermark">
            <span>WM: Rifkillah muliya al-faris</span>
        </div>
    </div>
</body>
</html>

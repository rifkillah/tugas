<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Portal E-Perpustakaan Kampus</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #eef2f7 0%, #dbeafe 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            max-width: 680px;
            width: 100%;
            background: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #0284c7;
        }
        .watermark-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        h2 {
            color: #1e293b;
            font-size: 24px;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 24px;
        }
        .user-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .user-card p {
            font-size: 14px;
            color: #334155;
            margin-bottom: 10px;
            line-height: 1.6;
        }
        .user-card p:last-child {
            margin-bottom: 0;
        }
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 20px;
            color: #ffffff;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .bg-pustakawan {
            background: #ea580c;
        }
        .bg-anggota {
            background: #16a34a;
        }
        .menu-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 22px;
            margin-bottom: 25px;
        }
        .menu-box h4 {
            color: #1e293b;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .menu-box p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 16px;
        }
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-pustakawan {
            background: #ea580c;
            color: #ffffff;
        }
        .btn-pustakawan:hover {
            background: #c2410c;
        }
        .btn-anggota {
            background: #16a34a;
            color: #ffffff;
        }
        .btn-anggota:hover {
            background: #15803d;
        }
        .btn-logout {
            background: #dc2626;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s ease;
        }
        .btn-logout:hover {
            background: #b91c1c;
        }
        .footer-wm {
            margin-top: 24px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="watermark-badge">
            👤 Developer: Rifkillah muliya al-faris
        </div>

        <h2>Selamat Datang, {{ Auth::user()->name }}!</h2>
        <p class="subtitle">Portal E-Perpustakaan Kampus — Sistem Autentikasi & Hak Akses</p>

        <div class="user-card">
            <p><strong>Nama Pengguna:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Alamat Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Status Role Kamu:</strong>
                @if (Auth::user()->role === 'pustakawan')
                    <span class="badge bg-pustakawan">Pustakawan (Pengelola Perpustakaan)</span>
                @else
                    <span class="badge bg-anggota">Anggota (Mahasiswa Peminjam)</span>
                @endif
            </p>
        </div>

        <div class="menu-box">
            <h4>Menu Pengujian Otorisasi Role (Middleware RBAC):</h4>
            <p>Klik tombol di bawah untuk menguji pembatasan hak akses rute berdasarkan role Anda saat ini:</p>
            <div class="btn-group">
                <a href="{{ url('/perpus/kelola-buku') }}" class="btn btn-pustakawan">
                    📚 Buka Halaman Kelola Buku (Role: Pustakawan)
                </a>
                <a href="{{ url('/perpus/pinjam-buku') }}" class="btn btn-anggota">
                    📖 Buka Halaman Pinjam Buku (Role: Anggota)
                </a>
            </div>
        </div>

        <form method="POST" action="{{ url('/perpus/logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar (Logout)</button>
        </form>
    </div>

    <div class="footer-wm">
        Portal E-Perpustakaan Kampus &copy; {{ date('Y') }} — Rifkillah muliya al-faris
    </div>

</body>
</html>

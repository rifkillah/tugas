<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - Portal E-Perpustakaan Kampus</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #eef2f7 0%, #dbeafe 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #0284c7;
        }
        .header-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-title h2 {
            color: #1e293b;
            font-size: 22px;
            margin-bottom: 6px;
        }
        .header-title p {
            color: #64748b;
            font-size: 13px;
        }
        .watermark-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
            width: 100%;
        }
        label {
            display: block;
            margin-top: 14px;
            font-weight: 600;
            font-size: 13px;
            color: #334155;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease;
        }
        input:focus, select:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        button {
            width: 100%;
            margin-top: 22px;
            padding: 11px;
            background: #0284c7;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        button:hover {
            background: #0369a1;
        }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 15px;
        }
        .link {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: #64748b;
        }
        .link a {
            color: #0284c7;
            text-decoration: none;
            font-weight: 600;
        }
        .link a:hover {
            text-decoration: underline;
        }
        .footer-wm {
            margin-top: 16px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="watermark-badge">
            👤 Developer: Rifkillah muliya al-faris
        </div>

        <div class="header-title">
            <h2>Registrasi Akun Baru</h2>
            <p>Portal E-Perpustakaan Kampus</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('/perpus/register') }}">
            @csrf

            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required>

            <label for="email">Alamat Email Kampus</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="contoh@kampus.ac.id" required>

            <label for="password">Password (Minimal 6 Karakter)</label>
            <input type="password" id="password" name="password" placeholder="Buat kata sandi aman" required>

            <label for="role">Pilih Role Pengguna</label>
            <select id="role" name="role" required>
                <option value="anggota" {{ old('role') == 'anggota' ? 'selected' : '' }}>Anggota (Mahasiswa Peminjam)</option>
                <option value="pustakawan" {{ old('role') == 'pustakawan' ? 'selected' : '' }}>Pustakawan (Pengelola Perpustakaan)</option>
            </select>

            <button type="submit">Daftar Akun E-Perpustakaan</button>
        </form>

        <div class="link">
            Sudah memiliki akun? <a href="{{ url('/perpus/login') }}">Login di sini</a>
        </div>
    </div>

    <div class="footer-wm">
        Portal E-Perpustakaan Kampus &copy; {{ date('Y') }} — Rifkillah muliya al-faris
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Buku - Anggota Perpustakaan</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background: #ffffff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #16a34a;
        }
        .watermark-badge {
            display: inline-block;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        h1 {
            color: #166534;
            font-size: 24px;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #4b5563;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
            padding: 14px;
            border-radius: 6px;
            font-size: 14px;
            color: #166534;
            margin-bottom: 25px;
        }
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 25px;
        }
        .book-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
        }
        .book-card h3 {
            font-size: 15px;
            color: #1e293b;
            margin-bottom: 6px;
        }
        .book-card p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 12px;
        }
        .book-card .btn-pinjam {
            display: inline-block;
            padding: 6px 12px;
            background: #16a34a;
            color: white;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            text-decoration: none;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background: #0284c7;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s ease;
        }
        .btn-back:hover {
            background: #0369a1;
        }
        .footer-wm {
            margin-top: 24px;
            font-size: 12px;
            color: #4b5563;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="watermark-badge">
            👤 Developer: Rifkillah muliya al-faris
        </div>

        <h1>Form Peminjaman Buku Mahasiswa</h1>
        <p class="subtitle">Akses Khusus: <strong>Role Anggota (Mahasiswa)</strong></p>

        <div class="info-box">
            ✅ <strong>Verifikasi Otorisasi Berhasil!</strong> Halo <strong>{{ Auth::user()->name }}</strong>, Anda dapat meminjam buku literatur perkuliahan karena memiliki akun dengan hak akses <strong>Anggota</strong>.
        </div>

        <div class="book-list">
            <div class="book-card">
                <h3>Pemrograman Web Modern dengan Laravel</h3>
                <p>Pengarang: Septa, M.Kom<br>Status: Tersedia (10)</p>
                <a href="#" class="btn-pinjam" onclick="alert('Permintaan pinjam buku berhasil diajukan!'); return false;">+ Pinjam Buku Ini</a>
            </div>
            <div class="book-card">
                <h3>Keamanan Sistem Informasi & Autentikasi Web</h3>
                <p>Pengarang: Rifkillah muliya al-faris<br>Status: Tersedia (8)</p>
                <a href="#" class="btn-pinjam" onclick="alert('Permintaan pinjam buku berhasil diajukan!'); return false;">+ Pinjam Buku Ini</a>
            </div>
            <div class="book-card">
                <h3>Basis Data Relasional & Optimasi MySQL</h3>
                <p>Pengarang: Ahmad Fauzi, Ph.D<br>Status: Tersedia (5)</p>
                <a href="#" class="btn-pinjam" onclick="alert('Permintaan pinjam buku berhasil diajukan!'); return false;">+ Pinjam Buku Ini</a>
            </div>
        </div>

        <a href="{{ url('/perpus/dashboard') }}" class="btn-back">⬅ Kembali ke Dashboard</a>
    </div>

    <div class="footer-wm">
        Portal E-Perpustakaan Kampus &copy; {{ date('Y') }} — Rifkillah muliya al-faris
    </div>

</body>
</html>

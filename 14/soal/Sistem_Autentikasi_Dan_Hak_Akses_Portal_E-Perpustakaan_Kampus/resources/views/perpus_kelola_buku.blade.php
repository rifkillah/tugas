<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Koleksi Buku - Pustakawan</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
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
            border-top: 5px solid #ea580c;
        }
        .watermark-badge {
            display: inline-block;
            background: #fff7ed;
            color: #c2410c;
            border: 1px solid #fed7aa;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        h1 {
            color: #9a3412;
            font-size: 24px;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #78716c;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #e7e5e4;
            font-size: 14px;
        }
        th {
            background: #fafaf9;
            color: #44403c;
            font-weight: 600;
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
            color: #78716c;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="watermark-badge">
            👤 Developer: Rifkillah muliya al-faris
        </div>

        <h1>Panel Pengelolaan Koleksi Buku Perpustakaan</h1>
        <p class="subtitle">Akses Khusus: <strong>Role Pustakawan</strong></p>

        <div class="info-box">
            ✅ <strong>Verifikasi Otorisasi Berhasil!</strong> Halo <strong>{{ Auth::user()->name }}</strong>, Anda berhasil mengakses modul manajemen buku karena memiliki akun dengan hak akses <strong>Pustakawan</strong>.
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Buku</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Stok</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>BK-001</td>
                    <td>Pemrograman Web Modern dengan Laravel</td>
                    <td>Septa, M.Kom</td>
                    <td>10</td>
                    <td><span style="color: #16a34a; font-weight: 600;">Tersedia</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>BK-002</td>
                    <td>Keamanan Sistem Informasi & Autentikasi Web</td>
                    <td>Rifkillah muliya al-faris</td>
                    <td>8</td>
                    <td><span style="color: #16a34a; font-weight: 600;">Tersedia</span></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>BK-003</td>
                    <td>Basis Data Relasional & Optimasi MySQL</td>
                    <td>Ahmad Fauzi, Ph.D</td>
                    <td>5</td>
                    <td><span style="color: #ea580c; font-weight: 600;">Menipis</span></td>
                </tr>
            </tbody>
        </table>

        <a href="{{ url('/perpus/dashboard') }}" class="btn-back">⬅ Kembali ke Dashboard</a>
    </div>

    <div class="footer-wm">
        Portal E-Perpustakaan Kampus &copy; {{ date('Y') }} — Rifkillah muliya al-faris
    </div>

</body>
</html>

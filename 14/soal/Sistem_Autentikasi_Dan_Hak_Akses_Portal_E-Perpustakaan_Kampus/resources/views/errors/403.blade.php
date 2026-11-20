<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .card {
            background: #ffffff;
            max-width: 500px;
            width: 100%;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-top: 5px solid #dc2626;
            text-align: center;
        }
        .status-code {
            font-size: 56px;
            font-weight: 800;
            color: #dc2626;
            line-height: 1;
            margin-bottom: 12px;
        }
        h2 {
            font-size: 20px;
            color: #1e293b;
            margin-bottom: 12px;
        }
        .message-box {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
            padding: 14px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
        }
        .watermark-badge {
            display: inline-block;
            background: #f1f5f9;
            color: #0369a1;
            border: 1px solid #bae6fd;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #0284c7;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #0369a1;
        }
        .footer-wm {
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="watermark-badge">
            👤 Developer: Rifkillah muliya al-faris
        </div>

        <div class="status-code">403</div>
        <h2>Akses Ditolak (Forbidden)</h2>

        <div class="message-box">
            {{ $exception->getMessage() ?: 'AKSES DITOLAK: Anda tidak memiliki izin untuk mengakses halaman ini!' }}
        </div>

        <a href="{{ url('/perpus/dashboard') }}" class="btn">⬅ Kembali ke Dashboard</a>
    </div>

    <div class="footer-wm">
        Portal E-Perpustakaan Kampus &copy; {{ date('Y') }} — Rifkillah muliya al-faris
    </div>

</body>
</html>

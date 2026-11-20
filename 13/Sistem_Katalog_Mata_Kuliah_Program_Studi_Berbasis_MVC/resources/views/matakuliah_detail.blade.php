<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mata Kuliah - {{ $mk['nama'] }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            padding: 40px;
        }
        .card {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            color: #ff2d20;
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .row {
            margin-bottom: 12px;
            line-height: 1.6;
        }
        .label {
            font-weight: bold;
            width: 150px;
            display: inline-block;
            color: #555;
        }
        .deskripsi-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            margin-top: 15px;
            line-height: 1.6;
        }
        .btn-kembali {
            display: inline-block;
            margin-top: 20px;
            background: #2c3e50;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-kembali:hover {
            background: #1a252f;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Detail Silabus Mata Kuliah</h2>
        <div class="row"><span class="label">Kode Matkul</span>: <b>{{ $mk['kode'] }}</b></div>
        <div class="row"><span class="label">Nama Matkul</span>: {{ $mk['nama'] }}</div>
        <div class="row"><span class="label">Beban Studi</span>: {{ $mk['sks'] }} SKS (Semester {{ $mk['semester'] }})</div>
        <div class="row"><span class="label">Kategori</span>: {{ $mk['kategori'] }}</div>
        <div class="row"><span class="label">Dosen Pengampu</span>: {{ $mk['dosen'] }}</div>
        <div class="deskripsi-box">
            <b>Deskripsi & Capaian Pembelajaran:</b><br>
            {{ $mk['deskripsi'] }}
        </div>
        <a href="{{ url('/matakuliah') }}" class="btn-kembali">&laquo; Kembali ke Katalog Mata Kuliah</a>
    </div>
</body>
</html>

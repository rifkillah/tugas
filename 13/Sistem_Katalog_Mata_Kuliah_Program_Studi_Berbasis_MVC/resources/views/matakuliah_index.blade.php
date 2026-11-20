<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul_halaman }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 3px solid #ff2d20;
            padding-bottom: 10px;
        }
        .info-box {
            background: #eef6ff;
            border-left: 4px solid #3498db;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }
        .badge-wajib {
            background-color: #27ae60;
        }
        .badge-peminatan {
            background-color: #e67e22;
        }
        .btn-detail {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 13px;
        }
        .btn-detail:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $judul_halaman }}</h1>
        <div class="info-box">
            Menampilkan <b>{{ count($data_matkul) }} Mata Kuliah</b> aktif dengan akumulasi total <b>{{ $total_sks }} SKS</b>.
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Semester</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_matkul as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><b>{{ $item['kode'] }}</b></td>
                    <td>{{ $item['nama'] }}</td>
                    <td>{{ $item['sks'] }} SKS</td>
                    <td>Semester {{ $item['semester'] }}</td>
                    <td>
                        @if ($item['kategori'] === 'Wajib')
                            <span class="badge badge-wajib">Wajib</span>
                        @else
                            <span class="badge badge-peminatan">Peminatan</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ url('/matakuliah/' . $item['kode']) }}" class="btn-detail">Lihat Silabus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>

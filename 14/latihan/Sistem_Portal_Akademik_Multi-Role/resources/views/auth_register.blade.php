<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Akun Baru</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; display: flex; justify-content: center; padding-top: 50px; }
        .card { background: white; width: 380px; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        label { display: block; margin-top: 12px; font-weight: bold; font-size: 14px; }
        input, select { width: 100%; padding: 9px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; margin-top: 20px; padding: 10px; background: #27ae60; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .error { color: #e74c3c; font-size: 13px; margin-top: 5px; }
        .link { text-align: center; margin-top: 15px; font-size: 14px; }
        .watermark { text-align: center; margin-top: 18px; padding-top: 12px; border-top: 1px dashed #e2e8f0; font-size: 12px; color: #64748b; }
        .watermark span { background: #f1f5f9; padding: 4px 10px; border-radius: 20px; font-weight: bold; color: #1e293b; display: inline-block; border: 1px solid #cbd5e1; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Registrasi Akun Kampus</h2>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <label>Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>

            <label>Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@kampus.ac.id" required>

            <label>Password (Min. 6 Karakter)</label>
            <input type="password" name="password" placeholder="Masukkan password" required>

            <label>Pilih Role Pengguna</label>
            <select name="role" required>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="admin">Administrator Kampus</option>
            </select>

            <button type="submit">Daftar Sekarang</button>
        </form>

        <div class="link">
            Sudah punya akun? <a href="{{ url('/login') }}">Login di sini</a>
        </div>

        <div class="watermark">
            <span>WM: Rifkillah muliya al-faris</span>
        </div>
    </div>
</body>
</html>

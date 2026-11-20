<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal RESTful API Katalog Buku E-Perpustakaan Kampus - Rifkillah muliya al-faris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --success: #16a34a;
            --warning: #d97706;
            --dark: #0f172a;
            --text: #334155;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            padding: 30px 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1060px;
            margin: 0 auto;
        }

        .card {
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border);
            padding: 32px;
            margin-bottom: 24px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .author-tag {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }

        .author-tag strong {
            color: var(--primary);
            font-weight: 700;
        }

        .badge-status-group {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-mysql {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: currentColor;
            border-radius: 50%;
            display: inline-block;
        }

        /* Alert Styling */
        .alert {
            display: none;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 22px;
            line-height: 1.5;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-sukses {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-errors-list {
            margin-top: 6px;
            padding-left: 20px;
            font-size: 13px;
        }

        /* Form Styling */
        .form-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 26px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-edit {
            background-color: #0284c7;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-edit:hover {
            background-color: #0369a1;
        }

        .btn-hapus {
            background-color: var(--danger);
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-hapus:hover {
            background-color: var(--danger-hover);
        }

        .btn-detail {
            background-color: #475569;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
        }

        .btn-detail:hover {
            background-color: #334155;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }

        /* Table Styling */
        .table-responsive {
            overflow-x: auto;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            background: #ffffff;
        }

        thead {
            background-color: #0f172a;
        }

        th {
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 12px 14px;
            white-space: nowrap;
        }

        td {
            padding: 13px 14px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        .badge-code {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            padding: 3px 8px;
            border-radius: 5px;
            font-family: Consolas, monospace;
            font-weight: 700;
            font-size: 12px;
        }

        .badge-stok {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 9999px;
            font-weight: 700;
            font-size: 12px;
        }

        .stok-tersedia {
            background-color: #dcfce7;
            color: #15803d;
        }

        .stok-habis {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        /* Endpoints Info Panel */
        .api-info-panel {
            background: #f1f5f9;
            border-radius: 12px;
            padding: 16px 20px;
            margin-top: 24px;
            font-size: 13px;
        }

        .api-info-panel h4 {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .api-endpoint-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 8px;
        }

        .api-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 6px;
            font-family: Consolas, monospace;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .method {
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 10.5px;
        }

        .method-get { background: #dbeafe; color: #1e40af; }
        .method-post { background: #dcfce7; color: #166534; }
        .method-put { background: #fef3c7; color: #92400e; }
        .method-delete { background: #fee2e2; color: #991b1b; }

        /* Modal Edit */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-card {
            background: #ffffff;
            border-radius: 14px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
            padding: 24px;
            animation: fadeIn 0.25s ease-out;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .modal-header h3 {
            font-size: 17px;
            color: var(--dark);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #64748b;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        /* Watermark footer */
        .watermark-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
            text-align: center;
            font-size: 13px;
            color: #64748b;
        }

        .watermark-badge {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            padding: 5px 14px;
            border-radius: 9999px;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0 4px;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        }

        .db-badge {
            display: inline-block;
            background: #0ea5e9;
            color: #ffffff;
            padding: 3px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header Section with Watermark -->
            <div class="header-row">
                <div class="header-title">
                    <h1>📚 Portal Konsumen RESTful API Katalog Buku</h1>
                    <div class="author-tag">
                        Praktikum Pemrograman Web &bull; Dikerjakan oleh: <strong>Rifkillah muliya al-faris</strong>
                    </div>
                </div>
                <div class="badge-status-group">
                    <span class="badge badge-mysql" title="Database MySQL aktif di Laragon / phpMyAdmin">
                        🐬 MySQL: perpus_kampus (phpMyAdmin)
                    </span>
                    <span class="badge badge-success" id="indikatorApi">
                        <span class="status-dot"></span> API STATUS: 200 OK
                    </span>
                </div>
            </div>

            <!-- Feedback Alert Box -->
            <div id="kotakPesan" class="alert"></div>

            <!-- Form Tambah Buku (POST /api/buku) -->
            <div class="form-section">
                <div class="section-title">
                    <span>➕ Tambah Koleksi Buku Baru (POST /api/buku)</span>
                </div>
                <form id="formApiBuku">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kode_buku">Kode Buku *</label>
                            <input type="text" id="kode_buku" class="form-control" placeholder="Contoh: BK-004" required>
                        </div>
                        <div class="form-group">
                            <label for="judul_buku">Judul Buku *</label>
                            <input type="text" id="judul_buku" class="form-control" placeholder="Judul Buku Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis *</label>
                            <input type="text" id="penulis" class="form-control" placeholder="Nama Penulis / Pengarang" required>
                        </div>
                        <div class="form-group">
                            <label for="stok">Stok Buku *</label>
                            <input type="number" id="stok" class="form-control" min="0" placeholder="0" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" id="btnSimpan" class="btn btn-primary">
                                + Simpan ke API
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Koleksi Buku (GET /api/buku) -->
            <div class="table-section">
                <div class="section-title" style="justify-content: space-between;">
                    <span>📖 Data Katalog Buku Terkini (Konsumsi GET /api/buku)</span>
                    <span id="labelJumlahData" style="font-size: 13px; font-weight: 600; color: #64748b;">Memuat data...</span>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 120px;">Kode Buku</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th style="width: 90px; text-align: center;">Stok</th>
                                <th style="width: 220px; text-align: center;">Aksi API</th>
                            </tr>
                        </thead>
                        <tbody id="tabelBodyApi">
                            <tr>
                                <td colspan="6" style="text-align: center; color: #64748b; padding: 25px;">
                                    Memuat data buku dari endpoint <code>/api/buku</code>...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Panel Informasi API Endpoints -->
            <div class="api-info-panel">
                <h4>
                    <span>📡 Endpoint RESTful API E-Perpustakaan (/api/buku)</span>
                    <a href="/api/buku" target="_blank" style="color: var(--primary); text-decoration: none; font-size: 12px; font-weight: 600;">
                        Buka Raw JSON &rarr;
                    </a>
                </h4>
                <div class="api-endpoint-list">
                    <div class="api-item"><span class="method method-get">GET</span> /api/buku &rarr; Ambil seluruh koleksi (200 OK)</div>
                    <div class="api-item"><span class="method method-post">POST</span> /api/buku &rarr; Tambah koleksi baru (201 Created)</div>
                    <div class="api-item"><span class="method method-get">GET</span> /api/buku/{id} &rarr; Detail 1 buku (200 / 404)</div>
                    <div class="api-item"><span class="method method-put">PUT</span> /api/buku/{id} &rarr; Update informasi buku (200 OK)</div>
                    <div class="api-item"><span class="method method-delete">DELETE</span> /api/buku/{id} &rarr; Hapus buku (200 OK)</div>
                </div>
            </div>

            <!-- Watermark Footer -->
            <div class="watermark-footer">
                <div>Watermark Pembuat Aplikasi: <span class="watermark-badge">Rifkillah muliya al-faris</span></div>
                <div style="margin-top: 8px; font-size: 12px;">
                    Database: <span class="db-badge">MySQL</span> &bull; Terhubung ke phpMyAdmin: <code>localhost/phpmyadmin</code> &bull; Database: <strong>perpus_kampus</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Buku (PUT /api/buku/{id}) -->
    <div id="modalEdit" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>✏️ Edit Data Buku (PUT /api/buku/{id})</h3>
                <button type="button" class="modal-close" onclick="tutupModalEdit()">&times;</button>
            </div>
            <form id="formEditBuku">
                <input type="hidden" id="edit_id">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="edit_kode_buku">Kode Buku *</label>
                    <input type="text" id="edit_kode_buku" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="edit_judul_buku">Judul Buku *</label>
                    <input type="text" id="edit_judul_buku" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <label for="edit_penulis">Penulis *</label>
                    <input type="text" id="edit_penulis" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 16px;">
                    <label for="edit_stok">Stok Buku *</label>
                    <input type="number" id="edit_stok" min="0" class="form-control" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="tutupModalEdit()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript RESTful API Consumer -->
    <script>
        const API_URL = '/api/buku';

        // Menampilkan pesan notifikasi hasil konsumsi API
        function tampilkanPesan(teks, sukses = true, errors = null) {
            const box = document.getElementById('kotakPesan');
            box.style.display = 'block';
            box.className = 'alert ' + (sukses ? 'alert-sukses' : 'alert-error');

            let content = `<strong>${sukses ? '✓ Berhasil: ' : '✗ Perhatian: '}</strong> ${teks}`;

            if (errors && typeof errors === 'object') {
                content += '<ul class="alert-errors-list">';
                for (const field in errors) {
                    errors[field].forEach(err => {
                        content += `<li>${err}</li>`;
                    });
                }
                content += '</ul>';
            }

            box.innerHTML = content;

            // Otomatis scroll ke pesan jika perlu
            box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // 1. Fungsi Mengkonsumsi Endpoint GET /api/buku (Mengambil semua buku)
        async function muatDataBuku() {
            const tbody = document.getElementById('tabelBodyApi');
            const indikator = document.getElementById('indikatorApi');
            const labelJumlah = document.getElementById('labelJumlahData');

            try {
                const res = await fetch(API_URL, {
                    headers: { 'Accept': 'application/json' }
                });

                if (res.ok) {
                    indikator.className = 'badge badge-success';
                    indikator.innerHTML = '<span class="status-dot"></span> API STATUS: 200 OK';
                } else {
                    indikator.className = 'badge badge-mysql';
                    indikator.innerHTML = `<span class="status-dot"></span> API STATUS: ${res.status}`;
                }

                const json = await res.json();
                tbody.innerHTML = '';

                if (!json.data || json.data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #64748b; padding: 25px;">Belum ada buku di dalam katalog API.</td></tr>';
                    labelJumlah.innerText = 'Total: 0 buku';
                    return;
                }

                labelJumlah.innerText = `Total: ${json.data.length} buku (Respons JSON status: 200 OK)`;

                json.data.forEach((buku, index) => {
                    const stokClass = buku.stok > 0 ? 'stok-tersedia' : 'stok-habis';
                    const stokText = buku.stok > 0 ? `${buku.stok} Eks` : 'Habis';

                    tbody.innerHTML += `
                        <tr>
                            <td><strong>${buku.id}</strong></td>
                            <td><span class="badge-code">${escapeHtml(buku.kode_buku)}</span></td>
                            <td><strong>${escapeHtml(buku.judul_buku)}</strong></td>
                            <td>${escapeHtml(buku.penulis)}</td>
                            <td style="text-align: center;">
                                <span class="badge-stok ${stokClass}">${stokText}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-detail" onclick="lihatDetailViaApi(${buku.id})" title="GET /api/buku/${buku.id}">
                                        Detail
                                    </button>
                                    <button class="btn btn-edit" onclick="bukaModalEdit(${buku.id}, '${escapeQuote(buku.kode_buku)}', '${escapeQuote(buku.judul_buku)}', '${escapeQuote(buku.penulis)}', ${buku.stok})" title="PUT /api/buku/${buku.id}">
                                        Edit
                                    </button>
                                    <button class="btn btn-hapus" onclick="hapusViaApi(${buku.id}, '${escapeQuote(buku.judul_buku)}')" title="DELETE /api/buku/${buku.id}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } catch (err) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align: center; color: #dc2626; padding: 25px;">Gagal memuat API: ${err.message}</td></tr>`;
                indikator.className = 'badge badge-mysql';
                indikator.innerHTML = '<span class="status-dot"></span> API ERROR';
            }
        }

        // 2. Fungsi Mengkonsumsi Endpoint POST /api/buku (Menambahkan buku baru)
        document.getElementById('formApiBuku').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btnSimpan = document.getElementById('btnSimpan');
            btnSimpan.disabled = true;
            btnSimpan.innerText = 'Menyimpan...';

            const payload = {
                kode_buku: document.getElementById('kode_buku').value.trim(),
                judul_buku: document.getElementById('judul_buku').value.trim(),
                penulis: document.getElementById('penulis').value.trim(),
                stok: parseInt(document.getElementById('stok').value, 10)
            };

            try {
                const res = await fetch(API_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();

                if (res.status === 201) {
                    tampilkanPesan(json.pesan, true);
                    this.reset();
                    muatDataBuku();
                } else if (res.status === 422) {
                    tampilkanPesan(json.pesan, false, json.errors);
                } else {
                    tampilkanPesan(json.pesan || 'Terjadi kesalahan pada server API', false);
                }
            } catch (error) {
                tampilkanPesan('Gagal menghubungi server API: ' + error.message, false);
            } finally {
                btnSimpan.disabled = false;
                btnSimpan.innerText = '+ Simpan ke API';
            }
        });

        // 3. Fungsi Mengkonsumsi Endpoint GET /api/buku/{id} (Menampilkan detail buku)
        async function lihatDetailViaApi(id) {
            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const json = await res.json();

                if (res.ok && json.data) {
                    const b = json.data;
                    alert(
                        `--- DETAIL BUKU (GET /api/buku/${id}) ---\n\n` +
                        `ID: ${b.id}\n` +
                        `Kode Buku: ${b.kode_buku}\n` +
                        `Judul: ${b.judul_buku}\n` +
                        `Penulis: ${b.penulis}\n` +
                        `Stok: ${b.stok} eksemplar\n` +
                        `Dibuat: ${b.created_at}\n\n` +
                        `Pesan API: ${json.pesan}`
                    );
                } else {
                    tampilkanPesan(json.pesan || 'Detail buku tidak ditemukan', false);
                }
            } catch (err) {
                tampilkanPesan('Gagal mengambil detail buku: ' + err.message, false);
            }
        }

        // 4. Fungsi Modal Edit & PUT /api/buku/{id}
        function bukaModalEdit(id, kode, judul, penulis, stok) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_kode_buku').value = kode;
            document.getElementById('edit_judul_buku').value = judul;
            document.getElementById('edit_penulis').value = penulis;
            document.getElementById('edit_stok').value = stok;
            document.getElementById('modalEdit').style.display = 'flex';
        }

        function tutupModalEdit() {
            document.getElementById('modalEdit').style.display = 'none';
        }

        document.getElementById('formEditBuku').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;

            const payload = {
                kode_buku: document.getElementById('edit_kode_buku').value.trim(),
                judul_buku: document.getElementById('edit_judul_buku').value.trim(),
                penulis: document.getElementById('edit_penulis').value.trim(),
                stok: parseInt(document.getElementById('edit_stok').value, 10)
            };

            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const json = await res.json();

                if (res.status === 200) {
                    tutupModalEdit();
                    tampilkanPesan(json.pesan, true);
                    muatDataBuku();
                } else if (res.status === 422) {
                    tampilkanPesan(json.pesan, false, json.errors);
                } else {
                    tampilkanPesan(json.pesan || 'Gagal memperbarui data buku', false);
                }
            } catch (err) {
                tampilkanPesan('Gagal menghubungi server API: ' + err.message, false);
            }
        });

        // 5. Fungsi Mengkonsumsi Endpoint DELETE /api/buku/{id}
        async function hapusViaApi(id, judul) {
            if (!confirm(`Apakah Anda yakin ingin menghapus buku "${judul}" via DELETE /api/buku/${id}?`)) {
                return;
            }

            try {
                const res = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                const json = await res.json();

                if (res.ok) {
                    tampilkanPesan(json.pesan, true);
                    muatDataBuku();
                } else {
                    tampilkanPesan(json.pesan || 'Gagal menghapus data buku', false);
                }
            } catch (err) {
                tampilkanPesan('Gagal menghapus buku: ' + err.message, false);
            }
        }

        // Helpers
        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>"']/g, function(m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }

        function escapeQuote(str) {
            if (!str) return '';
            return String(str).replace(/'/g, "\\'").replace(/"/g, '&quot;');
        }

        // Inisialisasi: Panggil fungsi GET saat halaman pertama kali dibuka
        document.addEventListener('DOMContentLoaded', muatDataBuku);
    </script>
</body>
</html>

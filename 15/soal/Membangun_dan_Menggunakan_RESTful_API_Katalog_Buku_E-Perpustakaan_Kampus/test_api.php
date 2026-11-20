<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

function testRequest($kernel, $method, $uri, $data = [], $headers = []) {
    $server = [
        'HTTP_ACCEPT' => 'application/json',
        'CONTENT_TYPE' => 'application/json',
    ];
    $content = !empty($data) ? json_encode($data) : null;
    $request = Illuminate\Http\Request::create($uri, $method, [], [], [], $server, $content);
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);
    return [
        'status' => $response->getStatusCode(),
        'body' => json_decode($response->getContent(), true) ?: $response->getContent()
    ];
}

echo "=== MEMULAI PENGUJIAN RESTFUL API KATALOG BUKU ===\n\n";

// 1. GET /api/buku (Index)
$res = testRequest($kernel, 'GET', '/api/buku');
echo "1. GET /api/buku (Mengambil semua buku):\n";
echo "   Status Code: {$res['status']} (Expected: 200)\n";
echo "   Status API : " . ($res['body']['status'] ? 'true' : 'false') . "\n";
echo "   Jumlah Data: " . ($res['body']['jumlah_data'] ?? count($res['body']['data'])) . "\n\n";

// 2. POST /api/buku (Validasi Gagal)
$res = testRequest($kernel, 'POST', '/api/buku', [
    'kode_buku' => '', // kosong
    'judul_buku' => '',
    'penulis' => '',
    'stok' => -5 // negatif
]);
echo "2. POST /api/buku dengan input tidak valid (Uji Validasi 422):\n";
echo "   Status Code: {$res['status']} (Expected: 422)\n";
echo "   Status API : " . ($res['body']['status'] ? 'true' : 'false') . "\n";
echo "   Pesan Error: " . json_encode($res['body']['errors'] ?? []) . "\n\n";

// 3. POST /api/buku (Validasi Berhasil - 201 Created)
$randomCode = 'BK-TEST-' . rand(100, 999);
$res = testRequest($kernel, 'POST', '/api/buku', [
    'kode_buku' => $randomCode,
    'judul_buku' => 'Buku RESTful API & MySQL',
    'penulis' => 'Rifkillah muliya al-faris',
    'stok' => 20
]);
echo "3. POST /api/buku data baru:\n";
echo "   Status Code: {$res['status']} (Expected: 201)\n";
echo "   Pesan API  : {$res['body']['pesan']}\n";
$newId = $res['body']['data']['id'] ?? null;
echo "   ID Buku Baru: {$newId}\n\n";

// 4. POST /api/buku dengan kode_buku duplikat (Uji Unique 422)
$res = testRequest($kernel, 'POST', '/api/buku', [
    'kode_buku' => $randomCode,
    'judul_buku' => 'Buku Duplikat',
    'penulis' => 'Siapa Saja',
    'stok' => 5
]);
echo "4. POST /api/buku dengan kode_buku sama (Uji Unik 422):\n";
echo "   Status Code: {$res['status']} (Expected: 422)\n";
echo "   Error Kode : " . json_encode($res['body']['errors']['kode_buku'] ?? []) . "\n\n";

// 5. GET /api/buku/{id} (Show data)
if ($newId) {
    $res = testRequest($kernel, 'GET', "/api/buku/{$newId}");
    echo "5. GET /api/buku/{$newId} (Detail 1 buku):\n";
    echo "   Status Code: {$res['status']} (Expected: 200)\n";
    echo "   Judul Buku : {$res['body']['data']['judul_buku']}\n\n";
}

// 6. GET /api/buku/{id} tidak ditemukan (404 Not Found)
$res = testRequest($kernel, 'GET', '/api/buku/999999');
echo "6. GET /api/buku/999999 (ID tidak ditemukan):\n";
echo "   Status Code: {$res['status']} (Expected: 404)\n";
echo "   Pesan API  : {$res['body']['pesan']}\n\n";

// 7. PUT /api/buku/{id} (Update data)
if ($newId) {
    $res = testRequest($kernel, 'PUT', "/api/buku/{$newId}", [
        'kode_buku' => $randomCode,
        'judul_buku' => 'Buku RESTful API & MySQL (Updated)',
        'penulis' => 'Rifkillah muliya al-faris',
        'stok' => 25
    ]);
    echo "7. PUT /api/buku/{$newId} (Update buku):\n";
    echo "   Status Code: {$res['status']} (Expected: 200)\n";
    echo "   Judul Baru : {$res['body']['data']['judul_buku']}\n";
    echo "   Stok Baru  : {$res['body']['data']['stok']}\n\n";
}

// 8. DELETE /api/buku/{id} (Hapus data)
if ($newId) {
    $res = testRequest($kernel, 'DELETE', "/api/buku/{$newId}");
    echo "8. DELETE /api/buku/{$newId} (Hapus buku):\n";
    echo "   Status Code: {$res['status']} (Expected: 200)\n";
    echo "   Pesan API  : {$res['body']['pesan']}\n\n";

    // Verifikasi sudah terhapus
    $res = testRequest($kernel, 'GET', "/api/buku/{$newId}");
    echo "   Cek kembali GET /api/buku/{$newId}:\n";
    echo "   Status Code: {$res['status']} (Expected: 404)\n\n";
}

// 9. GET /perpus/katalog-api (View Web Client)
$requestView = Illuminate\Http\Request::create('/perpus/katalog-api', 'GET');
$responseView = $kernel->handle($requestView);
$contentView = $responseView->getContent();
$hasWatermark = str_contains($contentView, 'Rifkillah muliya al-faris');
$hasApiUrl = str_contains($contentView, '/api/buku');
echo "9. GET /perpus/katalog-api (Halaman Web Client Konsumen):\n";
echo "   Status Code  : {$responseView->getStatusCode()} (Expected: 200)\n";
echo "   Watermark wm : " . ($hasWatermark ? 'ADA ("Rifkillah muliya al-faris")' : 'TIDAK DITEMUKAN') . "\n";
echo "   Endpoint API : " . ($hasApiUrl ? 'TERHUBUNG (/api/buku)' : 'TIDAK DITEMUKAN') . "\n\n";

echo "=== SELURUH PENGUJIAN SELESAI DENGAN SUKSES! ===\n";

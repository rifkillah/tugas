<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BukuApiController extends Controller
{
    // 1. GET /api/buku -> Mengambil seluruh koleksi buku
    public function index()
    {
        $bukus = Buku::orderBy('id', 'asc')->get();

        return response()->json([
            'status' => true,
            'pesan' => 'Daftar koleksi buku berhasil diambil',
            'jumlah_data' => $bukus->count(),
            'data' => $bukus
        ], 200);
    }

    // 2. POST /api/buku -> Menambahkan buku baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_buku' => 'required|string|max:50|unique:bukus,kode_buku',
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:150',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal! Periksa kembali data yang dikirim.',
                'errors' => $validator->errors()
            ], 422);
        }

        $buku = Buku::create($request->only(['kode_buku', 'judul_buku', 'penulis', 'stok']));

        return response()->json([
            'status' => true,
            'pesan' => 'Berhasil menambahkan buku baru ke katalog via POST /api/buku (201 Created)!',
            'data' => $buku
        ], 201);
    }

    // 3. GET /api/buku/{id} -> Menampilkan detail 1 buku
    public function show(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'status' => false,
                'pesan' => 'Data buku dengan ID tersebut tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'pesan' => 'Detail data buku berhasil ditemukan',
            'data' => $buku
        ], 200);
    }

    // 4. PUT /api/buku/{id} -> Memperbarui informasi buku
    public function update(Request $request, string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'status' => false,
                'pesan' => 'Data buku tidak ditemukan!'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'kode_buku' => 'required|string|max:50|unique:bukus,kode_buku,' . $id,
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:150',
            'stok' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'pesan' => 'Validasi gagal saat memperbarui data buku!',
                'errors' => $validator->errors()
            ], 422);
        }

        $buku->update($request->only(['kode_buku', 'judul_buku', 'penulis', 'stok']));

        return response()->json([
            'status' => true,
            'pesan' => 'Data buku berhasil diperbarui!',
            'data' => $buku
        ], 200);
    }

    // 5. DELETE /api/buku/{id} -> Menghapus data buku
    public function destroy(string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'status' => false,
                'pesan' => 'Data buku tidak ditemukan!'
            ], 404);
        }

        $buku->delete();

        return response()->json([
            'status' => true,
            'pesan' => 'Data buku berhasil dihapus dari katalog server API!',
            'data' => $buku
        ], 200);
    }
}

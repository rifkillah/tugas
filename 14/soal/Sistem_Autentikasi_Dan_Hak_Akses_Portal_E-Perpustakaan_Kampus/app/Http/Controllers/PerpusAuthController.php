<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerpusAuthController extends Controller
{
    /**
     * Menampilkan form pendaftaran
     */
    public function register()
    {
        return view('perpus_register');
    }

    /**
     * Menyimpan data pendaftaran akun baru
     */
    public function simpanRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:pustakawan,anggota',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/perpus/login')->with('sukses', 'Registrasi akun E-Perpustakaan berhasil! Silakan masuk.');
    }

    /**
     * Menampilkan form login
     */
    public function login()
    {
        return view('perpus_login');
    }

    /**
     * Memverifikasi kredensial login
     */
    public function cekLogin(Request $request)
    {
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect('/perpus/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kombinasi Email dan Password tidak ditemukan!',
        ])->onlyInput('email');
    }

    /**
     * Halaman Dashboard Utama E-Perpustakaan
     */
    public function dashboard()
    {
        return view('perpus_dashboard');
    }

    /**
     * Halaman Kelola Koleksi Buku (Khusus Pustakawan)
     */
    public function kelolaBuku()
    {
        return view('perpus_kelola_buku');
    }

    /**
     * Halaman Peminjaman Buku (Khusus Anggota)
     */
    public function pinjamBuku()
    {
        return view('perpus_pinjam_buku');
    }

    /**
     * Menghapus sesi login pengguna dan redirect ke login
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/perpus/login')->with('sukses', 'Kamu telah berhasil keluar (Logout) dari Portal E-Perpustakaan.');
    }
}

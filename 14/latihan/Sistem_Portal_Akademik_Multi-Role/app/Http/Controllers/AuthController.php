<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function tampilRegister()
    {
        return view('auth_register');
    }

    public function prosesRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,mahasiswa'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/login')->with('sukses', 'Registrasi berhasil! Silakan login dengan akun kamu.');
    }

    public function tampilLogin()
    {
        return view('auth_login');
    }

    public function prosesLogin(Request $request)
    {
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kombinasi Email dan Password tidak ditemukan!',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('auth_dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('sukses', 'Kamu telah berhasil keluar (Logout).');
    }
}

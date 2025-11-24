<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // LOGIKA REGISTRASI
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ], [
            'email.unique' => 'Email ini sudah terdaftar, silakan login.',
            'email.required' => 'Email wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // 2. Ambil Nama dari Email (Contoh: fahrezy@... -> fahrezy)
        $name = strstr($request->email, '@', true);

        // 3. Simpan ke Database
        $user = \App\Models\User::create([
            'name' => ucfirst($name), // Ubah huruf depan jadi kapital
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'mahasiswa'
        ]);

        // 4. Auto Login setelah daftar
        \Illuminate\Support\Facades\Auth::login($user);

        // 5. Kembali ke Home
        return redirect('/')->with('success', 'Akun berhasil dibuat! Halo, ' . ucfirst($name));
    }

    // LOGIKA LOGIN
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek Cocok tidak dengan Database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Sukses, balik ke Home
        }

        // 3. Jika Gagal, balik ke Home tapi bawa pesan Error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    // LOGIKA LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

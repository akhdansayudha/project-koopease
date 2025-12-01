<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Redirect ke Home & Buka Modal Login
    public function showLoginForm()
    {
        return redirect()->route('home')->with('open_auth_modal', 'login');
    }

    // Redirect ke Home & Buka Modal Register
    public function showRegisterForm()
    {
        return redirect()->route('home')->with('open_auth_modal', 'register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Simpan mode auth ke session agar modal tetap terbuka di tab login jika gagal
        if (!Auth::attempt($credentials, $request->remember)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput()
                ->with('open_auth_modal', 'login'); // Trigger modal lagi
        }

        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Hapus confirmed jika form tidak ada konfirmasi
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'mahasiswa',
        ]);

        Auth::login($user);

        return redirect(route('home'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('home')); // Logout kembali ke home publik
    }
}

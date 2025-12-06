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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                // Validasi Domain
                function ($attribute, $value, $fail) {
                    if (!preg_match('/@(student\.)?telkomuniversity\.ac\.id$/', $value)) {
                        $fail('Gunakan email resmi Telkom University.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[0-9]/',
            ],
            'terms' => 'required|accepted',
        ], [
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.regex' => 'Kata sandi harus mengandung minimal satu angka.',
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
        ]);

        // --- LOGIKA PENENTUAN ROLE OTOMATIS ---
        // Cek apakah email mengandung '@student.'
        $isStudent = str_contains($request->email, '@student.telkomuniversity.ac.id');
        $role = $isStudent ? 'mahasiswa' : 'pegawai'; // Jika bukan student, anggap pegawai/dosen

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role, // Gunakan variabel role yang sudah ditentukan
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

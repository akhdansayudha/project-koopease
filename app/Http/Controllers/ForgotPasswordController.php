<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Tambahkan ini
use App\Models\User;
use App\Mail\ResetPasswordOtp; // Tambahkan ini
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan Halaman Awal
    public function index()
    {
        return view('auth.forgot-password');
    }

    // 2. Kirim OTP (Generate & Kirim Email Asli)
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tersebut tidak terdaftar di sistem kami.'
        ]);

        $email = $request->email;
        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $otp,
            'created_at' => Carbon::now()
        ]);

        // --- PENGIRIMAN EMAIL (UPDATED) ---
        try {
            Mail::to($email)->send(new ResetPasswordOtp($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Pastikan koneksi internet stabil.']);
        }

        // Redirect kembali ke halaman forgot-password tapi dengan step 2 (Input OTP)
        // Kita tidak lagi me-return view 'simulate-email'
        return back()
            ->with('step', 2)
            ->with('email', $email)
            ->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    // 3. Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric'
        ]);

        $check = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$check) {
            return back()->with('step', 2)->with('email', $request->email)->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // Cek Kadaluarsa (Misal 5 menit)
        $createdAt = Carbon::parse($check->created_at);
        if ($createdAt->addMinutes(5)->isPast()) {
            return back()->with('step', 2)->with('email', $request->email)->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.']);
        }

        // Jika Sukses, lanjut ke step 3 (Input Password Baru)
        return back()
            ->with('step', 3)
            ->with('email', $request->email)
            ->with('otp_code', $request->otp)
            ->with('success', 'OTP valid! Silakan buat kata sandi baru.');
    }

    // 4. Reset Password Akhir
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'otp_code' => 'required'
        ]);

        // Verifikasi lagi untuk keamanan ganda
        $check = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp_code)
            ->first();

        if (!$check) {
            return back()->with('error', 'Token tidak valid atau sesi habis.');
        }

        // Update Password User
        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        // Hapus Token agar tidak bisa dipakai lagi
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Redirect ke Home dengan modal login terbuka
        return redirect()->route('home')
            ->with('success', 'Kata sandi berhasil diubah! Silakan login.')
            ->with('open_login_modal', true);
        // Pastikan di home.blade.php Anda menangkap session 'open_login_modal' 
        // untuk membuka popup login otomatis (optional)
    }
}

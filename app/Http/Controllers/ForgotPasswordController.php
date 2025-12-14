<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // 1. Tampilkan Halaman Awal
    public function index()
    {
        return view('auth.forgot-password');
    }

    // 2. Kirim OTP (Generate & Simpan)
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
        // PERBAIKAN DISINI: Gunakan toDateTimeString() agar formatnya 'YYYY-MM-DD HH:MM:SS'
        // tanpa embel-embel timezone, sehingga cocok dengan tipe data TIMESTAMP di PostgreSQL
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $otp,
            'created_at' => Carbon::now()->toDateTimeString()
        ]);

        return back()
            ->with('step', 2)
            ->with('email', $email)
            ->with('success', "Kode OTP telah dikirimkan ke email $email");
    }

    // 3. Simulasi Buka Email
    public function simulateEmail($email)
    {
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        return view('auth.simulate-email', compact('tokenData', 'email'));
    }

    // 4. Verifikasi OTP
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

        // Parse waktu dari database
        // Karena di database sudah string 'Y-m-d H:i:s', Carbon akan memparsingnya sesuai config timezone aplikasi
        $createdAt = Carbon::parse($check->created_at);

        if ($createdAt->addMinutes(2)->isPast()) {
            return back()->with('step', 2)->with('email', $request->email)->withErrors(['otp' => 'Kode OTP sudah kadaluarsa (Maks. 2 menit). Silakan kirim ulang.']);
        }

        return back()
            ->with('step', 3)
            ->with('email', $request->email)
            ->with('otp_code', $request->otp)
            ->with('success', 'OTP valid! Silakan buat kata sandi baru.');
    }

    // 5. Reset Password Akhir
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'otp_code' => 'required'
        ]);

        $check = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp_code)
            ->first();

        if (!$check) {
            return back()->with('error', 'Token tidak valid.');
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('home')->with('open_auth_modal', 'login')->with('success', 'Kata sandi berhasil diubah! Silakan login.');
    }
}

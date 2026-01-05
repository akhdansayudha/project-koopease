<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }

        .rounded-bento {
            border-radius: 32px;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-lg bg-white rounded-bento shadow-2xl border border-gray-100 overflow-hidden relative">

        {{-- Header Decorative --}}
        <div class="h-32 bg-blue-600 relative overflow-hidden flex items-center justify-center">
            <div
                class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl transform translate-x-10 -translate-y-10">
            </div>
            <div
                class="absolute bottom-0 left-0 w-24 h-24 bg-purple-500/20 rounded-full blur-xl transform -translate-x-5 translate-y-5">
            </div>

            <div class="text-center relative z-10 text-white">
                <div
                    class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-2 border border-white/30">
                    <i class="bi bi-shield-lock-fill text-2xl"></i>
                </div>
                <h1 class="text-xl font-bold">Pemulihan Akun</h1>
            </div>
        </div>

        <div class="p-8">
            {{-- Tombol Kembali --}}
            <a href="{{ route('home') }}"
                class="absolute top-4 left-4 text-white/80 hover:text-white transition z-20 flex items-center gap-1 text-xs font-bold bg-black/10 px-3 py-1.5 rounded-full backdrop-blur-sm hover:bg-black/20">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 flex items-start gap-3 shadow-sm">
                    <i class="bi bi-check-circle-fill text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold text-sm">Berhasil!</p>
                        <p class="text-xs leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if ($errors->any())
                <div
                    class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 flex items-start gap-3 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold text-sm">Terjadi Kesalahan</p>
                        <ul class="text-xs list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- LOGIKA TAMPILAN BERDASARKAN STEP --}}
            @php $step = session('step', 1); @endphp

            {{-- STEP 1: INPUT EMAIL --}}
            @if ($step == 1)
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900">Lupa Kata Sandi?</h2>
                    <p class="text-gray-500 text-sm mt-2">Masukkan email yang terdaftar, kami akan mengirimkan kode OTP
                        untuk Anda.</p>
                </div>

                <form action="{{ route('forgot.sendOtp') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 ml-1">Email Kampus</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" required
                                placeholder="nama@student.telkomuniversity.ac.id"
                                class="w-full h-12 pl-10 pr-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02]">
                        Kirim Kode OTP
                    </button>
                </form>

                {{-- STEP 2: INPUT OTP --}}
            @elseif($step == 2)
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900">Verifikasi OTP</h2>
                    <p class="text-gray-500 text-sm mt-2">
                        Silakan buka email Anda untuk cek kode OTP yang dikirimkan. <br>
                    </p>
                </div>

                <form action="{{ route('forgot.verifyOtp') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 ml-1">Kode OTP (6 Digit)</label>
                        <input type="text" name="otp" required placeholder="123456" maxlength="6"
                            class="w-full h-12 px-4 text-center tracking-[0.5em] text-lg font-mono bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition font-bold">
                        <p class="text-[10px] text-gray-400 mt-2 text-center">Kode hanya berlaku selama 5 menit.</p>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02]">
                        Verifikasi Kode
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <form action="{{ route('forgot.sendOtp') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        <button type="submit"
                            class="text-xs text-gray-500 hover:text-blue-600 font-bold transition">Kirim Ulang
                            Kode</button>
                    </form>
                </div>

                {{-- STEP 3: RESET PASSWORD BARU --}}
            @elseif($step == 3)
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-extrabold text-gray-900">Buat Sandi Baru</h2>
                    <p class="text-gray-500 text-sm mt-2">Silakan masukkan kata sandi baru untuk akun
                        <strong>{{ session('email') }}</strong></p>
                </div>

                <form action="{{ route('forgot.reset') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <input type="hidden" name="otp_code" value="{{ session('otp_code') }}">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 ml-1">Kata Sandi Baru</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                            class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2 ml-1">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi kata sandi"
                            class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium">
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-200 transition transform hover:scale-[1.02]">
                        Ubah Kata Sandi
                    </button>
                </form>
            @endif

        </div>
    </div>

</body>

</html>

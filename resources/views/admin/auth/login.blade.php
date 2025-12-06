<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <!-- Main Container -->
    <div
        class="w-full max-w-5xl bg-white rounded-[40px] shadow-2xl overflow-hidden border border-gray-100 flex flex-col md:flex-row min-h-[600px]">

        <!-- Left Side: Visual / Brand -->
        <div class="hidden md:flex md:w-1/2 bg-gray-900 relative flex-col justify-between p-12 text-white">
            <div class="absolute inset-0 opacity-20">
                <!-- Abstract Pattern -->
                <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="#3b82f6" />
                </svg>
            </div>

            <div class="relative z-10">
                <div
                    class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-900/50">
                    <i class="bi bi-shop text-2xl"></i>
                </div>
                <h2 class="text-4xl font-extrabold tracking-tight leading-tight">KoopEase<br>Admin Panel</h2>
                <p class="mt-4 text-gray-400 font-medium text-lg">Kelola operasional koperasi mahasiswa dengan mudah,
                    cepat, dan efisien.</p>
            </div>

            <div class="relative z-10">
                <div
                    class="flex items-center gap-4 bg-gray-800/50 backdrop-blur-sm p-4 rounded-2xl border border-gray-700">
                    <div
                        class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Keamanan</p>
                        <p class="text-sm font-semibold">Area Terbatas Administrator</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full md:w-1/2 p-8 md:p-16 flex flex-col justify-center bg-white relative">

            <!-- Mobile Logo (Visible only on small screens) -->
            <div class="md:hidden mb-8 flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i class="bi bi-shop text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold text-gray-900">KoopEase</span>
            </div>

            <div class="mb-8">
                <h3 class="text-2xl font-extrabold text-gray-900">Selamat Datang Kembali!</h3>
                <p class="text-gray-500 mt-2">Silakan masuk untuk mengakses dashboard.</p>
            </div>

            <!-- Error Alert -->
            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-100 rounded-2xl p-4 flex gap-3 items-start"
                    x-data="{ show: true }" x-show="show">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mt-0.5"></i>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-red-800">Akses Ditolak</h4>
                        <p class="text-xs text-red-600 mt-1 leading-relaxed">{{ session('error') }}</p>
                        <a href="{{ url('/') }}"
                            class="text-xs font-bold text-red-700 underline mt-2 inline-block hover:text-red-900">
                            Masuk lewat Halaman Utama &rarr;
                        </a>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-600"><i
                            class="bi bi-x-lg"></i></button>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide ml-1">Email
                        Administrator</label>
                    <div class="relative">
                        <input type="email" name="email" required autofocus value="{{ old('email') }}"
                            class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-blue-500 rounded-2xl outline-none transition-all font-semibold text-gray-800 placeholder-gray-400 @error('email') !border-red-500 !bg-red-50 @enderror"
                            placeholder="nama@admin.com">
                        <div
                            class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none text-gray-400">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="space-y-2" x-data="{ showPass: false }">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wide ml-1">Password</label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required
                            class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:bg-white focus:border-blue-500 rounded-2xl outline-none transition-all font-semibold text-gray-800 placeholder-gray-400"
                            placeholder="••••••••">
                        <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-blue-600 transition-colors cursor-pointer">
                            <i class="bi" :class="showPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember"
                            class="w-5 h-5 rounded-lg border-gray-300 text-blue-600 focus:ring-blue-500 transition cursor-pointer">
                        <span class="text-sm font-semibold text-gray-500 group-hover:text-blue-600 transition">Ingat
                            Saya</span>
                    </label>
                    <a href="#" class="text-sm font-bold text-blue-600 hover:underline hover:text-blue-700">Lupa
                        Password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 bg-gray-900 hover:bg-black text-white rounded-2xl font-bold text-lg shadow-xl shadow-gray-200 hover:shadow-2xl hover:shadow-gray-300 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3">
                    <span>Masuk ke Dashboard</span>
                    <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-gray-600 transition">
                    <i class="bi bi-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

</body>

</html>

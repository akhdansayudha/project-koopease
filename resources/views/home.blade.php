<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KoopEase - Koperasi Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }

        /* Custom Utilities */
        .rounded-bento {
            border-radius: 24px;
        }

        .card-hoverable {
            transition: all 0.2s ease;
        }

        .card-hoverable:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    authOpen: {{ $errors->any() ? 'true' : 'false' }},
    authMode: '{{ $errors->has('email') && old('authMode') == 'login' ? 'login' : ($errors->any() ? 'register' : 'login') }}'
}">

    <nav class="w-full h-20 bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100">

        <div class="max-w-7xl mx-auto px-6 md:px-8 h-full flex items-center justify-between">

            <div class="flex items-center gap-2">
                <span class="text-2xl font-bold text-gray-900">KoopEase</span>
            </div>

            <div class="hidden md:flex flex-1 max-w-xl mx-8 relative">
                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">🔍</span>
                <input type="text" placeholder="Mau beli apa?"
                    class="w-full h-12 pl-12 pr-4 bg-gray-50 rounded-full text-sm outline-none focus:ring-2 focus:ring-blue-100 transition border border-transparent focus:border-blue-200">
            </div>

            <div class="flex items-center gap-4">

                <a href="#"
                    class="flex items-center gap-2 px-5 py-2.5 bg-gray-50 rounded-full hover:bg-gray-100 transition text-sm font-bold text-gray-700">
                    <span>🛍️</span>
                    <span class="hidden md:inline">Keranjang</span>
                </a>

                @guest
                    <button @click="authOpen = true"
                        class="text-sm font-bold text-gray-700 hover:text-blue-600 px-2 transition">
                        Masuk
                    </button>
                @endguest

                @auth
                    <div x-data="{ dropdownOpen: false }" class="relative">

                        <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false"
                            class="flex items-center gap-2 pl-4 pr-2 py-1.5 bg-white border border-gray-200 rounded-full hover:shadow-md transition cursor-pointer">

                            <span class="text-sm font-bold text-gray-700">
                                Hai, {{ ucfirst(Auth::user()->name) }} </span>

                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }} </div>
                        </button>

                        <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100" style="display: none;"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">

                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-xs text-gray-400">Login sebagai</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">📦 Pesanan
                                Saya</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">⚙️
                                Pengaturan</a>

                            <form action="{{ route('logout') }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

            </div>

        </div>
    </nav>

    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-8">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hei, TelUtizen! 👋</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-auto lg:h-[400px]">

            <div
                class="lg:col-span-8 rounded-bento bg-blue-600 relative overflow-hidden p-10 flex flex-col justify-center text-white shadow-sm group">
                <div
                    class="absolute right-0 bottom-0 w-64 h-64 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 transform translate-y-1/2 translate-x-1/2">
                </div>

                <div class="relative z-10 max-w-lg">
                    <span
                        class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold mb-4 inline-block text-white">PROMO
                        UJIAN</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight">Diskon 50%</h2>
                    <p class="text-lg opacity-90 mb-8 font-medium">Khusus pembelian paket alat tulis (Pensil + Penghapus
                        + Penggaris).</p>
                    <button
                        class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-50 transition transform hover:scale-105">
                        Cek Sekarang
                    </button>
                </div>
            </div>

            <div class="lg:col-span-4 grid grid-cols-2 gap-4">
                <div
                    class="bg-white rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border border-gray-100">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-xl mb-2">🍔
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-sm">Snack &<br>Makanan</span>
                        <span class="text-gray-400 text-xs">➜</span>
                    </div>
                </div>
                <div
                    class="bg-white rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border border-gray-100">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-xl mb-2">🥤
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-sm">Minuman<br>Dingin</span>
                        <span class="text-gray-400 text-xs">➜</span>
                    </div>
                </div>
                <div
                    class="bg-white rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border border-gray-100">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-xl mb-2">✏️
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-sm">Alat Tulis<br>(ATK)</span>
                        <span class="text-gray-400 text-xs">➜</span>
                    </div>
                </div>
                <div
                    class="bg-white rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border border-gray-100">
                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-xl mb-2">🎓
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-sm">Merch<br>Kampus</span>
                        <span class="text-gray-400 text-xs">➜</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Produk Terpopuler 🔥</h3>
                <a href="#" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">

                @foreach ($products as $p)
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 card-hoverable flex flex-col h-full">
                        <div class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden relative group-img">
                            <img src="{{ $p->gambar_url }}" alt="{{ $p->nama_produk }}"
                                class="w-full h-full object-cover">
                        </div>

                        <div class="flex-1 flex flex-col">
                            <span class="text-xs text-gray-400 mb-1">Stok: {{ $p->stok }}</span>

                            <h4 class="font-bold text-gray-900 text-sm mb-2 line-clamp-2">{{ $p->nama_produk }}</h4>

                            <div class="mt-auto flex justify-between items-center">
                                <span class="font-bold text-blue-600 text-base">Rp
                                    {{ number_format($p->harga, 0, ',', '.') }}</span>

                                <button
                                    class="w-8 h-8 rounded-full bg-gray-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="h-20"></div>
        </div>

        <div x-show="authOpen" style="display: none;" class="fixed inset-0 z-[60] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <div x-show="authOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="authOpen = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="authOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">

                    <button @click="authOpen = false"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-50 rounded-full p-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div class="px-8 py-8">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900"
                                x-text="authMode === 'login' ? 'Selamat Datang! 👋' : 'Gabung KoopEase 🚀'"></h3>
                            <p class="text-sm text-gray-500 mt-2">Koperasi mahasiswa dalam genggaman.</p>
                        </div>

                        <div class="bg-gray-100 p-1 rounded-full flex mb-6 relative">
                            <div class="absolute top-1 bottom-1 w-[48%] bg-white rounded-full shadow-sm transition-all duration-300 ease-in-out"
                                :class="authMode === 'login' ? 'left-1' : 'left-[51%]'"></div>

                            <button @click="authMode = 'login'"
                                class="flex-1 py-2 text-sm font-bold rounded-full relative z-10 transition-colors"
                                :class="authMode === 'login' ? 'text-gray-900' : 'text-gray-500'">
                                Masuk
                            </button>
                            <button @click="authMode = 'register'"
                                class="flex-1 py-2 text-sm font-bold rounded-full relative z-10 transition-colors"
                                :class="authMode === 'register' ? 'text-gray-900' : 'text-gray-500'">
                                Daftar
                            </button>
                        </div>

                        <form action="{{ route('register') }}" method="POST" x-show="authMode === 'register'">
                            @csrf
                            <input type="hidden" name="authMode" value="register">

                            @if ($errors->any())
                                <div
                                    class="mb-4 p-3 bg-red-50 text-red-600 text-xs rounded-xl font-bold border border-red-100">
                                    @foreach ($errors->all() as $error)
                                        • {{ $error }}<br>
                                    @endforeach
                                </div>
                            @endif

                            <div class="space-y-4">

                                <div
                                    class="bg-blue-50 text-blue-700 px-4 py-3 rounded-xl text-xs flex gap-2 items-start border border-blue-100">
                                    <span class="text-sm">ℹ️</span>
                                    <span class="leading-relaxed">Gunakan email kampus aktif untuk verifikasi otomatis
                                        mahasiswa.</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1 ml-1">Email Kampus</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        placeholder="nama@student.telkomuniversity.ac.id"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-200 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1 ml-1">Buat Kata
                                        Sandi</label>
                                    <input type="password" name="password" required placeholder="••••••••"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-200 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm">
                                </div>

                                <button type="submit"
                                    class="w-full h-12 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg transition transform hover:scale-[1.02]">
                                    Buat Akun Baru
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

</body>

</html>

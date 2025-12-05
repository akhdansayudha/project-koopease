<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    authOpen: false,
    toastOpen: {{ session('success') ? 'true' : 'false' }}
}" x-init="if (toastOpen) setTimeout(() => toastOpen = false, 4000)">

    @include('components.navbar')

    <div x-show="toastOpen" x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[100] w-[90%] max-w-sm" style="display: none;">

        <div
            class="bg-gray-900/90 backdrop-blur-md text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-gray-700/50">
            <div class="bg-green-500 rounded-full p-1">
                <i class="bi bi-check text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-xs font-bold text-gray-200">Berhasil</h4>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto p-6 md:p-8">

        <div class="mb-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold text-sm transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <div
                class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl shadow-sm">
                👤
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Profil Saya</h1>
                <p class="text-gray-500 text-sm font-medium">Kelola informasi identitas Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm text-center relative overflow-hidden group hover:shadow-md transition duration-300">

                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-blue-500 to-blue-700"></div>
                    <div
                        class="absolute top-0 right-0 w-full h-32 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20">
                    </div>

                    <div
                        class="relative mx-auto w-32 h-32 bg-white rounded-full p-1.5 mb-4 shadow-md mt-12 ring-4 ring-blue-50">
                        <div
                            class="w-full h-full bg-gradient-to-b from-blue-50 to-blue-100 rounded-full flex items-center justify-center text-5xl font-extrabold text-blue-600">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div
                            class="absolute bottom-1 right-1 bg-gray-900 text-white p-2 rounded-full cursor-pointer hover:bg-blue-600 transition shadow-lg border-2 border-white">
                            <i class="bi bi-camera-fill text-xs block"></i>
                        </div>
                    </div>

                    <h2 class="text-2xl font-extrabold text-gray-900 mb-1">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm mb-8 font-medium">{{ $user->email }}</p>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 p-3 rounded-2xl border border-blue-100 flex flex-col items-center">
                            <span
                                class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Status</span>
                            <div class="flex items-center gap-1 text-blue-700 font-bold">
                                <i class="bi bi-mortarboard-fill text-sm"></i>
                                <span>{{ ucfirst($user->role) }}</span>
                            </div>
                        </div>
                        <div class="bg-green-50 p-3 rounded-2xl border border-green-100 flex flex-col items-center">
                            <span
                                class="text-[10px] font-bold text-green-500 uppercase tracking-wider mb-1">Bergabung</span>
                            <div class="flex items-center gap-1 text-green-700 font-bold">
                                <i class="bi bi-calendar-check text-sm"></i>
                                <span class="text-sm">{{ $user->created_at->translatedFormat('d F Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-pencil-square text-blue-600"></i> Edit Informasi
                    </h3>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 gap-6">

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative group">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-blue-500 transition">
                                        <i class="bi bi-person-fill"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition outline-none font-bold text-gray-800">
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Email Kampus</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                        <i class="bi bi-envelope-fill"></i>
                                    </span>
                                    <input type="email" value="{{ $user->email }}" disabled
                                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-gray-500 cursor-not-allowed font-bold">
                                </div>
                                <p class="text-xs text-gray-400 mt-2 ml-1 flex items-center gap-1">
                                    <i class="bi bi-info-circle-fill text-blue-400"></i>
                                    Email tidak dapat diubah karena terhubung dengan akun kampus.
                                </p>
                            </div>

                            <div class="flex justify-end mt-4">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02] flex items-center gap-2">
                                    <i class="bi bi-check-lg"></i> Simpan Perubahan
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    @include('components.footer')

</body>

</html>

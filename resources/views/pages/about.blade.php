<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
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

        .card-hoverable {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hoverable:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Custom Text Gradient */
        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #4f46e5, #9333ea);
        }
    </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-10 flex-grow w-full">

        {{-- 1. Hero Section --}}
        <div
            class="relative rounded-bento bg-white p-8 md:p-16 text-center border border-gray-100 overflow-hidden shadow-sm">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500">
            </div>
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-100 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-100 rounded-full blur-3xl opacity-60"></div>

            <div class="relative z-10 max-w-3xl mx-auto space-y-6">
                <span
                    class="inline-block py-1 px-3 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider border border-indigo-100">
                    Koperasi Digital Masa Depan
                </span>
                <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Belanja Kebutuhan Kuliah, <br>
                    <span class="text-gradient">Tanpa Antre, Tanpa Ribet.</span>
                </h1>
                <p class="text-lg text-gray-500 leading-relaxed">
                    KoopEase hadir mengubah cara mahasiswa berinteraksi dengan koperasi.
                    Kami menggabungkan kenyamanan teknologi modern dengan semangat ekonomi kerakyatan kampus.
                </p>
            </div>
        </div>

        {{-- 2. Value Proposition (Bento Grid) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Card: Efficiency --}}
            <div
                class="bg-indigo-600 rounded-bento p-8 text-white card-hoverable flex flex-col justify-between relative overflow-hidden group">
                <div
                    class="absolute right-0 top-0 opacity-10 transform translate-x-1/4 -translate-y-1/4 group-hover:scale-110 transition duration-500">
                    <i class="bi bi-lightning-charge-fill text-9xl"></i>
                </div>
                <div class="relative z-10">
                    <div
                        class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6">
                        <i class="bi bi-lightning-charge-fill text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Efisiensi Waktu</h3>
                    <p class="text-indigo-100 text-sm leading-relaxed">
                        Lupakan antrean panjang di jam istirahat. Pilih barang di kelas, bayar, dan ambil saat pesanan
                        siap.
                    </p>
                </div>
            </div>

            {{-- Card: Transparency --}}
            <div
                class="bg-white rounded-bento p-8 border border-gray-100 card-hoverable flex flex-col justify-between relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mb-6">
                        <i class="bi bi-search text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Transparansi Stok</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Cek ketersediaan alat tulis, snack, atau merchandise secara <em>real-time</em> langsung dari
                        smartphone Anda.
                    </p>
                </div>
            </div>

            {{-- Card: Community --}}
            <div
                class="bg-gray-900 rounded-bento p-8 text-white card-hoverable flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute right-0 bottom-0 opacity-20 transform translate-y-1/4">
                    <i class="bi bi-people-fill text-8xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="w-12 h-12 bg-gray-700 rounded-2xl flex items-center justify-center mb-6">
                        <i class="bi bi-heart-fill text-2xl text-pink-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Dari Mahasiswa</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Platform ini dibangun, dikelola, dan dikembangkan oleh mahasiswa untuk kesejahteraan civitas
                        akademika.
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. Meet The Team Section --}}
        <div class="py-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Meet the Minds 🧠</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Di balik KoopEase, terdapat tim berdedikasi dari <strong>Kelompok 3 PPL IS-06-03</strong> yang
                    bekerja keras menghadirkan solusi terbaik.
                </p>
            </div>

            {{-- Team Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">

                {{-- 1. Product Owner --}}
                <div class="bg-white p-6 rounded-[32px] border border-gray-100 card-hoverable text-center group">
                    <div
                        class="w-24 h-24 mx-auto mb-4 rounded-full p-1 bg-gradient-to-br from-orange-400 to-red-500 group-hover:scale-105 transition duration-300">
                        <img src="https://ui-avatars.com/api/?name=Akhdan+Sayudha&background=fff&color=333&size=128"
                            class="w-full h-full rounded-full object-cover border-2 border-white">
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-1">Akhdan Sayudha L.</h4>
                    <span
                        class="inline-block px-3 py-1 bg-orange-100 text-orange-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Product Owner
                    </span>
                </div>

                {{-- 2. Scrum Master --}}
                <div class="bg-white p-6 rounded-[32px] border border-gray-100 card-hoverable text-center group">
                    <div
                        class="w-24 h-24 mx-auto mb-4 rounded-full p-1 bg-gradient-to-br from-green-400 to-emerald-600 group-hover:scale-105 transition duration-300">
                        <img src="https://ui-avatars.com/api/?name=Dyah+Lutfi&background=fff&color=333&size=128"
                            class="w-full h-full rounded-full object-cover border-2 border-white">
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-1">Dyah Lutfi Atviana</h4>
                    <span
                        class="inline-block px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Scrum Master
                    </span>
                </div>

                {{-- 3. Developer 1 --}}
                <div class="bg-white p-6 rounded-[32px] border border-gray-100 card-hoverable text-center group">
                    <div
                        class="w-24 h-24 mx-auto mb-4 rounded-full p-1 bg-gradient-to-br from-blue-400 to-indigo-600 group-hover:scale-105 transition duration-300">
                        <img src="https://ui-avatars.com/api/?name=Naufal+Fahrezy&background=fff&color=333&size=128"
                            class="w-full h-full rounded-full object-cover border-2 border-white">
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-1">M. Naufal Fahrezy</h4>
                    <span
                        class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Developer 1
                    </span>
                </div>

                {{-- 4. Developer 2 --}}
                <div class="bg-white p-6 rounded-[32px] border border-gray-100 card-hoverable text-center group">
                    <div
                        class="w-24 h-24 mx-auto mb-4 rounded-full p-1 bg-gradient-to-br from-blue-400 to-indigo-600 group-hover:scale-105 transition duration-300">
                        <img src="https://ui-avatars.com/api/?name=Jihan+Natasya&background=fff&color=333&size=128"
                            class="w-full h-full rounded-full object-cover border-2 border-white">
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-1">Jihan Natasya R.</h4>
                    <span
                        class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Developer 2
                    </span>
                </div>

                {{-- 5. Developer 3 --}}
                <div class="bg-white p-6 rounded-[32px] border border-gray-100 card-hoverable text-center group">
                    <div
                        class="w-24 h-24 mx-auto mb-4 rounded-full p-1 bg-gradient-to-br from-blue-400 to-indigo-600 group-hover:scale-105 transition duration-300">
                        <img src="https://ui-avatars.com/api/?name=Salsabilla+Chandra&background=fff&color=333&size=128"
                            class="w-full h-full rounded-full object-cover border-2 border-white">
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-1">Salsabilla Chandra</h4>
                    <span
                        class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Developer 3
                    </span>
                </div>

            </div>
        </div>

        {{-- 4. Tech Stack Badge (Optional aesthetic touch) --}}
        <div class="border-t border-gray-100 pt-8 pb-4">
            <p class="text-center text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Powered by Modern
                Technology</p>
            <div
                class="flex flex-wrap justify-center gap-6 opacity-60 grayscale hover:grayscale-0 transition duration-500">
                <div class="flex items-center gap-2">
                    <i class="bi bi-code-slash text-xl text-red-500"></i> <span class="font-bold text-sm">Laravel
                        10</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-wind text-xl text-cyan-500"></i> <span class="font-bold text-sm">Tailwind CSS</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-braces text-xl text-blue-400"></i> <span class="font-bold text-sm">Alpine.js</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="bi bi-database text-xl text-indigo-500"></i> <span class="font-bold text-sm">MySQL</span>
                </div>
            </div>
        </div>

        {{-- 5. CTA --}}
        <div class="rounded-bento bg-gray-900 p-8 md:p-12 text-center relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Siap untuk Pengalaman Belanja Baru?</h2>
                <p class="text-gray-400 mb-8 max-w-xl mx-auto">Bergabunglah dengan ekosistem KoopEase dan nikmati
                    kemudahan transaksi di kampus.</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3 px-8 rounded-full transition transform hover:scale-105 shadow-lg shadow-indigo-900/50">
                    <i class="bi bi-cart-check"></i> Mulai Belanja Sekarang
                </a>
            </div>
            {{-- Abstract background effect --}}
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/50 to-purple-900/50"></div>
        </div>

    </div>

    @include('components.footer')

</body>

</html>

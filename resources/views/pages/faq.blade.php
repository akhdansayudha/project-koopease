<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan (FAQ) - KoopEase</title>
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
            border-radius: 24px;
        }

        /* Smooth Scroll behavior */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }
    </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen" x-data="{ activeCategory: 'umum', activeQuestion: null }">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-8 flex-grow w-full">

        {{-- Header Section (Cyan Theme) --}}
        <div class="rounded-bento p-8 md:p-12 relative overflow-hidden bg-cyan-600 text-white shadow-lg">
            {{-- Decorative Blobs --}}
            <div
                class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl opacity-50 transform translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute left-0 bottom-0 w-48 h-48 bg-teal-400/30 rounded-full mix-blend-overlay filter blur-2xl opacity-50 transform -translate-x-1/2 translate-y-1/2">
            </div>

            <div class="relative z-10 text-center md:text-left flex flex-col md:flex-row items-center gap-6">
                <div
                    class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center border border-white/30 shadow-inner transform -rotate-3 hover:rotate-0 transition duration-500">
                    <i class="bi bi-question-circle-fill text-4xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Pusat Bantuan</h1>
                    <p class="text-cyan-50 max-w-2xl text-lg leading-relaxed">
                        Temukan jawaban atas pertanyaan umum seputar pemesanan, pembayaran, dan layanan KoopEase di
                        sini.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Sidebar Navigasi Kategori (Sticky) --}}
            <div class="lg:col-span-4 hidden lg:block sticky top-28">
                <div class="bg-white rounded-bento p-6 shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-2">Kategori Topik</h3>

                    <nav class="space-y-2">
                        <button @click="activeCategory = 'umum'; window.location.hash = '#umum'"
                            :class="activeCategory === 'umum' ? 'bg-cyan-50 text-cyan-700 border-cyan-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="w-full text-left px-4 py-3 rounded-xl border text-sm font-bold transition flex items-center gap-3">
                            <i class="bi bi-info-circle text-lg"></i>
                            Umum & Akun
                        </button>

                        <button @click="activeCategory = 'pesanan'; window.location.hash = '#pesanan'"
                            :class="activeCategory === 'pesanan' ? 'bg-cyan-50 text-cyan-700 border-cyan-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="w-full text-left px-4 py-3 rounded-xl border text-sm font-bold transition flex items-center gap-3">
                            <i class="bi bi-cart3 text-lg"></i>
                            Pemesanan
                        </button>

                        <button @click="activeCategory = 'pembayaran'; window.location.hash = '#pembayaran'"
                            :class="activeCategory === 'pembayaran' ? 'bg-cyan-50 text-cyan-700 border-cyan-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="w-full text-left px-4 py-3 rounded-xl border text-sm font-bold transition flex items-center gap-3">
                            <i class="bi bi-wallet2 text-lg"></i>
                            Pembayaran
                        </button>

                        <button @click="activeCategory = 'pengambilan'; window.location.hash = '#pengambilan'"
                            :class="activeCategory === 'pengambilan' ? 'bg-cyan-50 text-cyan-700 border-cyan-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="w-full text-left px-4 py-3 rounded-xl border text-sm font-bold transition flex items-center gap-3">
                            <i class="bi bi-box-seam text-lg"></i>
                            Pengambilan Barang
                        </button>
                    </nav>

                    {{-- Quick Contact Card (UPDATED TO EMAIL) --}}
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-3 text-center">Masih butuh bantuan?</p>
                        <a href="mailto:projectkoopease@gmail.com"
                            class="flex items-center justify-center gap-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-md shadow-blue-200">
                            <i class="bi bi-envelope-fill"></i> Hubungi via Email
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Content (Accordions) --}}
            <div class="lg:col-span-8 space-y-10">

                {{-- Mobile Category Menu (Scrollable) --}}
                <div class="lg:hidden flex gap-2 overflow-x-auto pb-2 scrollbar-hide snap-x">
                    <button @click="activeCategory = 'umum'"
                        :class="activeCategory === 'umum' ? 'bg-cyan-600 text-white shadow-md' :
                            'bg-white text-gray-600 border border-gray-200'"
                        class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap snap-center transition">Umum</button>
                    <button @click="activeCategory = 'pesanan'"
                        :class="activeCategory === 'pesanan' ? 'bg-cyan-600 text-white shadow-md' :
                            'bg-white text-gray-600 border border-gray-200'"
                        class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap snap-center transition">Pemesanan</button>
                    <button @click="activeCategory = 'pembayaran'"
                        :class="activeCategory === 'pembayaran' ? 'bg-cyan-600 text-white shadow-md' :
                            'bg-white text-gray-600 border border-gray-200'"
                        class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap snap-center transition">Pembayaran</button>
                    <button @click="activeCategory = 'pengambilan'"
                        :class="activeCategory === 'pengambilan' ? 'bg-cyan-600 text-white shadow-md' :
                            'bg-white text-gray-600 border border-gray-200'"
                        class="px-5 py-2 rounded-full text-sm font-bold whitespace-nowrap snap-center transition">Pengambilan</button>
                </div>

                {{-- SECTION 1: UMUM --}}
                <div id="umum" x-show="activeCategory === 'umum'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                            <i class="bi bi-info-circle-fill text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Umum & Akun</h2>
                    </div>

                    <div class="space-y-4">
                        {{-- Q1 --}}
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 1 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 1 ? null : 1)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Apa itu KoopEase?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 1 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 1" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    KoopEase adalah platform digital Koperasi Mahasiswa yang memudahkan civitas
                                    akademika untuk memesan kebutuhan harian, alat tulis, makanan, dan merchandise
                                    kampus secara online. Pesan dari mana saja, ambil di toko tanpa antre.
                                </p>
                            </div>
                        </div>

                        {{-- Q2 --}}
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 2 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 2 ? null : 2)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Siapa saja yang bisa mendaftar?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 2 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 2" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    Layanan ini dikhususkan untuk <strong>Mahasiswa Aktif, Dosen, dan Staf
                                        Universitas</strong>. Saat pendaftaran, Anda mungkin diminta untuk memverifikasi
                                    identitas menggunakan NIM atau Email Institusi.
                                </p>
                            </div>
                        </div>

                        {{-- Q3 --}}
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 3 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 3 ? null : 3)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Saya lupa password, bagaimana cara reset?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 3 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 3" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    Saat ini fitur reset password otomatis sedang dalam pengembangan. Silakan hubungi
                                    admin melalui WhatsApp dengan menyertakan bukti kepemilikan akun (Foto KTM) untuk
                                    bantuan reset password manual.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PEMESANAN --}}
                <div id="pesanan" x-show="activeCategory === 'pesanan'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                            <i class="bi bi-cart-fill text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Pemesanan</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 4 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 4 ? null : 4)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Bagaimana cara melakukan pemesanan?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 4 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 4" x-collapse class="px-5 pb-5 pt-0">
                                <div class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    <ol class="list-decimal list-inside space-y-2">
                                        <li>Login ke akun KoopEase Anda.</li>
                                        <li>Cari produk di halaman Beranda atau Kategori.</li>
                                        <li>Klik tombol <strong>(+)</strong> atau <strong>Masuk Keranjang</strong>.</li>
                                        <li>Buka halaman Keranjang, periksa pesanan, lalu klik
                                            <strong>Checkout</strong>.
                                        </li>
                                        <li>Selesaikan pembayaran dan tunggu konfirmasi.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 5 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 5 ? null : 5)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Bisakah saya membatalkan pesanan?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 5 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 5" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    Ya, Anda bisa membatalkan pesanan secara mandiri di menu <strong>Pesanan
                                        Saya</strong> selama status pesanan masih <span
                                        class="bg-yellow-100 text-yellow-800 px-1.5 rounded text-xs font-bold">Menunggu
                                        Pembayaran</span> atau <span
                                        class="bg-blue-100 text-blue-800 px-1.5 rounded text-xs font-bold">Menunggu
                                        Konfirmasi</span>. Jika status sudah diproses, pembatalan tidak dapat dilakukan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: PEMBAYARAN --}}
                <div id="pembayaran" x-show="activeCategory === 'pembayaran'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                            <i class="bi bi-wallet-fill text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Pembayaran</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 6 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 6 ? null : 6)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Apa saja metode pembayaran yang tersedia?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 6 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 6" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    Kami menerima pembayaran melalui Transfer Bank (BCA, Mandiri, BNI, BRI) dan E-Wallet
                                    (GoPay, Dana, OVO, ShopeePay). Detail nomor rekening akan muncul setelah Anda
                                    melakukan Checkout.
                                </p>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 7 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 7 ? null : 7)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Apakah saya perlu upload bukti bayar?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 7 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 7" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    <strong>Ya, Wajib.</strong> Sistem kami memerlukan verifikasi manual. Setelah
                                    transfer, harap unggah bukti screenshot/foto struk di halaman detail pesanan agar
                                    admin segera memproses pesanan Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: PENGAMBILAN --}}
                <div id="pengambilan" x-show="activeCategory === 'pengambilan'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-cyan-100 flex items-center justify-center text-cyan-600">
                            <i class="bi bi-box-seam-fill text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Pengambilan Barang</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 8 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 8 ? null : 8)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Dimana lokasi pengambilan barang?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 8 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 8" x-collapse class="px-5 pb-5 pt-0">
                                <div class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    <p class="mb-2">Barang dapat diambil di:</p>
                                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 inline-block">
                                        <p class="font-bold text-gray-800">Gedung Student Center Lt. 1</p>
                                        <p class="text-xs">Ruang Koperasi Mahasiswa (Sebelah Kantin)</p>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Jam Operasional: Senin - Jumat, 09.00 - 16.00
                                        WIB</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden transition-all duration-200"
                            :class="activeQuestion === 9 ? 'shadow-md ring-1 ring-cyan-200' : 'hover:border-cyan-200'">
                            <button @click="activeQuestion = (activeQuestion === 9 ? null : 9)"
                                class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 transition">
                                <span class="font-bold text-gray-800">Apakah pengambilan bisa diwakilkan?</span>
                                <i class="bi bi-chevron-down transition-transform duration-300 text-gray-400"
                                    :class="activeQuestion === 9 ? 'rotate-180 text-cyan-600' : ''"></i>
                            </button>
                            <div x-show="activeQuestion === 9" x-collapse class="px-5 pb-5 pt-0">
                                <p class="text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                                    Bisa, asalkan perwakilan tersebut membawa bukti <strong>Order ID</strong>
                                    (Screenshot Invoice) dan mengetahui nama pemesan. Kami menyarankan untuk memberitahu
                                    admin terlebih dahulu jika pengambilan diwakilkan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('components.footer')

    {{-- Update Active Link di Footer --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Cek hash URL saat load untuk set active tab (contoh: /faq#pembayaran)
            if (window.location.hash) {
                const hash = window.location.hash.substring(1); // hapus tanda #
                if (['umum', 'pesanan', 'pembayaran', 'pengambilan'].includes(hash)) {
                    // Update state Alpine secara manual jika di luar x-data scope (opsional, tapi di sini x-data di body jadi aman)
                    const body = document.querySelector('body');
                    body.__x.$data.activeCategory = hash;
                }
            }
        });
    </script>
</body>

</html>

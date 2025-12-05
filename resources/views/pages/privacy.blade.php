<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - KoopEase</title>
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

        .card-hoverable {
            transition: all 0.2s ease;
        }

        .card-hoverable:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-8 flex-grow w-full">

        {{-- Header Section (Bento Style) --}}
        <div class="rounded-bento p-8 md:p-12 relative overflow-hidden bg-blue-600 text-white shadow-lg">
            {{-- Decorative Blobs --}}
            <div
                class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full mix-blend-overlay filter blur-3xl opacity-50 transform translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute left-0 bottom-0 w-48 h-48 bg-blue-400/20 rounded-full mix-blend-overlay filter blur-2xl opacity-50 transform -translate-x-1/2 translate-y-1/2">
            </div>

            <div class="relative z-10 text-center md:text-left flex flex-col md:flex-row items-center gap-6">
                <div
                    class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center border border-white/30 shadow-inner">
                    <i class="bi bi-shield-lock-fill text-4xl text-white"></i>
                </div>
                <div>
                    <div class="flex items-center gap-3 justify-center md:justify-start mb-2">
                        <span
                            class="bg-blue-500/50 border border-blue-400/50 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
                            Legal & Compliance
                        </span>
                        <span class="text-blue-100 text-xs font-medium">
                            Update: 05 Desember 2025
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Kebijakan Privasi</h1>
                    <p class="text-blue-100 max-w-2xl text-lg leading-relaxed">
                        Transparansi adalah kunci. Ketahui bagaimana KoopEase mengelola dan melindungi data pribadi Anda
                        sebagai civitas akademika.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kolom Kiri: Ringkasan & Kontak --}}
            <div class="space-y-6">
                <div class="rounded-bento bg-white p-6 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-list-check text-blue-600"></i> Poin Utama
                    </h3>
                    <ul class="space-y-3 text-sm font-medium text-gray-600">
                        <li class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition cursor-default">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            Data hanya untuk keperluan transaksi.
                        </li>
                        <li class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition cursor-default">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            Tidak ada penjualan data ke pihak ketiga.
                        </li>
                        <li class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition cursor-default">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            Enkripsi password tingkat tinggi.
                        </li>
                    </ul>
                </div>

                <div
                    class="rounded-bento bg-gradient-to-br from-gray-900 to-gray-800 p-6 text-white text-center shadow-lg">
                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-envelope-paper text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Butuh Bantuan Privasi?</h3>
                    <p class="text-gray-300 text-xs mb-6 leading-relaxed">
                        Jika Anda merasa ada penyalahgunaan data atau ingin menghapus akun permanen.
                    </p>
                    <a href="mailto:projectkoopease@gmail.com"
                        class="block w-full bg-white text-gray-900 font-bold py-3 rounded-xl hover:bg-gray-100 transition transform hover:scale-105">
                        Hubungi DPO Kami
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan: Detail Konten --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Section 1 --}}
                <div class="rounded-bento bg-white p-8 border border-gray-100 card-hoverable group">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0 text-blue-600 font-bold text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            1</div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Data yang Kami Kumpulkan</h2>
                            <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                                Kami mengumpulkan data seminimal mungkin yang diperlukan untuk memastikan layanan
                                berjalan lancar.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <h4 class="font-bold text-gray-800 text-sm mb-2 flex items-center gap-2">
                                        <i class="bi bi-person text-blue-500"></i> Identitas Diri
                                    </h4>
                                    <p class="text-xs text-gray-500">Nama Lengkap, NIM, Program Studi (untuk verifikasi
                                        mahasiswa).</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <h4 class="font-bold text-gray-800 text-sm mb-2 flex items-center gap-2">
                                        <i class="bi bi-telephone text-blue-500"></i> Kontak
                                    </h4>
                                    <p class="text-xs text-gray-500">Email (Institusi/Pribadi) & Nomor WhatsApp untuk
                                        notifikasi pesanan.</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <h4 class="font-bold text-gray-800 text-sm mb-2 flex items-center gap-2">
                                        <i class="bi bi-wallet2 text-blue-500"></i> Transaksi
                                    </h4>
                                    <p class="text-xs text-gray-500">Riwayat pembelian, metode pembayaran, dan waktu
                                        transaksi.</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                                    <h4 class="font-bold text-gray-800 text-sm mb-2 flex items-center gap-2">
                                        <i class="bi bi-laptop text-blue-500"></i> Data Teknis
                                    </h4>
                                    <p class="text-xs text-gray-500">IP Address & Cookies sesi login untuk keamanan
                                        akun.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2 --}}
                <div class="rounded-bento bg-white p-8 border border-gray-100 card-hoverable group">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center flex-shrink-0 text-purple-600 font-bold text-xl group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            2</div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Penggunaan & Berbagi Data</h2>
                            <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                                Data Anda digunakan sepenuhnya untuk operasional internal KoopEase dan tidak
                                diperjualbelikan.
                            </p>
                            <ul class="space-y-3">
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <i class="bi bi-check-circle-fill text-purple-500 mt-0.5"></i>
                                    <span><strong>Proses Pesanan:</strong> Memastikan barang yang dipesan sampai ke
                                        tangan yang tepat.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <i class="bi bi-check-circle-fill text-purple-500 mt-0.5"></i>
                                    <span><strong>Analitik Kampus:</strong> Data agregat (tanpa nama) mungkin digunakan
                                        untuk laporan statistik koperasi ke pihak universitas.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <i class="bi bi-check-circle-fill text-purple-500 mt-0.5"></i>
                                    <span><strong>Hukum:</strong> Kami hanya membuka data jika diwajibkan oleh hukum
                                        yang berlaku di Indonesia.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Section 3 --}}
                <div class="rounded-bento bg-white p-8 border border-gray-100 card-hoverable group">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center flex-shrink-0 text-orange-600 font-bold text-xl group-hover:bg-orange-600 group-hover:text-white transition-colors duration-300">
                            3</div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Keamanan & Cookies</h2>
                            <p class="text-gray-600 leading-relaxed mb-4 text-sm">
                                Kami menggunakan teknologi standar industri untuk menjaga keamanan Anda.
                            </p>
                            <div class="bg-orange-50 rounded-2xl p-5 border border-orange-100">
                                <h5 class="font-bold text-gray-900 text-sm mb-2">🍪 Kebijakan Cookies</h5>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    KoopEase menggunakan "Session Cookies" yang bersifat sementara. Cookies ini akan
                                    hilang otomatis saat Anda menutup browser atau logout. Kami tidak menggunakan
                                    cookies pelacak (tracking cookies) pihak ketiga untuk iklan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('components.footer')
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KoopEase - Koperasi Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }

        .rounded-bento {
            border-radius: 24px;
        }
    </style>
</head>

<body class="text-gray-800">

<!-- Footer -->
<footer class="bg-white border-t border-gray-100 mt-0">
    <div class="max-w-7xl mx-auto px-6 md:px-8 py-12">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- Brand & Description -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl font-bold text-gray-900">KoopEase</span>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-4 max-w-md">
                    Koperasi mahasiswa modern yang menyediakan berbagai kebutuhan sehari-hari dengan harga
                    terjangkau.
                    Dari alat tulis, snack, minuman, hingga merchandise kampus - semua dalam genggaman Anda.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                        <i class="bi bi-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-pink-600 transition-colors">
                        <i class="bi bi-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <i class="bi bi-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-600 transition-colors">
                        <i class="bi bi-youtube text-xl"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-bold text-gray-900 text-lg mb-4">Menu Cepat</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-house w-4"></i>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-shop w-4"></i>
                            Semua Produk
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-percent w-4"></i>
                            Promo & Diskon
                        </a>
                    </li>
                    <li>
                    <li>
                        <a href="{{ route('about') }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-info-circle w-4"></i>
                            Tentang Kami
                        </a>
                    </li>
                    </li>
                </ul>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="font-bold text-gray-900 text-lg mb-4">Kategori</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('kategori', 'snack-makanan') }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-egg-fried w-4"></i>
                            Snack & Makanan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kategori', 'minuman-dingin') }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-cup-straw w-4"></i>
                            Minuman Dingin
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kategori', 'alat-tulis') }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-pencil w-4"></i>
                            Alat Tulis (ATK)
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kategori', 'merch-kampus') }}"
                            class="text-gray-600 hover:text-blue-600 transition-colors text-sm flex items-center gap-2">
                            <i class="bi bi-mortarboard w-4"></i>
                            Merch Kampus
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Support & Contact -->
        <div class="rounded-bento bg-gray-50 p-6 mb-8 border border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                        <i class="bi bi-phone text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Pesan Praktis</h4>
                        <p class="text-gray-600 text-xs">Bayar online, ambil pakai kode unik</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center text-green-600">
                        <i class="bi bi-clock text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Buka Setiap Hari</h4>
                        <p class="text-gray-600 text-xs">08.00 - 16.00 WIB</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600">
                        <i class="bi bi-shield-check text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm">Pembayaran Aman</h4>
                        <p class="text-gray-600 text-xs">Transaksi terjamin keamanannya</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="pt-8 border-t border-gray-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="text-center lg:text-left">
                    <p class="text-gray-600 text-sm">
                        &copy; 2025 <span class="font-bold text-blue-600">KoopEase</span>. All rights reserved.
                    </p>
                </div>

                <div class="text-center">
                    <p class="text-gray-500 text-sm font-medium bg-gray-100 px-4 py-2 rounded-full inline-block">
                        <i class="bi bi-people-fill me-2"></i>
                        Dibuat oleh Kelompok 3 PPL IS-06-03
                    </p>
                </div>

                <div class="flex justify-center lg:justify-end gap-6 text-sm">
                    <a href="{{ route('privacy') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                        Kebijakan Privasi
                    </a>
                    <a href="{{ route('terms') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
                        Syarat & Ketentuan
                    </a>
                    <a href="{{ route('faq') }}" class="text-gray-500 hover:text-gray-700 transition-colors">FAQ</a>
                </div>
            </div>
        </div>
    </div>
</footer>

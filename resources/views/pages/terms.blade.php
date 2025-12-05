<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat & Ketentuan - KoopEase</title>
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

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
            /* Offset untuk sticky header */
        }
    </style>
</head>

<body class="text-gray-800 flex flex-col min-h-screen" x-data="{ activeSection: 'intro' }">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-8 flex-grow w-full">

        {{-- Header Section (Orange/Warm Theme) --}}
        <div class="rounded-bento p-8 md:p-12 relative overflow-hidden bg-orange-500 text-white shadow-lg">
            {{-- Decorative Blobs --}}
            <div
                class="absolute left-0 top-0 w-64 h-64 bg-yellow-300/30 rounded-full mix-blend-overlay filter blur-3xl opacity-60 transform -translate-x-1/2 -translate-y-1/2">
            </div>
            <div
                class="absolute right-0 bottom-0 w-48 h-48 bg-red-500/30 rounded-full mix-blend-overlay filter blur-2xl opacity-60 transform translate-x-1/2 translate-y-1/2">
            </div>

            <div class="relative z-10 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                <div
                    class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-3xl flex items-center justify-center border border-white/30 shadow-inner transform rotate-3">
                    <i class="bi bi-file-earmark-text-fill text-4xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Syarat & Ketentuan</h1>
                    <p class="text-orange-50 max-w-2xl text-lg leading-relaxed">
                        Aturan main penggunaan platform KoopEase demi kenyamanan dan keamanan transaksi bersama.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">

            {{-- Sidebar Navigasi (Sticky) --}}
            <div class="md:col-span-4 lg:col-span-3 hidden md:block sticky top-28">
                <div class="bg-white rounded-bento p-4 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 px-3">Daftar Isi</p>
                    <nav class="space-y-1">
                        <a href="#akun" @click="activeSection = 'akun'"
                            :class="activeSection === 'akun' ? 'bg-orange-50 text-orange-600 border-orange-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="block px-4 py-3 rounded-xl border text-sm font-bold transition flex justify-between items-center">
                            1. Akun & Keanggotaan
                            <i class="bi bi-chevron-right text-xs"
                                :class="activeSection === 'akun' ? 'opacity-100' : 'opacity-0'"></i>
                        </a>
                        <a href="#transaksi" @click="activeSection = 'transaksi'"
                            :class="activeSection === 'transaksi' ? 'bg-orange-50 text-orange-600 border-orange-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="block px-4 py-3 rounded-xl border text-sm font-bold transition flex justify-between items-center">
                            2. Transaksi & Harga
                            <i class="bi bi-chevron-right text-xs"
                                :class="activeSection === 'transaksi' ? 'opacity-100' : 'opacity-0'"></i>
                        </a>
                        <a href="#pengambilan" @click="activeSection = 'pengambilan'"
                            :class="activeSection === 'pengambilan' ?
                                'bg-orange-50 text-orange-600 border-orange-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="block px-4 py-3 rounded-xl border text-sm font-bold transition flex justify-between items-center">
                            3. Pengambilan Barang
                            <i class="bi bi-chevron-right text-xs"
                                :class="activeSection === 'pengambilan' ? 'opacity-100' : 'opacity-0'"></i>
                        </a>
                        <a href="#pembatalan" @click="activeSection = 'pembatalan'"
                            :class="activeSection === 'pembatalan' ?
                                'bg-orange-50 text-orange-600 border-orange-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="block px-4 py-3 rounded-xl border text-sm font-bold transition flex justify-between items-center">
                            4. Pembatalan & Refund
                            <i class="bi bi-chevron-right text-xs"
                                :class="activeSection === 'pembatalan' ? 'opacity-100' : 'opacity-0'"></i>
                        </a>
                        <a href="#sanksi" @click="activeSection = 'sanksi'"
                            :class="activeSection === 'sanksi' ? 'bg-orange-50 text-orange-600 border-orange-200 shadow-sm' :
                                'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-transparent'"
                            class="block px-4 py-3 rounded-xl border text-sm font-bold transition flex justify-between items-center">
                            5. Larangan & Sanksi
                            <i class="bi bi-chevron-right text-xs"
                                :class="activeSection === 'sanksi' ? 'opacity-100' : 'opacity-0'"></i>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="md:col-span-8 lg:col-span-9 space-y-6">

                <section id="akun" class="bg-white p-8 rounded-bento border border-gray-100 card-hoverable"
                    @mouseenter="activeSection = 'akun'">
                    <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                        <span class="bg-orange-100 text-orange-600 font-bold px-3 py-1 rounded-lg text-sm">Pasal
                            1</span>
                        <h3 class="text-xl font-bold text-gray-900">Akun & Keanggotaan</h3>
                    </div>
                    <div class="space-y-4 text-gray-600 text-sm leading-relaxed">
                        <p>
                            1.1. Layanan KoopEase eksklusif untuk mahasiswa aktif, dosen, dan staf universitas.
                        </p>
                        <p>
                            1.2. Pengguna wajib mendaftar menggunakan data yang valid (Nama sesuai KTM/KTP).
                        </p>
                        <p>
                            1.3. Anda bertanggung jawab penuh atas keamanan kata sandi akun Anda. Segala aktivitas yang
                            terjadi melalui akun Anda dianggap sah sebagai tindakan Anda.
                        </p>
                    </div>
                </section>

                <section id="transaksi" class="bg-white p-8 rounded-bento border border-gray-100 card-hoverable"
                    @mouseenter="activeSection = 'transaksi'">
                    <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                        <span class="bg-orange-100 text-orange-600 font-bold px-3 py-1 rounded-lg text-sm">Pasal
                            2</span>
                        <h3 class="text-xl font-bold text-gray-900">Transaksi & Pembayaran</h3>
                    </div>
                    <div class="space-y-4 text-gray-600 text-sm leading-relaxed">
                        <p>
                            2.1. Harga yang tertera adalah harga final dalam mata uang Rupiah (IDR).
                        </p>
                        <p>
                            2.2. Pembayaran dapat dilakukan melalui transfer bank atau metode lain yang tersedia di
                            platform.
                        </p>
                        <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100 flex gap-3">
                            <i class="bi bi-exclamation-triangle-fill text-yellow-600 mt-0.5"></i>
                            <div>
                                <span class="font-bold text-yellow-800">Batas Waktu Pembayaran:</span>
                                <p class="text-yellow-700 mt-1">Pembayaran harus diselesaikan maksimal <strong>1x24
                                        jam</strong> setelah checkout. Jika melebihi batas waktu, pesanan otomatis
                                    dibatalkan sistem.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="pengambilan" class="bg-white p-8 rounded-bento border border-gray-100 card-hoverable"
                    @mouseenter="activeSection = 'pengambilan'">
                    <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                        <span class="bg-orange-100 text-orange-600 font-bold px-3 py-1 rounded-lg text-sm">Pasal
                            3</span>
                        <h3 class="text-xl font-bold text-gray-900">Pengambilan Barang</h3>
                    </div>
                    <div class="space-y-4 text-gray-600 text-sm leading-relaxed">
                        <p>
                            3.1. KoopEase saat ini menerapkan sistem <strong>Ambil di Tempat (Pick-up)</strong>.
                        </p>
                        <p>
                            3.2. Pesanan baru dapat diambil setelah status berubah menjadi <span
                                class="text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded">Siap Diambil</span>.
                        </p>
                        <p>
                            3.3. Lokasi pengambilan: <strong>Gedung Student Center Lt. 1 (Ruang Koperasi)</strong> pada
                            jam kerja (09.00 - 16.00 WIB).
                        </p>
                        <p>
                            3.4. Wajib menunjukkan bukti <strong>Order ID</strong> (QR Code atau Invoice Digital) kepada
                            petugas jaga.
                        </p>
                    </div>
                </section>

                <section id="pembatalan" class="bg-white p-8 rounded-bento border border-gray-100 card-hoverable"
                    @mouseenter="activeSection = 'pembatalan'">
                    <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                        <span class="bg-orange-100 text-orange-600 font-bold px-3 py-1 rounded-lg text-sm">Pasal
                            4</span>
                        <h3 class="text-xl font-bold text-gray-900">Kebijakan Pembatalan</h3>
                    </div>
                    <div class="space-y-4 text-gray-600 text-sm leading-relaxed">
                        <p>
                            4.1. Pembatalan mandiri hanya dapat dilakukan jika status pesanan:
                        </p>
                        <ul class="flex flex-wrap gap-2">
                            <li class="px-3 py-1 bg-gray-100 rounded-lg text-xs font-bold border border-gray-200">
                                Menunggu Pembayaran</li>
                            <li class="px-3 py-1 bg-gray-100 rounded-lg text-xs font-bold border border-gray-200">
                                Menunggu Konfirmasi</li>
                        </ul>
                        <p>
                            4.2. Jika pesanan sudah berstatus <strong>"Diproses"</strong>, pembatalan tidak dapat
                            dilakukan melalui sistem. Hubungi admin segera jika ada kesalahan krusial.
                        </p>
                        <p>
                            4.3. Pengembalian dana (Refund) untuk pesanan yang dibatalkan oleh sistem (karena stok
                            kosong, dll) akan diproses dalam 3-5 hari kerja.
                        </p>
                    </div>
                </section>

                <section id="sanksi" class="bg-white p-8 rounded-bento border border-gray-100 card-hoverable"
                    @mouseenter="activeSection = 'sanksi'">
                    <div class="flex items-center gap-3 mb-4 border-b border-gray-100 pb-4">
                        <span class="bg-red-100 text-red-600 font-bold px-3 py-1 rounded-lg text-sm">Pasal 5</span>
                        <h3 class="text-xl font-bold text-gray-900">Larangan & Sanksi</h3>
                    </div>
                    <ul class="space-y-3 text-gray-600 text-sm list-disc list-inside">
                        <li>Dilarang melakukan pemesanan fiktif (Order tapi tidak bayar berulang kali).</li>
                        <li>Dilarang menyalahgunakan sistem voucher/diskon (jika ada).</li>
                        <li>Pelanggaran berat dapat mengakibatkan penangguhan (suspend) atau pemblokiran akun secara
                            permanen.</li>
                    </ul>
                </section>

            </div>
        </div>
    </div>

    @include('components.footer')
</body>

</html>

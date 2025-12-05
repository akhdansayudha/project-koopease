<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran #{{ $order->id }} - KoopEase</title>
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
    copiedTotal: false,
    successModalOpen: false,

    submitPayment() {
        this.successModalOpen = true;
        setTimeout(() => {
            document.getElementById('payment-form').submit();
        }, 5000);
    }
}">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8">

        <div class="mb-6">
            <a href="{{ route('cart.index') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold text-sm transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Keranjang
            </a>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <div
                class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl shadow-sm">
                💳</div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pembayaran</h1>
                <p class="text-gray-500 text-sm font-medium">Selesaikan transaksi untuk Order ID: <span
                        class="text-blue-600 font-bold">#{{ $order->id }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">

                <div
                    class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition duration-300">
                    <div
                        class="absolute top-0 right-0 bg-blue-600 w-32 h-32 rounded-bl-[100px] -mr-10 -mt-10 opacity-5 group-hover:opacity-10 transition">
                    </div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-lg text-gray-900">Transfer Bank</h3>
                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">Dicek
                            Manual</span>
                    </div>
                    <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 mb-6 relative">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Bank Tujuan</p>
                                <span class="text-2xl font-extrabold text-gray-900">BANK MANDIRI</span>
                            </div>
                            <img src="https://tsoftxyzzudztphjgxrp.supabase.co/storage/v1/object/public/assets/Logo%20Mandiri%20-%20Dianisa.com.png"
                                class="h-8 opacity-90">
                        </div>
                        <div class="w-full h-px bg-blue-200 border-t border-dashed border-blue-300 my-4"></div>
                        <div>
                            <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Nomor Rekening</p>
                            <div class="flex items-center gap-3">
                                <span
                                    class="text-3xl font-mono font-bold text-gray-900 tracking-wide">123-00-9876543-2</span>
                                <button onclick="navigator.clipboard.writeText('1230098765432')"
                                    class="bg-white text-blue-600 hover:bg-blue-600 hover:text-white p-2 rounded-lg shadow-sm transition"
                                    title="Salin Rekening"><i class="bi bi-clipboard"></i></button>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 font-medium">a.n Koperasi Mahasiswa Telkom</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start bg-yellow-50 p-4 rounded-2xl border border-yellow-100">
                        <div class="bg-yellow-100 p-2 rounded-full text-yellow-600"><i
                                class="bi bi-info-lg text-lg"></i></div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 mb-1">Penting!</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">Mohon transfer <strong>tepat hingga 3 digit
                                    terakhir</strong> agar sistem kami dapat memverifikasi pembayaran Anda secara
                                otomatis.</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Rincian Pesanan</h3>
                    <div class="space-y-4">
                        @php $realSubtotal = 0; @endphp
                        @foreach ($order->orderItems as $item)
                            @php
                                // FIX HARGA 0: Jika harga di history 0, ambil dari master produk
                                $hargaSatuan =
                                    $item->harga_saat_pesan > 0 ? $item->harga_saat_pesan : $item->product->harga;
                                $subtotalItem = $hargaSatuan * $item->quantity;
                                $realSubtotal += $subtotalItem;
                            @endphp
                            <div
                                class="flex gap-4 items-center p-3 hover:bg-gray-50 rounded-2xl transition border border-transparent hover:border-gray-100">
                                <div
                                    class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img src="{{ $item->product->gambar_url ? asset($item->product->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 line-clamp-1">
                                        {{ $item->product->nama_produk }}</p>
                                    <p class="text-xs text-gray-500 font-medium">{{ $item->quantity }} x Rp
                                        {{ number_format($hargaSatuan, 0, ',', '.') }}</p>
                                </div>
                                <span class="text-sm font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg">
                                    Rp {{ number_format($subtotalItem, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-xl sticky top-24">
                    <h3 class="font-bold text-xl text-gray-900 mb-6">Rincian Tagihan</h3>

                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Total Harga Barang</span>
                            <span class="font-bold text-gray-900">Rp
                                {{ number_format($realSubtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Biaya Penanganan</span>
                            <span class="font-bold text-green-600">Gratis</span>
                        </div>
                        <div class="flex justify-between items-center bg-blue-50 p-3 rounded-xl border border-blue-100">
                            <div class="flex items-center gap-2">
                                <span class="text-blue-600"><i class="bi bi-stars"></i></span>
                                <span class="text-sm font-bold text-blue-800">Kode Unik</span>
                            </div>
                            <span class="font-bold text-blue-800">+ Rp
                                {{ number_format($order->total_price - $realSubtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-dashed border-gray-200 my-6"></div>

                    <div class="mb-8 text-center cursor-pointer group relative"
                        @click="navigator.clipboard.writeText('{{ $order->total_price }}'); copiedTotal = true; setTimeout(() => copiedTotal = false, 2000)">

                        <p class="text-xs text-gray-500 font-bold uppercase mb-1">Total yang harus dibayar</p>
                        <div class="flex justify-center items-center gap-2">
                            <span class="text-4xl font-extrabold text-blue-700 tracking-tight">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                            <i class="bi bi-clipboard text-gray-300 group-hover:text-blue-600 transition"></i>
                        </div>

                        <span x-show="copiedTotal" x-transition
                            class="absolute -bottom-6 left-1/2 -translate-x-1/2 bg-green-600 text-white text-[10px] font-bold py-1 px-3 rounded-full shadow-lg">
                            Nominal Disalin!
                        </span>
                    </div>

                    <button @click="submitPayment()"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-blue-200 transition transform hover:scale-[1.02] flex items-center justify-center gap-3">
                        <span>Saya Sudah Bayar</span>
                        <i class="bi bi-check-circle-fill"></i>
                    </button>

                    <form id="payment-form" action="{{ route('checkout.confirm', $order->id) }}" method="POST"
                        style="display: none;">
                        @csrf
                    </form>

                    <p class="text-xs text-center text-gray-400 mt-6 leading-relaxed">
                        Klik tombol di atas jika Anda sudah melakukan transfer sesuai nominal.
                    </p>
                </div>
            </div>

        </div>
    </div>

    @include('components.footer')

    <div x-show="successModalOpen" style="display: none;" class="fixed inset-0 z-[99] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                class="relative transform overflow-hidden rounded-[40px] bg-white text-center shadow-2xl transition-all sm:w-full sm:max-w-md p-10 border border-white/20">
                <div
                    class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6 animate-[bounce_1s_infinite]">
                    <i class="bi bi-check-lg text-5xl text-green-500"></i>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-3">Pembayaran Diterima!</h3>
                <p class="text-gray-500 mb-8 text-lg leading-relaxed">Terima kasih! Admin kami sedang memverifikasi
                    pembayaran Anda.</p>
                <div class="w-full bg-gray-100 rounded-full h-3 mb-3 overflow-hidden">
                    <div class="bg-green-500 h-3 rounded-full animate-[width_5s_ease-in-out_forwards]"
                        style="width: 0%; transition: width 5s;"></div>
                </div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Mengalihkan halaman...</p>
            </div>
        </div>
    </div>
    <style>
        @keyframes width {
            from {
                width: 0%;
            }

            to {
                width: 100%;
            }
        }
    </style>

</body>

</html>

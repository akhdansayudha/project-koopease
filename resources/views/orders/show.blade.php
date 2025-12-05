<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->id }} - KoopEase</title>
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
    copiedId: false,
    cancelModalOpen: false,

    closeCancelModal() {
        this.cancelModalOpen = false;
    },

    handleBackdropClick(e) {
        if (e.target === e.currentTarget) {
            this.closeCancelModal();
        }
    }
}" x-cloak>

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8">

        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('orders.index') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold text-sm transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>

            <a href="https://wa.me/6281234567890?text=Halo Admin KoopEase, saya butuh bantuan untuk pesanan #{{ $order->id }}"
                target="_blank"
                class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-green-600 transition bg-white px-4 py-2 rounded-full border border-gray-200 hover:border-green-200 shadow-sm">
                <i class="bi bi-whatsapp text-green-500"></i> Butuh Bantuan?
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">

                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-6 border-b border-gray-100 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Order ID</p>
                            <div class="flex items-center gap-2 group cursor-pointer"
                                @click="navigator.clipboard.writeText('{{ $order->id }}'); copiedId = true; setTimeout(() => copiedId = false, 2000)">
                                <h1 class="text-2xl font-mono font-extrabold text-gray-900">#{{ $order->id }}</h1>
                                <i class="bi"
                                    :class="copiedId ? 'bi-check-circle-fill text-green-500' :
                                        'bi-clipboard text-gray-300 group-hover:text-blue-600'"></i>
                                <span x-show="copiedId" x-transition
                                    class="text-xs text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded">Disalin</span>
                            </div>
                        </div>
                        <span class="bg-gray-50 text-gray-600 px-3 py-1 rounded-lg text-xs font-bold">
                            {{ $order->orderItems->count() }} Item
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach ($order->orderItems as $item)
                            @php
                                $harga = $item->harga_saat_pesan > 0 ? $item->harga_saat_pesan : $item->product->harga;
                            @endphp
                            <div class="flex gap-4 items-start">
                                <div
                                    class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img src="{{ $item->product->gambar_url ? asset($item->product->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 mb-1">{{ $item->product->nama_produk }}</h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $item->quantity }} barang x Rp
                                        {{ number_format($harga, 0, ',', '.') }}</p>
                                    <p class="font-bold text-blue-600">Rp
                                        {{ number_format($harga * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="h-px bg-gray-100 w-full"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4">Status Pesanan</h3>

                    <div class="relative border-l-2 border-gray-100 ml-3 space-y-6 pl-6 py-2">
                        <div class="relative">
                            <div
                                class="absolute -left-[31px] bg-green-500 h-4 w-4 rounded-full border-4 border-white shadow-sm">
                            </div>
                            <p class="text-sm font-bold text-gray-900">Pesanan Dibuat</p>
                            <p class="text-xs text-gray-500">
                                {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                        </div>

                        <div class="relative">
                            <div
                                class="absolute -left-[31px] {{ $order->status == 'dibatalkan' ? 'bg-red-500' : ($order->status == 'selesai' ? 'bg-green-500' : 'bg-blue-600 animate-pulse') }} h-4 w-4 rounded-full border-4 border-white shadow-sm">
                            </div>

                            @if ($order->status == 'dibatalkan')
                                <p class="text-sm font-bold text-red-600">Dibatalkan</p>
                            @else
                                <p class="text-sm font-bold text-blue-600 capitalize">
                                    {{ str_replace('_', ' ', $order->status) }}</p>
                            @endif

                            <p class="text-xs text-gray-500">Update Terakhir:
                                {{ $order->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if ($order->status == 'menunggu_pembayaran')
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <a href="{{ route('checkout.show', $order->id) }}"
                                class="block w-full text-center bg-blue-600 text-white px-4 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                Lanjut Pembayaran
                            </a>

                            <div class="mt-3 bg-orange-50 rounded-xl p-3 text-center border border-orange-100">
                                <p class="text-xs text-orange-800">
                                    Selesaikan pembayaran sebelum: <br>
                                    <span
                                        class="font-bold">{{ $order->created_at->addHour()->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                        WIB</span>
                                </p>
                            </div>

                            <div class="mt-4 text-center">
                                <button @click="cancelModalOpen = true"
                                    class="text-xs font-bold text-red-500 hover:text-red-700 hover:underline transition">
                                    Batalkan Pesanan
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4">Rincian Pembayaran</h3>
                    <div class="space-y-3 text-sm mb-4">
                        <div class="flex justify-between text-gray-500">
                            <span>Metode Bayar</span>
                            <span class="font-bold text-gray-900">Transfer Manual</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Total Harga</span>
                            <span class="font-bold text-gray-900">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-xl flex justify-between items-center">
                        <span class="text-sm font-bold text-blue-800">Total Bayar</span>
                        <span class="text-lg font-extrabold text-blue-600">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @include('components.footer')

    <!-- Modal Konfirmasi Batalkan Pesanan - DIKOREKSI -->
    <div x-show="cancelModalOpen" style="display: none;" class="fixed inset-0 z-[99] overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
            closeCancelModal() {
                    cancelModalOpen = false;
                },
                handleBackdropClick(e) {
                    if (e.target === e.currentTarget) {
                        this.closeCancelModal();
                    }
                }
        }"
        @keydown.escape="closeCancelModal()">

        <!-- Backdrop -->
        <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
            @click="handleBackdropClick($event)">
        </div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-sm p-6"
                @click.stop>

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                        <i class="bi bi-x-lg text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Batalkan Pesanan?</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button @click="closeCancelModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-xl transition">
                        Tidak
                    </button>

                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-red-200 transition">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

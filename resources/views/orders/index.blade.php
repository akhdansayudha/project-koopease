<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    authOpen: false,

    // State Modal Batal
    cancelModalOpen: false,
    cancelData: {
        id: '',
        url: '',
        items: []
    },

    // State Modal Beli Lagi (Rebuy)
    rebuyModalOpen: false,
    rebuyItems: [],
    rebuySelectAll: true,

    // --- Logic Modal Batal ---
    openCancelModal(id, url, items) {
        this.cancelData = { id: id, url: url, items: items };
        this.cancelModalOpen = true;
        document.body.style.overflow = 'hidden'; // Disable scroll body
    },
    closeCancelModal() {
        this.cancelModalOpen = false;
        document.body.style.overflow = 'auto'; // Enable scroll body
    },

    // --- Logic Modal Beli Lagi ---
    openRebuyModal(items) {
        // Clone item dan tambahkan property 'selected: true' secara default
        // Gunakan JSON parse/stringify untuk deep copy agar aman
        this.rebuyItems = JSON.parse(JSON.stringify(items)).map(item => ({ ...item, selected: true }));
        this.rebuySelectAll = true;
        this.rebuyModalOpen = true;
    },
    closeRebuyModal() {
        this.rebuyModalOpen = false;
        document.body.style.overflow = 'auto'; // Enable scroll saat modal tutup
    },
    toggleSelectAllRebuy() {
        this.rebuyItems.forEach(item => item.selected = this.rebuySelectAll);
    },
    updateRebuySelectAllState() {
        const allSelected = this.rebuyItems.length > 0 && this.rebuyItems.every(item => item.selected);
        this.rebuySelectAll = allSelected;
    },
    // Logic Backdrop (Klik di luar modal untuk tutup)
    handleBackdropClick(e) {
        if (e.target === e.currentTarget) {
            this.closeRebuyModal();
        }
    }

}" @keydown.escape="closeCancelModal(); closeRebuyModal()">

    @include('components.navbar')

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
                📦
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pesanan Saya</h1>
                <p class="text-gray-500 text-sm font-medium">Riwayat transaksi Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($orders as $order)
                <div
                    class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm hover:shadow-md transition duration-300">

                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-gray-100 pb-4 gap-4">
                        <div class="flex gap-4 items-center">
                            <div class="bg-gray-50 p-3 rounded-xl">
                                <i class="bi bi-receipt text-2xl text-gray-400"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Order ID</p>
                                <p class="font-mono font-bold text-gray-900 text-lg">#{{ $order->id }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $order->created_at->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div>
                            @if ($order->status == 'menunggu_pembayaran')
                                <span
                                    class="bg-yellow-50 text-yellow-700 px-4 py-2 rounded-xl text-sm font-bold border border-yellow-100 flex items-center gap-2">
                                    <i class="bi bi-clock"></i> Menunggu Pembayaran
                                </span>
                            @elseif($order->status == 'menunggu_konfirmasi')
                                <span
                                    class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-sm font-bold border border-blue-100 flex items-center gap-2">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Konfirmasi
                                </span>
                            @elseif($order->status == 'diproses')
                                <span
                                    class="bg-purple-50 text-purple-700 px-4 py-2 rounded-xl text-sm font-bold border border-purple-100 flex items-center gap-2">
                                    <i class="bi bi-gear"></i> Sedang Diproses
                                </span>
                            @elseif($order->status == 'siap_diambil')
                                <span
                                    class="bg-green-50 text-green-700 px-4 py-2 rounded-xl text-sm font-bold border border-green-100 flex items-center gap-2">
                                    <i class="bi bi-box-seam"></i> Siap Diambil
                                </span>
                            @elseif($order->status == 'selesai')
                                <span
                                    class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-sm font-bold border border-gray-200 flex items-center gap-2">
                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                </span>
                            @elseif($order->status == 'dibatalkan')
                                <span
                                    class="bg-red-50 text-red-600 px-4 py-2 rounded-xl text-sm font-bold border border-red-100 flex items-center gap-2">
                                    <i class="bi bi-x-circle"></i> Dibatalkan
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        @foreach ($order->orderItems->take(2) as $item)
                            @php
                                $hargaSatuan =
                                    $item->harga_saat_pesan > 0 ? $item->harga_saat_pesan : $item->product->harga;
                                $totalPerItem = $hargaSatuan * $item->quantity;
                            @endphp

                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img src="{{ $item->product->gambar_url ? asset($item->product->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900 line-clamp-1">
                                        {{ $item->product->nama_produk }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} barang x Rp
                                        {{ number_format($hargaSatuan, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($totalPerItem, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        @if ($order->orderItems->count() > 2)
                            <p class="text-xs text-gray-400 pl-16">+ {{ $order->orderItems->count() - 2 }} produk
                                lainnya</p>
                        @endif
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-end md:items-center pt-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                            <p class="text-2xl font-extrabold text-blue-600">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex flex-wrap gap-3 w-full md:w-auto">
                            <a href="{{ route('orders.show', $order->id) }}"
                                class="flex-1 md:flex-none text-center bg-white border border-gray-200 text-gray-700 px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-50 transition min-w-[140px]">
                                Lihat Detail
                            </a>

                            {{-- Persiapan Data (Common untuk Cancel dan Rebuy) --}}
                            @php
                                $itemsForJs = $order->orderItems
                                    ->map(function ($item) {
                                        $harga =
                                            $item->harga_saat_pesan > 0
                                                ? $item->harga_saat_pesan
                                                : $item->product->harga;
                                        return [
                                            'id' => $item->id,
                                            'product_id' => $item->product_id,
                                            'name' => $item->product->nama_produk ?? 'Produk Dihapus',
                                            'qty' => $item->quantity,
                                            'price' => $harga,
                                            'price_formatted' => 'Rp ' . number_format($harga, 0, ',', '.'),
                                            'image' =>
                                                $item->product && $item->product->gambar_url
                                                    ? asset($item->product->gambar_url)
                                                    : asset('images/products/default-empty.jpg'),
                                        ];
                                    })
                                    ->values(); // <--- PENTING: Tambahkan ->values() agar index direset jadi 0,1,2 (Array Murni)
                            @endphp

                            @if ($order->status == 'menunggu_pembayaran')
                                <a href="{{ route('checkout.show', $order->id) }}"
                                    class="flex-1 md:flex-none text-center bg-blue-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-200 min-w-[140px]">
                                    Bayar Sekarang
                                </a>

                                <button type="button"
                                    class="flex-1 md:flex-none text-center bg-red-50 text-red-600 px-6 py-3 rounded-xl font-bold text-sm hover:bg-red-100 transition border border-red-100 min-w-[140px]"
                                    @click="openCancelModal('{{ $order->id }}', '{{ route('orders.cancel', $order->id) }}', {{ json_encode($itemsForJs) }})">
                                    Batalkan
                                </button>
                            @elseif($order->status == 'siap_diambil')
                                <button
                                    class="flex-1 md:flex-none text-center bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition shadow-lg shadow-green-200 min-w-[140px]"
                                    onclick="alert('Silakan ambil pesanan Anda di lokasi yang ditentukan')">
                                    Ambil Pesanan
                                </button>
                            @elseif($order->status == 'selesai')
                                {{-- Tombol Beli Lagi (Trigger Modal Rebuy) --}}
                                <button type="button"
                                    class="flex-1 md:flex-none text-center bg-gray-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-gray-700 transition"
                                    @click="openRebuyModal({{ json_encode($itemsForJs) }})">
                                    Beli Lagi
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            @php
                                $statusSteps = [
                                    'menunggu_pembayaran' => 1,
                                    'menunggu_konfirmasi' => 2,
                                    'diproses' => 3,
                                    'siap_diambil' => 4,
                                    'selesai' => 5,
                                ];
                                $currentStep = $statusSteps[$order->status] ?? 0;
                                $progress = ($currentStep / 5) * 100;
                            @endphp

                            <span class="text-xs font-bold text-gray-500">Status Pesanan</span>
                            <span class="text-xs font-bold text-blue-600">{{ $currentStep }}/5</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $progress }}%"></div>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span
                                class="text-xs text-gray-500 {{ $currentStep >= 1 ? 'text-blue-600 font-bold' : '' }}">Menunggu
                                Bayar</span>
                            <span
                                class="text-xs text-gray-500 {{ $currentStep >= 2 ? 'text-blue-600 font-bold' : '' }}">Konfirmasi</span>
                            <span
                                class="text-xs text-gray-500 {{ $currentStep >= 3 ? 'text-blue-600 font-bold' : '' }}">Diproses</span>
                            <span
                                class="text-xs text-gray-500 {{ $currentStep >= 4 ? 'text-blue-600 font-bold' : '' }}">Siap
                                Diambil</span>
                            <span
                                class="text-xs text-gray-500 {{ $currentStep >= 5 ? 'text-blue-600 font-bold' : '' }}">Selesai</span>
                        </div>
                    </div>

                </div>
            @empty
                <div class="text-center py-20 bg-white rounded-[40px] border border-gray-100">
                    <div class="text-6xl mb-4">🧾</div>
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h2>
                    <p class="text-gray-500 mb-6">Yuk mulai belanja kebutuhanmu sekarang!</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 transition">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    @include('components.footer')
    <!-- Include Modal Batal -->
    @include('orders.cancel-modal')
    <!-- Include Modal Beli Lagi -->
    @include('orders.rebuy-modal')

</body>

</html>

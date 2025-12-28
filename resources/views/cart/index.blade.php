<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - KoopEase</title>
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
    deleteModalOpen: false,
    deleteAction: '',
    itemName: '',

    confirmDelete(url, name) {
        this.deleteAction = url;
        this.itemName = name;
        this.deleteModalOpen = true;
    },

    closeDeleteModal() {
        this.deleteModalOpen = false;
    },

    handleBackdropClick(e) {
        if (e.target === e.currentTarget) {
            this.closeDeleteModal();
        }
    }
}">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8">

        <div class="mb-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold text-sm transition">
                <i class="bi bi-arrow-left"></i> Kembali Belanja
            </a>
        </div>

        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-xl">
                🛒
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h1>
                <p class="text-gray-500 text-sm">{{ $cartItems->count() }} Item di keranjangmu</p>
            </div>
        </div>

        @if (session('success'))
            <div
                class="mb-6 bg-green-50 text-green-700 px-4 py-3 rounded-2xl border border-green-200 flex items-center gap-2 text-sm font-bold">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($cartItems->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-4">
                    @foreach ($cartItems as $item)
                        <div
                            class="bg-white rounded-[24px] p-4 border border-gray-100 flex gap-4 items-center shadow-sm hover:shadow-md transition">

                            <div class="w-24 h-24 bg-gray-50 rounded-2xl flex-shrink-0 overflow-hidden">
                                <img src="{{ $item->product->gambar_url ? asset($item->product->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                    class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 mb-1">{{ $item->product->nama_produk }}</h3>
                                <p class="text-xs text-gray-500 mb-3">
                                    {{ $item->product->category->nama_kategori ?? 'Umum' }}</p>
                                <span class="font-bold text-blue-600">Rp
                                    {{ number_format($item->product->harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex flex-col items-end gap-3">

                                <button
                                    @click="confirmDelete('{{ route('cart.destroy', $item->id) }}', '{{ $item->product->nama_produk }}')"
                                    class="text-gray-400 hover:text-red-500 transition">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>

                                <div class="flex items-center bg-gray-50 rounded-xl p-1 border border-gray-100">
                                    @if ($item->quantity > 1)
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="type" value="minus">
                                            <button type="submit"
                                                class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-gray-600 shadow-sm hover:text-blue-600 font-bold text-sm">-</button>
                                        </form>
                                    @else
                                        <button
                                            @click="confirmDelete('{{ route('cart.destroy', $item->id) }}', '{{ $item->product->nama_produk }}')"
                                            class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-red-500 shadow-sm hover:bg-red-50 font-bold text-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif

                                    <span class="w-8 text-center text-sm font-bold">{{ $item->quantity }}</span>

                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="type" value="plus">
                                        <button type="submit"
                                            class="w-7 h-7 flex items-center justify-center bg-white rounded-lg text-gray-600 shadow-sm hover:text-blue-600 font-bold text-sm">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[32px] p-6 border border-gray-100 sticky top-24 shadow-sm">
                        <h3 class="font-bold text-lg mb-4">Ringkasan Pesanan</h3>
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Total Item</span>
                                <span class="font-bold">{{ $cartItems->sum('quantity') }} barang</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Subtotal</span>
                                <span class="font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-px bg-gray-100 my-2"></div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-gray-900">Total Bayar</span>
                                <span class="font-extrabold text-xl text-blue-600">Rp
                                    {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02] flex items-center justify-center gap-2">
                                <span>Checkout / Bayar</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                        <p class="text-xs text-gray-400 text-center mt-4 leading-relaxed">
                            <i class="bi bi-shield-lock me-1"></i> Pembayaran aman dengan Kode Unik. Barang diambil
                            langsung di Koperasi.
                        </p>
                    </div>
                </div>

            </div>
        @else
            <div class="text-center py-20 bg-white rounded-[40px] border border-gray-100">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Keranjang Masih Kosong</h2>
                <p class="text-gray-500 mb-6">Yuk, cari jajanan atau kebutuhan kuliahmu dulu!</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 transition">
                    Mulai Belanja
                </a>
            </div>
        @endif

    </div>

    @include('components.footer')

    <!-- Modal Konfirmasi Hapus - DIKOREKSI -->
    <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
            closeDeleteModal() {
                    deleteModalOpen = false;
                },
                handleBackdropClick(e) {
                    if (e.target === e.currentTarget) {
                        this.closeDeleteModal();
                    }
                }
        }"
        @keydown.escape="closeDeleteModal()">

        <!-- Backdrop -->
        <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
            @click="handleBackdropClick($event)">
        </div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-sm p-6"
                @click.stop>

                <div class="text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                        <i class="bi bi-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Produk?</h3>
                    <p class="text-sm text-gray-500 mb-6">
                        Apakah Anda yakin ingin menghapus <span class="font-bold text-gray-800"
                            x-text="itemName"></span> dari keranjang?
                    </p>
                </div>

                <div class="flex gap-3">
                    <form :action="deleteAction" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full bg-red-50 text-red-600 hover:bg-red-100 font-bold py-3 rounded-xl transition">
                            Ya, Hapus
                        </button>
                    </form>

                    <button @click="closeDeleteModal()"
                        class="flex-1 bg-blue-600 text-white hover:bg-blue-700 font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02]">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

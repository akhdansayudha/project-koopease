<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian: {{ $query }} - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    authOpen: false,
    productModalOpen: false,
    activeProduct: { name: '', price: '', stock: '', desc: '', image: '', category: '', quantity: 1 },
    notifOpen: false,
    notifMsg: '',
    addToCart() { this.productModalOpen = false;
        this.notifMsg = 'Berhasil menambahkan ' + this.activeProduct.name + ' ke keranjang';
        this.notifOpen = true;
        setTimeout(() => this.notifOpen = false, 3000); }
}">

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8">

        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-blue-600 mb-2 inline-block">← Kembali
                ke Beranda</a>
            <h1 class="text-3xl font-bold text-gray-900">
                Hasil pencarian: <span class="text-blue-600">"{{ $query }}"</span>
            </h1>
            <p class="text-gray-500 mt-1">Ditemukan {{ $products->count() }} produk</p>
        </div>

        @if ($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 pb-20">
                @foreach ($products as $p)
                    <div @click="productModalOpen = true; 
                        activeProduct = { 
                            id: '{{ $p->product_id }}',
                            name: '{{ $p->nama_produk }}', 
                            price: 'Rp {{ number_format($p->harga, 0, ',', '.') }}', 
                            stock: '{{ $p->stok }}', 
                            desc: '{{ $p->deskripsi ?? 'Tidak ada deskripsi.' }}', 
                            image: '{{ $p->gambar_url ? asset($p->gambar_url) : asset('images/products/default-empty.jpg') }}',
                            category: '{{ $p->category->nama_kategori ?? 'Umum' }}',
                            sold: '{{ $p->terjual ?? 0 }} terjual', 
                            rating: '5.0', 
                            quantity: 1 
                        }"
                        class="bg-white rounded-2xl p-4 border border-gray-100 card-hoverable flex flex-col h-full cursor-pointer relative group transition-all">

                        <div
                            class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden relative group-img flex items-center justify-center">
                            <img src="{{ $p->gambar_url ? asset($p->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                class="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                                onerror="this.onerror=null; this.src='{{ asset('images/products/default-empty.jpg') }}';">

                            <div
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-10">
                                <span
                                    class="text-white text-xs font-bold border border-white px-3 py-1 rounded-full backdrop-blur-sm">Lihat
                                    Detail</span>
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col">
                            <span class="text-xs text-gray-400 mb-1">Stok: {{ $p->stok }}</span>
                            <h4 class="font-bold text-gray-900 text-sm mb-2 line-clamp-2">{{ $p->nama_produk }}</h4>

                            <div class="mt-auto flex justify-between items-center">
                                <span class="font-bold text-blue-600 text-base">Rp
                                    {{ number_format($p->harga, 0, ',', '.') }}</span>
                                <button @click.stop
                                    class="w-8 h-8 rounded-full bg-gray-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition z-20">+</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-[40px] border border-gray-100">
                <div class="text-6xl mb-4">🔍</div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h2>
                <p class="text-gray-500 mb-6">Coba cari dengan kata kunci lain (misal: "roti", "pulpen").</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 transition">
                    Lihat Semua Produk
                </a>
            </div>
        @endif

    </div>

    @include('components.product-modal')
    @include('components.footer')

</body>

</html>

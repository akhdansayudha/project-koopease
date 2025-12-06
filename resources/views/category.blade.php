<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->nama_kategori }} - KoopEase</title>
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

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    productModalOpen: false,
    activeProduct: { name: '', price: '', stock: '', desc: '', image: '', category: '', quantity: 1 },
    notifOpen: false,
    notifMsg: '',
    sortBy: 'default',
    sortLabel: 'Default',
    filterOpen: false,
    addToCart() {
        this.productModalOpen = false;
        this.notifMsg = 'Berhasil menambahkan ' + this.activeProduct.name + ' ke keranjang';
        this.notifOpen = true;
        setTimeout(() => this.notifOpen = false, 5000);
    }
}">

    <div x-show="notifOpen" x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[100] w-[90%] max-w-sm" style="display: none;">
        <div
            class="bg-gray-900/90 backdrop-blur-md text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-gray-700/50">
            <div class="bg-green-500 rounded-full p-1">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="text-xs font-bold text-gray-200">Sukses</h4>
                <p class="text-sm font-medium" x-text="notifMsg"></p>
            </div>
        </div>
    </div>

    @include('components.navbar')

    <div class="max-w-7xl mx-auto p-6 md:p-8 space-y-8">

        {{-- Logika Warna Dinamis dari Database (tanpa array hardcoded) --}}
        @php
            // Ambil warna dari database, default ke 'gray' jika kosong
            $color = $category->color ?? 'gray';

            // Generate class string untuk Header
            $headerBg = "bg-{$color}-50";
            $headerText = "text-{$color}-800";
            $headerBorder = "border-{$color}-200";
            $btnBg = "bg-{$color}-600";
        @endphp

        <div
            class="rounded-bento p-8 relative overflow-hidden {{ $headerBg }} border {{ $headerBorder }} text-gray-800 shadow-sm">
            <div
                class="absolute right-0 bottom-0 w-64 h-64 bg-white/30 rounded-full mix-blend-multiply filter blur-3xl opacity-40 transform translate-y-1/2 translate-x-1/2">
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('home') }}" class="{{ $headerText }}/80 hover:{{ $headerText }} transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </a>
                    <h1 class="text-4xl font-bold {{ $headerText }}">
                        {{ $category->nama_kategori }}
                    </h1>
                </div>
                <p class="text-lg text-gray-700 mb-2">Jelajahi berbagai pilihan
                    {{ strtolower($category->nama_kategori) }} terbaik untuk kebutuhan sehari-hari</p>
                <span
                    class="text-sm {{ $headerText }} bg-white/50 backdrop-blur-sm px-3 py-1 rounded-full font-bold border {{ $headerBorder }}">
                    {{ $products->count() }} Produk Tersedia
                </span>
            </div>
        </div>

        <div class="relative group" x-data="{
            container: $refs.catContainer,
            showLeft: false,
            showRight: false,
            {{-- Default false dulu, nanti diupdate di init --}}
        
            init() {
                // Tunggu sebentar agar elemen ter-render sepenuhnya
                this.$nextTick(() => {
                    // Cari elemen yang punya atribut data-active='true'
                    const activeEl = this.$refs.catContainer.querySelector('[data-active=\'true\']');
        
                    if (activeEl) {
                        // Scroll otomatis agar elemen aktif berada di tengah (center)
                        activeEl.scrollIntoView({ behavior: 'auto', block: 'nearest', inline: 'center' });
                    }
        
                    // Update status tombol panah kiri/kanan
                    this.updateScroll();
                });
            },
        
            updateScroll() {
                // Cek apakah bisa scroll ke kiri
                this.showLeft = this.container.scrollLeft > 0;
                // Cek apakah bisa scroll ke kanan (dengan toleransi 10px)
                this.showRight = this.container.scrollLeft < (this.container.scrollWidth - this.container.clientWidth - 10);
            },
        
            scroll(offset) {
                this.container.scrollBy({ left: offset, behavior: 'smooth' });
            }
        }">
            {{-- Tombol Navigasi Kiri (Hanya muncul jika item > 4) --}}
            @if ($allCategories->count() > 4)
                <button x-show="showLeft" @click="scroll(-300)" x-transition
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 z-20 bg-white shadow-lg border border-gray-100 rounded-full w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-600 hover:scale-110 transition hidden md:flex">
                    <i class="bi bi-chevron-left"></i>
                </button>
            @endif

            {{-- Container Kategori --}}
            <div x-ref="catContainer" @scroll.debounce="updateScroll()"
                class="
                {{-- Jika <= 4, gunakan Flexbox yang mengisi ruang (responsive split) --}}
                @if ($allCategories->count() <= 4) flex flex-wrap md:flex-nowrap gap-4
                @else
                {{-- Jika > 4, gunakan overflow scroll (slider) --}}
                    flex gap-4 overflow-x-auto scrollbar-hide snap-x snap-mandatory py-1 px-1 @endif
                ">

                @foreach ($allCategories as $cat)
                    @php
                        $isActive = $category->category_id === $cat->category_id;
                        $catColor = $cat->color ?? 'gray';

                        $bgColor = "bg-{$catColor}-100";
                        $textColor = 'text-gray-900';
                        $borderColor = "border-{$catColor}-200";

                        $widthClass =
                            $allCategories->count() <= 4 ? 'w-1/2 md:w-full flex-1' : 'min-w-[200px] md:min-w-[240px]';
                    @endphp

                    {{-- TAMBAHKAN: data-active="{{ $isActive ? 'true' : 'false' }}" --}}
                    <a href="{{ route('kategori', $cat->slug) }}" data-active="{{ $isActive ? 'true' : 'false' }}"
                        class="{{ $widthClass }} bg-white rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border {{ $borderColor }} {{ $isActive ? 'ring-2 ring-' . $catColor . '-300 shadow-md' : 'opacity-80 hover:opacity-100' }} snap-center">

                        <div
                            class="w-10 h-10 {{ $bgColor }} rounded-full flex items-center justify-center text-xl mb-3">
                            {{ $cat->icon }}
                        </div>

                        <div class="flex justify-between items-end">
                            <span class="font-bold text-sm {{ $textColor }} whitespace-nowrap truncate">
                                {{ $cat->nama_kategori }}
                            </span>
                            @if ($isActive)
                                <span
                                    class="text-{{ $catColor }}-600 text-xs ml-2 bg-white px-2 py-0.5 rounded-full shadow-sm">Aktif</span>
                            @else
                                <span class="text-gray-400 text-xs ml-2">➜</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Tombol Navigasi Kanan (Hanya muncul jika item > 4) --}}
            @if ($allCategories->count() > 4)
                <button x-show="showRight" @click="scroll(300)" x-transition
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 z-20 bg-white shadow-lg border border-gray-100 rounded-full w-10 h-10 flex items-center justify-center text-gray-600 hover:text-blue-600 hover:scale-110 transition hidden md:flex">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        </div>

        <div
            class="rounded-bento bg-white p-6 border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div class="flex items-center gap-4" x-data="{ sortOpen: false }">
                <span class="text-sm font-bold text-gray-700">Urutkan:</span>

                <div class="relative">
                    <button @click="sortOpen = !sortOpen" @click.outside="sortOpen = false"
                        class="flex items-center justify-between gap-3 px-5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 hover:bg-white hover:shadow-md hover:border-blue-200 transition min-w-[180px]">
                        <span class="flex items-center gap-2">
                            <i class="bi"
                                :class="{
                                    'bi-arrow-clockwise': sortBy === 'default',
                                    'bi-arrow-down': sortBy === 'price_low',
                                    'bi-arrow-up': sortBy === 'price_high',
                                    'bi-fire': sortBy === 'popular',
                                    'bi-star-fill': sortBy === 'rating'
                                }"></i>
                            <span x-text="sortLabel"></span>
                        </span>
                        <i class="bi bi-chevron-down text-xs text-gray-400 transition-transform"
                            :class="sortOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="sortOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                        style="display: none;">

                        <div class="py-1">
                            <button @click="sortBy = 'default'; sortLabel = 'Default'; sortOpen = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2"
                                :class="sortBy === 'default' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                <i class="bi bi-arrow-clockwise w-5"></i> Default
                            </button>
                            <button @click="sortBy = 'price_low'; sortLabel = 'Harga Terendah'; sortOpen = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2"
                                :class="sortBy === 'price_low' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                <i class="bi bi-arrow-down w-5"></i> Harga Terendah
                            </button>
                            <button @click="sortBy = 'price_high'; sortLabel = 'Harga Tertinggi'; sortOpen = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2"
                                :class="sortBy === 'price_high' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                <i class="bi bi-arrow-up w-5"></i> Harga Tertinggi
                            </button>
                            <button @click="sortBy = 'popular'; sortLabel = 'Terlaris'; sortOpen = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2"
                                :class="sortBy === 'popular' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                <i class="bi bi-fire w-5"></i> Terlaris
                            </button>
                            <button @click="sortBy = 'rating'; sortLabel = 'Rating Tertinggi'; sortOpen = false"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-blue-50 hover:text-blue-700 flex items-center gap-2"
                                :class="sortBy === 'rating' ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-700'">
                                <i class="bi bi-star-fill w-5"></i> Rating Tertinggi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 text-sm text-gray-500">
                Menampilkan <span class="font-bold text-gray-900">{{ $products->count() }}</span> produk
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-20">
            @forelse ($products as $p)
                {{-- Ambil warna kategori produk untuk label kecil --}}
                @php
                    $pColor = $p->category->color ?? 'gray';
                @endphp

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
                    class="rounded-bento bg-white p-5 border border-gray-100 card-hoverable flex flex-col h-full cursor-pointer relative group overflow-hidden">

                    @if ($p->terjual > 50)
                        <div class="absolute top-4 left-4 z-20">
                            <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-sm">
                                <i class="bi bi-fire me-1"></i>Terlaris
                            </span>
                        </div>
                    @endif

                    <div
                        class="h-48 bg-gray-50 rounded-2xl mb-4 overflow-hidden relative flex items-center justify-center">
                        <img src="{{ $p->gambar_url ? asset($p->gambar_url) : asset('images/products/default-empty.jpg') }}"
                            alt="{{ $p->nama_produk }}"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-110"
                            onerror="this.onerror=null; this.src='{{ asset('images/products/default-empty.jpg') }}';">

                        <div
                            class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-10">
                            <span
                                class="text-white text-sm font-bold bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full border border-white/30">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </span>
                        </div>
                    </div>

                    <div class="flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            {{-- Label Kategori Dinamis --}}
                            <span
                                class="text-xs font-bold text-{{ $pColor }}-800 bg-{{ $pColor }}-50 px-2 py-1 rounded-full border border-{{ $pColor }}-200">
                                {{ $p->category->nama_kategori ?? 'Umum' }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">
                                <i class="bi bi-box-seam me-1"></i>Stok: {{ $p->stok }}
                            </span>
                        </div>

                        <h4 class="font-bold text-gray-900 text-base mb-3 line-clamp-2 leading-tight">
                            {{ $p->nama_produk }}
                        </h4>

                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center gap-1">
                                <i class="bi bi-star-fill text-yellow-400"></i>
                                <span class="text-xs font-bold text-gray-700">5.0</span>
                            </div>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">
                                <i class="bi bi-graph-up-arrow me-1"></i>{{ $p->terjual ?? 0 }} terjual
                            </span>
                        </div>

                        <div class="mt-auto flex justify-between items-center">
                            <div>
                                <span class="font-bold text-blue-600 text-lg">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </span>
                            </div>
                            <button @click.stop
                                class="w-10 h-10 rounded-full bg-gray-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-110 shadow-sm z-20">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full rounded-bento bg-white p-12 text-center border border-gray-100">
                    <div class="text-8xl mb-6">📦</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Produk</h3>
                    <p class="text-gray-500 text-lg mb-6">Produk untuk kategori ini sedang dalam persiapan.</p>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 transition transform hover:scale-105">
                        <i class="bi bi-house me-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>
            @endforelse
        </div>

    </div>

    @include('components.auth-modal')
    @include('components.product-modal')
    @include('components.footer')

</body>

</html>

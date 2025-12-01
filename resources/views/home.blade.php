<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KoopEase - Koperasi Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

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

        [x-cloak] {
            display: none !important;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    productModalOpen: false,
    activeProduct: {
        id: '',
        name: '',
        price: '',
        stock: '',
        desc: '',
        image: '',
        category: '',
        sold: '',
        rating: '5.0',
        quantity: 1
    },
    notifOpen: false,
    notifMsg: '',

    openProductModal(productData) {
        this.activeProduct = { ...productData };
        this.productModalOpen = true;
    },

    closeProductModal() {
        this.productModalOpen = false;
        setTimeout(() => {
            this.activeProduct = {
                id: '',
                name: '',
                price: '',
                stock: '',
                desc: '',
                image: '',
                category: '',
                sold: '',
                rating: '5.0',
                quantity: 1
            };
        }, 300);
        document.body.style.overflow = 'auto';
    },

    addToCart() {
        this.closeProductModal();
        this.notifMsg = 'Berhasil menambahkan ' + this.activeProduct.name + ' ke keranjang';
        this.notifOpen = true;
        setTimeout(() => this.notifOpen = false, 5000);
    },

    handleEscape(e) {
        if (e.key === 'Escape' && this.productModalOpen) {
            this.closeProductModal();
        }
    }
}" x-init="window.addEventListener('keydown', handleEscape)" @keydown.escape="handleEscape($event)">

    <!-- Notifikasi Toast -->
    <div x-show="notifOpen" x-cloak x-transition:enter="transform ease-out duration-300 transition"
        x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0"
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[100] w-[90%] max-w-sm">
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

        <div>
            <h1 class="text-3xl font-bold text-gray-900">Hei, TelUtizen! 👋</h1>
        </div>

        <!-- Banner Promosi -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 h-auto lg:h-[400px]">

            <!-- Main Banner -->
            <div class="lg:col-span-8 rounded-bento relative overflow-hidden shadow-sm h-[320px] md:h-auto"
                x-data="{
                    activeSlide: 0,
                    slides: [{
                            title: 'Diskon 50%',
                            desc: 'Khusus pembelian paket alat tulis (Pensil + Penghapus + Penggaris).',
                            btnText: 'Cek ATK',
                            link: '{{ route('kategori', 'alat-tulis') }}',
                            bgColor: 'bg-blue-600',
                            tag: 'PROMO UJIAN'
                        },
                        {
                            title: 'Jajan Hemat',
                            desc: 'Lapar saat jam kuliah? Dapatkan paket bundling snack dan minuman.',
                            btnText: 'Lihat Snack',
                            link: '{{ route('kategori', 'snack-makanan') }}',
                            bgColor: 'bg-orange-500',
                            tag: 'BEST SELLER'
                        },
                        {
                            title: 'Merch Baru!',
                            desc: 'Tampil kece dengan Hoodie dan Totebag edisi terbaru universitas.',
                            btnText: 'Pre-Order',
                            link: '{{ route('kategori', 'merch-kampus') }}',
                            bgColor: 'bg-purple-600',
                            tag: 'NEW ARRIVAL'
                        }
                    ],
                    timer: null,
                
                    init() {
                        this.startAutoSlide();
                    },
                
                    startAutoSlide() {
                        this.timer = setInterval(() => {
                            this.nextSlide();
                        }, 5000);
                    },
                
                    stopAutoSlide() {
                        clearInterval(this.timer);
                    },
                
                    nextSlide() {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                    },
                
                    prevSlide() {
                        this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                    }
                }" @mouseenter="stopAutoSlide()" @mouseleave="startAutoSlide()">

                <!-- Slides Container -->
                <div class="relative h-full w-full overflow-hidden">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="absolute inset-0 w-full h-full transition-transform duration-700 ease-in-out flex flex-col justify-center p-8 md:p-12"
                            x-show="activeSlide === index"
                            x-transition:enter="transform transition duration-700 ease-in-out"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition duration-700 ease-in-out"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                            :class="slide.bgColor">

                            <!-- Background Effects -->
                            <div
                                class="absolute right-0 bottom-0 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-20 transform translate-y-1/2 translate-x-1/2 pointer-events-none">
                            </div>
                            <div
                                class="absolute top-0 left-0 w-40 h-40 bg-white rounded-full mix-blend-overlay filter blur-2xl opacity-10 transform -translate-y-1/2 -translate-x-1/2 pointer-events-none">
                            </div>

                            <!-- Content -->
                            <div class="relative z-10 max-w-lg">
                                <span
                                    class="bg-white/20 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold mb-4 inline-block text-white tracking-wider"
                                    x-text="slide.tag"></span>

                                <h2 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight text-white"
                                    x-text="slide.title"></h2>

                                <p class="text-lg text-white/90 mb-8 font-medium leading-relaxed" x-text="slide.desc">
                                </p>

                                <a :href="slide.link"
                                    class="inline-block bg-white text-gray-900 px-8 py-3 rounded-full font-bold shadow-lg hover:bg-gray-50 transition transform hover:scale-105 hover:shadow-xl">
                                    <span x-text="slide.btnText"></span>
                                </a>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Navigation Arrows -->
                <div class="absolute bottom-6 right-8 z-20 flex gap-2">
                    <button @click="prevSlide()"
                        class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/20 transition border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <button @click="nextSlide()"
                        class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white hover:bg-white/20 transition border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Quick Access Cards -->
            <div class="lg:col-span-4 grid grid-cols-2 gap-4">

                {{-- Loop 3 Kategori Teratas dari Database --}}
                @foreach ($categories as $cat)
                    {{-- UPDATE: href menggunakan slug dinamis & tambah class 'h-full' --}}
                    <a href="{{ route('kategori', $cat->slug) }}"
                        class="h-full bg-white rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border border-gray-100">

                        {{-- Icon & Warna Dinamis --}}
                        <div
                            class="w-10 h-10 bg-{{ $cat->color }}-100 rounded-full flex items-center justify-center text-xl mb-2">
                            {{-- Menampilkan Emoji dari Database --}}
                            {{ $cat->icon }}
                        </div>

                        <div class="flex justify-between items-end">
                            <span class="font-bold text-sm leading-tight line-clamp-2">
                                {{ $cat->nama_kategori }}
                            </span>
                            <span class="text-gray-400 text-xs shrink-0 ml-2">➜</span>
                        </div>
                    </a>
                @endforeach

                {{-- Card Ke-4: Tombol "Kategori Lainnya" (Statis) --}}
                <a href="{{ route('kategori', 'snack-makanan') }}"
                    class="h-full bg-gray-50 rounded-bento p-5 flex flex-col justify-between cursor-pointer card-hoverable border border-gray-200 group">

                    <div
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-xl mb-2 shadow-sm border border-gray-100">
                        <i class="bi bi-grid-fill text-gray-600 group-hover:text-blue-600 transition-colors"></i>
                    </div>

                    <div class="flex justify-between items-end">
                        <span
                            class="font-bold text-sm text-gray-600 group-hover:text-blue-600 transition-colors leading-tight">
                            Kategori<br>Lainnya
                        </span>
                        <span
                            class="text-gray-400 text-xs group-hover:translate-x-1 transition-transform shrink-0 ml-2">➜</span>
                    </div>
                </a>

            </div>
        </div>

        <!-- Produk Terpopuler -->
        <div class="mt-12">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">Produk Terpopuler 🔥</h3>
                <a href="{{ route('search') }}?sort=terlaris"
                    class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 pb-20">
                @foreach ($products as $p)
                    <div @click="openProductModal({ 
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
            })"
                        class="bg-white rounded-2xl p-4 border border-gray-100 card-hoverable flex flex-col h-full cursor-pointer relative group transition-all">

                        <!-- Product Image -->
                        <div
                            class="h-40 bg-gray-100 rounded-xl mb-4 overflow-hidden relative flex items-center justify-center">
                            <img src="{{ $p->gambar_url ? asset($p->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                alt="{{ $p->nama_produk }}"
                                class="w-full h-full object-cover transition duration-300 group-hover:scale-110"
                                onerror="this.onerror=null; this.src='{{ asset('images/products/default-empty.jpg') }}';">

                            <div
                                class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-10">
                                <span
                                    class="text-white text-xs font-bold border border-white px-3 py-1 rounded-full backdrop-blur-sm">
                                    Lihat Detail
                                </span>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 flex flex-col">
                            <!-- Badge Terlaris -->
                            @if ($loop->iteration <= 3)
                                <div class="absolute top-3 left-3 z-10">
                                    <span
                                        class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                        <span>🔥</span>
                                        <span>Top {{ $loop->iteration }}</span>
                                    </span>
                                </div>
                            @endif

                            <span class="text-xs text-gray-400 mb-1">Stok: {{ $p->stok }}</span>
                            <h4 class="font-bold text-gray-900 text-sm mb-2 line-clamp-2">{{ $p->nama_produk }}</h4>

                            <!-- Info Penjualan -->
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <span>🛒</span>
                                    <span>{{ $p->terjual ?? 0 }} terjual</span>
                                </span>
                            </div>

                            <div class="mt-auto flex justify-between items-center">
                                <span class="font-bold text-blue-600 text-base">
                                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                                </span>
                                <button
                                    @click.stop="openProductModal({ 
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
                            })"
                                    class="w-8 h-8 rounded-full bg-gray-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition z-20">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Include Components -->
    @include('components.auth-modal')
    @include('components.product-modal')
    @include('components.footer')

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        /* Custom scrollbar untuk container notifikasi (jika diperlukan styling tambahan) */
        .notif-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .notif-scroll::-webkit-scrollbar-track {
            background: #f9fafb;
        }

        .notif-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .notif-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body>
    <nav class="w-full h-20 bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 md:px-8 h-full flex items-center justify-between">

            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition">
                <span class="text-2xl font-bold text-gray-900">KoopEase</span>
            </a>

            {{-- SEARCH BAR --}}
            <div class="hidden md:block flex-1 max-w-xl mx-8 relative" x-data="{
                query: '{{ request('query') }}',
                suggestions: [],
                showSuggestions: false,
            
                async fetchSuggestions() {
                    if (this.query.length < 2) {
                        this.suggestions = [];
                        this.showSuggestions = false;
                        return;
                    }
                    const response = await fetch(`/api/search-suggestions?q=${this.query}`);
                    this.suggestions = await response.json();
                    this.showSuggestions = this.suggestions.length > 0;
                },
            
                selectSuggestion(productName) {
                    this.query = productName;
                    this.showSuggestions = false;
                    this.$nextTick(() => {
                        this.$refs.searchForm.submit();
                    });
                }
            }"
                @click.outside="showSuggestions = false">

                <form x-ref="searchForm" action="{{ route('search') }}" method="GET" class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-30">
                        <i class="bi bi-search text-gray-400 text-lg"></i>
                    </div>

                    <input type="text" name="query" x-model="query" @input.debounce.300ms="fetchSuggestions()"
                        @keydown.escape="showSuggestions = false" autocomplete="off"
                        placeholder="Cari jajan, alat tulis..."
                        class="w-full h-12 pl-12 pr-4 bg-gray-50 rounded-full text-sm outline-none focus:ring-2 focus:ring-blue-100 transition border border-transparent focus:border-blue-200 relative z-20">
                </form>

                <div x-show="showSuggestions" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" x-cloak
                    class="absolute top-14 left-0 w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                    <ul>
                        <template x-for="item in suggestions" :key="item.product_id">
                            <li>
                                <button type="button" @click="selectSuggestion(item.nama_produk)"
                                    class="w-full text-left px-4 py-3 hover:bg-blue-50 transition flex items-center gap-3 group border-b border-gray-50 last:border-0">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        <img :src="item.gambar_url ? item.gambar_url : '/images/products/default-empty.jpg'"
                                            class="w-full h-full object-cover" :alt="item.nama_produk"
                                            onerror="this.onerror=null; this.src='/images/products/default-empty.jpg';">
                                    </div>
                                    <span class="text-sm font-bold text-gray-700 group-hover:text-blue-600"
                                        x-text="item.nama_produk"></span>
                                </button>
                            </li>
                        </template>
                    </ul>
                    <div class="bg-gray-50 px-4 py-2 text-[10px] text-gray-400 text-center border-t border-gray-100">
                        Tekan Enter untuk melihat semua hasil
                    </div>
                </div>
            </div>

            {{-- RIGHT MENU --}}
            <div class="flex items-center gap-3">

                @auth
                    {{-- NOTIFICATION DROPDOWN --}}
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                            class="relative flex items-center justify-center w-10 h-10 bg-gray-50 hover:bg-gray-100 rounded-full transition text-gray-600 border border-gray-100 mr-2">
                            <i class="bi bi-bell text-lg" :class="{ 'text-blue-600': notifOpen }"></i>

                            @if (isset($unreadNotifCount) && $unreadNotifCount > 0)
                                <span
                                    class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white animate-pulse">
                                    {{ $unreadNotifCount }}
                                </span>
                            @endif
                        </button>

                        {{-- PANGGIL COMPONENT NOTIFIKASI BARU DISINI --}}
                        @include('components.notification')

                    </div>

                    {{-- CART BUTTON --}}
                    <a href="{{ route('cart.index') }}"
                        class="relative flex items-center gap-2 px-5 py-2.5 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition text-sm font-bold border border-blue-100">
                        <span>🛍️</span>
                        <span class="hidden md:inline">Keranjang</span>

                        @if (isset($cartCount) && $cartCount > 0)
                            <span
                                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- USER MENU --}}
                    <div x-data="{ dropdownOpen: false }" class="relative ml-2">
                        <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false"
                            class="flex items-center gap-2 pl-4 pr-2 py-1.5 bg-white border border-gray-200 rounded-full hover:shadow-md transition cursor-pointer">
                            <span class="text-sm font-bold text-gray-700">
                                Hai, {{ ucfirst(Auth::user()->name) }}
                            </span>
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        </button>

                        <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100" style="display: none;"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">

                            <div class="px-4 py-3 border-b border-gray-50">
                                <p class="text-xs text-gray-400">Login sebagai</p>
                                <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <span>👤</span> <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('orders.index') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <span>📦</span> <span>Pesanan Saya</span>
                            </a>
                            <a href="{{ route('settings.index') }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                                <span>⚙️</span> <span>Pengaturan</span>
                            </a>

                            <form action="{{ route('logout') }}" method="POST" class="w-full border-t border-gray-100">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 font-bold transition">
                                    <span>🚪</span> <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <button @click="$dispatch('open-auth-modal', { mode: 'login' })"
                        class="text-sm font-bold text-gray-700 hover:text-blue-600 px-2 transition">
                        Masuk
                    </button>
                    <button @click="$dispatch('open-auth-modal', { mode: 'register' })"
                        class="text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-full transition">
                        Daftar
                    </button>
                @endguest

            </div>
        </div>
    </nav>
</body>

</html>
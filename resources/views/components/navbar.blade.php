<!DOCTYPE html>
<html lang="id">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>
    <nav class="w-full h-20 bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 md:px-8 h-full flex items-center justify-between">

            <a href="{{ route('home') }}" class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition">
                <span class="text-2xl font-bold text-gray-900">KoopEase</span>
            </a>

            <!-- Search Bar (Tetap sama) -->
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

            <div class="flex items-center gap-3">

                @auth
                    <div x-data="{ notifOpen: false }" class="relative">
                        <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                            class="relative flex items-center justify-center w-10 h-10 bg-gray-50 hover:bg-gray-100 rounded-full transition text-gray-600 border border-gray-100 mr-2">
                            <i class="bi bi-bell text-lg" :class="{ 'text-blue-600': notifOpen }"></i>

                            @if (isset($unreadNotifCount) && $unreadNotifCount > 0)
                                <span
                                    class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                                    {{ $unreadNotifCount }}
                                </span>
                            @endif
                        </button>

                        <div x-show="notifOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" style="display: none;"
                            class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">

                            <div
                                class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-white sticky top-0 z-10">
                                <h3 class="font-bold text-sm text-gray-900">Notifikasi</h3>
                                @if (isset($unreadNotifCount) && $unreadNotifCount > 0)
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-blue-600 font-bold hover:underline">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="max-h-80 overflow-y-auto">
                                @if (isset($notifications) && count($notifications) > 0)
                                    @foreach ($notifications as $notif)
                                        <!-- LOGIKA KLIK: Mengarah ke route 'notifications.read' -->
                                        <a href="{{ route('notifications.read', $notif->id) }}"
                                            class="block px-4 py-3 border-b border-gray-50 transition-all duration-200 group
                                            {{-- LOGIKA VISUAL: Beda warna background dan opacity --}}
                                            {{ $notif->is_read ? 'bg-white hover:bg-gray-50 opacity-70' : 'bg-blue-50/60 hover:bg-blue-50 border-l-4 border-l-blue-500' }}">

                                            <div class="flex gap-3">
                                                <div class="mt-1 flex-shrink-0">
                                                    @if (str_contains(strtolower($notif->message), 'berhasil') || str_contains(strtolower($notif->message), 'siap'))
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                                            <i class="bi bi-check-circle-fill text-sm"></i>
                                                        </div>
                                                    @elseif(str_contains(strtolower($notif->message), 'batal'))
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                                            <i class="bi bi-x-circle-fill text-sm"></i>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                            <i class="bi bi-info-circle-fill text-sm"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm text-gray-800 leading-snug {{ $notif->is_read ? 'font-medium' : 'font-bold' }}">
                                                        {{ $notif->message }}
                                                    </p>
                                                    <p
                                                        class="text-[10px] mt-1 {{ $notif->is_read ? 'text-gray-400' : 'text-blue-600 font-semibold' }}">
                                                        {{ $notif->created_at->locale('id')->diffForHumans() }}
                                                    </p>
                                                </div>
                                                @if (!$notif->is_read)
                                                    <div class="mt-2 w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                        <div
                                            class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-2">
                                            <i class="bi bi-bell-slash text-gray-400 text-xl"></i>
                                        </div>
                                        <p class="text-xs text-gray-500 font-medium">Belum ada notifikasi</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

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

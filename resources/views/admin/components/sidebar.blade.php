<aside id="sidebar"
    class="bg-white border-r border-gray-200 flex flex-col h-screen w-64 z-50 lg:sidebar-open sidebar-closed">

    {{-- HEADER SIDEBAR --}}
    <div class="p-4 flex items-center gap-3 border-b border-gray-100 min-h-[70px]">
        <div
            class="w-8 h-8 bg-blue-600 rounded-xl flex items-center justify-center text-white flex-shrink-0 shadow-lg shadow-blue-200">
            <i class="bi bi-shop-window text-sm"></i>
        </div>
        <div class="flex-1 overflow-hidden">
            <span class="text-lg font-bold text-gray-800 whitespace-nowrap tracking-tight">KoopEase</span>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-700 transition-colors">
            <i class="bi bi-x-circle-fill text-xl"></i>
        </button>
    </div>

    {{-- MENU NAVIGASI --}}
    <nav class="flex-1 p-4 space-y-1.5 sidebar-scroll overflow-y-auto">
        <p class="px-2 text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 mt-1">Menu Utama</p>

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
                  {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-grid-fill text-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Dashboard</span>
        </a>

        {{-- Produk --}}
        <a href="{{ route('admin.products.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
                  {{ request()->routeIs('admin.products.*') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-box-seam-fill text-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Produk</span>
        </a>

        {{-- Pesanan --}}
        @php
            $pendingOrders = \App\Models\Order::whereIn('status', [
                'menunggu_pembayaran',
                'menunggu_konfirmasi',
            ])->count();
        @endphp
        <a href="{{ route('admin.orders.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
                  {{ request()->routeIs('admin.orders.*') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-bag-check-fill text-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Pesanan</span>
            @if ($pendingOrders > 0)
                <span
                    class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full min-w-[20px] h-[20px] flex items-center justify-center shadow-sm shadow-red-200">
                    {{ $pendingOrders }}
                </span>
            @endif
        </a>

        {{-- Kategori --}}
        <a href="{{ route('admin.categories.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
                  {{ request()->routeIs('admin.categories.*') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-layers-fill text-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Kategori</span>
        </a>

        {{-- Pengguna --}}
        <a href="{{ route('admin.users.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
                  {{ request()->routeIs('admin.users.*') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-people-fill text-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Pengguna</span>
        </a>

        {{-- Laporan --}}
        <a href="{{ route('admin.reports.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
                  {{ request()->routeIs('admin.reports.*') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-pie-chart-fill text-lg transition-colors {{ request()->routeIs('admin.reports.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Laporan</span>
        </a>

        {{-- Broadcast --}}
        <a href="{{ route('admin.notifications.index') }}"
            class="group flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 font-medium
          {{ request()->routeIs('admin.notifications.*') ? 'text-blue-600 bg-blue-50 ring-1 ring-blue-100' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                <i
                    class="bi bi-megaphone-fill text-lg transition-colors {{ request()->routeIs('admin.notifications.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            <span class="text-sm whitespace-nowrap">Broadcast</span>
        </a>
    </nav>

    {{-- FOOTER SIDEBAR --}}
    <div class="p-4 border-t border-gray-100">
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="group flex items-center gap-3 w-full px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-red-50 hover:text-red-600 hover:ring-1 hover:ring-red-100">
                <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                    <i
                        class="bi bi-box-arrow-right text-lg text-gray-400 group-hover:text-red-500 transition-colors"></i>
                </div>
                <span class="whitespace-nowrap">Keluar Admin</span>
            </button>
        </form>
    </div>
</aside>

{{-- Script Javascript untuk Handle State & Animasi --}}
<script>
    // Definisikan fungsi secara global agar bisa dipanggil dari Header
    window.toggleSidebar = function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay = document.getElementById('sidebarOverlay');

        // Cek status saat ini
        const isClosed = sidebar.classList.contains('sidebar-closed');
        const isMobile = window.innerWidth < 1024;

        if (isClosed) {
            // BUKA SIDEBAR
            sidebar.classList.remove('sidebar-closed');
            sidebar.classList.add('lg:sidebar-open');

            if (mainContent) {
                mainContent.classList.add('lg:content-shrinked');
                mainContent.classList.remove('lg:content-expanded');
            }

            if (isMobile && overlay) {
                overlay.classList.remove('hidden');
            }

            localStorage.setItem('sidebarState', 'open');
        } else {
            // TUTUP SIDEBAR
            sidebar.classList.add('sidebar-closed');
            sidebar.classList.remove('lg:sidebar-open');

            if (mainContent) {
                mainContent.classList.remove('lg:content-shrinked');
                mainContent.classList.add('lg:content-expanded');
            }

            if (isMobile && overlay) {
                overlay.classList.add('hidden');
            }

            localStorage.setItem('sidebarState', 'closed');
        }
    };

    // Script Inisialisasi (Jalan Otomatis saat Load)
    (function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay = document.getElementById('sidebarOverlay');

        // 1. CEK LOCALSTORAGE - Default 'open' jika null/tidak ada
        const savedState = localStorage.getItem('sidebarState');
        const isSidebarOpen = savedState === 'closed' ? false : true;
        const isMobile = window.innerWidth < 1024;

        // 2. TERAPKAN STATE AWAL (Tanpa Animasi)
        if (!isSidebarOpen && !isMobile) {
            // Force Close - Hanya di desktop
            sidebar.classList.remove('lg:sidebar-open');
            sidebar.classList.add('sidebar-closed');

            if (mainContent) {
                mainContent.classList.remove('lg:content-shrinked');
                mainContent.classList.add('lg:content-expanded');
            }
        } else {
            // Force Open atau di mobile
            sidebar.classList.add('lg:sidebar-open');
            sidebar.classList.remove('sidebar-closed');

            if (mainContent) {
                mainContent.classList.add('lg:content-shrinked');
                mainContent.classList.remove('lg:content-expanded');
            }

            // Di mobile, sidebar default tertutup
            if (isMobile) {
                sidebar.classList.add('sidebar-closed');
                sidebar.classList.remove('lg:sidebar-open');
                if (overlay) overlay.classList.add('hidden');
            }
        }

        // 3. Handle overlay click untuk mobile
        if (overlay) {
            overlay.addEventListener("click", function() {
                if (window.innerWidth < 1024) {
                    toggleSidebar();
                }
            });
        }

        // 4. Handle resize event
        window.addEventListener('resize', function() {
            const isMobile = window.innerWidth < 1024;
            const savedState = localStorage.getItem('sidebarState');
            const isSidebarOpen = savedState === 'closed' ? false : true;

            if (isMobile) {
                // Di mobile, selalu tutup sidebar
                sidebar.classList.add('sidebar-closed');
                if (overlay) overlay.classList.add('hidden');
            } else {
                // Di desktop, ikuti state yang disimpan
                if (isSidebarOpen) {
                    sidebar.classList.remove('sidebar-closed');
                    sidebar.classList.add('lg:sidebar-open');
                    if (mainContent) {
                        mainContent.classList.add('lg:content-shrinked');
                        mainContent.classList.remove('lg:content-expanded');
                    }
                } else {
                    sidebar.classList.add('sidebar-closed');
                    sidebar.classList.remove('lg:sidebar-open');
                    if (mainContent) {
                        mainContent.classList.remove('lg:content-shrinked');
                        mainContent.classList.add('lg:content-expanded');
                    }
                }
            }
        });

        // 5. AKTIFKAN ANIMASI (Delay sedikit agar render awal selesai)
        setTimeout(() => {
            sidebar.classList.add('transition-all', 'duration-300');
            if (mainContent) {
                mainContent.classList.add('transition-all', 'duration-300');
            }
        }, 100);
    })();
</script>

<style>
    .sidebar-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sidebar-scroll::-webkit-scrollbar {
        display: none;
    }

    #sidebar {
        flex-shrink: 0;
        height: 100vh;
        position: sticky;
        top: 0;
        transition: width 0.3s ease, transform 0.3s ease;
    }

    /* Logic Tampilan Desktop */
    .lg\:sidebar-open {
        width: 16rem;
        transform: translateX(0);
    }

    .sidebar-closed {
        width: 0 !important;
        border: none;
        overflow: hidden;
        transform: translateX(0);
    }

    /* Content classes untuk desktop */
    .lg\:content-shrinked {
        width: calc(100% - 16rem);
    }

    .lg\:content-expanded {
        width: 100%;
    }

    /* Mobile specific styles */
    @media (max-width: 1023px) {
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
        }

        .sidebar-closed {
            width: 16rem !important;
            transform: translateX(-100%);
            border-right: 1px solid #e5e7eb;
        }
    }

    /* Desktop specific styles */
    @media (min-width: 1024px) {
        #sidebar {
            position: sticky;
            height: 100vh;
        }
    }
</style>

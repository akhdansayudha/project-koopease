<header
    class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-30 shadow-sm">
    <!-- Left Section: Hanya tombol burger menu -->
    <div class="flex items-center gap-4">
        <button id="sidebarToggle" type="button" onclick="toggleSidebar()"
            class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg p-2 transition">
            <i class="bi bi-list text-2xl"></i>
        </button>
    </div>

    <!-- Right Section: User Profile & Notifications -->
    <div class="flex items-center gap-3">
        <!-- Notifications -->
        <div x-data="{ notifOpen: false }" class="relative">
            <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                class="relative flex items-center justify-center w-10 h-10 bg-gray-50 hover:bg-gray-100 rounded-full transition text-gray-600 border border-gray-100 mr-2">
                <i class="bi bi-bell text-lg"></i>

                @php
                    use App\Models\Notification;
                    $unreadNotifCount = Notification::where('is_read', false)->count();
                @endphp
                @if ($unreadNotifCount > 0)
                    <span
                        class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white ring-2 ring-white">
                        {{ $unreadNotifCount }}
                    </span>
                @endif
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="notifOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" x-cloak
                class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">

                <div class="px-4 py-3 border-b border-gray-50 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-sm text-gray-900">Notifikasi</h3>
                    <span class="text-[10px] text-gray-500">Terbaru</span>
                </div>

                <div class="max-h-64 overflow-y-auto">
                    @php
                        $notifications = Notification::latest()->limit(5)->get();
                    @endphp
                    @if (count($notifications) > 0)
                        @foreach ($notifications as $notif)
                            <a href="{{ $notif->order_id ? route('orders.show', $notif->order_id) : '#' }}"
                                class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 transition {{ $notif->is_read ? 'opacity-60' : 'bg-blue-50/30' }}">
                                <div class="flex gap-3">
                                    <div class="mt-1">
                                        @if (str_contains(strtolower($notif->message), 'berhasil') || str_contains(strtolower($notif->message), 'siap'))
                                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        @elseif(str_contains(strtolower($notif->message), 'batal'))
                                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                        @else
                                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <p
                                            class="text-xs text-gray-800 leading-snug {{ $notif->is_read ? '' : 'font-bold' }}">
                                            {{ $notif->message }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1">
                                            {{ $notif->created_at->locale('id')->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="px-4 py-6 text-center">
                            <p class="text-xs text-gray-400">Belum ada notifikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Profile -->
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

            <!-- Profile Dropdown -->
            <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95" x-cloak
                class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">

                <div class="px-4 py-3 border-b border-gray-50">
                    <p class="text-xs text-gray-400">Login sebagai</p>
                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition">
                    <span>👤</span> <span>Profil Saya</span>
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
    </div>
</header>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>

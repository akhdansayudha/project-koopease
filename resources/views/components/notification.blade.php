<div x-show="notifOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2" style="display: none;"
    class="absolute right-0 mt-3 w-[90vw] md:w-[400px] bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-[60] origin-top-right ring-1 ring-black ring-opacity-5"
    @click.stop>

    {{-- HEADER --}}
    <div class="px-5 py-4 border-b border-gray-50 flex justify-between items-center bg-white sticky top-0 z-10">
        <h3 class="font-extrabold text-lg text-gray-900">Notifikasi</h3>
        @if (isset($unreadNotifCount) && $unreadNotifCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit"
                    class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-50 px-2 py-1 rounded-lg transition">
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- LIST --}}
    <div class="max-h-[70vh] overflow-y-auto notif-scroll bg-white">
        @if (isset($notifications) && count($notifications) > 0)
            @foreach ($notifications as $notif)
                {{-- LOGIKA WARNA BADGE BERDASARKAN TIPE --}}
                @php
                    // Default Style (Info)
                    $badgeBg = 'bg-blue-100';
                    $badgeText = 'text-blue-700';
                    $label = 'Info';
                    $borderClass = 'border-l-transparent'; // Default border

                    // Cek Tipe dari Database atau Isi Pesan
                    if ($notif->type == 'order' || str_contains(strtolower($notif->message), 'pesanan')) {
                        $badgeBg = 'bg-green-100';
                        $badgeText = 'text-green-700';
                        $label = 'Pesanan';
                    } elseif ($notif->type == 'promo' || str_contains(strtolower($notif->message), 'diskon')) {
                        $badgeBg = 'bg-red-100';
                        $badgeText = 'text-red-700';
                        $label = 'Promo';
                    }

                    // Style untuk Belum Dibaca (Background sedikit gelap + border kiri)
                    if (!$notif->is_read) {
                        $borderClass = 'border-l-blue-500 bg-slate-50';
                    } else {
                        $borderClass = 'border-l-transparent hover:bg-gray-50';
                    }
                @endphp

                <a href="{{ route('notifications.read', $notif->id) }}"
                    class="block w-full text-left px-5 py-4 border-b border-gray-50 transition duration-200 group border-l-[3px] {{ $borderClass }}">

                    <div class="flex flex-col gap-1"> {{-- Mengurangi gap antar elemen flex --}}

                        {{-- BARIS ATAS: Badge Label & Waktu --}}
                        <div class="flex items-center justify-between mb-0"> {{-- Mengurangi margin bottom --}}
                            <span
                                class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $badgeBg }} {{ $badgeText }}">
                                {{ $label }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-medium">
                                {{ $notif->created_at->locale('id')->diffForHumans() }}
                            </span>
                        </div>

                        {{-- KONTEN PESAN --}}
                        <div class="w-full relative">
                            <p
                                class="text-sm leading-snug break-words whitespace-pre-line {{ $notif->is_read ? 'text-gray-600 font-medium' : 'text-gray-900 font-bold' }}">
                                {{ $notif->message }}
                            </p>

                            {{-- Indikator Merah Kecil jika belum dibaca (Opsional, di pojok kanan bawah pesan) --}}
                            @if (!$notif->is_read)
                                <span
                                    class="absolute -right-2 top-1 inline-block w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            {{-- STATE KOSONG --}}
            <div class="px-6 py-16 text-center flex flex-col items-center justify-center">
                <div
                    class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                    <i class="bi bi-bell-slash text-gray-400 text-2xl"></i>
                </div>
                <h4 class="text-gray-900 font-bold mb-1">Tidak ada notifikasi</h4>
                <p class="text-xs text-gray-500 max-w-[200px] mx-auto leading-relaxed">
                    Belum ada aktivitas terbaru yang perlu ditampilkan.
                </p>
            </div>
        @endif
    </div>
</div>

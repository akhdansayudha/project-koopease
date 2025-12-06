@extends('layouts.admin')

@section('content')
    <div class="space-y-6" x-data="{
        search: '{{ request('search') }}',
        toastOpen: false,
        toastMessage: '',
    
        // Fungsi untuk memunculkan Toast
        showToast(message) {
            this.toastMessage = message;
            this.toastOpen = true;
            setTimeout(() => {
                this.toastOpen = false;
            }, 3000);
        },
    
        // Fungsi Copy Data
        copyOrderData(order) {
            // Helper Format Rupiah
            const formatRp = (num) => 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
    
            // Helper Format Tanggal & Jam
            const dateObj = new Date(order.created_at);
            const dateStr = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            const timeStr = dateObj.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':') + ' WIB';
    
            // Generate List Item dengan Harga
            let itemsText = '';
            order.order_items.forEach(item => {
                const productName = item.product ? item.product.nama_produk : 'Item dihapus';
                // Gunakan harga saat pesan jika ada, jika tidak gunakan harga produk saat ini
                const price = item.harga_saat_pesan || (item.product ? item.product.harga : 0);
                itemsText += `- ${item.quantity}x ${productName} ${formatRp(price)}\n`;
            });
    
            // Template Text Sesuai Request
            const textToCopy = `DETAIL PESANAN #${order.id}\n` +
                `------------------------\n` +
                `👤 Pelanggan: ${order.user ? order.user.name : 'Guest'}\n` +
                `📅 Tanggal: ${dateStr}\n` +
                `🕑 Jam: ${timeStr}\n` +
                `💰 Total: ${formatRp(order.total_price)}\n` +
                `📦 Status: ${order.status.toUpperCase().replace('_', ' ')}\n` +
                `------------------------\n` +
                `DAFTAR ITEM:\n` +
                `${itemsText}`;
    
            // Eksekusi Copy ke Clipboard
            navigator.clipboard.writeText(textToCopy).then(() => {
                this.showToast(`Detail pesanan #${order.id} berhasil disalin`);
            });
        }
    }">

        <div class="flex flex-col gap-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pesanan</h1>
                    <p class="text-gray-500 text-sm mt-1">Pantau transaksi dan status pesanan masuk.</p>
                </div>

                <form action="{{ route('admin.orders.index') }}" method="GET" class="relative group w-full md:w-80">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari ID, Nama, atau Nominal..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-all duration-200 shadow-sm placeholder-gray-400 font-medium">
                    <div
                        class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200">
                        <i class="bi bi-search text-lg"></i>
                    </div>
                </form>
            </div>

            <div class="w-full bg-white rounded-2xl border border-gray-100 shadow-sm p-1">
                <div class="flex">
                    @php
                        $statuses = [
                            'semua' => 'Semua',
                            'menunggu_pembayaran' => 'Menunggu Bayar',
                            'menunggu_konfirmasi' => 'Cek Konfirmasi',
                            'siap_diambil' => 'Siap Diambil',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Batal',
                        ];
                        $currentStatus = request('status', 'semua');
                    @endphp

                    @foreach ($statuses as $key => $label)
                        <a href="{{ route('admin.orders.index', ['status' => $key, 'search' => request('search')]) }}"
                            class="flex-1 min-w-0 text-center px-2 py-3 mx-0.5 text-xs font-bold transition-all duration-200 border-0 rounded-2xl
                {{ $currentStatus == $key
                    ? 'bg-gray-900 text-white shadow-md transform -translate-y-0.5'
                    : 'bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-[10%] text-left">Order ID</th>
                            <th class="px-6 py-4 w-[22%] text-left">Pelanggan</th>
                            <th class="px-6 py-4 w-[18%] text-left">Total</th>
                            <th class="px-6 py-4 w-[15%] text-left">Tanggal</th>
                            <th class="px-6 py-4 w-[22%] text-left">Status</th>
                            <th class="px-6 py-4 w-[13] text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 font-mono font-bold text-gray-900 text-left">
                                    #{{ $order->id }}
                                </td>

                                <td class="px-6 py-4 text-left">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-700 flex items-center justify-center font-bold text-xs">
                                            {{ substr($order->user->name ?? 'G', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">
                                                {{ $order->user->name ?? 'User Terhapus' }}</p>
                                            <p class="text-xs text-gray-400">{{ $order->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-left">
                                    <span class="font-bold text-gray-900">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-left">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-700 text-xs">
                                            {{ $order->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-[10px] text-gray-400">
                                            {{ $order->created_at->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-left">
                                    @php
                                        $statusStyles = [
                                            'menunggu_pembayaran' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                            'menunggu_konfirmasi' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'siap_diambil' => 'bg-purple-50 text-purple-700 border-purple-100',
                                            'selesai' => 'bg-green-50 text-green-700 border-green-100',
                                            'dibatalkan' => 'bg-red-50 text-red-700 border-red-100',
                                        ];
                                        $style =
                                            $statusStyles[$order->status] ?? 'bg-gray-50 text-gray-600 border-gray-200';
                                        $label = ucwords(str_replace('_', ' ', $order->status));
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border {{ $style }}">
                                        {{ $label }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-left">
                                    <div class="flex items-center justify-start gap-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <button @click="copyOrderData({{ $order }})"
                                            class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-200 rounded-lg text-gray-600 hover:text-green-600 hover:border-green-200 hover:bg-green-50 transition-all shadow-sm group"
                                            title="Salin Data Pesanan">
                                            <i class="bi bi-clipboard group-hover:hidden"></i>
                                            <i class="bi bi-clipboard-check-fill hidden group-hover:block"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-inbox text-4xl text-gray-300"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Tidak ada pesanan ditemukan</h3>
                                        <p class="text-gray-500 text-sm mt-1">Coba ubah filter status atau kata kunci
                                            pencarian.</p>
                                        <a href="{{ route('admin.orders.index') }}"
                                            class="mt-4 text-sm font-bold text-blue-600 hover:underline">
                                            Reset Filter
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $orders->links('admin.components.pagination') }}
                </div>
            @endif
        </div>

        <div x-show="toastOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-gray-900 text-white px-5 py-3 rounded-2xl shadow-2xl"
            style="display: none;">
            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-black">
                <i class="bi bi-check text-sm font-bold"></i>
            </div>
            <span class="text-sm font-semibold" x-text="toastMessage"></span>
        </div>

    </div>
@endsection

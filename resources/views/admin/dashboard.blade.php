@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Dashboard</h1>
                <p class="text-gray-500 text-sm mt-1">Ringkasan aktivitas koperasi hari ini</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center gap-3">
                <span class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total Penjualan</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">Rp
                            {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl"><i
                            class="bi bi-cash-coin"></i></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-2xl border border-red-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-red-600 uppercase tracking-wide">Stok Menipis</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['low_stock'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center text-xl"><i
                            class="bi bi-box-seam"></i></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Total Produk</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['total_products'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-xl flex items-center justify-center text-xl"><i
                            class="bi bi-grid-fill"></i></div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Jumlah Pengguna</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['total_customers'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-600 text-white rounded-xl flex items-center justify-center text-xl"><i
                            class="bi bi-person-badge"></i></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Status Pesanan</h3>
                        <a href="{{ route('admin.orders.index') }}"
                            class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua</a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @php
                            $statusConfig = [
                                'menunggu_pembayaran' => [
                                    'title' => 'Menunggu Bayar',
                                    'color' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'icon' => 'bi-clock',
                                ],
                                'menunggu_konfirmasi' => [
                                    'title' => 'Cek Konfirmasi',
                                    'color' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'icon' => 'bi-file-earmark-text',
                                ],
                                'siap_diambil' => [
                                    'title' => 'Siap Diambil',
                                    'color' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'icon' => 'bi-bag-check',
                                ],
                                'selesai' => [
                                    'title' => 'Selesai',
                                    'color' => 'bg-green-100 text-green-800 border-green-200',
                                    'icon' => 'bi-check-circle',
                                ],
                                'dibatalkan' => [
                                    'title' => 'Dibatalkan',
                                    'color' => 'bg-red-100 text-red-800 border-red-200',
                                    'icon' => 'bi-x-circle',
                                ],
                            ];
                        @endphp
                        @foreach ($statusConfig as $status => $config)
                            @php
                                $count = $orderStatusCounts[$status]->count ?? 0;
                            @endphp
                            <div
                                class="text-center p-4 rounded-xl border-2 {{ $config['color'] }} hover:shadow-md transition-shadow">
                                <div
                                    class="w-10 h-10 {{ str_replace('bg-', 'bg-', $config['color']) }} rounded-lg flex items-center justify-center mx-auto mb-2">
                                    <i class="{{ $config['icon'] }} text-lg"></i>
                                </div>
                                <h4 class="text-xl font-extrabold">{{ $count }}</h4>
                                <p class="text-xs font-semibold mt-1">{{ $config['title'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Penjualan Minggu Ini</h3>
                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-lg">Update
                            Realtime</span>
                    </div>
                    <div class="w-full h-64">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col h-full">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Produk Terlaris</h3>
                <div class="space-y-4 overflow-y-auto max-h-[500px] pr-2 custom-scrollbar">
                    @forelse($bestSellingProducts as $product)
                        <div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">

                            <div class="flex-shrink-0 w-6 text-center">
                                @if ($loop->iteration == 1)
                                    <span class="text-yellow-500 font-black text-lg">1</span>
                                @elseif($loop->iteration == 2)
                                    <span class="text-gray-400 font-bold text-lg">2</span>
                                @elseif($loop->iteration == 3)
                                    <span class="text-orange-700 font-bold text-lg">3</span>
                                @else
                                    <span class="text-gray-300 font-bold">{{ $loop->iteration }}</span>
                                @endif
                            </div>

                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                @if ($product->gambar_url)
                                    <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_produk }}"
                                        class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <i class="bi bi-image text-gray-400"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $product->nama_produk }}</p>
                                <p class="text-xs text-gray-500">Terjual: {{ $product->terjual }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">Rp
                                    {{ number_format($product->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500">
                            <i class="bi bi-box text-2xl mb-2"></i>
                            <p class="text-sm">Belum ada data penjualan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-900 font-bold uppercase text-xs">
                        <tr>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Tanggal & Waktu</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-gray-900">#{{ $order->id }}</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $order->user->name ?? 'User Terhapus' }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $order->user->email ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match ($order->status) {
                                            'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-800',
                                            'menunggu_konfirmasi' => 'bg-orange-100 text-orange-800',
                                            'siap_diambil' => 'bg-blue-100 text-blue-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            'dibatalkan' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                        $statusLabel = ucwords(str_replace('_', ' ', $order->status));
                                    @endphp
                                    <span
                                        class="{{ $statusClass }} px-3 py-1.5 rounded-full text-xs font-bold">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ $order->created_at->format('d M Y') }}</span>
                                        <span class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}
                                            WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-semibold transition-colors">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dailySalesChart').getContext('2d');

            // Gradient Fill
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // Blue 600 with opacity
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailySalesLabels) !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($dailySalesData) !!},
                        borderColor: '#2563eb', // Blue 600
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4 // Smooth curve
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#f3f4f6'
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                },
                                font: {
                                    size: 10
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
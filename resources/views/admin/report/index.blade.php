@extends('layouts.admin')

@section('content')
    {{-- Custom CSS untuk mempercantik Input Date Native --}}
    <style>
        /* Mengubah icon kalender bawaan menjadi invisible tapi menutupi seluruh input agar mudah diklik,
               atau kita ganti style icon-nya */
        input[type="date"]::-webkit-calendar-picker-indicator {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%236b7280" class="bi bi-calendar-event" viewBox="0 0 16 16"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>');
            background-position: center;
            background-size: 20px;
            cursor: pointer;
            opacity: 0.6;
            transition: 0.2s;
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
            transform: scale(1.1);
        }
    </style>

    {{-- Logic PHP untuk menentukan state tombol aktif --}}
    @php
        $isToday = $startDate->isSameDay(now()) && $endDate->isSameDay(now());
        $isWeek = $startDate->isSameDay(now()->startOfWeek()) && $endDate->isSameDay(now()->endOfWeek());
        $isMonth = $startDate->isSameDay(now()->startOfMonth()) && $endDate->isSameDay(now()->endOfMonth());
    @endphp

    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Laporan & Analisis</h1>
                <p class="text-gray-500 text-sm mt-1">Rekapitulasi penjualan periode <span
                        class="font-bold text-gray-800">{{ $startDate->format('d M Y') }} -
                        {{ $endDate->format('d M Y') }}</span></p>
            </div>

            <a href="{{ route('admin.reports.export', request()->query()) }}"
                class="group relative inline-flex items-center justify-center px-6 py-2.5 font-semibold text-white transition-all duration-200 bg-gray-900 rounded-xl hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-lg shadow-gray-200 hover:shadow-gray-300 hover:-translate-y-0.5">
                <i class="bi bi-file-earmark-pdf-fill mr-2"></i>
                Unduh PDF
            </a>
        </div>

        {{-- Filter Section (Revised Layout) --}}
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm" x-data>
            <form action="{{ route('admin.reports.index') }}" method="GET">
                {{-- Menggunakan Grid System agar proporsi lebih rapi --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">

                    {{-- Kolom 1: Periode Cepat (Lebar: 5/12) --}}
                    <div class="lg:col-span-5 space-y-3">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">
                            <i class="bi bi-lightning-charge-fill text-yellow-500 mr-1"></i> Periode Cepat
                        </label>
                        <div class="flex bg-gray-100/80 p-1.5 rounded-xl border border-gray-200 gap-1">
                            {{-- Button Hari Ini --}}
                            <button type="button"
                                @click="$refs.start.value = '{{ now()->format('Y-m-d') }}'; $refs.end.value = '{{ now()->format('Y-m-d') }}'; $el.closest('form').submit()"
                                class="flex-1 px-3 py-2 text-xs font-bold rounded-lg transition-all duration-200 shadow-sm {{ $isToday ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                                Hari Ini
                            </button>
                            {{-- Button Minggu Ini --}}
                            <button type="button"
                                @click="$refs.start.value = '{{ now()->startOfWeek()->format('Y-m-d') }}'; $refs.end.value = '{{ now()->endOfWeek()->format('Y-m-d') }}'; $el.closest('form').submit()"
                                class="flex-1 px-3 py-2 text-xs font-bold rounded-lg transition-all duration-200 shadow-sm {{ $isWeek ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                                Minggu Ini
                            </button>
                            {{-- Button Bulan Ini --}}
                            <button type="button"
                                @click="$refs.start.value = '{{ now()->startOfMonth()->format('Y-m-d') }}'; $refs.end.value = '{{ now()->endOfMonth()->format('Y-m-d') }}'; $el.closest('form').submit()"
                                class="flex-1 px-3 py-2 text-xs font-bold rounded-lg transition-all duration-200 shadow-sm {{ $isMonth ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">
                                Bulan Ini
                            </button>
                        </div>
                    </div>

                    {{-- Kolom 2: Custom Date Range (Lebar: 5/12) --}}
                    <div class="lg:col-span-5 grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase ml-1">Dari</label>
                            <div class="relative group">
                                <input type="date" name="start_date" x-ref="start"
                                    value="{{ $startDate->format('Y-m-d') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border-gray-200 bg-gray-50 text-gray-700 font-semibold text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-sm cursor-pointer hover:bg-gray-100">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase ml-1">Sampai</label>
                            <div class="relative group">
                                <input type="date" name="end_date" x-ref="end"
                                    value="{{ $endDate->format('Y-m-d') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border-gray-200 bg-gray-50 text-gray-700 font-semibold text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all shadow-sm cursor-pointer hover:bg-gray-100">
                            </div>
                        </div>
                    </div>

                    {{-- Kolom 3: Submit Button (Lebar: 2/12) --}}
                    <div class="lg:col-span-2">
                        <button type="submit"
                            class="w-full h-[46px] bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                    </div>

                </div>
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm">
                <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Omset (Selesai)</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">Rp
                    {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200 shadow-sm">
                <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Produk Terjual</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($stats['items_sold']) }} <span
                        class="text-sm font-medium text-green-700">Pcs</span></h3>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm">
                <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Transaksi Berhasil</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($stats['total_transactions']) }}
                </h3>
            </div>
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-2xl border border-orange-200 shadow-sm">
                <p class="text-xs font-bold text-orange-600 uppercase tracking-wide">Pesanan Dibatalkan</p>
                <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ number_format($statusSummary['dibatalkan']) }}
                </h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                {{-- Status Summary --}}
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Total Pesanan Masuk</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                            <span class="text-xs font-bold text-yellow-800 uppercase">Menunggu Bayar</span>
                            <span class="font-extrabold text-gray-900">{{ $statusSummary['menunggu_pembayaran'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="text-xs font-bold text-blue-800 uppercase">Perlu Konfirmasi</span>
                            <span class="font-extrabold text-gray-900">{{ $statusSummary['menunggu_konfirmasi'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl border border-purple-100">
                            <span class="text-xs font-bold text-purple-800 uppercase">Siap Diambil</span>
                            <span class="font-extrabold text-gray-900">{{ $statusSummary['siap_diambil'] }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl border border-green-100">
                            <span class="text-xs font-bold text-green-800 uppercase">Selesai</span>
                            <span class="font-extrabold text-gray-900">{{ $statusSummary['selesai'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Top Products --}}
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Top 5 Produk Terlaris</h3>
                    <div class="space-y-4">
                        @foreach ($topProducts as $idx => $prod)
                            <div
                                class="flex items-center justify-between border-b border-gray-50 pb-2 last:border-0 last:pb-0">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ $idx + 1 }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate">
                                            {{ $prod->nama_produk_saat_pesan }}</p>
                                        <p class="text-xs text-gray-500">Rp
                                            {{ number_format($prod->total_sales, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="shrink-0">
                                    <span
                                        class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg">
                                        {{ $prod->total_qty }} Sold
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                {{-- Chart Section --}}
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Grafik Omset Harian</h3>
                        {{-- Indikator rentang waktu chart --}}
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">
                            {{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}
                        </span>
                    </div>
                    <div class="h-64 w-full relative">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Table Details --}}
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Rincian Transaksi (Selesai)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50/50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 w-[20%]">Tanggal</th>
                                    <th class="px-6 py-4 w-[15%]">Order ID</th>
                                    <th class="px-6 py-4 w-[20%]">Pelanggan</th>
                                    <th class="px-6 py-4 w-[25%]">Item</th>
                                    <th class="px-6 py-4 w-[20%] text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($finishedOrders as $order)
                                    <tr class="hover:bg-gray-50/80 transition-colors">
                                        <td class="px-6 py-4">
                                            <span
                                                class="font-semibold text-gray-700">{{ $order->created_at->format('d M Y') }}</span>
                                            <span
                                                class="text-xs text-gray-400 block">{{ $order->created_at->format('H:i') }}
                                                WIB</span>
                                        </td>
                                        <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $order->user->name ?? 'Guest' }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            <div class="truncate max-w-[150px]"
                                                title="{{ $order->orderItems->count() }} Items">
                                                {{ $order->orderItems->count() }} Item:
                                                {{ $order->orderItems->first()->product->nama_produk ?? 'Deleted' }}...
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            Tidak ada transaksi selesai pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            // Data chart diambil dinamis dari Controller ($chartData)
            // Ini sudah otomatis menyesuaikan dengan filter tanggal yang dipilih user
            const labels = {!! json_encode($chartData->keys()) !!};
            const data = {!! json_encode($chartData->values()) !!};

            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan',
                        data: data,
                        borderColor: '#2563eb',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2563eb',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.3 // Sedikit dilengkungkan agar lebih modern
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
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 13,
                                weight: 'bold'
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', {
                                            style: 'currency',
                                            currency: 'IDR',
                                            minimumFractionDigits: 0
                                        }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [2, 4],
                                color: '#e2e8f0'
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection

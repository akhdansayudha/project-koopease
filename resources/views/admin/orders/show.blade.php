@extends('layouts.admin')

@section('content')
    {{-- Tambahkan x-data di sini untuk state modal --}}
    <div x-data="{ cancelModalOpen: false }">
        <div class="mb-6">
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold text-sm transition mb-4">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900">Detail Pesanan #{{ $order->id }}</h1>
                    <p class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('l, d F Y H:i') }}</p>
                </div>

                @php
                    $statusColors = [
                        'menunggu_pembayaran' => 'bg-gray-100 text-gray-600',
                        'menunggu_konfirmasi' => 'bg-blue-100 text-blue-700',
                        'diproses' => 'bg-orange-100 text-orange-700',
                        'siap_diambil' => 'bg-purple-100 text-purple-700',
                        'selesai' => 'bg-green-100 text-green-700',
                        'dibatalkan' => 'bg-red-100 text-red-700',
                    ];
                    $color = $statusColors[$order->status] ?? 'bg-gray-100';
                @endphp
                <span class="px-4 py-2 rounded-xl font-bold text-sm capitalize {{ $color }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-6 text-lg">Barang Dipesan</h3>
                    <div class="space-y-6">
                        @foreach ($order->orderItems as $item)
                            <div class="flex gap-4 items-start">
                                <div
                                    class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img src="{{ $item->product->gambar_url ? asset($item->product->gambar_url) : asset('images/products/default-empty.jpg') }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-base">{{ $item->product->nama_produk }}</h4>
                                    <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp
                                        {{ number_format($item->harga_saat_pesan ?? $item->product->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900 text-lg">Rp
                                        {{ number_format(($item->harga_saat_pesan ?? $item->product->harga) * $item->quantity, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            @if (!$loop->last)
                                <div class="border-b border-gray-100"></div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-dashed border-gray-200 flex justify-between items-center">
                        <span class="font-bold text-gray-500">Total Transaksi</span>
                        <span class="font-extrabold text-2xl text-blue-600">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Informasi Pembeli</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Nama</p>
                            <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Email</p>
                            <p class="font-bold text-gray-900">{{ $order->user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-lg sticky top-8">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg">Aksi Admin</h3>

                    <p class="text-sm text-gray-500 mb-6">
                        Ubah status pesanan sesuai proses lapangan. Pastikan pembayaran valid sebelum memproses.
                    </p>

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-3">
                        @csrf @method('PATCH')

                        @if ($order->status == 'menunggu_pembayaran')
                            <div class="p-3 bg-yellow-50 rounded-xl text-yellow-700 text-sm mb-2">
                                User belum melakukan konfirmasi bayar.
                            </div>
                            <button type="button" @click="cancelModalOpen = true"
                                class="w-full py-3 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl font-bold transition flex items-center justify-center gap-2">
                                <i class="bi bi-x-circle"></i> Batalkan Pesanan
                            </button>
                        @elseif ($order->status == 'menunggu_konfirmasi')
                            <button type="submit" name="status" value="diproses"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-200 transition flex items-center justify-center gap-2">
                                <i class="bi bi-check-circle"></i> Terima Pembayaran
                            </button>
                            <button type="button" @click="cancelModalOpen = true"
                                class="w-full py-3 bg-white border border-red-200 text-red-600 hover:bg-red-50 rounded-xl font-bold transition flex items-center justify-center gap-2">
                                <i class="bi bi-x-circle"></i> Tolak / Batalkan
                            </button>
                        @elseif($order->status == 'diproses')
                            <button type="submit" name="status" value="siap_diambil"
                                class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold shadow-lg shadow-purple-200 transition flex items-center justify-center gap-2">
                                <i class="bi bi-box-seam"></i> Barang Siap Diambil
                            </button>
                        @elseif($order->status == 'siap_diambil')
                            <button type="submit" name="status" value="selesai"
                                class="w-full py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-200 transition flex items-center justify-center gap-2">
                                <i class="bi bi-check-all"></i> Selesaikan Pesanan
                            </button>
                        @else
                            <div class="bg-gray-50 p-4 rounded-xl text-center text-gray-400 text-sm font-bold">
                                Tidak ada aksi tersedia.
                            </div>
                        @endif

                    </form>
                </div>
            </div>

        </div>

        @include('admin.orders.cancel-modal')
    </div>
@endsection

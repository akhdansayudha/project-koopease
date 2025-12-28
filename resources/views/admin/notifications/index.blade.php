@extends('layouts.admin')

@section('content')
    <div class="space-y-6" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        openDeleteModal(url) {
            this.deleteUrl = url;
            this.deleteModalOpen = true;
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Broadcast Notifikasi</h1>
                <p class="text-gray-500 text-sm mt-1">Kirim pengumuman atau promo ke pengguna aplikasi.</p>
            </div>

            <a href="{{ route('admin.notifications.create') }}"
                class="group relative inline-flex items-center justify-center px-6 py-2.5 font-semibold text-white transition-all duration-200 bg-blue-600 rounded-xl hover:bg-blue-700 focus:outline-none shadow-lg shadow-blue-200 hover:shadow-blue-300 hover:-translate-y-0.5">
                <i class="bi bi-megaphone-fill mr-2"></i>
                Buat Pesan Baru
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Pengiriman</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-[15%]">Tanggal</th>
                            <th class="px-6 py-4 w-[10%] text-center">Tipe</th> {{-- KOLOM BARU --}}
                            <th class="px-6 py-4 w-[45%]">Isi Pesan</th>
                            <th class="px-6 py-4 w-[15%] text-center">Penerima</th>
                            <th class="px-6 py-4 w-[15%] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($blasts as $blast)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-semibold text-gray-700">{{ $blast->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-400 block">{{ $blast->created_at->format('H:i') }}
                                        WIB</span>
                                </td>

                                {{-- KOLOM TIPE --}}
                                <td class="px-6 py-4 text-center">
                                    @if ($blast->type == 'promo')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700">
                                            PROMO
                                        </span>
                                    @elseif($blast->type == 'order')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700">
                                            PESANAN
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-700">
                                            INFO
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    {{-- whitespace-pre-wrap: Agar enter dan spasi terbaca --}}
                                    <p class="text-gray-800 font-medium whitespace-pre-wrap text-xs leading-relaxed max-h-20 overflow-y-auto custom-scrollbar">{{ $blast->raw_message }}</p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200">
                                        {{ $blast->recipient_count }} User
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.notifications.show', $blast->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button
                                            @click="openDeleteModal('{{ route('admin.notifications.destroy', $blast->id) }}')"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <i class="bi bi-inbox text-2xl text-gray-400"></i>
                                        </div>
                                        <p>Belum ada riwayat broadcast notifikasi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($blasts->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $blasts->links('admin.components.pagination') }}
                </div>
            @endif
        </div>

        {{-- Modal Delete tetap sama --}}
        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak
            @keydown.escape="deleteModalOpen = false">
            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/50">
                    <div class="px-8 py-10 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-50 mb-6">
                            <i class="bi bi-exclamation-triangle-fill text-3xl text-red-500"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 leading-tight mb-2">Tarik Notifikasi?</h3>
                        <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                            Tindakan ini akan <strong>menghapus notifikasi ini dari seluruh penerima</strong>.
                            Data tidak dapat dikembalikan.
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="deleteModalOpen = false"
                                class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">Batal</button>
                            <form :action="deleteUrl" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full py-3.5 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">Ya,
                                    Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

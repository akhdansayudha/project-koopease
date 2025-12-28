@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.notifications.index') }}"
                class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:text-blue-600 hover:border-blue-200 transition shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Detail Broadcast</h1>

                    {{-- BADGE TIPE --}}
                    @if ($ref->type == 'promo')
                        <span
                            class="px-2.5 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">PROMO</span>
                    @elseif($ref->type == 'order')
                        <span
                            class="px-2.5 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 border border-green-200">PESANAN</span>
                    @else
                        <span
                            class="px-2.5 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">INFO</span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm mt-1">
                    Dikirim pada: {{ $ref->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-2">Isi Pesan (Format Asli)</h3>
                {{-- Gunakan whitespace-pre-wrap agar enter terbaca --}}
                <p class="text-lg font-medium text-gray-900 leading-relaxed max-w-4xl whitespace-pre-wrap">{{ $ref->raw_message }}</p>

                @if ($notifications->count() > 0)
                    <div class="mt-4 pt-4 border-t border-blue-200">
                        <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-2">Contoh Pesan
                            Terpersonalisasi:</h4>
                        <p class="text-sm text-gray-700 italic whitespace-pre-wrap">"{{ $notifications->first()->message }}"</p>
                    </div>
                @endif
            </div>
            <div class="absolute right-0 bottom-0 opacity-10 transform translate-x-4 translate-y-4">
                <i class="bi bi-chat-quote-fill text-9xl text-blue-600"></i>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Daftar Penerima ({{ $notifications->count() }})</h3>

                @php
                    $readCount = $notifications->where('is_read', true)->count();
                    $percentage = $notifications->count() > 0 ? round(($readCount / $notifications->count()) * 100) : 0;
                @endphp

                <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200">
                    <span class="text-xs font-bold text-gray-500">Dibaca:</span>
                    <span class="text-xs font-bold text-blue-600">{{ $readCount }} ({{ $percentage }}%)</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-[5%]">No</th>
                            <th class="px-6 py-4 w-[30%]">Nama User</th>
                            <th class="px-6 py-4 w-[35%]">Email</th>
                            <th class="px-6 py-4 w-[30%] text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($notifications as $notif)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 text-gray-400 font-mono text-xs">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    {{ $notif->user->name ?? 'User Terhapus' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $notif->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($notif->is_read)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                            <i class="bi bi-check-all text-sm"></i> Dibaca
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 border border-gray-200">
                                            <i class="bi bi-check text-sm"></i> Terkirim
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

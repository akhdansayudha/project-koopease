@extends('layouts.admin')

@section('content')
    <div class="w-full space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.notifications.index') }}"
                    class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-xl text-gray-600 hover:text-blue-600 hover:border-blue-200 transition shadow-sm">
                    <i class="bi bi-arrow-left text-lg"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Tulis Pesan Broadcast</h1>
                    <p class="text-gray-500 text-sm">Kirim notifikasi massal ke perangkat pengguna.</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
            <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-2 space-y-6">

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Kategori
                                Notifikasi</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                {{-- INFO --}}
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="type" value="info" class="peer sr-only" checked>
                                    <div
                                        class="p-3 rounded-2xl border-2 border-gray-100 bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all hover:border-blue-200 text-center">
                                        <span class="block text-blue-600 mb-1"><i
                                                class="bi bi-info-circle-fill text-xl"></i></span>
                                        <span class="font-bold text-gray-900 text-sm">Info</span>
                                    </div>
                                </label>

                                {{-- PROMO --}}
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="type" value="promo" class="peer sr-only">
                                    <div
                                        class="p-3 rounded-2xl border-2 border-gray-100 bg-gray-50 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all hover:border-red-200 text-center">
                                        <span class="block text-red-500 mb-1"><i class="bi bi-percent text-xl"></i></span>
                                        <span class="font-bold text-gray-900 text-sm">Promo</span>
                                    </div>
                                </label>

                                {{-- PESANAN (SYSTEM) --}}
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="type" value="order" class="peer sr-only">
                                    <div
                                        class="p-3 rounded-2xl border-2 border-gray-100 bg-gray-50 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all hover:border-green-200 text-center">
                                        <span class="block text-green-600 mb-1"><i
                                                class="bi bi-bag-check-fill text-xl"></i></span>
                                        <span class="font-bold text-gray-900 text-sm">Pesanan</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-2 ml-1">Target Penerima</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="target" value="promo" class="peer sr-only" checked>
                                    <div
                                        class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-all group-hover:border-gray-300 h-full">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-tag-fill text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm">Promo Hunter</p>
                                                <p class="text-xs opacity-70">User dengan notify_promo aktif</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 transition text-white">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="target" value="all" class="peer sr-only">
                                    <div
                                        class="p-4 rounded-2xl border-2 border-gray-100 bg-gray-50 peer-checked:border-gray-900 peer-checked:bg-gray-900 peer-checked:text-white transition-all group-hover:border-gray-300 h-full">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                                                <i class="bi bi-people-fill text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-sm">Semua Mahasiswa</p>
                                                <p class="text-xs opacity-70">Seluruh user terdaftar</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="absolute top-4 right-4 opacity-0 peer-checked:opacity-100 transition text-white">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2 ml-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase">Isi Pesan</label>
                                <span class="text-xs text-gray-400" id="charCount">0/255</span>
                            </div>

                            <div class="relative">
                                <textarea name="message" id="messageInput" rows="5" required maxlength="255"
                                    class="w-full rounded-2xl border-gray-200 bg-white focus:border-blue-500 focus:ring-0 text-gray-900 text-sm p-4 transition-colors placeholder-gray-400 resize-none shadow-sm"
                                    placeholder="Tulis pesan menarik untuk pengguna..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-blue-50 rounded-2xl p-5 border border-blue-100">
                            <div class="flex items-start gap-3">
                                <i class="bi bi-magic text-blue-600 text-xl mt-0.5"></i>
                                <div>
                                    <h3 class="font-bold text-blue-900 text-sm mb-1">Personalisasi Pesan</h3>
                                    <p class="text-xs text-blue-700 leading-relaxed mb-3">
                                        Gunakan variabel berikut agar pesan terasa lebih personal:
                                    </p>
                                    <div class="space-y-2">
                                        <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition"
                                            onclick="insertVariable('{nama}')">
                                            <code
                                                class="bg-white px-2 py-1 rounded border border-blue-200 text-blue-600 text-xs font-mono">{nama}</code>
                                            <span class="text-xs text-blue-600">Nama User</span>
                                        </div>
                                        <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition"
                                            onclick="insertVariable('{email}')">
                                            <code
                                                class="bg-white px-2 py-1 rounded border border-blue-200 text-blue-600 text-xs font-mono">{email}</code>
                                            <span class="text-xs text-blue-600">Email User</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
                            <h3 class="font-bold text-gray-900 text-sm mb-2">Konfirmasi</h3>
                            <p class="text-xs text-gray-500 mb-4">
                                Pesan akan dikirimkan langsung ke notifikasi aplikasi pengguna.
                            </p>
                            <button type="submit"
                                class="w-full py-3.5 rounded-xl bg-gray-900 text-white font-bold hover:bg-black transition shadow-lg shadow-gray-200 flex items-center justify-center gap-2">
                                <span>Kirim Broadcast</span>
                                <i class="bi bi-send-fill text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const messageInput = document.getElementById('messageInput');
        const charCount = document.getElementById('charCount');

        messageInput.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCount.textContent = `${currentLength}/255`;
            if (currentLength >= 255) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        function insertVariable(variable) {
            const textarea = document.getElementById('messageInput');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const before = text.substring(0, start);
            const after = text.substring(end, text.length);

            textarea.value = before + variable + after;
            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
            textarea.focus();
            textarea.dispatchEvent(new Event('input'));
        }
    </script>
@endsection

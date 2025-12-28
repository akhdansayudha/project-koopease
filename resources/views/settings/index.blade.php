<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - KoopEase</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #F9FAFB;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="text-gray-800" x-data="{
    authOpen: false,
    toastOpen: {{ session('success') ? 'true' : 'false' }},
    deleteModalOpen: false,
    confirmText: '',
    notifyOrder: {{ Auth::user()->notify_order ? 'true' : 'false' }},
    notifyPromo: {{ Auth::user()->notify_promo ? 'true' : 'false' }},
    saving: false,

    async updateNotification(type, value) {
        this.saving = true;
        try {
            const response = await fetch('{{ route('settings.notifications') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    ['notify_' + type]: value
                })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error('Update failed');
            }
        } catch (error) {
            console.error('Gagal update:', error);
            if (type === 'order') this.notifyOrder = !value;
            if (type === 'promo') this.notifyPromo = !value;
        } finally {
            this.saving = false;
        }
    }
}" x-init="if (toastOpen) { setTimeout(() => toastOpen = false, 4000); }">

    @include('components.navbar')

    <!-- TOAST NOTIFICATION -->
    <div x-show="toastOpen" x-transition:enter="transform ease-out duration-300"
        x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="-translate-y-2 opacity-0" x-cloak
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-[100] w-[90%] max-w-sm">
        <div
            class="bg-gray-900/90 backdrop-blur-md text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 border border-gray-700/50">
            <div class="bg-green-500 rounded-full p-1"><i class="bi bi-check text-white text-lg"></i></div>
            <div class="flex-1">
                <h4 class="text-xs font-bold text-gray-200">Berhasil</h4>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="max-w-7xl mx-auto p-6 md:p-8 mb-20">

        <div class="mb-6">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-blue-600 font-bold text-sm transition">
                <i class="bi bi-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="flex items-center gap-4 mb-8">
            <div
                class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 text-2xl shadow-sm">
                ⚙️
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900">Pengaturan</h1>
                <p class="text-gray-500 text-sm font-medium">Kelola keamanan dan preferensi akun</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- KOLOM KIRI -->
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm relative overflow-hidden sticky top-24">
                    <div class="absolute top-0 right-0 bg-green-500 w-16 h-16 rounded-bl-full opacity-10"></div>
                    <h3 class="font-bold text-gray-900 mb-2">Status Keamanan</h3>
                    <div class="flex items-center gap-2 text-green-600 bg-green-50 px-3 py-1.5 rounded-xl w-fit mb-4">
                        <i class="bi bi-shield-check-fill"></i>
                        <span class="text-sm font-bold">Akun Aman</span>
                    </div>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Password terakhir diubah: <br>
                        <span class="font-bold text-gray-700">
                            {{ Auth::user()->updated_at->locale('id')->diffForHumans() }}
                        </span>
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400">Disarankan mengganti kata sandi secara berkala untuk menjaga
                            keamanan akun.</p>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN -->
            <div class="lg:col-span-2 space-y-8">

                <!-- 1. Ganti Password -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-key-fill text-blue-600"></i> Ganti Kata Sandi
                    </h3>

                    <form action="{{ route('settings.password') }}" method="POST">
                        @csrf @method('PATCH')

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400"><i
                                            class="bi bi-shield-lock"></i></span>
                                    <input type="password" name="current_password" required placeholder="••••••••"
                                        class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition outline-none font-bold">
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi Baru</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400"><i
                                                class="bi bi-lock"></i></span>
                                        <input type="password" name="password" required placeholder="Min. 8 karakter"
                                            class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition outline-none font-bold">
                                    </div>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Baru</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400"><i
                                                class="bi bi-check-circle"></i></span>
                                        <input type="password" name="password_confirmation" required
                                            placeholder="Ulangi password"
                                            class="w-full pl-11 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition outline-none font-bold">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-2xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02] flex items-center gap-2">
                                    <i class="bi bi-check-lg"></i> Simpan Sandi Baru
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 2. Notifikasi -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                        <i class="bi bi-bell-fill text-orange-500"></i> Preferensi Notifikasi
                    </h3>

                    <div class="space-y-4">
                        <div
                            class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Status Pesanan</h4>
                                <p class="text-xs text-gray-500">Terima notifikasi saat pesanan diproses.</p>
                            </div>
                            <button @click="notifyOrder = !notifyOrder; updateNotification('order', notifyOrder)"
                                :class="notifyOrder ? 'bg-blue-600' : 'bg-gray-200'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none"
                                :disabled="saving">
                                <span :class="notifyOrder ? 'translate-x-6' : 'translate-x-1'"
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300"></span>
                            </button>
                        </div>

                        <div
                            class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl hover:bg-gray-50 transition">
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">Promo & Diskon</h4>
                                <p class="text-xs text-gray-500">Info promo terbaru dari koperasi.</p>
                            </div>
                            <button @click="notifyPromo = !notifyPromo; updateNotification('promo', notifyPromo)"
                                :class="notifyPromo ? 'bg-blue-600' : 'bg-gray-200'"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300 focus:outline-none"
                                :disabled="saving">
                                <span :class="notifyPromo ? 'translate-x-6' : 'translate-x-1'"
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 3. Zona Bahaya -->
                <div class="bg-red-50 rounded-[32px] p-8 border border-red-100">
                    <h3 class="font-bold text-xl text-red-700 mb-4 flex items-center gap-2">
                        <i class="bi bi-exclamation-triangle-fill"></i> Zona Bahaya
                    </h3>
                    <p class="text-sm text-red-600/80 mb-6">
                        Menghapus akun bersifat permanen. Semua data riwayat pesanan Anda akan hilang.
                    </p>
                    <button @click="deleteModalOpen = true; confirmText = ''; document.body.style.overflow = 'hidden'"
                        class="bg-white text-red-600 border border-red-200 hover:bg-red-600 hover:text-white font-bold py-3 px-6 rounded-xl transition flex items-center gap-2">
                        <i class="bi bi-trash"></i> Hapus Akun Saya
                    </button>
                </div>

            </div>
        </div>
    </div>

    @include('components.footer')

    <!-- MODAL KONFIRMASI HAPUS -->
    <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
            closeDeleteModal() {
                    deleteModalOpen = false;
                    confirmText = '';
                    document.body.style.overflow = 'auto';
                },
        
                handleBackdropClick(e) {
                    if (e.target === e.currentTarget) {
                        this.closeDeleteModal();
                    }
                }
        }"
        @keydown.escape="closeDeleteModal()">

        <!-- Backdrop -->
        <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
            @click="handleBackdropClick($event)">
        </div>

        <!-- Modal Content -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/50"
                @click.stop>

                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-4">
                            <i class="bi bi-exclamation-triangle text-red-600 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Akun Permanen?</h3>
                        <div class="bg-red-50 rounded-xl p-4 mb-4 text-left border border-red-100">
                            <p class="text-sm text-red-700 font-bold mb-2">Tindakan ini tidak dapat dibatalkan!</p>
                            <ul class="text-xs text-red-600 space-y-1">
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-x-circle-fill mt-0.5"></i>
                                    <span>Semua data profil akan dihapus</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="bi bi-x-circle-fill mt-0.5"></i>
                                    <span>Riwayat pesanan akan hilang</span>
                                </li>
                            </ul>
                        </div>

                        <form action="{{ route('settings.delete-account') }}" method="POST">
                            @csrf @method('DELETE')

                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">
                                    Ketik <span class="font-mono text-red-600">HAPUS AKUN</span> untuk konfirmasi
                                </label>
                                <input type="text" name="confirmation" x-model="confirmText"
                                    placeholder="HAPUS AKUN"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-50 transition outline-none font-bold text-center tracking-wider placeholder-gray-300 uppercase">
                            </div>

                            <div class="flex gap-3">
                                <button type="button" @click="closeDeleteModal()"
                                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition">
                                    Batalkan
                                </button>

                                <button type="submit" :disabled="confirmText !== 'HAPUS AKUN'"
                                    class="flex-1 font-bold py-3 rounded-xl transition shadow-lg disabled:cursor-not-allowed"
                                    :class="confirmText === 'HAPUS AKUN' ?
                                        'bg-red-600 hover:bg-red-700 text-white cursor-pointer shadow-red-200 hover:scale-[1.02]' :
                                        'bg-gray-200 text-gray-400 shadow-none'">
                                    Hapus Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

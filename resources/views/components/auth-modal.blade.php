<!-- resources/views/components/auth-modal.blade.php -->
<div x-data="{
    // Buka modal jika ada error ATAU ada session flash 'open_auth_modal'
    authOpen: {{ $errors->any() || session('open_auth_modal') ? 'true' : 'false' }},

    // Tentukan mode berdasarkan input lama ATAU session flash ATAU default 'login'
    authMode: '{{ old('authMode', session('open_auth_modal', 'login')) }}',

    closeAuthModal() {
        this.authOpen = false;
        setTimeout(() => {
            this.authMode = 'login';
        }, 300);
        document.body.style.overflow = 'auto';
    },

    handleBackdropClick(e) {
        if (e.target === e.currentTarget) {
            this.closeAuthModal();
        }
    }
}" x-init="if (authOpen) document.body.style.overflow = 'hidden';
$watch('authOpen', value => document.body.style.overflow = value ? 'hidden' : 'auto');"
    @open-auth-modal.window="authMode = $event.detail.mode; authOpen = true;" @keydown.escape="closeAuthModal()">

    <!-- Modal Auth -->
    <template x-if="authOpen">
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <!-- Backdrop -->
            <div x-show="authOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
                @click="handleBackdropClick($event)">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="authOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100"
                    @click.stop>

                    <button @click="closeAuthModal()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-50 rounded-full p-2 z-10 transition hover:bg-gray-100">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>

                    <div class="px-8 py-8">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-gray-900"
                                x-text="authMode === 'login' ? 'Selamat Datang! 👋' : 'Gabung KoopEase 🚀'"></h3>
                            <p class="text-sm text-gray-500 mt-2">Koperasi mahasiswa dalam genggaman.</p>
                        </div>

                        <!-- Toggle Switch -->
                        <div class="bg-gray-100 p-1 rounded-full flex mb-8 relative">
                            <div class="absolute top-1 bottom-1 w-[48%] bg-white rounded-full shadow-sm transition-all duration-300 ease-in-out"
                                :class="authMode === 'login' ? 'left-1' : 'left-[51%]'"></div>

                            <button @click="authMode = 'login'"
                                class="flex-1 py-2.5 text-sm font-bold rounded-full relative z-10 transition-colors"
                                :class="authMode === 'login' ? 'text-gray-900' : 'text-gray-500'">
                                Masuk
                            </button>
                            <button @click="authMode = 'register'"
                                class="flex-1 py-2.5 text-sm font-bold rounded-full relative z-10 transition-colors"
                                :class="authMode === 'register' ? 'text-gray-900' : 'text-gray-500'">
                                Daftar
                            </button>
                        </div>

                        <!-- LOGIN FORM -->
                        <form action="{{ route('login') }}" method="POST" x-show="authMode === 'login'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100">

                            @csrf
                            <input type="hidden" name="authMode" value="login">

                            @if ($errors->has('email') && old('authMode') == 'login')
                                <div
                                    class="mb-4 p-3 bg-red-50 text-red-600 text-xs rounded-xl font-bold border border-red-100 flex gap-2 items-center">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $errors->first('email') }}
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Email
                                        Kampus</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        placeholder="nama@student.telkomuniversity.ac.id"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-1.5 ml-1">
                                        <label class="block text-xs font-bold text-gray-700">Kata Sandi</label>
                                        <a href="#" class="text-xs font-bold text-blue-600 hover:underline">Lupa
                                            sandi?</a>
                                    </div>
                                    <input type="password" name="password" required placeholder="••••••••"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">
                                </div>

                                <button type="submit"
                                    class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02] mt-2">
                                    Masuk Sekarang
                                </button>
                            </div>
                        </form>

                        <!-- REGISTER FORM -->
                        <form action="{{ route('register') }}" method="POST" x-show="authMode === 'register'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100">
                            @csrf
                            <input type="hidden" name="authMode" value="register">

                            @if ($errors->any() && old('authMode') == 'register')
                                <div
                                    class="mb-4 p-3 bg-red-50 text-red-600 text-xs rounded-xl font-bold border border-red-100 flex gap-2 items-center">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="space-y-4">
                                <div
                                    class="bg-blue-50 text-blue-700 px-4 py-3 rounded-xl text-xs flex gap-2 items-start border border-blue-100">
                                    <i class="bi bi-info-circle-fill mt-0.5"></i>
                                    <span class="leading-relaxed font-medium">Gunakan email kampus aktif untuk
                                        verifikasi otomatis mahasiswa.</span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Nama
                                        Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                        placeholder="Nama Lengkap Anda"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Email
                                        Kampus</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                        placeholder="nama@student.telkomuniversity.ac.id"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Buat Kata
                                        Sandi</label>
                                    <input type="password" name="password" required placeholder="••••••••"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">
                                </div>

                                <button type="submit"
                                    class="w-full h-12 bg-gray-900 hover:bg-black text-white font-bold rounded-xl shadow-lg transition transform hover:scale-[1.02] mt-2">
                                    Buat Akun Baru
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

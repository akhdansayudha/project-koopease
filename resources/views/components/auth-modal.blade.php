<!-- resources/views/components/auth-modal.blade.php -->
<div x-data="{
    authOpen: {{ $errors->any() || session('open_auth_modal') ? 'true' : 'false' }},
    authMode: '{{ old('authMode', session('open_auth_modal', 'login')) }}',

    // --- STATE FORM REGISTER ---
    regName: '{{ old('name') }}', // Menyimpan Nama
    regEmailUser: '', // Menyimpan Username Email (sebelum @)
    regEmailDomain: '@student.telkomuniversity.ac.id', // Default Domain
    regPassword: '', // Menyimpan Password
    regTerms: false, // Status Checkbox Terms
    showPass: false, // Status visibility password

    // --- VALIDASI REALTIME ---
    get isLengthValid() { return this.regPassword.length >= 8; },
    get hasNumber() { return /\d/.test(this.regPassword); },

    // Cek apakah SEMUA form sudah diisi dengan benar
    get isFormValid() {
        return this.regName.trim() !== '' &&
            this.regEmailUser.trim() !== '' &&
            this.isLengthValid &&
            this.hasNumber &&
            this.regTerms;
    },

    closeAuthModal() {
        this.authOpen = false;
        setTimeout(() => {
            this.authMode = 'login';
            // Reset form saat modal ditutup (Optional)
            this.regPassword = '';
            this.showPass = false;
        }, 300);
    },

    handleBackdropClick(e) {
        if (e.target === e.currentTarget) {
            this.closeAuthModal();
        }
    }
}" x-init=""
    @open-auth-modal.window="authMode = $event.detail.mode; authOpen = true;" @keydown.escape="closeAuthModal()">

    <template x-if="authOpen">
        <div class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">

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

                        {{-- Toggle Login/Register --}}
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

                        {{-- FORM LOGIN --}}
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
                                        <a href="{{ route('forgot.index') }}"
                                            class="text-xs font-bold text-blue-600 hover:underline">
                                            Lupa sandi?
                                        </a>
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

                        {{-- FORM REGISTER --}}
                        <form action="{{ route('register') }}" method="POST" x-show="authMode === 'register'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100">
                            @csrf
                            <input type="hidden" name="authMode" value="register">

                            {{-- Hidden input yang menggabungkan User + Domain untuk dikirim ke Backend --}}
                            <input type="hidden" name="email" :value="regEmailUser + regEmailDomain">

                            @if ($errors->any() && old('authMode') == 'register')
                                <div
                                    class="mb-4 p-3 bg-red-50 text-red-600 text-xs rounded-xl font-bold border border-red-100 flex flex-col gap-1">
                                    @foreach ($errors->all() as $error)
                                        <div class="flex gap-2 items-center">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                            <span>{{ $error }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="space-y-4">
                                {{-- Input Nama --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Nama
                                        Lengkap</label>
                                    <input type="text" name="name" x-model="regName" required
                                        placeholder="Nama Lengkap Anda"
                                        class="w-full h-12 px-4 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">
                                </div>

                                {{-- Input Email Split (User & Domain) --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Email
                                        Kampus</label>
                                    <div class="flex items-center">
                                        {{-- Username Input --}}
                                        <input type="text" x-model="regEmailUser" required
                                            placeholder="nama_pengguna"
                                            class="flex-1 w-full h-12 pl-4 pr-2 bg-gray-50 rounded-l-xl border-y border-l border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400 z-10">

                                        {{-- Domain Dropdown --}}
                                        <div class="relative max-w-[50%]">
                                            <select x-model="regEmailDomain"
                                                class="h-12 pl-2 pr-8 bg-gray-100 hover:bg-gray-200 rounded-r-xl border-l border-gray-200 text-xs font-bold text-gray-700 outline-none cursor-pointer appearance-none transition w-full">
                                                <option value="@student.telkomuniversity.ac.id">
                                                    @student.telkomuniversity.ac.id</option>
                                                <option value="@telkomuniversity.ac.id">@telkomuniversity.ac.id
                                                </option>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-gray-500">
                                                <i class="bi bi-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Input Password --}}
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1.5 ml-1">Buat Kata
                                        Sandi</label>
                                    <div class="relative">
                                        <input :type="showPass ? 'text' : 'password'" name="password" required
                                            x-model="regPassword" placeholder="••••••••"
                                            class="w-full h-12 pl-4 pr-12 bg-gray-50 rounded-xl border border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition text-sm font-medium placeholder-gray-400">

                                        <button type="button" @click="showPass = !showPass"
                                            class="absolute inset-y-0 right-0 px-4 flex items-center text-gray-400 hover:text-blue-600 transition cursor-pointer">
                                            <i class="bi"
                                                :class="showPass ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
                                        </button>
                                    </div>

                                    {{-- Indikator Realtime Requirements --}}
                                    <div class="mt-2 ml-1 space-y-1">
                                        <div class="flex items-center gap-2 text-xs transition-colors duration-200"
                                            :class="isLengthValid ? 'text-green-600 font-bold' : 'text-gray-400'">
                                            <i class="bi"
                                                :class="isLengthValid ? 'bi-check-circle-fill' : 'bi-circle'"></i>
                                            <span>Minimal 8 karakter</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs transition-colors duration-200"
                                            :class="hasNumber ? 'text-green-600 font-bold' : 'text-gray-400'">
                                            <i class="bi"
                                                :class="hasNumber ? 'bi-check-circle-fill' : 'bi-circle'"></i>
                                            <span>Mengandung setidaknya satu angka</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Checkbox Terms --}}
                                <div class="flex items-start gap-3 mt-4">
                                    <div class="flex items-center h-5">
                                        <input id="terms" name="terms" type="checkbox" x-model="regTerms"
                                            required
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer">
                                    </div>
                                    <label for="terms"
                                        class="ml-1 text-xs text-gray-500 font-medium leading-relaxed">
                                        Saya menyetujui
                                        <a href="{{ route('terms') }}" target="_blank"
                                            class="text-blue-600 hover:underline font-bold">Syarat & Ketentuan</a>
                                        serta
                                        <a href="{{ route('privacy') }}" target="_blank"
                                            class="text-blue-600 hover:underline font-bold">Kebijakan Privasi</a>
                                        yang berlaku di KoopEase.
                                    </label>
                                </div>

                                {{-- Tombol Submit --}}
                                <button type="submit"
                                    class="w-full h-12 bg-gray-900 text-white font-bold rounded-xl shadow-lg transition transform mt-4 flex items-center justify-center gap-2"
                                    :class="isFormValid ? 'hover:bg-black hover:scale-[1.02]' :
                                        'opacity-50 cursor-not-allowed shadow-none'"
                                    :disabled="!isFormValid">
                                    <span>Buat Akun Baru</span>
                                    <i class="bi bi-arrow-right" x-show="isFormValid"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

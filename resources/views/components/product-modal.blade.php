<div x-show="productModalOpen" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
        closeProductModal() {
                productModalOpen = false;
                document.body.style.overflow = 'auto';
            },
    
            handleBackdropClick(e) {
                if (e.target === e.currentTarget) {
                    this.closeProductModal();
                }
            }
    }"
    @keydown.escape="closeProductModal()">

    <!-- Backdrop -->
    <div x-show="productModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="handleBackdropClick($event)">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="productModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/50"
            @click.stop x-ref="modalContent">

            <div class="relative h-64 w-full bg-gray-100 group">
                <img :src="activeProduct.image"
                    class="h-full w-full object-cover transition-transform duration-700 hover:scale-105"
                    alt="Product Image">

                <div class="absolute top-5 left-5">
                    <span
                        class="bg-white/90 backdrop-blur-md text-gray-900 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm"
                        x-text="activeProduct.category"></span>
                </div>

                <button @click="closeProductModal()"
                    class="absolute top-5 right-5 bg-white/50 hover:bg-white text-gray-800 rounded-full p-2 transition backdrop-blur-md shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="px-8 pt-6 pb-8">

                <div class="mb-4">
                    <div class="flex justify-between items-start">
                        <h3 class="text-2xl font-extrabold text-gray-900 leading-tight" x-text="activeProduct.name">
                        </h3>
                        <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg border border-yellow-100">
                            <span class="text-xs">⭐</span>
                            <span class="text-xs font-bold text-yellow-700" x-text="activeProduct.rating"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-2 text-sm">
                        <span class="font-bold text-gray-900 text-lg" x-text="activeProduct.price"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-gray-500" x-text="activeProduct.sold"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-green-600 font-bold bg-green-50 px-2 rounded-md text-xs py-0.5">Stok: <span
                                x-text="activeProduct.stock"></span></span>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-4 mb-6">
                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Deskripsi Produk</h4>
                    <p class="text-sm text-gray-600 leading-relaxed" x-text="activeProduct.desc"></p>
                </div>

                <!-- STATE: User Belum Login -->
                <div x-show="!{{ auth()->check() ? 'true' : 'false' }}" class="space-y-4">
                    <!-- Info Login Required -->
                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1">Masuk untuk Membeli</h4>
                        <p class="text-sm text-gray-600 mb-4">Login atau daftar akun untuk menambahkan produk ke
                            keranjang dan melakukan pembelian</p>

                        <!-- CTA Buttons -->
                        <div class="grid grid-cols-2 gap-3">
                            <button
                                @click="closeProductModal(); $nextTick(() => { $dispatch('open-auth-modal', { mode: 'login' }) })"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition transform hover:scale-[1.02]">
                                Masuk
                            </button>
                            <button
                                @click="closeProductModal(); $nextTick(() => { $dispatch('open-auth-modal', { mode: 'register' }) })"
                                class="bg-gray-900 hover:bg-black text-white font-bold py-3 rounded-xl transition transform hover:scale-[1.02]">
                                Daftar
                            </button>
                        </div>
                    </div>

                    <!-- Quick Benefits -->
                    <div class="grid grid-cols-3 gap-2 text-center">
                        <div class="bg-gray-50 rounded-xl p-3">
                            <div
                                class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                <span class="text-xs">🛒</span>
                            </div>
                            <span class="text-xs font-bold text-gray-700">Keranjang</span>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <div
                                class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                <span class="text-xs">🚚</span>
                            </div>
                            <span class="text-xs font-bold text-gray-700">Gratis Ongkir</span>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <div
                                class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-1">
                                <span class="text-xs">⭐</span>
                            </div>
                            <span class="text-xs font-bold text-gray-700">Akun Mahasiswa</span>
                        </div>
                    </div>
                </div>

                <!-- STATE: User Sudah Login -->
                <div x-show="{{ auth()->check() ? 'true' : 'false' }}" class="space-y-4">
                    <!-- Quantity Selector -->
                    <div
                        class="flex items-center justify-between bg-white border border-gray-100 p-3 rounded-2xl shadow-sm">
                        <span class="text-sm font-bold text-gray-700 ml-2">Jumlah Beli</span>
                        <div class="flex items-center gap-3 bg-gray-100 rounded-xl p-1">
                            <button @click="if(activeProduct.quantity > 1) activeProduct.quantity--"
                                class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-blue-600 font-bold transition">
                                -
                            </button>
                            <span class="w-8 text-center font-bold text-gray-900"
                                x-text="activeProduct.quantity"></span>
                            <button @click="if(activeProduct.quantity < activeProduct.stock) activeProduct.quantity++"
                                class="w-8 h-8 flex items-center justify-center bg-white rounded-lg shadow-sm text-gray-600 hover:text-blue-600 font-bold transition">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-3">
                        <form action="{{ route('cart.store') }}" method="POST" class="contents">
                            @csrf
                            <input type="hidden" name="product_id" :value="activeProduct.id">
                            <input type="hidden" name="quantity" :value="activeProduct.quantity">

                            <input type="hidden" name="is_direct_buy" value="true">

                            <button type="submit"
                                class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold py-3.5 rounded-2xl transition border border-blue-100 flex items-center justify-center gap-2">
                                <span>Beli Sekarang</span>
                            </button>
                        </form>

                        <form action="{{ route('cart.store') }}" method="POST" class="contents">
                            @csrf
                            <input type="hidden" name="product_id" :value="activeProduct.id">
                            <input type="hidden" name="quantity" :value="activeProduct.quantity">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-blue-200 transition transform hover:scale-[1.02] flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 text-white group-hover:-translate-y-1 transition duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span>Masuk Keranjang</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

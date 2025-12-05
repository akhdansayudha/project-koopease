<div x-show="cancelModalOpen" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
        closeCancelModal() {
                cancelModalOpen = false;
                document.body.style.overflow = 'auto'; // Mengembalikan scroll
            },
            handleBackdropClick(e) {
                if (e.target === e.currentTarget) {
                    this.closeCancelModal();
                }
            }
    }"
    @keydown.escape="closeCancelModal()">

    <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="handleBackdropClick($event)">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/50"
            @click.stop>

            <div class="p-8">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-6">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Batalkan Pesanan?</h3>
                    <p class="text-gray-500 text-sm">
                        Apakah Anda yakin ingin membatalkan pesanan <span class="font-bold text-gray-800"
                            x-text="'#' + cancelData.id"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <div class="bg-gray-50 rounded-2xl p-4 mb-8 border border-gray-100 max-h-48 overflow-y-auto">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Produk dalam pesanan</p>
                    <div class="space-y-3">
                        <template x-for="item in cancelData.items" :key="item.name">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-white rounded-lg overflow-hidden border border-gray-200 shrink-0">
                                    <img :src="item.image" class="w-full h-full object-cover">
                                </div>
                                <div class="text-left flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate" x-text="item.name"></p>
                                    <p class="text-xs text-gray-500" x-text="item.qty + ' barang'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="closeCancelModal()"
                        class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                        Tidak, Kembali
                    </button>

                    <form :action="cancelData.url" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full py-3.5 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-200 hover:scale-[1.02] transform duration-200">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

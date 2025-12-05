<div x-show="rebuyModalOpen" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
        // Logic Lokal untuk menutup modal dan mereset scroll (Sama seperti cancel-modal)
        closeRebuyModal() {
                rebuyModalOpen = false;
                document.body.style.overflow = 'auto'; // PENTING: Mengembalikan scroll & interaksi
            },
            handleBackdropClick(e) {
                if (e.target === e.currentTarget) {
                    this.closeRebuyModal();
                }
            }
    }"
    @keydown.escape="closeRebuyModal()">

    <div x-show="rebuyModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="handleBackdropClick($event)">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center">

        <div x-show="rebuyModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all w-full max-w-lg border border-white/50 flex flex-col max-h-[85vh]"
            @click.stop>

            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-extrabold text-gray-900">Beli Lagi Pesanan</h3>
                    <p class="text-xs text-gray-500 mt-1">Pilih produk yang ingin dibeli kembali.</p>
                </div>
                <button @click="closeRebuyModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center cursor-pointer select-none group">
                        <div class="relative">
                            <input type="checkbox" class="sr-only" x-model="rebuySelectAll"
                                @change="toggleSelectAllRebuy()">
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-md group-hover:border-blue-500 transition flex items-center justify-center"
                                :class="{ 'bg-blue-600 border-blue-600': rebuySelectAll }">
                                <i class="bi bi-check text-white text-sm" x-show="rebuySelectAll"></i>
                            </div>
                        </div>
                        <span class="ml-2 text-sm font-bold text-gray-600">Pilih Semua</span>
                    </label>

                    <span class="text-xs text-gray-500">
                        <span x-text="rebuyItems.filter(i => i.selected).length"></span> item dipilih
                    </span>
                </div>

                <form id="rebuyForm" action="{{ route('cart.store') }}" method="POST" class="space-y-3">
                    @csrf

                    <template x-for="(item, index) in rebuyItems" :key="item.id">
                        <label
                            class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/30 transition cursor-pointer select-none"
                            :class="{ 'ring-1 ring-blue-500 bg-blue-50/50 border-blue-500': item.selected }">

                            <div class="relative shrink-0">
                                <input type="checkbox" class="sr-only" x-model="item.selected"
                                    @change="updateRebuySelectAllState()">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-md transition flex items-center justify-center"
                                    :class="{ 'bg-blue-600 border-blue-600': item.selected }">
                                    <i class="bi bi-check text-white text-sm" x-show="item.selected"></i>
                                </div>
                            </div>

                            <div class="w-12 h-12 bg-white rounded-lg overflow-hidden border border-gray-200 shrink-0">
                                <img :src="item.image" class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate" x-text="item.name"></p>
                                <p class="text-xs text-gray-500">
                                    <span x-text="item.qty"></span> x <span x-text="item.price_formatted"></span>
                                </p>
                            </div>

                            <template x-if="item.selected">
                                <div>
                                    <input type="hidden" :name="'products[' + index + '][product_id]'"
                                        :value="item.product_id">
                                    <input type="hidden" :name="'products[' + index + '][quantity]'"
                                        :value="item.qty">
                                </div>
                            </template>
                        </label>
                    </template>
                </form>
            </div>

            <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3">
                <button type="button" @click="closeRebuyModal()"
                    class="flex-1 py-3 rounded-xl border border-gray-200 text-gray-600 font-bold hover:bg-white hover:shadow-sm transition">
                    Batal
                </button>

                <button type="button" @click="document.getElementById('rebuyForm').submit()"
                    class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                    :disabled="rebuyItems.filter(i => i.selected).length === 0">
                    Masuk Keranjang (<span x-text="rebuyItems.filter(i => i.selected).length"></span>)
                </button>
            </div>

        </div>
    </div>
</div>

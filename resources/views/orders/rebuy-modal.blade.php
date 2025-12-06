<div x-show="rebuyModalOpen" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak x-data="{
        // Logic Lokal: Tutup modal & Reset Scroll Body
        closeRebuyModal() {
                rebuyModalOpen = false;
                document.body.style.overflow = 'auto';
            },
            handleBackdropClick(e) {
                if (e.target === e.currentTarget) {
                    this.closeRebuyModal();
                }
            }
    }"
    @keydown.escape="closeRebuyModal()">

    {{-- Style untuk Scrollbar Cantik --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e5e7eb;
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #d1d5db;
        }
    </style>

    {{-- Backdrop Overlay --}}
    <div x-show="rebuyModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="handleBackdropClick($event)">
    </div>

    {{-- Modal Panel Container --}}
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">

        <div x-show="rebuyModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/60 flex flex-col max-h-[85vh]"
            @click.stop>

            {{-- 1. Header Section --}}
            <div class="pt-8 px-8 pb-4 text-center bg-white z-10">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 mb-4 shadow-sm">
                    <i class="bi bi-bag-plus-fill text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-gray-900">Beli Lagi</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih produk dari pesanan lalu untuk dibeli kembali.</p>
            </div>

            {{-- 2. Scrollable Content --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar px-6 sm:px-8 py-2">

                {{-- Select All Bar --}}
                <div
                    class="sticky top-0 z-10 bg-white/95 backdrop-blur-sm py-3 mb-2 border-b border-gray-100 flex items-center justify-between">
                    <label class="flex items-center cursor-pointer select-none group">
                        <div class="relative">
                            <input type="checkbox" class="sr-only" x-model="rebuySelectAll"
                                @change="toggleSelectAllRebuy()">
                            <div class="w-5 h-5 border-2 border-gray-300 rounded-lg group-hover:border-blue-500 transition-colors duration-200 flex items-center justify-center"
                                :class="{ 'bg-blue-600 border-blue-600': rebuySelectAll }">
                                <i class="bi bi-check text-white text-sm" x-show="rebuySelectAll"></i>
                            </div>
                        </div>
                        <span
                            class="ml-3 text-sm font-bold text-gray-700 group-hover:text-blue-600 transition-colors">Pilih
                            Semua</span>
                    </label>
                    <span
                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                        <span x-text="rebuyItems.filter(i => i.selected).length"></span> item
                    </span>
                </div>

                {{-- Product List Form --}}
                <form id="rebuyForm" action="{{ route('cart.store') }}" method="POST" class="space-y-3 pb-4">
                    @csrf

                    <template x-for="(item, index) in rebuyItems" :key="index">
                        <label
                            class="group relative flex items-center gap-4 p-3 rounded-2xl border transition-all duration-200 cursor-pointer select-none"
                            :class="item.selected ?
                                'bg-blue-50/40 border-blue-500 ring-1 ring-blue-500 shadow-sm' :
                                'bg-white border-gray-100 hover:border-blue-200 hover:bg-gray-50'">

                            {{-- Checkbox --}}
                            <div class="relative shrink-0">
                                <input type="checkbox" class="sr-only" x-model="item.selected"
                                    @change="updateRebuySelectAllState()">
                                <div class="w-5 h-5 border-2 border-gray-300 rounded-lg transition-all duration-200 flex items-center justify-center"
                                    :class="item.selected ? 'bg-blue-600 border-blue-600 scale-110' :
                                        'group-hover:border-blue-400'">
                                    <i class="bi bi-check text-white text-sm" x-show="item.selected"></i>
                                </div>
                            </div>

                            {{-- Image --}}
                            <div
                                class="w-14 h-14 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shrink-0 shadow-sm">
                                <img :src="item.image"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    onerror="this.src='https://via.placeholder.com/100?text=Produk'">
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate group-hover:text-blue-700 transition-colors"
                                    x-text="item.name"></p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                                        Qty: <span x-text="item.qty"></span>
                                    </span>
                                    <span class="text-xs font-bold text-gray-900" x-text="item.price_formatted"></span>
                                </div>
                            </div>

                            {{-- Hidden Inputs (Array Format) --}}
                            <template x-if="item.selected">
                                <div>
                                    <input type="hidden" :name="`products[${index}][product_id]`"
                                        :value="item.product_id">
                                    <input type="hidden" :name="`products[${index}][quantity]`" :value="item.qty">
                                    <input type="hidden" :name="`products[${index}][is_direct_buy]`" value="true">
                                </div>
                            </template>
                        </label>
                    </template>

                    <div x-show="rebuyItems.length === 0" class="text-center py-8">
                        <p class="text-gray-400 text-sm">Tidak ada produk yang dapat ditampilkan.</p>
                    </div>
                </form>
            </div>

            {{-- 3. Footer Action --}}
            <div class="p-6 border-t border-gray-100 bg-gray-50 flex gap-3 z-20">
                <button type="button" @click="closeRebuyModal()"
                    class="flex-1 py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-white hover:text-gray-800 hover:shadow-md hover:border-gray-300 transition-all duration-200">
                    Batal
                </button>

                <button type="button" @click="document.getElementById('rebuyForm').submit()"
                    class="flex-1 py-3.5 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 hover:shadow-blue-300 transition-all duration-200 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none"
                    :disabled="rebuyItems.filter(i => i.selected).length === 0">
                    <span class="flex items-center justify-center gap-2">
                        <span>Masuk Keranjang</span>
                        <span x-show="rebuyItems.filter(i => i.selected).length > 0"
                            class="bg-blue-500 text-blue-50 text-[10px] font-bold px-1.5 py-0.5 rounded-full"
                            x-text="rebuyItems.filter(i => i.selected).length"></span>
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

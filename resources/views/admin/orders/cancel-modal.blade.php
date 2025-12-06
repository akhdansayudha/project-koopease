<div x-show="cancelModalOpen" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>

    <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="cancelModalOpen = false">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center pointer-events-none">
        <div x-show="cancelModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-white/50 pointer-events-auto">

            <div class="p-8">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-100 mb-6">
                    <i class="bi bi-exclamation-triangle-fill text-3xl text-red-600"></i>
                </div>

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2">Konfirmasi Pembatalan</h3>
                    <p class="text-gray-500 text-sm">
                        Apakah Anda yakin ingin membatalkan pesanan <span
                            class="font-bold text-gray-800">#{{ $order->id }}</span>?
                        <br>Stok produk akan dikembalikan secara otomatis.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="cancelModalOpen = false"
                        class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                        Kembali
                    </button>

                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="w-full">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit"
                            class="w-full py-3.5 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">
                            Ya, Batalkan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

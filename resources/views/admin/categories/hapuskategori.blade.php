<div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak @keydown.escape="deleteModalOpen = false">

    <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/50">

            <div class="px-8 py-10 text-center">
                <div
                    class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-50 mb-6 animate-pulse">
                    <i class="bi bi-exclamation-triangle text-4xl text-red-500"></i>
                </div>

                <h3 class="text-2xl font-extrabold text-gray-900 leading-tight mb-2">Hapus Kategori?</h3>
                <p class="text-sm text-gray-500 mb-8">
                    Apakah Anda yakin ingin menghapus kategori ini? Data produk di dalamnya mungkin akan terdampak.
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="deleteModalOpen = false"
                        class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                        Batal
                    </button>

                    <form :action="deleteUrl" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-3.5 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 transition shadow-lg shadow-red-200">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

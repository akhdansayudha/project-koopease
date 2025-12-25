<div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak @keydown.escape="createModalOpen = false">

    <div x-show="createModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="createModalOpen = false">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="createModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/50">

            <div class="relative h-32 w-full bg-gray-100 flex items-center justify-center">
                <div class="text-gray-400 flex flex-col items-center">
                    <i class="bi bi-camera text-3xl mb-1"></i>
                    <span class="text-xs font-semibold">Upload Foto Produk</span>
                </div>
                <button @click="createModalOpen = false"
                    class="absolute top-5 right-5 bg-white/50 hover:bg-white text-gray-800 rounded-full p-2 transition backdrop-blur-md shadow-sm z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="px-8 pt-6 pb-8">
                <div class="mb-6">
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-tight">Tambah Produk</h3>
                    <p class="text-sm text-gray-500">Masukkan detail produk baru ke katalog.</p>
                </div>

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Nama
                                Produk</label>
                            <input type="text" name="nama_produk" required
                                class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 font-semibold py-3 px-4 transition-colors placeholder-gray-400"
                                placeholder="Contoh: Roti Coklat Aoka">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Kategori</label>
                                <div class="relative">
                                    <select name="category_id" required
                                        class="w-full appearance-none rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 font-semibold py-3 px-4 transition-colors">
                                        <option value="">Pilih...</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->category_id }}">{{ $cat->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    <i
                                        class="bi bi-chevron-down absolute right-4 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Harga
                                    (Rp)</label>
                                <input type="number" name="harga" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 font-semibold py-3 px-4 transition-colors"
                                    placeholder="0">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Stok
                                    Awal</label>
                                <input type="number" name="stok" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 font-semibold py-3 px-4 transition-colors"
                                    placeholder="0">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Status</label>
                                <div class="relative">
                                    <select name="is_available"
                                        class="w-full appearance-none rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 font-semibold py-3 px-4 transition-colors">
                                        <option value="1">Aktif</option>
                                        <option value="0">Nonaktif</option>
                                    </select>
                                    <i
                                        class="bi bi-chevron-down absolute right-4 top-3.5 text-gray-400 text-xs pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Deskripsi</label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 text-sm py-3 px-4 transition-colors placeholder-gray-400"
                                placeholder="Deskripsi singkat produk..."></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Foto
                                Produk</label>
                            <input type="file" name="image"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition-colors cursor-pointer" />
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <button type="button" @click="createModalOpen = false"
                            class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="py-3.5 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

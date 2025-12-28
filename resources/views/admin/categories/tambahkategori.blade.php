<div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak @keydown.escape="createModalOpen = false">

    <div x-show="createModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="createModalOpen = false">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="createModalOpen" x-data="{
            name: '',
            slug: '',
            selectedColor: 'blue',
            colors: [
                { name: 'orange', class: 'bg-orange-500 ring-orange-500' },
                { name: 'blue', class: 'bg-blue-500 ring-blue-500' },
                { name: 'yellow', class: 'bg-yellow-400 ring-yellow-400' },
                { name: 'purple', class: 'bg-purple-500 ring-purple-500' },
                { name: 'red', class: 'bg-red-500 ring-red-500' },
                { name: 'green', class: 'bg-green-500 ring-green-500' },
                { name: 'teal', class: 'bg-teal-500 ring-teal-500' },
                { name: 'pink', class: 'bg-pink-500 ring-pink-500' },
                { name: 'indigo', class: 'bg-indigo-500 ring-indigo-500' },
                { name: 'gray', class: 'bg-gray-500 ring-gray-500' },
                { name: 'black', class: 'bg-black ring-gray-800' }
            ],
            generateSlug() {
                this.slug = this.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            }
        }" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/50">

            <div class="px-8 py-8">
                <div class="mb-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 mb-4">
                        <i class="bi bi-tags-fill text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-tight">Kategori Baru</h3>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Nama
                                    Kategori</label>
                                <input type="text" name="nama_kategori" x-model="name" @input="generateSlug()"
                                    required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-900 font-semibold py-3 px-4 transition-colors placeholder-gray-400"
                                    placeholder="Contoh: Makanan">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Slug
                                    (URL)</label>
                                <input type="text" name="slug" x-model="slug"
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-gray-600 font-mono text-sm py-3 px-4 transition-colors"
                                    placeholder="auto-generated">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Ikon
                                    (Emoji)</label>
                                <input type="text" name="icon" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:border-gray-300 focus:ring-0 text-center text-2xl py-2 px-4 transition-colors placeholder-gray-400"
                                    placeholder="...">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-1.5 ml-1">Warna
                                    Tema</label>
                                <input type="hidden" name="color" x-model="selectedColor">
                                <div
                                    class="flex flex-wrap gap-3 bg-gray-50 p-3 rounded-2xl border border-transparent focus-within:bg-white focus-within:border-gray-200">
                                    <template x-for="c in colors" :key="c.name">
                                        <button type="button" @click="selectedColor = c.name"
                                            :class="selectedColor === c.name ? 'ring-2 ring-offset-2 scale-110 ' + c.class : c
                                                .class + ' opacity-70 hover:opacity-100 hover:scale-105'"
                                            class="w-8 h-8 rounded-full transition-all duration-200 shadow-sm"
                                            :title="c.name">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <button type="button" @click="createModalOpen = false"
                            class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="py-3.5 rounded-2xl bg-gray-900 text-white font-bold hover:bg-black transition shadow-lg shadow-gray-200">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div x-show="importModalOpen" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto"
    aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak @keydown.escape="importModalOpen = false">

    <div x-show="importModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="importModalOpen = false">
    </div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div x-show="importModalOpen" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-3xl border border-white/50">

            <div class="px-8 py-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-2xl font-extrabold text-gray-900 leading-tight">Import Produk Massal</h3>
                        <p class="text-sm text-gray-500 mt-1">Upload file data produk untuk menambah sekaligus.</p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Download
                            Template</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.template', ['format' => 'csv']) }}"
                                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 hover:text-gray-900 transition-colors"
                                title="Download Template CSV">
                                <i class="bi bi-filetype-csv text-lg text-green-600"></i>
                                <span>.CSV</span>
                            </a>

                            <a href="{{ route('admin.products.template', ['format' => 'xlsx']) }}"
                                class="inline-flex items-center gap-2 px-3 py-2 text-xs font-bold text-gray-700 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 hover:text-gray-900 transition-colors"
                                title="Download Template Excel">
                                <i class="bi bi-file-earmark-excel-fill text-lg text-green-600"></i>
                                <span>.XLSX</span>
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="relative group cursor-pointer mb-6">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl opacity-50 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div
                            class="relative border-2 border-dashed border-gray-300 group-hover:border-blue-400 rounded-3xl p-8 transition-colors text-center bg-white/50">

                            <input type="file" name="file" id="fileImport"
                                accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @change="handleFileSelect($event)">

                            <div x-show="!fileName">
                                <i
                                    class="bi bi-file-earmark-spreadsheet text-4xl text-gray-400 group-hover:text-blue-500 transition-colors mb-2 block"></i>
                                <span class="text-sm font-bold text-gray-600">Klik untuk upload</span>
                                <span class="text-xs text-gray-400 block mt-1">atau drag & drop file ke sini</span>
                            </div>

                            <div x-show="fileName" class="flex items-center justify-center gap-3">
                                <div
                                    class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl">
                                    <i class="bi bi-file-earmark-excel-fill"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-gray-900" x-text="fileName"></p>
                                    <p class="text-xs text-gray-500"><span x-text="totalRows"></span> Baris Data
                                        Ditemukan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="previewData.length > 0" class="mb-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Preview 5 Data Teratas
                        </h4>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-left text-xs text-gray-600">
                                <thead class="bg-gray-50 text-gray-900 font-bold uppercase">
                                    <tr>
                                        <template x-for="header in headers" :key="header">
                                            <th class="px-4 py-3" x-text="header"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    <template x-for="(row, index) in previewData" :key="index">
                                        <tr>
                                            <template x-for="header in headers" :key="header">
                                                <td class="px-4 py-2" x-text="row[header]"></td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2 text-right">*Hanya menampilkan sampel data. Semua data
                            valid akan diimport.</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="importModalOpen = false"
                            class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" :disabled="!fileName"
                            :class="!fileName ? 'opacity-50 cursor-not-allowed bg-gray-400' :
                                'bg-green-600 hover:bg-green-700 shadow-lg shadow-green-200'"
                            class="py-3.5 rounded-2xl text-white font-bold transition flex items-center justify-center gap-2">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            Proses Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

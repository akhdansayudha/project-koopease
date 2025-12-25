@extends('layouts.admin')

@section('content')
    <div class="space-y-6" x-data="{
        createModalOpen: false,
        editModalOpen: false,
        deleteModalOpen: false,
        importModalOpen: false,
        deleteUrl: '',

        // State untuk Import
        fileName: null,
        totalRows: 0,
        previewData: [],
        headers: [],

        formData: {
            id: null,
            name: '',
            category_id: '',
            price: '',
            stock: '',
            description: '',
            status: '1',
            image_url: null
        },
        openEditModal(data) {
            this.formData = {
                id: data.product_id,
                name: data.nama_produk,
                category_id: data.category_id,
                price: data.harga,
                stock: data.stok,
                description: data.deskripsi,
                status: data.is_available ? '1' : '0',
                image_url: data.gambar_url
            };
            this.editModalOpen = true;
        },
        openDeleteModal(url) {
            this.deleteUrl = url;
            this.deleteModalOpen = true;
        },
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.fileName = file.name;

                const reader = new FileReader();
                reader.onload = (e) => {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];

                    // Konversi ke JSON untuk preview
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, { limit: 6 }); // Ambil header + 5 baris
                    const allData = XLSX.utils.sheet_to_json(worksheet); // Untuk hitung total

                    if (jsonData.length > 0) {
                        this.headers = Object.keys(jsonData[0]);
                        this.previewData = jsonData;
                        this.totalRows = allData.length;
                    }
                };
                reader.readAsArrayBuffer(file);
            }
        },
        closeModals() {
            this.createModalOpen = false;
            this.editModalOpen = false;
            this.deleteModalOpen = false;
            this.importModalOpen = false; // Reset import modal
            this.fileName = null;
            this.previewData = [];
        }
    }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Produk</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola inventaris dan katalog produk koperasi.</p>
            </div>

            <div class="flex items-center gap-3">

                <button @click="importModalOpen = true"
                    class="inline-flex items-center justify-center px-4 py-2.5 font-bold text-gray-700 transition-all duration-200 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 shadow-sm hover:shadow-md">
                    <i class="bi bi-file-earmark-spreadsheet mr-2 text-lg"></i>
                    Import
                </button>

                <button @click="createModalOpen = true"
                    class="group relative inline-flex items-center justify-center px-6 py-2.5 font-semibold text-white transition-all duration-200 bg-gray-900 rounded-xl hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-lg shadow-gray-200 hover:shadow-gray-300 hover:-translate-y-0.5">
                    <i class="bi bi-plus-lg mr-2 transition-transform group-hover:rotate-90"></i>
                    Tambah Produk
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Total Produk</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ number_format($stats['total_products']) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-600 text-white rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Produk Aktif</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">
                            {{ number_format($stats['active_products']) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-600 text-white rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-2xl border border-red-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-red-600 uppercase tracking-wide">Stok Habis</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ number_format($stats['out_of_stock']) }}
                        </h3>
                    </div>
                    <div class="w-12 h-12 bg-red-600 text-white rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Kategori</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">
                            {{ number_format($stats['total_categories']) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <form action="{{ route('admin.products.index') }}" method="GET"
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Produk</h3>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <div class="relative group w-full md:w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama produk..."
                                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-all placeholder-gray-400">
                            <i
                                class="bi bi-search absolute left-3 top-3 text-gray-400 group-focus-within:text-gray-600 transition-colors"></i>
                        </div>

                        <div class="relative w-full md:w-48">
                            <select name="category" onchange="this.form.submit()"
                                class="w-full pl-3 pr-10 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-all appearance-none cursor-pointer">
                                <option value="all">Semua Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ request('category') == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <i
                                class="bi bi-chevron-down absolute right-3 top-3 text-gray-400 pointer-events-none text-xs"></i>
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-[30%]">Produk</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                            @if ($product->gambar_url)
                                                <img src="{{ $product->gambar_url }}" alt=""
                                                    class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                    <i class="bi bi-image text-lg"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-gray-900 truncate">{{ $product->nama_produk }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5 line-clamp-1">
                                                {{ Str::limit($product->deskripsi, 30) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $product->category->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-mono font-medium text-gray-900">
                                    Rp {{ number_format($product->harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($product->stok <= 5)
                                        <span class="text-red-600 font-bold flex items-center gap-1">
                                            {{ $product->stok }} <i class="bi bi-exclamation-circle-fill text-[10px]"></i>
                                        </span>
                                    @else
                                        <span class="text-gray-900 font-medium">{{ $product->stok }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($product->stok == 0)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Habis
                                        </span>
                                    @elseif($product->is_available)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-start gap-2">
                                        <button @click="openEditModal({{ $product }})"
                                            class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button
                                            @click="openDeleteModal('{{ route('admin.products.destroy', $product->product_id) }}')"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-box text-3xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Belum ada produk</h3>
                                        <button @click="createModalOpen = true"
                                            class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                            + Tambah Produk Baru
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $products->links('admin.components.pagination') }}
                </div>
            @endif
        </div>

        @include('admin.products.tambahproduk')
        @include('admin.products.editproduk')
        @include('admin.products.hapusproduk')
        @include('admin.products.importproduk')
    </div>
@endsection

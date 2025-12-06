@extends('layouts.admin')

@section('content')
    <div class="space-y-6" x-data="{
        createModalOpen: false,
        editModalOpen: false,
        deleteModalOpen: false,
        deleteUrl: '',
        formData: {
            id: null,
            name: '',
            slug: '',
            icon: '',
            color: ''
        },
        openEditModal(data) {
            this.formData = {
                id: data.category_id,
                name: data.nama_kategori,
                slug: data.slug,
                icon: data.icon,
                color: data.color
            };
            this.editModalOpen = true;
        },
        openDeleteModal(url) {
            this.deleteUrl = url;
            this.deleteModalOpen = true;
        }
    }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Kategori</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola kategori produk koperasi.</p>
            </div>

            <button @click="createModalOpen = true"
                class="group relative inline-flex items-center justify-center px-6 py-2.5 font-semibold text-white transition-all duration-200 bg-gray-900 rounded-xl hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-lg shadow-gray-200 hover:shadow-gray-300 hover:-translate-y-0.5">
                <i class="bi bi-plus-lg mr-2 transition-transform group-hover:rotate-90"></i>
                Tambah Kategori
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total Kategori</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['total_categories'] }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-2xl border border-orange-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between h-full gap-4">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-orange-600 uppercase tracking-wide">Produk Terbanyak</p>
                        <h3 class="text-lg font-extrabold text-gray-900 mt-2 leading-tight">
                            {{ $stats['most_populated']->nama_kategori ?? '-' }}
                        </h3>
                        <p class="text-xs text-orange-600 mt-1 font-medium">
                            {{ $stats['most_populated']->products_count ?? 0 }} Produk Total
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-orange-500 text-white rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-star-fill"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between h-full">
                    <div>
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Total Produk</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['total_products'] }}</h3>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-600 text-white rounded-xl flex items-center justify-center text-xl shrink-0">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <form action="{{ route('admin.categories.index') }}" method="GET"
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Kategori</h3>

                    <div class="relative group w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..."
                            class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-transparent text-gray-900 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-gray-200 focus:border-gray-400 transition-all placeholder-gray-400">
                        <i
                            class="bi bi-search absolute left-3 top-3 text-gray-400 group-focus-within:text-gray-600 transition-colors"></i>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50/50 text-gray-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 w-[8%]">ID</th>
                            <th class="px-6 py-4 w-[30%]">Kategori</th>
                            <th class="px-6 py-4 w-[12%]">Total</th>
                            <th class="px-6 py-4 w-[10%]">Aktif</th>
                            <th class="px-6 py-4 w-[10%]">Nonaktif</th>
                            <th class="px-6 py-4 w-[15%]">Dibuat Pada</th>
                            <th class="px-6 py-4 w-[15%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ $category->category_id }}</td>

                                {{-- UPDATE: Tampilan Kategori dengan Icon & Warna --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center text-lg shrink-0 border bg-{{ $category->color }}-100 text-{{ $category->color }}-700 border-{{ $category->color }}-200">
                                            {{ $category->icon ?? '📦' }}
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 text-base leading-tight">
                                                {{ $category->nama_kategori }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-mono mt-0.5">
                                                /{{ $category->slug }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $category->products_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-600 border border-green-100">
                                        {{ $category->active_products_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-600 border border-red-100">
                                        {{ $category->inactive_products_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($category->created_at)->format('d M Y') }}
                                        </span>
                                        <span class="text-gray-400">
                                            {{ \Carbon\Carbon::parse($category->created_at)->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-start gap-2">
                                        {{-- Pass object lengkap ke Edit Modal (termasuk slug, icon, color) --}}
                                        <button @click="openEditModal({{ $category }})"
                                            class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button
                                            @click="openDeleteModal('{{ route('admin.categories.destroy', $category->category_id) }}')"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-tags text-3xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Belum ada kategori</h3>
                                        <button @click="createModalOpen = true"
                                            class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                            + Tambah Kategori Baru
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $categories->links('admin.components.pagination') }}
                </div>
            @endif
        </div>

        @include('admin.categories.tambahkategori')
        @include('admin.categories.editkategori')
        @include('admin.categories.hapuskategori')
    </div>
@endsection

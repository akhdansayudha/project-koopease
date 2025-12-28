@extends('layouts.admin')

@section('content')
    <div class="space-y-6" x-data="{
        createModalOpen: false,
        editModalOpen: false,
        deleteModalOpen: false,
        deleteUrl: '',
        deleteName: '',
        formData: {
            id: null,
            name: '',
            email: '',
            role: 'mahasiswa', // Default
            password: ''
        },
        openEditModal(user) {
            this.formData = {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role,
                password: ''
            };
            this.editModalOpen = true;
        },
        openDeleteModal(user, url) {
            this.deleteName = user.name;
            this.deleteUrl = url;
            this.deleteModalOpen = true;
        }
    }">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">Manajemen Pengguna</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola data mahasiswa, pegawai, dan administrator.</p>
            </div>

            <button @click="createModalOpen = true"
                class="group relative inline-flex items-center justify-center px-6 py-2.5 font-semibold text-white transition-all duration-200 bg-gray-900 rounded-xl hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 shadow-lg shadow-gray-200 hover:shadow-gray-300 hover:-translate-y-0.5">
                <i class="bi bi-person-plus-fill mr-2 transition-transform group-hover:scale-110"></i>
                Tambah Pengguna
            </button>
        </div>

        {{-- UPDATE: Grid menjadi 5 kolom di layar besar (lg) untuk mengakomodasi Pegawai --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Total --}}
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl border border-blue-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">Total</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['total_users'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            {{-- Mahasiswa --}}
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-2xl border border-green-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-green-600 uppercase tracking-wide">Mahasiswa</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['students'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-green-600 text-white rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-backpack-fill"></i>
                    </div>
                </div>
            </div>

            {{-- NEW: Pegawai Card --}}
            <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 p-6 rounded-2xl border border-cyan-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-cyan-600 uppercase tracking-wide">Pegawai</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['employees'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-cyan-600 text-white rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
            </div>

            {{-- Admin --}}
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-2xl border border-purple-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-purple-600 uppercase tracking-wide">Admin</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['admins'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-purple-600 text-white rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
            </div>

            {{-- New This Month --}}
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-2xl border border-orange-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-orange-600 uppercase tracking-wide">Baru</p>
                        <h3 class="text-2xl font-extrabold text-gray-900 mt-2">+{{ $stats['new_this_month'] }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-orange-500 text-white rounded-xl flex items-center justify-center text-lg">
                        <i class="bi bi-person-plus"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <form action="{{ route('admin.users.index') }}" method="GET"
                    class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Pengguna</h3>

                    <div class="relative group w-full md:w-80">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
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
                            <th class="px-6 py-4 w-[5%]">ID</th>
                            <th class="px-6 py-4 w-[35%]">Pengguna</th>
                            <th class="px-6 py-4 w-[15%]">Role</th>
                            <th class="px-6 py-4 w-[20%]">Bergabung</th>
                            <th class="px-6 py-4 w-[25%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 flex items-center justify-center text-gray-600 font-bold border-2 border-white shadow-sm shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- UPDATE: Logika Badge untuk 3 Role --}}
                                    @if ($user->role == 'admin')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                            <i class="bi bi-shield-lock-fill text-[10px]"></i> Admin
                                        </span>
                                    @elseif($user->role == 'pegawai')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-cyan-50 text-cyan-700 border border-cyan-100">
                                            <i class="bi bi-briefcase-fill text-[10px]"></i> Pegawai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-green-50 text-green-700 border border-green-100">
                                            <i class="bi bi-backpack text-[10px]"></i> Mahasiswa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $user->created_at->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-start gap-2">
                                        <button @click="openEditModal({{ $user }})"
                                            class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 rounded-lg transition-colors"
                                            title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <button
                                            @click="openDeleteModal({{ $user }}, '{{ route('admin.users.destroy', $user->id) }}')"
                                            class="p-2 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg transition-colors"
                                            title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="bi bi-people text-3xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Tidak ada pengguna</h3>
                                        <button @click="createModalOpen = true"
                                            class="mt-4 text-sm font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                            + Tambah Pengguna Baru
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $users->links('admin.components.pagination') }}
                </div>
            @endif
        </div>

        {{-- MODAL CREATE --}}
        <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak
            @keydown.escape="createModalOpen = false">
            <div x-show="createModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="createModalOpen = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="createModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/50">
                    <div class="px-8 py-8">
                        <div class="mb-6 text-center">
                            <h3 class="text-2xl font-extrabold text-gray-900">Tambah Pengguna</h3>
                            <p class="text-sm text-gray-500">Buat akun baru untuk akses sistem.</p>
                        </div>
                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Email</label>
                                <input type="email" name="email" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Role</label>
                                    <select name="role"
                                        class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4 appearance-none">
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="pegawai">Pegawai</option> {{-- Added --}}
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Password</label>
                                    <input type="password" name="password" required
                                        class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4">
                                </div>
                            </div>
                            <div class="mt-8 grid grid-cols-2 gap-3">
                                <button type="button" @click="createModalOpen = false"
                                    class="py-3 rounded-2xl border border-gray-200 font-bold hover:bg-gray-50">Batal</button>
                                <button type="submit"
                                    class="py-3 rounded-2xl bg-gray-900 text-white font-bold hover:bg-black">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-[70] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak
            @keydown.escape="editModalOpen = false">
            <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="editModalOpen = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="editModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/50">
                    <div class="px-8 py-8">
                        <div class="mb-6 relative">
                            <span
                                class="bg-blue-50 text-blue-600 text-[10px] font-bold px-3 py-1 rounded-full uppercase border border-blue-100 mb-2 inline-block">Mode
                                Edit</span>
                            <h3 class="text-2xl font-extrabold text-gray-900">Edit Pengguna</h3>
                        </div>
                        <form method="POST" :action="'{{ url('admin/pengguna') }}/' + formData.id" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Nama
                                    Lengkap</label>
                                <input type="text" name="name" x-model="formData.name" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Email</label>
                                <input type="email" name="email" x-model="formData.email" required
                                    class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Role</label>
                                    <select name="role" x-model="formData.role"
                                        class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4 appearance-none">
                                        <option value="mahasiswa">Mahasiswa</option>
                                        <option value="pegawai">Pegawai</option> {{-- Added --}}
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-1">Password Baru
                                        (Opsional)</label>
                                    <input type="password" name="password" placeholder="Biarkan kosong jika tetap"
                                        class="w-full rounded-2xl border-transparent bg-gray-50 focus:bg-white focus:ring-2 focus:ring-gray-200 font-semibold py-3 px-4">
                                </div>
                            </div>
                            <div class="mt-8 grid grid-cols-2 gap-3">
                                <button type="button" @click="editModalOpen = false"
                                    class="py-3 rounded-2xl border border-gray-200 font-bold hover:bg-gray-50">Batal</button>
                                <button type="submit"
                                    class="py-3 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700">Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DELETE (TIDAK BERUBAH TAPI PERLU DITUTUP DIV NYA) --}}
        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-[80] overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak
            @keydown.escape="deleteModalOpen = false">
            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="deleteModalOpen = false">
            </div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-white/50">
                    <div class="px-8 py-10 text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-50 mb-6">
                            <i class="bi bi-person-x-fill text-4xl text-red-500"></i>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 leading-tight mb-2">Hapus Pengguna?</h3>
                        <p class="text-sm text-gray-500 mb-2">Apakah Anda yakin ingin menghapus akun:</p>
                        <p class="text-lg font-bold text-gray-900 mb-8" x-text="deleteName"></p>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" @click="deleteModalOpen = false"
                                class="py-3.5 rounded-2xl border border-gray-200 text-gray-600 font-bold hover:bg-gray-50">Batal</button>
                            <form :action="deleteUrl" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full py-3.5 rounded-2xl bg-red-600 text-white font-bold hover:bg-red-700 shadow-lg shadow-red-200">Ya,
                                    Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

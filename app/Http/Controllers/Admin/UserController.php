<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. Statistik untuk Cards (Update: Tambah Employees)
        $stats = [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'students' => User::where('role', 'mahasiswa')->count(),
            'employees' => User::where('role', 'pegawai')->count(), // Tambahan
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        // 2. Query Data dengan Pencarian
        $query = User::latest();

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Menggunakan ilike untuk PostgreSQL (Case Insensitive)
                // Jika error di MySQL, ganti 'ilike' menjadi 'like'
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,mahasiswa,pegawai', // Update: Tambah pegawai
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Asumsi kolom ini ada di database, jika tidak hapus saja
            'notify_order' => true,
            'notify_promo' => true,
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,mahasiswa,pegawai', // Update: Tambah pegawai
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() == $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }
}

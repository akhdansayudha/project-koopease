<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // 1. Tampilkan Halaman Profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    // 2. Update Data Profil
    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'name' => 'required|string|max:255',
            // Email biasanya tidak boleh diganti sembarangan karena akun kampus, 
            // tapi jika ingin fitur ganti email, uncomment baris bawah:
            // 'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->name = $request->name;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}

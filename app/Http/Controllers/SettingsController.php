<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.current_password' => 'Kata sandi saat ini salah.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::find(Auth::id());
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui!');
    }

    // Tambahkan method baru untuk update notifikasi
    public function updateNotifications(Request $request)
    {
        logger('Update notifications called');
        logger('Request data: ' . json_encode($request->all()));

        $request->validate([
            'notify_order' => 'sometimes|boolean',
            'notify_promo' => 'sometimes|boolean',
        ]);

        $user = User::find(Auth::id());

        if (!$user) {
            logger('User not found');
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        $changes = false;

        if ($request->has('notify_order')) {
            logger('Updating notify_order to: ' . $request->notify_order);
            $user->notify_order = (bool) $request->notify_order;
            $changes = true;
        }

        if ($request->has('notify_promo')) {
            logger('Updating notify_promo to: ' . $request->notify_promo);
            $user->notify_promo = (bool) $request->notify_promo;
            $changes = true;
        }

        if ($changes) {
            $user->save();
            logger('User saved successfully');
            logger('New values - notify_order: ' . $user->notify_order . ', notify_promo: ' . $user->notify_promo);
        }

        return response()->json([
            'success' => true,
            'message' => 'Preferensi notifikasi berhasil diperbarui!',
            'data' => [
                'notify_order' => $user->notify_order,
                'notify_promo' => $user->notify_promo
            ]
        ]);
    }

    public function deleteAccount(Request $request)
    {
        logger('Delete account method called');
        logger('User ID: ' . Auth::id());
        logger('Confirmation: ' . $request->confirmation);

        // Validasi konfirmasi
        $request->validate([
            'confirmation' => 'required|in:HAPUS AKUN'
        ], [
            'confirmation.in' => 'Anda harus mengetik HAPUS AKUN untuk konfirmasi.'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Debug: Cek apakah user ditemukan
        if (!$user) {
            return redirect()->route('login')->with('error', 'Sesi telah berakhir.');
        }

        // Hapus data terkait (pastikan model-model ini ada)
        \App\Models\CartItem::where('user_id', $user->id)->delete();

        $orders = \App\Models\Order::where('user_id', $user->id)->get();
        foreach ($orders as $order) {
            \App\Models\OrderItem::where('order_id', $order->id)->delete();
            $order->delete();
        }

        \App\Models\Notification::where('user_id', $user->id)->delete();

        // Logout user
        Auth::logout();

        // Hapus user - gunakan forceDelete() jika menggunakan SoftDeletes
        $deleted = $user->delete();

        // Jika menggunakan SoftDeletes, gunakan ini:
        // $deleted = $user->forceDelete();

        // Bersihkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Akun Anda berhasil dihapus.');
    }
}

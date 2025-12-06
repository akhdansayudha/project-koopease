<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        // Cari notifikasi milik user yang sedang login
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);

        // Update status menjadi sudah dibaca
        $notification->update(['is_read' => true]);

        // Redirect sesuai tipe notifikasi
        if ($notification->order_id) {
            // Jika terkait pesanan, arahkan ke detail pesanan
            return redirect()->route('orders.show', $notification->order_id);
        }

        // Jika notifikasi umum (blast promo dll), refresh halaman saja
        return back();
    }

    // Opsional: Tandai semua sudah dibaca
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }
}

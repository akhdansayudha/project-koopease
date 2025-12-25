<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class OrderController extends Controller
{
    // ... (Method index dan show tidak berubah) ...
    public function index(Request $request)
    {
        // ... (Kode sama seperti sebelumnya) ...
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Order::with(['user', 'orderItems.product'])->latest();

        if ($status && $status != 'semua') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('total_price', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'ilike', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // UPDATE PADA METHOD INI
    public function update(Request $request, $id)
    {
        $order = Order::with('orderItems')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:diproses,siap_diambil,selesai,dibatalkan'
        ]);

        try {
            DB::transaction(function () use ($order, $request) {
                $newStatus = $request->status;
                $oldStatus = $order->status;

                // 1. LOGIKA BATAL: Kembalikan Stok
                // Jika status baru 'dibatalkan' DAN sebelumnya bukan 'dibatalkan'
                if ($newStatus == 'dibatalkan' && $oldStatus != 'dibatalkan') {
                    foreach ($order->orderItems as $item) {
                        if ($item->product_id) {
                            Product::where('product_id', $item->product_id)
                                ->increment('stok', $item->quantity);
                        }
                    }
                }

                // 2. LOGIKA SELESAI: Tambah Counter Terjual
                // Jika status baru 'selesai' DAN sebelumnya bukan 'selesai'
                if ($newStatus == 'selesai' && $oldStatus != 'selesai') {
                    foreach ($order->orderItems as $item) {
                        if ($item->product_id) {
                            Product::where('product_id', $item->product_id)
                                ->increment('terjual', $item->quantity);
                        }
                    }
                }

                // 3. LOGIKA SIAP DIAMBIL: Kirim Notifikasi ke User
                if ($newStatus == 'siap_diambil' && $oldStatus != 'siap_diambil') {
                    Notification::create([
                        'user_id' => $order->user_id, // Mengirim ke pemilik pesanan
                        'order_id' => $order->id,     // Penting: Agar saat diklik redirect ke detail order
                        'message' => 'Pesanan #' . $order->id . ' sudah siap diambil. Silakan ambil pesanan Anda.',
                        'is_read' => false,
                    ]);
                }

                // Update status pesanan
                $order->update(['status' => $newStatus]);
            });

            return back()->with('success', 'Status pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

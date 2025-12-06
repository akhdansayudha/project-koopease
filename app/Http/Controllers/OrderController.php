<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // PENTING: Import Facade DB untuk transaksi

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('orderItems.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    // Batalkan Pesanan - Diperbarui dengan Pengembalian Stok
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('orderItems') // Load item agar bisa dikembalikan stoknya
            ->firstOrFail();

        // Hanya bisa dibatalkan jika status masih menunggu_pembayaran atau menunggu_konfirmasi
        if (in_array($order->status, ['menunggu_pembayaran', 'menunggu_konfirmasi'])) {

            try {
                // Gunakan Transaksi Database agar aman (Stok kembali + Status berubah)
                DB::transaction(function () use ($order) {

                    // 1. Kembalikan Stok Produk
                    foreach ($order->orderItems as $item) {
                        if ($item->product_id) {
                            // Cari produk dan tambahkan stoknya kembali sesuai qty pembelian
                            \App\Models\Product::where('product_id', $item->product_id)
                                ->increment('stok', $item->quantity);
                        }
                    }

                    // 2. Update Status Pesanan jadi 'dibatalkan'
                    $order->update(['status' => 'dibatalkan']);
                });

                return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
            }
        }

        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses atau selesai.');
    }
}

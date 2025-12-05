<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;

class CheckoutController extends Controller
{
    // 1. PROSES BUAT ORDER (Dipanggil saat klik Checkout di Keranjang)
    public function process()
    {
        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        // --- UPDATE 1: KODE UNIK BERURUTAN (1-99) ---
        // Ambil kode unik terakhir dari order yang statusnya 'menunggu_pembayaran'
        // agar tidak bentrok.
        $lastOrder = Order::where('status', 'menunggu_pembayaran')
            ->orderBy('created_at', 'desc')
            ->first();

        $lastCode = 0;
        if ($lastOrder) {
            // Ambil 2 digit terakhir dari total harga sebelumnya sebagai referensi kode unik
            $lastCode = $lastOrder->total_price % 100;
        }

        // Increment, jika > 99 reset ke 1
        $kodeUnik = $lastCode + 1;
        if ($kodeUnik > 99) {
            $kodeUnik = 1;
        }
        // --------------------------------------------

        $subtotal = $cartItems->sum(fn($item) => $item->product->harga * $item->quantity);
        $grandTotal = $subtotal + $kodeUnik;

        try {
            // Gunakan Transaction agar data konsisten (Order Created + Stok Berkurang)
            $order = DB::transaction(function () use ($userId, $cartItems, $grandTotal) {
                // A. Buat Order
                $newOrder = Order::create([
                    'user_id' => $userId,
                    'total_price' => $grandTotal,
                    'status' => 'menunggu_pembayaran',
                    'payment_method' => 'transfer_manual',
                ]);

                Notification::create([
                    'user_id' => $userId,
                    'order_id' => $newOrder->id,
                    'message' => 'Pesanan #' . $newOrder->id . ' berhasil dibuat. Silakan lakukan pembayaran.',
                    'is_read' => false
                ]);

                foreach ($cartItems as $item) {
                    // --- UPDATE 2: CEK & KURANGI STOK ---
                    // Lock row untuk menghindari race condition
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if (!$product || $product->stok < $item->quantity) {
                        // Jika stok habis saat proses, rollback transaksi
                        throw new \Exception("Stok untuk produk {$product->nama_produk} tidak mencukupi.");
                    }

                    // Kurangi Stok
                    $product->decrement('stok', $item->quantity);
                    // ------------------------------------

                    // B. Pindahkan item ke OrderItem
                    OrderItem::create([
                        'order_id' => $newOrder->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'harga_saat_pesan' => $item->product->harga,
                        'nama_produk_saat_pesan' => $item->product->nama_produk,
                    ]);
                }

                // C. Hapus Keranjang
                CartItem::where('user_id', $userId)->delete();

                return $newOrder;
            });

            return redirect()->route('checkout.show', $order->id);
        } catch (\Exception $e) {
            // Jika stok habis atau error lain, kembalikan ke keranjang dengan pesan error
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    // 2. TAMPILKAN HALAMAN PEMBAYARAN
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        // Jika status bukan menunggu pembayaran, lempar ke history
        if ($order->status != 'menunggu_pembayaran') {
            return redirect()->route('orders.index');
        }

        return view('checkout.index', compact('order'));
    }

    // 3. KONFIRMASI PEMBAYARAN (Dipanggil saat klik 'Saya Sudah Bayar')
    public function confirm($id)
    {
        $order = Order::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $order->update([
            'status' => 'menunggu_konfirmasi'
        ]);

        return redirect()->route('orders.index')->with('success', 'Pembayaran dikonfirmasi! Mohon tunggu admin.');
    }
}

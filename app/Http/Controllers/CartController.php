<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Helper function untuk mendapatkan jumlah item di keranjang
    private function getCartItemCount()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->count();
        }
        return 0;
    }

    // 1. Tampilkan Halaman Keranjang
    public function index()
    {
        $userId = Auth::id();

        // Ambil semua item di keranjang milik user ini, beserta data produknya
        $cartItems = CartItem::where('user_id', $userId)
            ->with('product')
            ->orderBy('id', 'asc')
            ->get();

        // Hitung Total Harga
        $total = $cartItems->sum(function ($item) {
            return $item->product->harga * $item->quantity;
        });

        // Jumlah item di keranjang untuk badge
        $cartItemCount = $this->getCartItemCount();

        return view('cart.index', compact('cartItems', 'total', 'cartItemCount'));
    }

    // 2. Tambah Item ke Keranjang (Support Single Add & Bulk Add)
    public function store(Request $request)
    {
        $userId = Auth::id();

        // --- SKENARIO 1: BULK ADD (Dari Modal Rebuy / Beli Lagi) ---
        // Cek apakah ada input 'products' yang berbentuk array
        if ($request->has('products') && is_array($request->products)) {

            $countAdded = 0;

            foreach ($request->products as $item) {
                // Pastikan data product_id dan quantity ada
                if (isset($item['product_id']) && isset($item['quantity'])) {

                    $productId = $item['product_id'];
                    $qty = (int) $item['quantity'];

                    if ($qty > 0) {
                        // Logika Cek/Update/Create (Sama seperti single add)
                        $existingItem = CartItem::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

                        if ($existingItem) {
                            $existingItem->quantity += $qty;
                            $existingItem->save();
                        } else {
                            CartItem::create([
                                'user_id' => $userId,
                                'product_id' => $productId,
                                'quantity' => $qty
                            ]);
                        }
                        $countAdded++;
                    }
                }
            }

            // Redirect ke halaman keranjang setelah proses selesai
            return redirect()->route('cart.index')->with('success', $countAdded . ' produk berhasil ditambahkan ke keranjang!');
        }

        // --- SKENARIO 2: SINGLE ADD (Logic Lama untuk Halaman Detail Produk) ---

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        // Cek apakah produk sudah ada di keranjang user ini?
        $existingItem = CartItem::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingItem) {
            // Jika sudah ada, update jumlahnya saja
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            // Jika belum ada, buat baru
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity 
            ]);
        }

        // Cek Direct Buy
        if ($request->has('is_direct_buy') && $request->is_direct_buy == 'true') {
            return redirect()->route('cart.index');
        }
        

        return redirect()->back()->with('success', 'Produk berhasil masuk keranjang!');
    }

    // 3. Update Jumlah (Tombol + / - di halaman keranjang) - AJAX VERSION
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();

        $oldQuantity = $cartItem->quantity;

        if ($request->type == 'plus') {
            $cartItem->increment('quantity');
        } elseif ($request->type == 'minus' && $cartItem->quantity > 1) {
            $cartItem->decrement('quantity');
        } elseif ($request->type == 'custom' && $request->has('quantity')) {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:999'
            ]);
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        // Reload the item with product data
        $cartItem->load('product');

        // Calculate new totals
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        $subtotal = $cartItem->product->harga * $cartItem->quantity;
        $total = $cartItems->sum(function ($item) {
            return $item->product->harga * $item->quantity;
        });
        $totalItems = $cartItems->sum('quantity');
        $cartItemCount = $cartItems->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'quantity' => $cartItem->quantity,
                'subtotal' => $subtotal,
                'total' => $total,
                'total_items' => $totalItems,
                'cart_item_count' => $cartItemCount,
                'item_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.'),
                'formatted_subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.')
            ]);
        }

        return redirect()->route('cart.index');
    }

    // 4. Hapus Item - AJAX VERSION - DIUBAH: Tambah nama produk
    public function destroy(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $productName = $cartItem->product->nama_produk; // Ambil nama produk sebelum dihapus
        $cartItem->delete();

        // Calculate new totals after deletion
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum(function ($item) {
            return $item->product->harga * $item->quantity;
        });
        $totalItems = $cartItems->sum('quantity');
        $cartItemCount = $cartItems->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'total' => $total,
                'total_items' => $totalItems,
                'cart_item_count' => $cartItemCount,
                'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.'),
                'message' => "Item '{$productName}' dihapus dari keranjang."
            ]);
        }

        return redirect()->route('cart.index')->with('success', "Item '{$productName}' dihapus dari keranjang.");
    }

    // 5. Mendapatkan jumlah item di keranjang (untuk AJAX/API)
    public function getCartCount()
    {
        $count = $this->getCartItemCount();
        return response()->json(['count' => $count]);
    }

    // 6. Get Cart Summary (untuk update ringkasan)
    public function getCartSummary()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->harga * $item->quantity;
        });

        $totalItems = $cartItems->sum('quantity');
        $cartItemCount = $cartItems->count();

        return response()->json([
            'total' => $total,
            'total_items' => $totalItems,
            'cart_item_count' => $cartItemCount,
            'formatted_total' => 'Rp ' . number_format($total, 0, ',', '.')
        ]);
    }
}

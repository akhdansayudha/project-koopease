<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // Tentukan nama tabel (opsional jika standar, tapi baik untuk kepastian)
    protected $table = 'cart_items';

    // Kolom yang boleh diisi
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    /**
     * Relasi: Setiap item keranjang pasti milik SATU Produk
     */
    public function product()
    {
        // Parameter ke-2 & ke-3 penting karena primary key produk Anda 'product_id', bukan 'id'
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    /**
     * Relasi: Setiap item keranjang milik SATU User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

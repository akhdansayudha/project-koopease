<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Karena primary key di DB bukan 'id', tapi 'order_item_id'
    protected $primaryKey = 'order_item_id';

    // Matikan timestamp karena di SQL tabel order_items tidak ada created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'harga_saat_pesan',        // Sesuaikan nama kolom
        'nama_produk_saat_pesan',  // Sesuaikan nama kolom
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}

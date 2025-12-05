<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'payment_method',
        'payment_proof_url' // Persiapan jika nanti ada upload bukti
    ];

    // Relasi: Order milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Order punya banyak Order Item (Detail Barang)
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}

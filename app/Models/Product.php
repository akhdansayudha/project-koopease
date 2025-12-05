<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'category_id',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'gambar_url',
        'is_available',
        'terjual'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'harga' => 'integer',
        'stok' => 'integer',
        'terjual' => 'integer',
    ];

    // --- TAMBAHKAN INI ---
    public $timestamps = false;
    // ---------------------

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}

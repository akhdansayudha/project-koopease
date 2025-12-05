<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Menentukan primary key custom (bukan 'id')
    protected $primaryKey = 'category_id';

    // Daftar kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'nama_kategori',
        'slug',   // Kolom Baru: untuk URL yang cantik (misal: snack-makanan)
        'icon',   // Kolom Baru: untuk menyimpan emoji atau ikon (misal: 🍔)
        'color',  // Kolom Baru: untuk tema warna (misal: orange, blue)
    ];

    // Mengatur format timestamp
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke Model Product (One to Many)
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}

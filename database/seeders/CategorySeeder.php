<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Masukkan 4 Kategori Utama
        Category::create(['nama_kategori' => 'Snack & Makanan']);
        Category::create(['nama_kategori' => 'Minuman Dingin']);
        Category::create(['nama_kategori' => 'Alat Tulis (ATK)']);
        Category::create(['nama_kategori' => 'Merchandise Kampus']);
    }
}

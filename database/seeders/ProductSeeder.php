<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID Kategori dari Database
        $snack = Category::where('nama_kategori', 'Snack & Makanan')->first()->category_id;
        $minuman = Category::where('nama_kategori', 'Minuman Dingin')->first()->category_id;
        $atk = Category::where('nama_kategori', 'Alat Tulis (ATK)')->first()->category_id;
        $merch = Category::where('nama_kategori', 'Merchandise Kampus')->first()->category_id;

        // Daftar Produk (Copy dari dummy array home.blade.php Anda)
        $products = [
            [
                'category_id' => $snack,
                'nama_produk' => 'Roti Aoka Coklat',
                'deskripsi' => 'Roti panggang lembut isi selai coklat lumer.',
                'harga' => 2500,
                'stok' => 50,
                'gambar_url' => 'https://down-id.img.susercontent.com/file/id-11134207-7r990-ls5583j3e4n558',
                'is_available' => true
            ],
            [
                'category_id' => $minuman,
                'nama_produk' => 'Teh Pucuk Harum',
                'deskripsi' => 'Teh melati dalam kemasan botol praktis.',
                'harga' => 4000,
                'stok' => 30,
                'gambar_url' => 'https://www.static-src.com/wcsstore/Indraprastha/images/catalog/full//97/MTA-2766327/teh-pucuk-harum_teh-pucuk-harum-minuman-teh-350-ml_full02.jpg',
                'is_available' => true
            ],
            [
                'category_id' => $atk,
                'nama_produk' => 'Pulpen Joyko Gel',
                'deskripsi' => 'Pulpen gel hitam anti macet.',
                'harga' => 3500,
                'stok' => 100,
                'gambar_url' => 'https://images.tokopedia.net/img/cache/700/VqbcmM/2021/6/15/4d945f4c-8f19-482d-8877-33d360f76906.jpg',
                'is_available' => true
            ],
            [
                'category_id' => $merch,
                'nama_produk' => 'Hoodie Telkom',
                'deskripsi' => 'Hoodie bahan cotton fleece nyaman dipakai kuliah.',
                'harga' => 150000,
                'stok' => 10,
                'gambar_url' => 'https://down-id.img.susercontent.com/file/id-11134207-7qul6-lf4j6j6j6j6j6j',
                'is_available' => true
            ]
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}

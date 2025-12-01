<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function show($slug)
    {
        // Cari kategori berdasarkan slug dari URL
        $category = Category::where('slug', $slug)->firstOrFail();

        // Ambil produk yang terkait (Aktif & Latest)
        $products = Product::with('category')
            ->where('category_id', $category->category_id)
            ->where('is_available', true)
            ->latest()
            ->get();

        // UPDATE: Ambil SEMUA kategori untuk navigasi slider
        // Urutkan agar rapi, misal berdasarkan id atau nama
        $allCategories = Category::orderBy('category_id', 'asc')->get();

        // Kirim $allCategories ke view (gantikan otherCategories)
        return view('category', compact('category', 'products', 'allCategories'));
    }
}

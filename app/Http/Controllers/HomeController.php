<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // UPDATE: Ambil 3 kategori pertama untuk ditampilkan di card "Quick Access"
        // Anda bisa menambahkan ->orderBy() jika ingin urutan tertentu
        $categories = Category::take(3)->get();

        // Ambil 10 produk dengan penjualan terbanyak (Produk Terpopuler)
        $products = Product::with('category')
            ->orderBy('terjual', 'DESC')
            ->take(10)
            ->get();

        // Kirim variable $products dan $categories ke view
        return view('home', compact('products', 'categories'));
    }

    public function kategori($slug)
    {
        // Mapping slug -> nama_kategori (harus sama persis dengan yang ada di DB)
        // Catatan: Jika Anda sudah menggunakan CategoryController untuk route kategori, method ini mungkin tidak terpakai lagi.
        $kategoriMap = [
            'snack-makanan'   => 'Snack & Makanan',
            'minuman-dingin'  => 'Minuman Dingin',
            'alat-tulis-atk'  => 'Alat Tulis (ATK)',
            'merch-kampus'    => 'Merchandise Kampus',
        ];

        // Validasi slug
        if (!isset($kategoriMap[$slug])) {
            abort(404);
        }

        $namaKategori = $kategoriMap[$slug];

        // Ambil kategori berdasarkan nama_kategori
        $category = Category::where('nama_kategori', $namaKategori)->firstOrFail();

        // Ambil produk berdasarkan kolom 'category_id'
        $products = Product::where('category_id', $category->category_id)
            ->where('is_available', true)
            ->orderBy('terjual', 'desc')
            ->paginate(20);

        // Ambil semua kategori untuk navigasi
        $allCategories = Category::all();

        return view('category', compact('category', 'products', 'allCategories'));
    }

    // Method Search
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Cari produk berdasarkan nama_produk (ILIKE untuk PostgreSQL, LIKE untuk MySQL)
        $products = \App\Models\Product::where('nama_produk', 'ILIKE', "%{$query}%")->get();

        return view('search', compact('products', 'query'));
    }

    // API untuk Suggestion Search
    public function getSuggestions(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Ambil maksimal 5 produk yang mirip
        $products = \App\Models\Product::where('nama_produk', 'ILIKE', "%{$query}%")
            ->limit(5)
            ->get(['product_id', 'nama_produk', 'gambar_url']);

        return response()->json($products);
    }

    // Tambahkan method ini di dalam class HomeController
    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function about()
    {
        return view('pages.about');
    }
}

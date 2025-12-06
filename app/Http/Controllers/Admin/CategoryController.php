<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import Str untuk generate slug

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_categories' => Category::count(),
            'most_populated' => Category::withCount('products')->orderBy('products_count', 'desc')->first(),
            'total_products' => Product::count(),
        ];

        $query = Category::withCount([
            'products',
            'products as active_products_count' => function ($query) {
                $query->where('is_available', true);
            },
            'products as inactive_products_count' => function ($query) {
                $query->where('is_available', false);
            }
        ])->latest();

        if ($request->has('search') && $request->filled('search')) {
            $query->where('nama_kategori', 'ilike', "%{$request->search}%");
        }

        $categories = $query->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('stats', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'icon' => 'required|string|max:50', // Validasi emoji/text singkat
            'color' => 'required|string|max:50',
        ]);

        Category::create([
            'nama_kategori' => $request->nama_kategori,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->nama_kategori),
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $id . ',category_id',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id . ',category_id',
            'icon' => 'required|string|max:50',
            'color' => 'required|string|max:50',
        ]);

        $category->update([
            'nama_kategori' => $request->nama_kategori,
            'slug' => $request->slug ? Str::slug($request->slug) : Str::slug($request->nama_kategori),
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Kategori masih memiliki produk.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}

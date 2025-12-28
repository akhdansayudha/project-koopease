<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; 
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dengan filter & search
     */
    public function index(Request $request)
    {
        // 1. Ambil Statistik untuk Cards (Query Hemat Resource)
        $stats = [
            'total_products'   => Product::count(),
            'active_products'  => Product::where('is_available', true)->count(),
            'out_of_stock'     => Product::where('stok', 0)->count(),
            'total_categories' => Category::count(),
        ];

        // 2. Query Data Produk
        $query = Product::with('category')->latest();

        // Logika Pencarian (PostgreSQL menggunakan 'ilike' untuk case-insensitive)
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Menggunakan tanda % di awal dan akhir string ("%{$search}%")
                // Artinya: Mencari data yang MENGANDUNG kata kunci tersebut

                // Gunakan 'ilike' karena Anda memakai Supabase (PostgreSQL) agar case-insensitive
                $q->where('nama_produk', 'ilike', "%{$search}%")
                    ->orWhere('deskripsi', 'ilike', "%{$search}%");
            });
        }

        // Filter Kategori
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }

        // Pagination 10 item per halaman
        $products = $query->paginate(10)->withQueryString();

        // Ambil semua kategori untuk dropdown filter & modal
        $categories = Category::all();

        return view('admin.products.index', compact('stats', 'products', 'categories'));
    }

    /**
     * Menyimpan produk baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,category_id', // Pastikan tabel categories ada
            'harga'        => 'required|numeric|min:0',
            'stok'         => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'is_available' => 'required|boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Handle Upload Gambar
        if ($request->hasFile('image')) {
            // Simpan ke storage/app/public/products
            $path = $request->file('image')->store('', 'supabase');

            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('supabase');
            $validated['gambar_url'] = $disk->url($path);
        } else {
            $validated['gambar_url'] = null;
        }

        Product::create($validated);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Mengupdate produk
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,category_id',
            'harga'        => 'required|numeric|min:0',
            'stok'         => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'is_available' => 'required|boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle Update Gambar
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama di Supabase jika ada
            if ($product->gambar_url) {
                // Kita perlu ambil nama filenya saja dari URL panjang
                $oldFile = basename($product->gambar_url);
                Storage::disk('supabase')->delete($oldFile);
            }

            // 2. Upload gambar baru
            $path = $request->file('image')->store('', 'supabase');

            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $disk = Storage::disk('supabase');
            $validated['gambar_url'] = $disk->url($path);
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar dari Supabase saat produk dihapus
        if ($product->gambar_url) {
            $oldFile = basename($product->gambar_url);
            Storage::disk('supabase')->delete($oldFile);
        }

        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function downloadTemplate($format)
    {
        // Validasi format agar hanya csv atau xlsx
        if (!in_array($format, ['csv', 'xlsx'])) {
            abort(404);
        }

        // Kita buat class anonim untuk mendefinisikan isi Excel
        $export = new class implements FromArray, WithHeadings {
            public function headings(): array
            {
                // Header ini HARUS SAMA PERSIS dengan key yang dipakai di fungsi import
                return [
                    'nama_produk',
                    'kategori',
                    'harga',
                    'stok',
                    'deskripsi'
                ];
            }

            public function array(): array
            {
                // Kita berikan 1 baris contoh data agar user paham cara isinya
                return [
                    ['Roti Coklat Aoka', 'Makanan Ringan', 2500, 50, 'Roti enak lembut'],
                    ['Teh Pucuk Harum', 'Minuman', 3500, 24, 'Teh melati segar']
                ];
            }
        };

        return Excel::download($export, 'template_produk.' . $format);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        try {
            // Kita gunakan DB Transaction agar jika 1 gagal, semua batal (aman)
            DB::beginTransaction();

            // Membaca file menjadi Array
            // Kita menggunakan anonymous class untuk mendefinisikan rules heading
            $rows = Excel::toArray(new class implements WithHeadingRow {}, $request->file('file'));

            // Ambil sheet pertama
            $sheetData = $rows[0] ?? [];
            $count = 0;

            foreach ($sheetData as $row) {
                // Lewati jika nama_produk kosong
                if (!isset($row['nama_produk']) || empty($row['nama_produk'])) continue;

                // 1. Cari atau Buat Kategori (Auto-create category if not exists)
                // Pastikan di Excel kolomnya bernama 'kategori'
                $categoryName = $row['kategori'] ?? 'Umum';

                // Gunakan firstOrCreate untuk mencegah duplikasi kategori
                $category = Category::firstOrCreate(
                    ['nama_kategori' => $categoryName]
                );

                // 2. Buat Produk
                Product::create([
                    'nama_produk'  => $row['nama_produk'],
                    'category_id'  => $category->category_id, // Ambil ID dari kategori yang ditemukan/dibuat
                    'harga'        => $row['harga'] ?? 0,
                    'stok'         => $row['stok'] ?? 0,
                    'deskripsi'    => $row['deskripsi'] ?? null,
                    'is_available' => true, // Default aktif
                    'gambar_url'   => null  // Kosongkan dulu untuk import massal
                ]);

                $count++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Berhasil mengimport $count produk!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}

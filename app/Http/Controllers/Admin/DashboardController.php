<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Set default session sidebar jika belum ada
        if (!session()->has('sidebar_collapsed')) {
            session(['sidebar_collapsed' => false]);
        }

        try {
            // 1. STATISTIK UTAMA (Real Data dari Database)
            $stats = [
                // Mengambil sum total_price dari tabel orders dimana status = 'selesai'
                'total_revenue' => Order::where('status', 'selesai')->sum('total_price') ?? 0,

                // Menghitung produk dengan stok < 5 dari tabel products
                'low_stock' => Product::where('stok', '<', 5)
                    ->where('is_available', true)
                    ->count() ?? 0,

                // Menghitung total produk aktif
                'total_products' => Product::where('is_available', true)->count() ?? 0,

                // Menghitung total user dengan role 'mahasiswa'
                'total_customers' => User::where('role', 'mahasiswa')->count() ?? 0,
            ];

            // 2. STATUS PESANAN (Group by status)
            // Mengambil hitungan real berdasarkan kolom 'status' di tabel orders
            $orderStatusCounts = Order::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get()
                ->keyBy('status');

            // List semua kemungkinan status agar tidak error jika count 0
            $allStatuses = ['menunggu_pembayaran', 'menunggu_konfirmasi', 'siap_diambil', 'selesai', 'dibatalkan'];

            foreach ($allStatuses as $status) {
                if (!$orderStatusCounts->has($status)) {
                    $orderStatusCounts->put($status, (object)['count' => 0]);
                }
            }

            $dailySales = Order::where('status', 'selesai')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->selectRaw("TO_CHAR(created_at, 'YYYY-MM-DD') as date, SUM(total_price) as revenue")
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            // Format data untuk Chart.js
            $dailySalesLabels = [];
            $dailySalesData = [];

            // Generate last 7 days labels to ensure empty days are shown with 0
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $displayDate = Carbon::now()->subDays($i)->format('d M');

                $dailySalesLabels[] = $displayDate;

                $record = $dailySales->firstWhere('date', $date);
                $dailySalesData[] = $record ? $record->revenue : 0;
            }

            // 3. PESANAN TERBARU (20 Data Terakhir)
            $recentOrders = Order::with('user') // Eager load relasi user
                ->latest('created_at')
                ->limit(20)
                ->get();

            // 4. PRODUK TERLARIS (Top 10)
            // Mengambil data berdasarkan kolom 'terjual' desc di tabel products
            $bestSellingProducts = Product::with('category')
                ->where('is_available', true)
                ->orderBy('terjual', 'desc')
                ->limit(10)
                ->get();

            // 5. CHART PENDAPATAN (PostgreSQL Compatible)
            $revenueChartData = $this->getRevenueChartData();
        } catch (\Exception $e) {
            // Error handling untuk development mode
            // dd($e->getMessage()); // Uncomment untuk debug jika error

            $stats = [
                'total_revenue' => 0,
                'low_stock' => 0,
                'total_products' => 0,
                'total_customers' => 0,
            ];
            $orderStatusCounts = collect();
            $recentOrders = collect();
            $bestSellingProducts = collect();
            $revenueChartData = [];
            $dailySalesLabels = [];
            $dailySalesData = [];
        }

        return view('admin.dashboard', compact(
            'stats',
            'orderStatusCounts',
            'recentOrders',
            'bestSellingProducts',
            'revenueChartData',
            'dailySalesLabels',
            'dailySalesData'
        ));
    }

    /**
     * Get revenue chart data (PostgreSQL Version)
     */
    private function getRevenueChartData()
    {
        // Ambil data 6 bulan terakhir
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        // PENTING: Menggunakan EXTRACT() karena Anda memakai PostgreSQL (Supabase)
        // Fungsi YEAR() dan MONTH() milik MySQL tidak akan jalan di sini.
        $revenueData = Order::where('status', 'selesai')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('EXTRACT(YEAR FROM created_at) as year, EXTRACT(MONTH FROM created_at) as month, SUM(total_price) as revenue')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartData = [];
        $current = $sixMonthsAgo->copy();

        for ($i = 0; $i < 6; $i++) {
            $monthVal = $current->month;
            $yearVal = $current->year;
            $monthName = $current->translatedFormat('M');

            // Mencocokkan data query dengan loop bulan
            $revenue = $revenueData->first(function ($item) use ($monthVal, $yearVal) {
                return (int)$item->year == $yearVal && (int)$item->month == $monthVal;
            });

            $chartData[] = [
                'month' => $monthName,
                'revenue' => $revenue ? $revenue->revenue : 0
            ];

            $current->addMonth();
        }

        return $chartData;
    }

    // API Route untuk real-time update (Opsional)
    public function getDashboardData()
    {
        $stats = [
            'total_revenue' => Order::where('status', 'selesai')->sum('total_price') ?? 0,
            'low_stock' => Product::where('stok', '<', 5)->where('is_available', true)->count() ?? 0,
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
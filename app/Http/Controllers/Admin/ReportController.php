<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // 1. Data Utama (Hanya yang Selesai untuk Laporan Keuangan)
        $finishedOrders = Order::with(['user', 'orderItems.product'])
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        // 2. Statistik Keuangan
        $stats = [
            'total_revenue' => $finishedOrders->sum('total_price'),
            'total_transactions' => $finishedOrders->count(),
            'items_sold' => $finishedOrders->sum(fn($o) => $o->orderItems->sum('quantity')),
            'average_order' => $finishedOrders->count() > 0 ? $finishedOrders->avg('total_price') : 0,
        ];

        // 3. Statistik Status (Mengambil SEMUA status dalam periode tersebut)
        $allOrdersStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Normalisasi data status agar tidak error jika kosong
        $statusSummary = [
            'menunggu_pembayaran' => $allOrdersStatus['menunggu_pembayaran'] ?? 0,
            'menunggu_konfirmasi' => $allOrdersStatus['menunggu_konfirmasi'] ?? 0,
            'siap_diambil' => $allOrdersStatus['siap_diambil'] ?? 0,
            'selesai' => $allOrdersStatus['selesai'] ?? 0,
            'dibatalkan' => $allOrdersStatus['dibatalkan'] ?? 0,
        ];

        // 4. Top 5 Produk Terlaris
        $topProducts = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('status', 'selesai')
                ->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->select(
                'nama_produk_saat_pesan',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(quantity * harga_saat_pesan) as total_sales')
            )
            ->groupBy('nama_produk_saat_pesan')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 5. Chart Data
        $chartData = $finishedOrders->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d M');
        })->map(function ($row) {
            return $row->sum('total_price');
        })->reverse(); // Urutkan tanggal

        return view('admin.reports.index', compact(
            'finishedOrders',
            'stats',
            'statusSummary',
            'topProducts',
            'startDate',
            'endDate',
            'chartData'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // Logic Query sama dengan Index
        $finishedOrders = Order::with(['user', 'orderItems'])
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->oldest()
            ->get();

        $stats = [
            'total_revenue' => $finishedOrders->sum('total_price'),
            'total_transactions' => $finishedOrders->count(),
            'items_sold' => $finishedOrders->sum(fn($o) => $o->orderItems->sum('quantity')),
        ];

        $allOrdersStatus = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusSummary = [
            'menunggu_pembayaran' => $allOrdersStatus['menunggu_pembayaran'] ?? 0,
            'menunggu_konfirmasi' => $allOrdersStatus['menunggu_konfirmasi'] ?? 0,
            'siap_diambil' => $allOrdersStatus['siap_diambil'] ?? 0,
            'selesai' => $allOrdersStatus['selesai'] ?? 0,
            'dibatalkan' => $allOrdersStatus['dibatalkan'] ?? 0,
        ];

        $topProducts = OrderItem::whereHas('order', function ($q) use ($startDate, $endDate) {
            $q->where('status', 'selesai')->whereBetween('created_at', [$startDate, $endDate]);
        })
            ->select('nama_produk_saat_pesan', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * harga_saat_pesan) as total_sales'))
            ->groupBy('nama_produk_saat_pesan')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $generatorName = Auth::user()->name;

        $pdf = Pdf::loadView('admin.reports.pdf', compact(
            'finishedOrders',
            'stats',
            'statusSummary',
            'topProducts',
            'startDate',
            'endDate',
            'generatorName'
        ));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_KoopEase_' . $startDate->format('dMY') . '-' . $endDate->format('dMY') . '.pdf');
    }
}

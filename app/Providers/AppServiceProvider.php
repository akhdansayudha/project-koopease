<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use Carbon\Carbon;
use App\Models\Notification; // Pastikan model ini sudah di-use
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') || $this->app->environment('local') === false) {
            URL::forceScheme('https');
        }

        // Set Locale Aplikasi & Carbon ke Indonesia
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');

        // View Composer untuk Navbar (Mengirim Data Keranjang & Notifikasi)
        View::composer('components.navbar', function ($view) {
            // Inisialisasi nilai default (untuk guest)
            $cartCount = 0;
            $unreadNotifCount = 0;
            $notifications = collect(); // Collection kosong

            // Jika User Login, ambil datanya
            if (Auth::check()) {
                $userId = Auth::id();

                // 1. Hitung Jumlah Item di Keranjang
                $cartCount = CartItem::where('user_id', $userId)->sum('quantity');

                // 2. Hitung Jumlah Notifikasi yang Belum Dibaca
                $unreadNotifCount = Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();

                // 3. Ambil 5 Notifikasi Terakhir (untuk ditampilkan di dropdown)
                $notifications = Notification::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }

            // Kirim semua variabel ke View Navbar
            $view->with('cartCount', $cartCount);
            $view->with('unreadNotifCount', $unreadNotifCount);
            $view->with('notifications', $notifications);
        });
    }
}

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController; // Controller Notifikasi User (Public)
use App\Http\Controllers\ForgotPasswordController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('kategori');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/api/search-suggestions', [HomeController::class, 'getSuggestions'])->name('api.search.suggestions');
Route::get('/kebijakan-privasi', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/syarat-ketentuan', [HomeController::class, 'terms'])->name('terms');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
Route::get('/tentang-kami', [HomeController::class, 'about'])->name('about');

// Authentication Routes (User Biasa)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group Route Lupa Sandi
Route::prefix('lupa-sandi')->group(function () {
    Route::get('/', [ForgotPasswordController::class, 'index'])->name('forgot.index');
    Route::post('/kirim-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot.sendOtp');
    Route::post('/verifikasi-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot.verifyOtp');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('forgot.reset');
});

Route::get('/email/{email}', [ForgotPasswordController::class, 'simulateEmail'])->name('forgot.simulate');

// ==========================================
// AUTHENTICATED USER ROUTES
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::prefix('keranjang')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/tambah', [CartController::class, 'store'])->name('cart.store');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/hapus/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    });

    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pembayaran/{id}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/pembayaran/{id}/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');

    Route::prefix('pesanan-saya')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    });

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('pengaturan')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
        Route::patch('/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications');
        Route::delete('/delete-account', [SettingsController::class, 'deleteAccount'])->name('settings.delete-account');
    });

    // Route Notifikasi User (Menggunakan NotificationController biasa)
    Route::get('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::prefix('admin')->group(function () {

    // Logic Redirect: Akses /admin
    Route::get('/', function () {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login');
    });

    // Auth Admin
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Admin Protected Routes
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/api/dashboard-data', [DashboardController::class, 'getDashboardData'])->name('admin.dashboard.data');

    Route::post('/toggle-sidebar', function (Request $request) {
        session(['sidebar_collapsed' => $request->collapsed]);
        return response()->json(['success' => true]);
    })->name('admin.sidebar.toggle');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('pesanan')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('/{id}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
    });

    Route::prefix('produk')->name('admin.products.')->group(function () {
        Route::get('/template/{format}', [ProductController::class, 'downloadTemplate'])->name('template');
        Route::post('/import', [ProductController::class, 'import'])->name('import');
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

    Route::resource('kategori', AdminCategoryController::class)->names('admin.categories');

    Route::prefix('pengguna')->name('admin.users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('laporan')->name('admin.reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export', [ReportController::class, 'exportPdf'])->name('export');
    });

    Route::prefix('notifikasi')->name('admin.notifications.')->group(function () {
        // PERBAIKAN DISINI: Gunakan Alias AdminNotificationController
        Route::get('/', [AdminNotificationController::class, 'index'])->name('index');
        Route::get('/buat', [AdminNotificationController::class, 'create'])->name('create');
        Route::post('/kirim', [AdminNotificationController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminNotificationController::class, 'show'])->name('show');
        Route::delete('/{id}', [AdminNotificationController::class, 'destroy'])->name('destroy');
    });
});

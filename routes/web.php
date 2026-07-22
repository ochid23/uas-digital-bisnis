<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController; 
use App\Http\Controllers\PartnerController;        
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController; 
use App\Http\Controllers\CheckoutController; 
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;

// 1. TAMBAHAN BARU: Import GoogleController dari folder Auth
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CertificateController; 

// ==========================================
// Rute Publik (Bebas Diakses Tanpa Login)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class,'show'])->name('events.show');
Route::get('/certificate/verify/{code}', [CertificateController::class, 'show'])->name('certificate.show');

// Rute Ulasan
Route::post('/events/{event}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Rute PWA Manifest & Service Worker
Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'), [
        'Content-Type' => 'application/manifest+json',
    ]);
});

Route::get('/sw.js', function () {
    return response()->file(public_path('sw.js'), [
        'Content-Type' => 'application/javascript',
    ]);
});

// Rute Webhook Midtrans (Diakses oleh sistem Midtrans)
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

// ==========================================
// Rute Otentikasi & SSO Google
// ==========================================
// Redirect login default mengarah ke halaman login admin/umum Anda
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute SSO Google yang diarahkan ke GoogleController baru
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);


// ==========================================
// Rute User Area (Membutuhkan Login)
// ==========================================
// Semua rute di dalam grup ini akan "mencegat" tamu dan melemparnya ke /login
Route::middleware(['auth'])->group(function () {
    
    // Rute Checkout (Diubah ke dalam auth middleware)
    Route::get('/checkout', [EventController::class,'checkout'])->name('checkout');
    Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

    // Rute Pembayaran & Sukses
    Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Tiket Saya
    Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');
});


// ==========================================
// Rute Admin Area (Panel Superadmin)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Rute Login Admin bebas akses
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Route Administrasi di balik tembok (Middleware auth & admin)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);

        Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::get('/partners/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
        Route::put('/partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('/partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');
    
        // Manajemen Pengguna (Ubah Role)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

        // Penerbitan E-Sertifikat Kehadiran
        Route::post('/transactions/{order_id}/issue-certificate', [CertificateController::class, 'issue'])->name('transactions.issue-certificate');
    });
});

// ==========================================
// Rute Organizer Area (Panel HIMA/Kepanitiaan)
// ==========================================
Route::prefix('organizer')->name('organizer.')->group(function () {
    
    // Jika Organizer belum login, arahkan ke halaman login admin (karena loginnya satu pintu)
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Mengamankan Route Organizer di balik tembok (Middleware auth & organizer)
    Route::middleware(['auth', 'organizer'])->group(function () {
        Route::get('/dashboard', [OrganizerDashboardController::class, 'index'])->name('dashboard');
        
        // Kelola event khusus organizer (hanya melihat, mengedit, dan menghapus event miliknya sendiri)
        Route::resource('events', OrganizerEventController::class);
        
        // Penerbitan E-Sertifikat Kehadiran oleh Organizer
        Route::post('/transactions/{order_id}/issue-certificate', [CertificateController::class, 'issue'])->name('transactions.issue-certificate');
    });
});
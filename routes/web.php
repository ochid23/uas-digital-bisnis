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
use App\Http\Controllers\CheckoutController; // Ditambahkan agar lebih rapi

// Rute User Area (Halaman Publik)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class,'show'])->name('events.show');
Route::get('/checkout', [EventController::class,'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Rute Checkout (Sesuai Langkah 4)
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// --- TAMBAHAN PERTEMUAN 11: Rute Pembayaran & Sukses ---
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');
// --------------------------------------------------------

// Redirect login default
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute Admin Area (Panel Admin)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    // Rute Login bebas akses
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Route Administrasi di balik tembok (Middleware)
    Route::middleware(['auth', 'admin'])->group(function () {
        
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // PERBAIKAN LANGKAH 4: Arahkan ke TransactionController
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        Route::resource('events', EventAdminController::class);
        
        // Soal 1 & 3: Rute Modul Kategori
        Route::resource('categories', CategoryController::class);

        // Soal 2 & 3: Rute Modul Partner
        Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::get('/partners/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
        Route::put('/partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('/partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');
    
    });
});
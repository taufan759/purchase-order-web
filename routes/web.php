<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrtuController;
use App\Http\Controllers\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', function () {
    return view('welcome2');
})->name('welcome2');

// Middleware untuk user yang sudah login (auth)
Route::middleware(['auth'])->group(function () {
    // Halaman welcome2 untuk user login
    Route::get('/welcome2', function () {
        return view('welcome2');
    })->name('welcome2');

    // Rute yang dapat diakses oleh semua user (hanya cetak)
    Route::get('/purchase_orders/{id}/pdf', [PurchaseOrderController::class, 'generatePDF'])->name('purchase_orders.pdf');
    Route::get('/purchase_orders/preview/{id}', [PurchaseOrderController::class, 'preview'])->name('purchase_orders.preview');
    Route::get('/drtus/{id}/pdf', [DrtuController::class, 'generatePDF'])->name('drtus.pdf');

    // Logout route
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Middleware khusus admin (peran admin)
Route::middleware(['auth', 'is_admin'])->group(function () {
    // Admin memiliki akses penuh ke semua fitur admin
    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::resource('drtus', DrtuController::class);

    // Akses untuk update, edit, delete purchase orders
    Route::put('/purchase_orders/{id}', [PurchaseOrderController::class, 'update'])->name('purchase_orders.update');
    Route::get('/purchase_orders/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('purchase_orders.edit');
    Route::get('/purchase_orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    Route::delete('/purchase_orders/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase_orders.destroy');

    // Akses untuk update, edit, delete drtus
    Route::get('/drtus', [DrtuController::class, 'index'])->name('drtus.index');
    Route::get('/drtus/{id}/edit', [DrtuController::class, 'edit'])->name('drtus.edit');
});

// Middleware untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/register', function() {
        return view('register');  // Ensure you have the 'register.blade.php' view
    })->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticating'])->name('authenticating');
});

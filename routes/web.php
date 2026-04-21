<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 1. PENGATUR LALU LINTAS AWAL
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'kasir' ? redirect()->route('sales.create') : redirect()->route('dashboard');
    }
    return redirect()->route('login'); 
});

// 2. KAVLING RUTE APOTEK (Wajib Login & Terverifikasi)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // =======================================================
    // [KAVLING 1] KASIR & ADMIN : Transaksi POS
    // =======================================================
    Route::middleware(['role:kasir,admin'])->group(function () {
        Route::get('/sales/create', \App\Livewire\Sales\Create::class)->name('sales.create');
        Route::get('/sales', \App\Livewire\Sales\Index::class)->name('sales.index');
        Route::get('/sales/{id}', \App\Livewire\Sales\Show::class)->name('sales.show');
    });

    // =======================================================
    // [KAVLING 2] OWNER & ADMIN : Pemantauan & Laporan (Read-Only)
    // =======================================================
    Route::middleware(['role:owner,admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

        Route::get('/users', \App\Livewire\Users\Index::class)->name('users.index');
        
        // Laporan
        Route::get('/reports/sales', \App\Livewire\Reports\Sales::class)->name('reports.sales');
        Route::get('/reports/stock-card', \App\Livewire\Reports\StockCard::class)->name('reports.stock-card');
        Route::get('/reports/shifts', \App\Livewire\Reports\Shift::class)->name('reports.shifts');

        // Master Data (Hanya View/Index)
        Route::get('/products', \App\Livewire\Products\Index::class)->name('products.index');
        Route::get('/categories', \App\Livewire\Categories\Index::class)->name('categories.index');
        Route::get('/suppliers', \App\Livewire\Suppliers\Index::class)->name('suppliers.index');
        Route::get('/customers', \App\Livewire\Customers\Index::class)->name('customers.index');
        
        // Transaksi Gudang & Inventory (Hanya View/Index)
        Route::get('/purchases', \App\Livewire\Purchases\Index::class)->name('purchases.index');
        Route::get('/purchases/{id}', \App\Livewire\Purchases\Show::class)->name('purchases.show')->where('id', '[0-9]+');
        Route::get('/purchase-orders', \App\Livewire\PurchaseOrders\Index::class)->name('purchase-orders.index');
        Route::get('/purchase-orders/{id}', \App\Livewire\PurchaseOrders\Show::class)->name('purchase-orders.show')->where('id', '[0-9]+');
        Route::get('/inventory/stock-opname', \App\Livewire\Inventory\StockOpname::class)->name('inventory.stock-opname');
        Route::get('/inventory/return-to-pbf', \App\Livewire\Inventory\ReturnToPbf::class)->name('inventory.return-to-pbf');
    });

    // =======================================================
    // [KAVLING 3] KHUSUS ADMIN : Eksekutor (Create, Edit, Delete)
    // =======================================================
    Route::middleware(['role:admin'])->group(function () {
        // Profil Apotek
        Route::get('/settings/profile', \App\Livewire\Settings\Profile::class)->name('settings.profile');
        
        // Tambah/Edit Master Data
        Route::get('/products/create', \App\Livewire\Products\Create::class)->name('products.create');
        Route::get('/products/{id}/edit', \App\Livewire\Products\Edit::class)->name('products.edit')->where('id', '[0-9]+');
        Route::get('/suppliers/create', \App\Livewire\Suppliers\Create::class)->name('suppliers.create');
        Route::get('/suppliers/{id}/edit', \App\Livewire\Suppliers\Edit::class)->name('suppliers.edit')->where('id', '[0-9]+');
        Route::get('/customers/create', \App\Livewire\Customers\Create::class)->name('customers.create');
        Route::get('/customers/{id}/edit', \App\Livewire\Customers\Edit::class)->name('customers.edit')->where('id', '[0-9]+');
        
        // Eksekusi Transaksi Gudang
        Route::get('/purchases/create', \App\Livewire\Purchases\Create::class)->name('purchases.create');
        Route::get('/purchases/{id}/print', [\App\Http\Controllers\PurchaseController::class, 'print'])->name('purchases.print')->where('id', '[0-9]+');
        Route::get('/purchase-orders/create', \App\Livewire\PurchaseOrders\Create::class)->name('purchase-orders.create');
        Route::get('/purchase-orders/{id}/print', [\App\Http\Controllers\PurchaseOrderController::class, 'print'])->name('purchase-orders.print')->where('id', '[0-9]+');
    });
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

require __DIR__.'/auth.php';
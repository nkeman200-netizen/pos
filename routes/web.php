<?php

use App\Http\Controllers\SaleController;
use App\Livewire\Purchases\Create as PurchaseCreate;
use App\Livewire\Purchases\Edit;
use App\Livewire\Purchases\Index as PurchaseIndex;
use App\Livewire\Purchases\Show as PurchaseShow;
use Illuminate\Support\Facades\Route;
use App\Livewire\Sales\Create;
use App\Livewire\Sales\Index;
use App\Livewire\Sales\Show;
use App\Livewire\Customers\Index as CustomerIndex;
use App\Livewire\Customers\Edit as CustomerEdit;
use App\Livewire\Customers\Create as CustomerCreate;
use App\Livewire\Products\Index as ProductIndex;
use App\Livewire\Products\Edit as ProductEdit;
use App\Livewire\Products\Create as ProductCreate;
use App\Livewire\Dashboard;
use App\Livewire\Suppliers;
use App\Livewire\PurchaseOrders;
use App\Livewire\Customers;
use App\Livewire\Categories;
use App\Http\Controllers\PurchaseOrderController;
Route::get('/', Dashboard::class)->name('dashboard');

Route::get('/categories', Categories\Index::class)->name('categories.index');

// Arahkan langsung ke Komponen Livewire
Route::get('/purchases', PurchaseIndex::class)->name('purchases.index');
Route::get('/purchases/create', PurchaseCreate::class)->name('purchases.create');
Route::get('purchases/{id}',PurchaseShow::class)->name('purchases.show');

// Arahkan langsung ke Komponen Livewire
Route::get('/sales', Index::class)->name('sales.index');
Route::get('/sales/create', Create::class)->name('sales.create');
Route::get('sales/{id}',Show::class)->name('sales.show');

// Arahkan langsung ke Komponen Livewire
Route::get('/products', ProductIndex::class)->name('products.index');
Route::get('/products/create', ProductCreate::class)->name('products.create');
Route::get('/products/{id}/edit', ProductEdit::class)->name('products.edit');

// Arahkan langsung ke Komponen Livewire
Route::get('/customers', CustomerIndex::class)->name('customers.index');
Route::get('/customers/create', CustomerCreate::class)->name('customers.create');
Route::get('/customers/{id}/edit', CustomerEdit::class)->name('customers.edit');

Route::get('/suppliers', Suppliers\Index::class)->name('suppliers.index');
Route::get('/suppliers/create', Suppliers\Create::class)->name('suppliers.create');
Route::get('/suppliers/{id}/edit', Suppliers\Edit::class)->name('suppliers.edit');
Route::get('/purchases/{id}/print', [App\Http\Controllers\PurchaseController::class, 'print'])->name('purchases.print');

// --- MASTER DATA ---
Route::get('/customers', Customers\Index::class)->name('customers.index');
Route::get('/customers/create', Customers\Create::class)->name('customers.create');
Route::get('/customers/{id}/edit', Customers\Edit::class)->name('customers.edit');

// --- TRANSAKSI ---
Route::get('/purchase-orders', PurchaseOrders\Index::class)->name('purchase-orders.index');
Route::get('/purchase-orders/create', PurchaseOrders\Create::class)->name('purchase-orders.create');
Route::get('/purchase-orders/{id}', PurchaseOrders\Show::class)->name('purchase-orders.show');
Route::get('/purchase-orders/{id}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');
<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Livewire\Purchases\Create as PurchaseCreate;
use App\Livewire\Purchases\Edit;
use App\Livewire\Purchases\Index as PurchaseIndex;
use App\Livewire\Purchases\Show as PurchaseShow;
use Illuminate\Support\Facades\Route;

Route::get('/', [SaleController::class,'create']);

Route::resource('products', ProductController::class);

Route::resource('customers', CustomerController::class);

Route::resource('sales', SaleController::class);

// Route::resource('purchases', PurchaseController::class);

Route::get('sales/edit', [SaleController::class,'edit'])->name('sales.edit');

// Arahkan langsung ke Komponen Livewire
Route::get('/purchases', PurchaseIndex::class)->name('purchases.index');
Route::get('/purchases/create', PurchaseCreate::class)->name('purchases.create');
Route::get('purchases/{id}',PurchaseShow::class)->name('purchases.show');
Route::get('/purchases/{id}/edit', Edit::class)->name('purchases.edit');
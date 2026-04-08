<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SaleController::class,'create']);

Route::resource('products', ProductController::class);

Route::resource('customers', CustomerController::class);

Route::resource('sales', SaleController::class);

Route::resource('purchase', PurchaseController::class);

Route::get('sales/edit', [SaleController::class,'edit'])->name('sales.edit');
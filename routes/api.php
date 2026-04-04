<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

// ==========================================
// PINTU LUAR (Siapapun boleh akses)
// ==========================================
Route::post('/login', [AuthController::class, 'login']);


// ==========================================
// RUANGAN DALAM (Wajib pakai Token/Kartu Akses)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {
    
    // Fitur Kasir
    Route::apiResource('transaksi', TransactionsController::class);
    
    // Fitur Dashboard Admin
    Route::get('/stok-kritis', [ProductsController::class, 'cekStokKritis']);
    Route::get('/statistik', [TransactionsController::class, 'statistikHariIni']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
});
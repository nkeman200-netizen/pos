<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // @use HasFactory<\Database\Factories\ProductsFactory>
    protected $fillable = ['sku', 'name', 'stock', 'purchase_price', 'selling_price'];

    // Relasi: Satu produk bisa muncul di banyak detail penjualan
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}

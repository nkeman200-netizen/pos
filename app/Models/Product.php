<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable; // WAJIB dipanggil

class Product extends Model
{
    use HasFactory, Auditable; // Pasang trait-nya di sini

    // Proteksi ketat! Hanya kolom ini yang boleh diisi dari form
    protected $fillable = [
        'category_id',
        'unit_id',
        'sku',
        'name',
        'selling_price',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function batches()
    {
        return $this->hasMany(ProductBatch::class);
    }

    // Accessor untuk menghitung total stok dari batch yang belum expired
    public function getStockAttribute()
    {
        return $this->batches()
                    ->where('expired_date', '>=', now())
                    ->sum('stock');
    }
}
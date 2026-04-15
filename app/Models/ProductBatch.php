<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ProductBatch extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'product_id',
        'batch_number',
        'expired_date',
        'purchase_price',
        'stock'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
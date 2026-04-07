<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    
    use Auditable;
    protected $fillable = ['sale_id','product_id','quantity','unit_price','subtotal'];

    public function sale()
    {
        // Anak 'milik' Bapak
        return $this->belongsTo(Sale::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

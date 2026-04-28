<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use Auditable;
    
    protected $fillable = [
        'invoice_number', 
        'total_price', 
        'pembayaran', 
        'kembalian', 
        'user_id', 
        'customer_id',
        'status',          
        'void_reason',
        'payment_method',     
        'payment_reference'   
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(SaleItem::class);
    }
}
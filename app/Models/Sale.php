<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use Auditable;
    
    // TAMBAHAN: Masukkan 'status' dan 'void_reason' ke dalam fillable
    protected $fillable = [
        'invoice_number', 
        'total_price', 
        'pembayaran', 
        'kembalian', 
        'user_id', 
        'customer_id',
        'status',          // <-- WAJIB ADA
        'void_reason'      // <-- WAJIB ADA
    ];

    // Relasi ke Kasir
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Pelanggan
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Detail (Isi keranjang) satu sale punya banyak details
    public function details()
    {
        return $this->hasMany(SaleItem::class);
    }
}
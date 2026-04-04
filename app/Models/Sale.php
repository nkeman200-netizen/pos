<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'total_price', 'pembayaran', 'kembalian', 'user_id', 'customer_id'];

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

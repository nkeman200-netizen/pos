<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'order_date',
        'expected_date',
        'status', // 'pending', 'received', 'cancelled'
        'total_amount',
        'notes'
    ];

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relasi ke User (Apoteker yang bikin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Item Obat
    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseFactory> */
    use HasFactory;
    use Auditable;
    protected $fillable=[
        'supplier_id',
        'purchase_number',
        'purchase_date',
        'user_id',
        'total_cost',
    ];
    protected $with = ['supplier', 'user','details'];
    public function details(){
        return $this->hasMany(PurchaseItem::class);
    }
    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeldTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'cart_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
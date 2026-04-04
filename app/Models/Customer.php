<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = ['name', 'phone', 'address'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

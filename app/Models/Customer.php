<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use Auditable;
    protected $table = 'customers';
    protected $fillable = ['name', 'phone', 'address'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Unit extends Model
{
    use HasFactory, Auditable;

    protected $fillable = ['name', 'short_name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
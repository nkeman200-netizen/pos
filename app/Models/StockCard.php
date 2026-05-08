<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockCard extends Model
{
    protected $fillable = [
        'product_id',
        'product_batch_id',
        'user_id',
        'transaction_type',
        'reference_id',
        'movement_type',
        'quantity',
        'stock_balance',
        'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function batch()
    {
        return $this->belongsTo(ProductBatch::class,'product_batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function recordMutation(array $data)
    {
        $lastCard = self::where('product_id', $data['product_id'])
            ->where('product_batch_id', $data['product_batch_id'])
            ->latest('id')
            ->first();

        $currentBalance = $lastCard ? $lastCard->stock_balance : 0;
        
        $newBalance = $data['movement_type'] === 'IN' 
            ? $currentBalance + $data['qty'] 
            : $currentBalance - $data['qty'];

        return self::create([
            'product_id'       => $data['product_id'],
            'product_batch_id' => $data['product_batch_id'],
            'user_id'          => $data['user_id'],
            'transaction_type' => $data['transaction_type'],
            'reference_id'     => $data['reference_id'],
            'movement_type'    => $data['movement_type'],
            'quantity'         => $data['qty'],
            'stock_balance'    => $newBalance,
            'notes'            => $data['notes'] ?? null,
        ]);
    }
}

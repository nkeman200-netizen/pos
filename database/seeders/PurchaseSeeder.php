<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (!$admin) return;

        $purchase = Purchase::create([
            'supplier_id' => 1,
            'purchase_number' => 'PUR-' . date('Ymd') . '-001',
            'purchase_date' => now(),
            'total_cost' => 0,
            'user_id' => $admin->id,
        ]);

        $total = 0;
        $productsToBuy = Product::limit(3)->get();

        foreach ($productsToBuy as $product) {
            $qty = 100;
            // Ambil harga beli langsung dari properti produk yang bener
            $costPrice = $product->purchase_price; 
            $subtotal = $qty * $costPrice;

            $purchase->details()->create([
                'product_id' => $product->id,
                'quantity' => $qty,
                'cost_price' => $costPrice,
                'subtotal' => $subtotal
            ]);

            $product->increment('stock', $qty);
            $total += $subtotal;
        }

        $purchase->update(['total_cost' => $total]);
    }
}
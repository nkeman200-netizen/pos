<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['sku' => 'OBT-001', 'name' => 'Amoxicillin 500mg', 'stock' => 0, 'purchase_price' => 1000, 'selling_price' => 1500],
            ['sku' => 'OBT-002', 'name' => 'Amoxicillin 250mg', 'stock' => 0, 'purchase_price' => 500, 'selling_price' => 800],
            ['sku' => 'OBT-003', 'name' => 'Paracetamol 500mg Tablet', 'stock' => 0, 'purchase_price' => 300, 'selling_price' => 500],
            ['sku' => 'OBT-004', 'name' => 'Paracetamol Syrup 60ml', 'stock' => 0, 'purchase_price' => 12000, 'selling_price' => 15000],
            ['sku' => 'OBT-005', 'name' => 'Amlodipine 5mg', 'stock' => 0, 'purchase_price' => 1500, 'selling_price' => 2000],
            ['sku' => 'OBT-006', 'name' => 'Amlodipine 10mg', 'stock' => 0, 'purchase_price' => 2800, 'selling_price' => 3500],
            ['sku' => 'OBT-007', 'name' => 'Vitamin C 1000mg IPI', 'stock' => 0, 'purchase_price' => 4000, 'selling_price' => 5000],
            ['sku' => 'OBT-008', 'name' => 'Minyak Kayu Putih 60ml', 'stock' => 0, 'purchase_price' => 21000, 'selling_price' => 25000],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $supplierData = [
            'name'    => 'akwafkw',
            'address' => 'cilacap',
            'phone'   => '0888024242'
        ];
        Supplier::firstOrCreate(
            $supplierData
        );
        
        $categories = [
            ['name' => 'Obat Bebas'],
            ['name' => 'Obat Bebas Terbatas'],
            ['name' => 'Obat Keras'],
            ['name' => 'Vitamin & Suplemen'],
            ['name' => 'Alat Kesehatan'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate($cat);
        }

        $units = [
            ['name' => 'Strip', 'short_name' => 'str'],
            ['name' => 'Botol', 'short_name' => 'btl'],
            ['name' => 'Tablet', 'short_name' => 'tab'],
            ['name' => 'Kapsul', 'short_name' => 'kps'],
            ['name' => 'Tube', 'short_name' => 'tb'],
            ['name' => 'Box', 'short_name' => 'box'],
        ];
        foreach ($units as $unit) {
            Unit::firstOrCreate($unit);
        }

        $products = [
            [
                'category_id' => 1,
                'unit_id' => 1, 
                'sku' => '899123456001',
                'name' => 'Paracetamol 500mg',
                'selling_price' => 5000,
                'is_active' => true,
            ],
            [
                'category_id' => 3,
                'unit_id' => 1,  
                'sku' => '899123456002',
                'name' => 'Amoxicillin 500mg',
                'selling_price' => 12000,
                'is_active' => true,
            ],
            [
                'category_id' => 4,
                'unit_id' => 2,   
                'sku' => '899123456003',
                'name' => 'Imboost Force Sirup 60ml',
                'selling_price' => 45000,
                'is_active' => true,
            ]
        ];

        foreach ($products as $prod) {
            $product = Product::firstOrCreate(['sku' => $prod['sku']], $prod);

            ProductBatch::firstOrCreate([
                'product_id' => $product->id,
                'batch_number' => 'BATCH-A-' . rand(1000, 9999),
            ], [
                'expired_date' => now()->addMonths(6)->format('Y-m-d'),
                'purchase_price' => $product->selling_price * 0.7, 
                'stock' => 50,
            ]);

            ProductBatch::firstOrCreate([
                'product_id' => $product->id,
                'batch_number' => 'BATCH-B-' . rand(1000, 9999),
            ], [
                'expired_date' => now()->addMonths(12)->format('Y-m-d'),
                'purchase_price' => $product->selling_price * 0.75, 
                'stock' => 100,
            ]);
        }
    }
}
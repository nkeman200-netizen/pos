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
        // 1. Buat User Admin & Kasir (Jika belum ada)
        User::firstOrCreate(
            ['name' => 'Admin Utama'],
            ['password' => Hash::make('password'), 'role' => 'admin']
        );

        $supplierData = [
            'name'    => 'akwafkw',
            'address' => 'cilacap',
            'phone'   => '0888024242'
        ];
        Supplier::firstOrCreate(
            $supplierData
        );
        
        // 2. Master Data: Kategori (Golongan Obat)
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

        // 3. Master Data: Satuan Kemasan
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

        // 4. Dummy Data: Produk (Profil Obat)
        $products = [
            [
                'category_id' => 1, // Obat Bebas
                'unit_id' => 1,     // Strip
                'sku' => '899123456001',
                'name' => 'Paracetamol 500mg',
                'selling_price' => 5000,
                'is_active' => true,
            ],
            [
                'category_id' => 3, // Obat Keras
                'unit_id' => 1,     // Strip
                'sku' => '899123456002',
                'name' => 'Amoxicillin 500mg',
                'selling_price' => 12000,
                'is_active' => true,
            ],
            [
                'category_id' => 4, // Vitamin
                'unit_id' => 2,     // Botol
                'sku' => '899123456003',
                'name' => 'Imboost Force Sirup 60ml',
                'selling_price' => 45000,
                'is_active' => true,
            ]
        ];

        foreach ($products as $prod) {
            $product = Product::firstOrCreate(['sku' => $prod['sku']], $prod);

            // 5. Dummy Data: Product Batches (Stok & Expired Date)
            // KITA BUAT 2 BATCH UNTUK SETIAP OBAT UNTUK TESTING FEFO!
            
            // Batch Pertama: Expired dekat (6 bulan lagi)
            ProductBatch::firstOrCreate([
                'product_id' => $product->id,
                'batch_number' => 'BATCH-A-' . rand(1000, 9999),
            ], [
                'expired_date' => now()->addMonths(6)->format('Y-m-d'),
                'purchase_price' => $product->selling_price * 0.7, // Modal 70% dari harga jual
                'stock' => 50,
            ]);

            // Batch Kedua: Expired masih lama (12 bulan lagi)
            ProductBatch::firstOrCreate([
                'product_id' => $product->id,
                'batch_number' => 'BATCH-B-' . rand(1000, 9999),
            ], [
                'expired_date' => now()->addMonths(12)->format('Y-m-d'),
                'purchase_price' => $product->selling_price * 0.75, // Modal fluktuatif
                'stock' => 100,
            ]);
        }
    }
}
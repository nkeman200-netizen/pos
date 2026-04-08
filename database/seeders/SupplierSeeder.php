<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            ['name' => 'PT. Kimia Farma Trading', 'phone' => '021-123456', 'address' => 'Jakarta Timur'],
            ['name' => 'PT. Enseval Putera Megatrading', 'phone' => '021-654321', 'address' => 'Kawasan Industri Pulogadung'],
            ['name' => 'PT. Bina San Prima', 'phone' => '022-778899', 'address' => 'Bandung'],
            ['name' => 'DEXA Group', 'phone' => '021-990011', 'address' => 'Tangerang'],
        ];

        foreach ($suppliers as $s) {
            Supplier::create($s);
        }
    }
}
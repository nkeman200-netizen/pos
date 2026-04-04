<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin Utama',
            'password' => Hash::make('password123'), // Wajib di-hash agar aman
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Reguler',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);

        // 2. Membuat Data Produk UMKM
        Product::create([
            'nama_produk' => 'Kopi Susu Gula Aren',
            'kode_produk' => 'KOP-001',
            'harga_beli' => 10000, // Modal
            'harga_jual' => 18000, // Harga jual ke pelanggan
            'stok' => 50,
            'kategori' => 'Minuman'
        ]);

        Product::create([
            'nama_produk' => 'Cireng Bumbu Rujak',
            'kode_produk' => 'CRG-001',
            'harga_beli' => 8000,
            'harga_jual' => 15000,
            'stok' => 30,
            'kategori' => 'Makanan'
        ]);

        Product::create([
            'nama_produk' => 'Keripik Kentang Balado',
            'kode_produk' => 'KRP-001',
            'harga_beli' => 5000,
            'harga_jual' => 10000,
            'stok' => 4, // Sengaja diset rendah (di bawah 5) untuk menguji fitur Auto-Alert nanti
            'kategori' => 'Camilan'
        ]);
    }
}

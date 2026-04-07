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
    }
}

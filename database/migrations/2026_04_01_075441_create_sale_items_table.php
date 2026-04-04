<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            // Hubungkan ke tabel sales
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            
            // Hubungkan ke tabel produk
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            
            $table->integer('quantity');
            
            // Simpan harga saat transaksi (karena harga produk bisa berubah di masa depan)
            $table->bigInteger('unit_price'); 
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};

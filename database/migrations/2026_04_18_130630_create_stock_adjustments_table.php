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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_batch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Siapa admin yang opname
            $table->integer('system_qty');   // Stok di sistem sebelum diubah
            $table->integer('physical_qty'); // Stok fisik riil di rak
            $table->integer('difference');   // physical - system (bisa minus kalau hilang)
            $table->string('reason');        // Alasan: Rusak, Hilang, Salah Input, dll
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};

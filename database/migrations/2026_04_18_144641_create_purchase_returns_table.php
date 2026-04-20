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
        // 1. Tabel Header Retur
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique(); // Contoh: RTV/2026/04/001
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Admin/Apoteker yang memproses
            $table->date('return_date');
            $table->text('notes')->nullable();
            $table->decimal('total_return_value', 15, 2)->default(0); // Estimasi nilai barang yang balik
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};

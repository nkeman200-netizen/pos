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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // Nomor invoice unik, misal: INV-20231012-0001
            $table->string('invoice_number')->unique();
            
            // Relasi ke Pelanggan (nullable karena bisa saja beli tanpa member/guest)
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            // 3. Relasi ke Kasir (Wajib: harus tahu siapa yang jualin)
            // Pastikan tabel 'users' sudah ada sebelum menjalankan ini
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->bigInteger('total_price')->default(0);
            $table->bigInteger('pembayaran')->default(0);
            $table->bigInteger('kembalian')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};

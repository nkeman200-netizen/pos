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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // Nomor unik misal: PO-20260415-001
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // Apoteker yg bikin PO
            $table->date('order_date'); // Tanggal pesan
            $table->date('expected_date')->nullable(); // Tanggal estimasi barang datang
            $table->enum('status', ['pending', 'received', 'cancelled'])->default('pending'); // Status pesanan
            $table->integer('total_amount')->default(0); // Total estimasi harga
            $table->text('notes')->nullable(); // Catatan tambahan buat PBF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

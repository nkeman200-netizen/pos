<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pin', 6)->nullable()->after('password')->comment('PIN untuk Fast Login Kasir');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->enum('status', ['completed', 'void'])->default('completed')->after('kembalian');
            $table->string('void_reason')->nullable()->after('status');
        });

        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->bigInteger('starting_cash')->default(0)->comment('Modal uang kembalian awal');
            $table->bigInteger('expected_cash')->default(0)->comment('Total uang yang seharusnya ada di laci');
            $table->bigInteger('actual_cash')->default(0)->comment('Total uang fisik yang dihitung kasir saat tutup');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });

        Schema::create('held_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->json('cart_data')->comment('Menyimpan array cart Livewire');
            $table->bigInteger('total_price')->default(0);
            $table->string('reference_notes')->nullable()->comment('Misal: Bapak Baju Merah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('held_transactions');
        Schema::dropIfExists('cashier_shifts');
        
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['status', 'void_reason']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('pin');
        });
    }
};
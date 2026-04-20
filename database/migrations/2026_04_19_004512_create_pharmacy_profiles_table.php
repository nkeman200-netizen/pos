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
        Schema::create('pharmacy_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Apotek Default');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('apoteker_name')->nullable(); // Nama Apoteker Penanggung Jawab (APJ)
            $table->string('sipa_number')->nullable(); // Surat Izin Praktik Apoteker
            $table->string('logo')->nullable(); // Untuk path gambar logo nanti
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_profiles');
    }
};

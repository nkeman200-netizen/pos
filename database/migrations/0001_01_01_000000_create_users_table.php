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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique(); // Wajib ada untuk Auth Laravel
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            // INI INTINYA: Kita set 3 Role sesuai kesepakatan, default-nya kasir
            $table->enum('role', ['owner', 'admin', 'kasir'])->default('kasir');
            
            $table->rememberToken();
            $table->timestamps();
        });

        // ... (biarkan schema password_reset_tokens & sessions di bawahnya tetap ada)
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

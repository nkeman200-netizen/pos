<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pharmacy_profiles', function (Blueprint $table) {
            // Teks QRIS aslinya bisa lumayan panjang, jadi pakai text() lebih aman dari string()
            $table->text('qris_string')->nullable()->after('address'); 
        });
    }

    public function down()
    {
        Schema::table('pharmacy_profiles', function (Blueprint $table) {
            $table->dropColumn('qris_string');
        });
    }
};

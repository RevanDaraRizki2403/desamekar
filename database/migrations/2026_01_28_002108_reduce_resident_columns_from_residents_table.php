<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menghapus kolom.
     */
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            // Menghapus kolom yang sudah tidak diperlukan agar tabel lebih ringkas
            $table->dropColumn([
                'birth_place', 
                'birth_date', 
                'address', 
                'religion', 
                'marital_status', 
                'occupation', 
                'phone'
            ]);
        });
    }

    /**
     * Mengembalikan kolom jika migrasi di-rollback.
     */
    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('occupation')->nullable();
            $table->string('phone')->nullable();
        });
    }
};
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
    Schema::table('pengajuan_dokumen', function (Blueprint $table) {
        // Menambahkan kolom baru setelah kolom tertentu agar rapi
        $table->string('user_id')->after('resident_id')->nullable();

    });
}

public function down(): void
{
    Schema::table('nama_tabel', function (Blueprint $table) {
        $table->dropColumn('nama_kolom_baru');
    });
}
};

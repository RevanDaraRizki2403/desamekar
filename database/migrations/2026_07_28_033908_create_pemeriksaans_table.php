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
    Schema::create('pemeriksaans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('ternak_id')->constrained('ternaks')->onDelete('cascade');
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        $table->date('tanggal_pemeriksaan');
        $table->decimal('bobot', 6, 2)->nullable();
        $table->string('status_kesehatan');
        $table->string('status_vaksin')->default('Belum'); // Kolom status vaksin
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaans');
    }
};

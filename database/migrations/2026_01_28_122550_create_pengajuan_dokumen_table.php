<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengajuan_dokumen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resident_id');
            $table->string('jenis_dokumen');
            $table->text('keperluan');
            $table->string('file_pendukung')->nullable();
            $table->enum('status', ['diajukan','diproses','selesai'])->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_dokumens');
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ternaks', function (Blueprint $table) {
            $table->id();
            
            // A. IDENTITAS TERNAK
            $table->string('kode_ternak')->unique();
            $table->string('no_ear_tag')->nullable();
            $table->date('tanggal_lahir')->nullable(); // Diganti dari tanggal_masuk
            $table->string('jenis'); // Sapi / Domba
            $table->string('ras')->nullable(); // Ras / Varian
            $table->string('jenis_kelamin'); // Jantan / Betina
            $table->string('lokasi_kandang')->nullable();

            // B. KONDISI FISIK
            $table->decimal('berat', 8, 2); // Bobot badan (kg)
            $table->decimal('tinggi_badan', 8, 2)->nullable(); // Tinggi badan (cm)
            $table->decimal('bcs', 3, 1)->nullable(); // Body Condition Score (1-5)

            // C. KESEHATAN & PEMELIHARAAN
            $table->integer('skor_kesehatan')->nullable(); // Skor Kesehatan (0-100)
            $table->integer('kualitas_pakan')->nullable(); // Kualitas Pakan (1-5)
            $table->string('status_vaksinasi')->nullable(); // Lengkap / Sebagian / Belum
            $table->string('riwayat_penyakit')->nullable(); // Tidak Ada / Ada
            $table->text('catatan_petugas')->nullable();

            // D. HASIL PREDIKSI AI (Otomatis dari Sistem)
            $table->boolean('kelayakan')->default(0); // 0 = Belum Layak, 1 = Layak
            $table->decimal('harga_jual', 15, 2)->nullable(); // Estimasi dari AI

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ternaks');
    }
};
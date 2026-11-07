<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ternak extends Model
{
    use HasFactory;

    protected $table = 'ternaks';

    protected $fillable = [
        'kode_ternak',
        'no_ear_tag',
        'tanggal_lahir', // Sudah diganti dari tanggal_masuk
        'jenis',
        'ras',
        'jenis_kelamin',
        'lokasi_kandang',
        'berat',
        'tinggi_badan',
        'bcs',
        'skor_kesehatan',
        'kualitas_pakan',
        'status_vaksinasi',
        'riwayat_penyakit',
        'catatan_petugas',
        'kelayakan',
        'harga_jual',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'ternak_id');
    }
}
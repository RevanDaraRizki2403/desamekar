<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasKandang extends Model
{
    use HasFactory;

    protected $table = 'petugas_kandangs';

    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'penugasan_kandang',
        'status',
    ];

    /**
     * Konversi tipe data otomatis (Casting)
     */
    protected $casts = [
        'status' => 'integer',
    ];
}
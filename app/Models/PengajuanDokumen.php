<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanDokumen extends Model
{
    // Pastikan nama tabel sesuai dengan di phpMyAdmin
    protected $table = 'pengajuan_dokumen'; 

    protected $guarded = [];

    // Relasi ke tabel Resident
    public function resident()
    {
        // Parameter kedua adalah foreign key di tabel pengajuan_dokumen
        return $this->belongsTo(Resident::class, 'resident_id','id');
    }
}
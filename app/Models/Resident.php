<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $guarded = [];

    // Tambahkan ini agar {{ $resident->nama }} bisa berfungsi
    public function getNamaAttribute()
    {
        return $this->name; 
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Aduan (Satu penduduk bisa memiliki banyak aduan)
     */
    public function aduan()
    {
        return $this->hasMany(Aduan::class, 'resident_id');
    }

    public function pengajuanDokumen()
{
    return $this->hasMany(PengajuanDokumen::class, 'resident_id');
}

}
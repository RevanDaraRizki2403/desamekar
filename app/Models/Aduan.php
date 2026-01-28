<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    protected $guarded = [];

    // TAMBAHKAN KODINGAN INI
    public function resident()
    {
        // Menyatakan bahwa Aduan dimiliki oleh satu Resident
        // Pastikan 'resident_id' adalah nama kolom foreign key di tabel aduans Anda
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function getStatusLabelAttribute() 
    {
        return match ($this->status) {
            'new'        => 'Baru',
            'processing' => 'Sedang Diproses',
            'completed'  => 'Selesai',
            default      => 'Tidak Diketahui',
        };
    }


}
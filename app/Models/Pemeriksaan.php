<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemeriksaan extends Model
{
    protected $fillable = [
        'ternak_id',
        'user_id',
        'tanggal_pemeriksaan',
        'bobot',
        'status_kesehatan',
        'status_vaksin', // <-- Sudah diganti
    ];

    public function ternak()
    {
        return $this->belongsTo(Ternak::class, 'ternak_id');
    }

    public function pemeriksa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
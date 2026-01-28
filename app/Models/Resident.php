<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $table = 'residents';

    // Gunakan fillable hanya untuk kolom yang masih ada di database
    protected $fillable = ['nik', 'name', 'gender', 'status'];

    /**
     * Relasi ke User (Satu penduduk memiliki satu akun user)
     */
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
}
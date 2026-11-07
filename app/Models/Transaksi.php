<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'ternak_id',
        'tanggal_transaksi',
        'nama_pembeli',
        'harga_final',
        'status',
        'catatan',
    ];

    public function ternak()
    {
        return $this->belongsTo(Ternak::class, 'ternak_id');
    }
}
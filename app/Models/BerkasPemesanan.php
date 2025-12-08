<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BerkasPemesanan extends Model
{
    use HasUuids;

    protected $table = 'berkas_pemesanans';

    protected $fillable = [
        'pemesanan_id',
        'jenis',         // enum: ktm, bukti_bayar
        'lokasi_berkas',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}

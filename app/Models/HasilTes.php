<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class HasilTes extends Model
{
    use HasUuids;

    protected $table = 'hasil_tes';

    protected $fillable = [
        'pemesanan_id',
        'status_hasil',  // enum: menunggu, negatif, positif, normal, abnormal
        'catatan',
        'berkas_hasil',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
        return $this->belongsTo(Pemesanan::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pembayaran extends Model
{
    use HasUuids;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pemesanan_id',
        'gateway',
        'order_id',
        'tipe',
        'jumlah',
        'status',
        'payload'
    ];

    protected $casts = ['payload' => 'array'];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}

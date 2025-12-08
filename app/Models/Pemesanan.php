<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemesanan extends Model
{
    use HasUuids;

    protected $table = 'pemesanans';

    protected $fillable = [
        'kode',
        'pengguna_id',
        'jenis_tes_id',
        'slot_waktu_id',
        'status',
        'dibayar_pada',
        'kedaluwarsa_pada',
        'total_bayar',
        'metode_bayar',
        'nomor_antrian',
        'waktu_tes',
        'qr_checkin',
    ];

    protected $casts = [
        'dibayar_pada'     => 'datetime',
        'kedaluwarsa_pada' => 'datetime',
        'waktu_tes'        => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
    public function jenisTes()
    {
        return $this->belongsTo(JenisTes::class, 'jenis_tes_id');
    }
    public function slotWaktu()
    {
        return $this->belongsTo(SlotWaktu::class, 'slot_waktu_id');
    }
    public function berkasPemesanan()
    {
        return $this->hasMany(BerkasPemesanan::class, 'pemesanan_id');
    }
    public function hasilTes()
    {
        return $this->hasOne(HasilTes::class, 'pemesanan_id');
    }
}

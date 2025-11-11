<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Pemesanan extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    // HAPUS baris $table kalau ada, atau pastikan:
    protected $table = 'pemesanans';

    protected $fillable = ['kode', 'pengguna_id', 'jenis_tes_id', 'slot_waktu_id', 'status', 'dibayar_pada'];
    protected $casts = ['dibayar_pada' => 'datetime'];

    public function pengguna()
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
    public function berkas()
    {
        return $this->hasMany(BerkasPemesanan::class, 'pemesanan_id');
    }
    public function hasil()
    {
        return $this->hasOne(HasilTes::class, 'pemesanan_id');
    }
}

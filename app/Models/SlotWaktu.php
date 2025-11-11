<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SlotWaktu extends Model
{
    use HasUuids;

    protected $table = 'slot_waktu';

    protected $fillable = [
        'jenis_tes_id',
        'tanggal',
        'mulai',
        'selesai',
        'kuota',
        'terpesan'
    ];

    protected $casts = [
        // pastikan kolom 'tanggal' bertipe date/datetime di DB
        'tanggal' => 'datetime',
        // kalau mulai/selesai bertipe TIME di DB, biarkan string (default)
        'kuota'    => 'integer',
        'terpesan' => 'integer',
    ];

    // default supaya aman kalau null
    protected $attributes = [
        'kuota' => 0,
        'terpesan' => 0,
    ];

    public function jenisTes()
    {
        return $this->belongsTo(JenisTes::class);
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function masihAdaKuota(): bool
    {
        $kuota    = (int) ($this->kuota ?? 0);
        $terpesan = (int) ($this->terpesan ?? 0);
        // jika kuota = 0 artinya tidak dibatasi → anggap masih ada kuota
        if ($kuota === 0) return true;
        return $terpesan < $kuota;
    }

    public function sisaKuota(): int
    {
        $kuota    = (int) ($this->kuota ?? 0);
        $terpesan = (int) ($this->terpesan ?? 0);
        if ($kuota === 0) return PHP_INT_MAX; // tak terbatas
        return max($kuota - $terpesan, 0);
    }
}

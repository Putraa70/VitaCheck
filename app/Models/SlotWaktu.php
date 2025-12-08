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
        'terpesan',
        'dihold',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
        $kuota     = (int) ($this->kuota ?? 0);
        $terpesan  = (int) ($this->terpesan ?? 0);
        $dihold    = (int) ($this->dihold ?? 0);

        return $kuota > ($terpesan + $dihold);
    }
}

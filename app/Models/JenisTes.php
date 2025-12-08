<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class JenisTes extends Model
{
    use HasUuids;

    protected $table = 'jenis_tes';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'biaya',
        'aktif',
    ];

    public static function generateKode(string $nama): string
    {
        // Buat slug uppercase: "Tes Narkoba" → TES_NARKOBA
        $base = Str::upper(Str::slug($nama, '_'));

        if ($base === '') {
            $base = Str::upper(Str::random(6));
        }

        $kode = $base;
        $i = 1;

        // Pastikan unik
        while (self::where('kode', $kode)->exists()) {
            $kode = $base . '_' . $i;
            $i++;
        }

        return $kode;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Fakultas extends Model
{
    use HasUuids;

    protected $table = 'fakultas';
    protected $fillable = ['nama', 'kode']; // kalau kolom kode tidak ada, tidak masalah karena valiasi tidak memasukkan

    public $incrementing = false;
    protected $keyType = 'string';
}

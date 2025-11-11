<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JenisTes extends Model
{
    use HasUuids;
    protected $table = 'jenis_tes';
    protected $fillable = ['kode', 'nama', 'deskripsi', 'biaya', 'aktif'];

    public function slotWaktu()
    {
        return $this->hasMany(SlotWaktu::class);
    }
}

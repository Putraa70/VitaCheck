<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Fakultas extends Model
{
    use HasUuids;
    protected $table = 'fakultas';
    protected $fillable = ['kode', 'nama'];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class);
    }
}

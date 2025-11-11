<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProgramStudi extends Model
{
    use HasUuids;
    protected $table = 'program_studi';
    protected $fillable = ['fakultas_id', 'kode', 'nama'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProfilMahasiswa extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $table = 'profil_mahasiswas';

    protected $fillable = ['pengguna_id', 'nim', 'program_studi_id', 'no_hp'];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }
}

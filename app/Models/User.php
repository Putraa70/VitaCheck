<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name', 'email', 'password', 'peran'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // ⬅️ PENTING
    ];

    /** RELASI **/
    public function profilMahasiswa()
    {
        return $this->hasOne(ProfilMahasiswa::class, 'pengguna_id');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'pengguna_id');
    }

    /** CHECK ADMIN **/
    public function isAdmin(): bool
    {
        return $this->peran === 'admin';
    }
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }
}

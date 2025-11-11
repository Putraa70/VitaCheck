<?php

namespace App\Policies;

use App\Models\{User, Pemesanan};

class PemesananPolicy
{
    public function lihat(User $user, Pemesanan $p)
    { // optional: dipakai kalau mau authorize per-aksi
        return $p->pengguna_id === $user->id || $user->peran === 'admin';
    }
}

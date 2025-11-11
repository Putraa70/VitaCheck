<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerbaruiProfilRequest;
use App\Models\{ProfilMahasiswa, ProgramStudi};

class ProfilMahasiswaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    // Tampilkan profil mahasiswa
    public function index()
    {
        $profil = auth()->user()->profilMahasiswa;
        return view('profil_mahasiswa.index', compact('profil'));
    }

    // Form edit profil
    public function edit()
    {
        $profil = auth()->user()->profilMahasiswa;
        $prodi  = ProgramStudi::with('fakultas')->get();
        return view('profil_mahasiswa.edit', compact('profil', 'prodi'));
    }

    // Simpan perubahan
    public function update(PerbaruiProfilRequest $request)
    {
        $user   = auth()->user();
        $profil = $user->profilMahasiswa;

        if (!$profil) {
            $profil = new ProfilMahasiswa();
            $profil->pengguna_id = $user->id;
        }

        $profil->nim              = $request->nim;
        $profil->program_studi_id = $request->program_studi_id;
        $profil->no_hp            = $request->no_hp;
        $profil->save();

        return redirect()->route('profil.index')->with('sukses', 'Profil berhasil diperbarui.');
    }
}

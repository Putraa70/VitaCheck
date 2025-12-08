<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\ProfilMahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil mahasiswa.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'profil' => $request->user()->profilMahasiswa,
            'program_studi' => ProgramStudi::with('fakultas')->get(),
        ]);
    }

    /**
     * Update data user + profil mahasiswa.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // =============================
        // UPDATE TABEL USERS
        // =============================
        $user->fill($request->only('name', 'email'));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();


        // =============================
        // UPDATE / BUAT PROFIL MAHASISWA
        // =============================
        $profil = $user->profilMahasiswa;

        if (!$profil) {
            // Jika belum ada → buat baru
            $profil = new ProfilMahasiswa();
            $profil->pengguna_id = $user->id;
        }

        $profil->nim = $request->input('nim');
        $profil->no_hp = $request->input('no_hp');
        $profil->program_studi_id = $request->input('program_studi_id');

        $profil->save();


        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

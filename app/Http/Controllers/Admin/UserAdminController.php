<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $q     = trim((string) $request->query('q'));
        $peran = $request->query('peran');

        $daftarPeran = ['admin', 'user'];

        $users = User::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($peran, fn($x) => $x->where('peran', $peran))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users'       => $users,
            'q'           => $q,
            'peran'       => $peran,
            'daftarPeran' => $daftarPeran,
        ]);
    }

    public function create()
    {
        $daftarPeran = ['admin', 'user'];

        return view('admin.users.create', [
            'daftarPeran' => $daftarPeran,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'peran'                 => ['required', 'in:admin,user'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            // kalau mau simpan jurusan/fakultas, bisa tambahkan:
            // 'fakultas_id'         => ['nullable', 'exists:fakultas,id'],
            // 'program_studi_id'    => ['nullable', 'exists:program_studi,id'],
        ]);

        User::create($data); // password auto hashed via casts di model

        return redirect()
            ->route('admin.users.index')
            ->with('sukses', 'User baru berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $daftarPeran = ['admin', 'user'];

        return view('admin.users.edit', [
            'user'        => $user,
            'daftarPeran' => $daftarPeran,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'peran'                 => ['required', 'in:admin,user'],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            // 'fakultas_id'         => ['nullable', 'exists:fakultas,id'],
            // 'program_studi_id'    => ['nullable', 'exists:program_studi,id'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('sukses', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('sukses', 'User berhasil dihapus.');
    }

    public function show(User $user)
    {
        // load relasi yang kita butuhkan
        $user->load(['profilMahasiswa', 'programStudi', 'fakultas']);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }
}

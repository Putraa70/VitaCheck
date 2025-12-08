<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class ProgramStudiAdminController extends Controller
{
    /**
     * Tampilkan daftar program studi.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $items = ProgramStudi::query()
            ->with('fakultas') // hindari N+1
            ->when($q, function ($query) use ($q) {
                // PostgreSQL => ILIKE; kalau MySQL, ganti jadi 'like'
                return $query->where(function ($x) use ($q) {
                    $x->where('nama', 'ILIKE', "%{$q}%")
                        ->orWhere('kode', 'ILIKE', "%{$q}%")
                        ->orWhereHas('fakultas', fn($y) => $y->where('nama', 'ILIKE', "%{$q}%"));
                });
            })
            ->orderBy('nama')
            ->paginate(12)
            ->withQueryString();

        return view('admin.program_studi.index', compact('items'));
    }

    /**
     * Form tambah program studi.
     */
    public function create()
    {
        $fakultas = Fakultas::orderBy('nama')->get(['id', 'nama']);

        return view('admin.program_studi.create', compact('fakultas'));
    }

    /**
     * Simpan program studi baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'        => ['required', 'string', 'max:20', 'unique:program_studi,kode'],
            'nama'        => ['required', 'string', 'max:150'],
            'fakultas_id' => ['required', 'uuid', 'exists:fakultas,id'],
        ]);

        ProgramStudi::create($data);

        return redirect()
            ->route('admin.program-studi.index')
            ->with('ok', 'Program studi berhasil ditambahkan.');
    }

    /**
     * Detail program studi.
     */
    public function show(ProgramStudi $programStudi)
    {
        $item = $programStudi->load('fakultas');

        return view('admin.program_studi.show', compact('item'));
    }

    /**
     * Form edit program studi.
     */
    public function edit(ProgramStudi $programStudi)
    {
        $item = $programStudi->load('fakultas');
        $fakultas = Fakultas::orderBy('nama')->get(['id', 'nama']);

        return view('admin.program_studi.edit', compact('item', 'fakultas'));
    }

    /**
     * Update program studi.
     */
    public function update(Request $request, ProgramStudi $programStudi)
    {
        $data = $request->validate([
            'kode'        => ['required', 'string', 'max:20', 'unique:program_studi,kode,' . $programStudi->id . ',id'],
            'nama'        => ['required', 'string', 'max:150'],
            'fakultas_id' => ['required', 'uuid', 'exists:fakultas,id'],
        ]);

        $programStudi->update($data);

        return redirect()
            ->route('admin.program-studi.index')
            ->with('ok', 'Program studi berhasil diperbarui.');
    }

    /**
     * Hapus program studi.
     */
    public function destroy(ProgramStudi $programStudi)
    {
        $programStudi->delete();

        return redirect()
            ->route('admin.program-studi.index')
            ->with('ok', 'Program studi berhasil dihapus.');
    }
}

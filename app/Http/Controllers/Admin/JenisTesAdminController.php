<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisTes;
use Illuminate\Http\Request;

class JenisTesAdminController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->query('q');

        $items = JenisTes::query()
            ->when($q, function ($x) use ($q) {
                $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'LIKE';
                $x->where('nama', $like, "%{$q}%")
                    ->orWhere('kode', $like, "%{$q}%");
            })
            ->orderBy('nama')
            ->paginate(15);

        if ($r->query()) {
            $items->appends($r->query());
        }

        return view('admin.jenis_tes.index', compact('items'));
    }

    public function create()
    {
        return view('admin.jenis_tes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => ['nullable', 'string', 'max:50', 'unique:jenis_tes,kode'],
            'nama'      => ['required', 'string', 'max:150', 'unique:jenis_tes,nama'],
            'deskripsi' => ['nullable', 'string'],
            'biaya'     => ['nullable', 'integer', 'min:0'],
            'aktif'     => ['nullable', 'boolean'],
        ]);

        if (empty($data['kode'])) {
            $data['kode'] = JenisTes::generateKode($data['nama']);
        }

        JenisTes::create($data);

        return redirect()
            ->route('admin.jenis-tes.index')
            ->with('sukses', 'Jenis tes berhasil ditambahkan.');
    }

    public function show(JenisTes $jenisTes)
    {
        $item = $jenisTes;

        return view('admin.jenis_tes.show', compact('item'));
    }

    public function edit(JenisTes $jenisTes)
    {
        $item = $jenisTes;

        return view('admin.jenis_tes.edit', compact('item'));
    }

    public function update(Request $request, JenisTes $jenisTes)
    {
        $data = $request->validate([
            'kode'      => ['nullable', 'string', 'max:50', 'unique:jenis_tes,kode,' . $jenisTes->id],
            'nama'      => ['required', 'string', 'max:150', 'unique:jenis_tes,nama,' . $jenisTes->id],
            'deskripsi' => ['nullable', 'string'],
            'biaya'     => ['nullable', 'integer', 'min:0'],
            'aktif'     => ['nullable', 'boolean'],
        ]);

        if (empty($data['kode'])) {
            $data['kode'] = JenisTes::generateKode($data['nama']);
        }

        $jenisTes->update($data);

        return redirect()
            ->route('admin.jenis-tes.index')
            ->with('sukses', 'Jenis tes berhasil diperbarui.');
    }

    public function destroy(JenisTes $jenisTes)
    {
        $jenisTes->delete();

        return redirect()
            ->route('admin.jenis-tes.index')
            ->with('sukses', 'Jenis tes dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;



class FakultasAdminController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->query('q');

        $items = Fakultas::query()
            ->when(
                $q,
                fn($x) =>
                // PostgreSQL → ILIKE; kalau MySQL pakai 'like'
                $x->where('nama', 'ILIKE', "%{$q}%")
            )
            ->latest()
            ->paginate(15);

        // setara dengan ->withQueryString(), tapi ramah linter
        if ($r->query()) {
            $items->appends($r->query());
        }

        return view('admin.fakultas.index', compact('items'));
    }


    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function store(Request $r)
    {
        $rules = [
            'nama' => ['required', 'string', 'max:100'],
        ];

        // jika kolom 'kode' memang ada di tabel, validasi juga
        if (schema_has_column('fakultas', 'kode')) {
            $rules['kode'] = ['required', 'unique:fakultas,kode'];
        }

        $data = $r->validate($rules);

        Fakultas::create($data);

        return redirect()
            ->route('admin.fakultas.index')
            ->with('sukses', 'Fakultas ditambah.');
    }

    public function show(Fakultas $fakultas)
    {
        $item = $fakultas;
        return view('admin.fakultas.show', compact('item'));
    }

    public function edit(Fakultas $fakultas)
    {
        $item = $fakultas;
        return view('admin.fakultas.edit', compact('item'));
    }

    public function update(Request $r, FakulTAS $fakultas) // case-insensitive, tapi rapikan
    {
        $rules = [
            'nama' => ['required', 'string', 'max:100'],
        ];

        if (schema_has_column('fakultas', 'kode')) {
            $rules['kode'] = ['required', 'unique:fakultas,kode,' . $fakultas->id . ',id'];
        }

        $data = $r->validate($rules);

        $fakultas->update($data);

        return redirect()
            ->route('admin.fakultas.index')
            ->with('sukses', 'Fakultas diperbarui.');
    }

    public function destroy(Fakultas $fakultas)
    {
        $fakultas->delete();

        return redirect()
            ->route('admin.fakultas.index')
            ->with('sukses', 'Fakultas dihapus.');
    }
}

/**
 * Helper sederhana agar aman dipakai di controller.
 * (Bisa kamu taruh di app/helpers.php kalau ada).
 */
if (! function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}

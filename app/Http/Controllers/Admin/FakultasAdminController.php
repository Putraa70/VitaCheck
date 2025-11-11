<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasAdminController extends Controller
{
    public function index()
    {
        $data = Fakultas::latest()->paginate(15);
        return view('admin.fakultas.index', compact('data'));
    }

    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:fakultas,kode',
            'nama' => 'required|string|max:100',
        ]);
        Fakultas::create($request->all());
        return redirect()->route('admin.fakultas.index')->with('sukses', 'Fakultas ditambah.');
    }

    public function show(Fakultas $fakulta)
    {
        return view('admin.fakultas.show', compact('fakulta'));
    }

    public function edit(Fakultas $fakulta)
    {
        return view('admin.fakultas.edit', compact('fakulta'));
    }

    public function update(Request $request, Fakultas $fakulta)
    {
        $request->validate([
            'kode' => 'required|unique:fakultas,kode,' . $fakulta->id,
            'nama' => 'required|string|max:100',
        ]);
        $fakulta->update($request->all());
        return redirect()->route('admin.fakultas.index')->with('sukses', 'Fakultas diperbarui.');
    }

    public function destroy(Fakultas $fakulta)
    {
        $fakulta->delete();
        return back()->with('sukses', 'Fakultas dihapus.');
    }
}

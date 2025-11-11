<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ProgramStudi, Fakultas};
use Illuminate\Http\Request;

class ProgramStudiAdminController extends Controller
{
    public function index()
    {
        $data = ProgramStudi::with('fakultas')->latest()->paginate(15);
        return view('admin.program_studi.index', compact('data'));
    }

    public function create()
    {
        $fakultas = Fakultas::pluck('nama', 'id');
        return view('admin.program_studi.create', compact('fakultas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'kode' => 'required|unique:program_studi,kode',
            'nama' => 'required|string|max:100',
        ]);
        ProgramStudi::create($request->all());
        return redirect()->route('admin.program-studi.index')->with('sukses', 'Program studi ditambah.');
    }

    public function show(ProgramStudi $program_studi)
    {
        return view('admin.program_studi.show', compact('program_studi'));
    }

    public function edit(ProgramStudi $program_studi)
    {
        $fakultas = Fakultas::pluck('nama', 'id');
        return view('admin.program_studi.edit', compact('program_studi', 'fakultas'));
    }

    public function update(Request $request, ProgramStudi $program_studi)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'kode' => 'required|unique:program_studi,kode,' . $program_studi->id,
            'nama' => 'required|string|max:100',
        ]);
        $program_studi->update($request->all());
        return redirect()->route('admin.program-studi.index')->with('sukses', 'Program studi diperbarui.');
    }

    public function destroy(ProgramStudi $program_studi)
    {
        $program_studi->delete();
        return back()->with('sukses', 'Program studi dihapus.');
    }
}

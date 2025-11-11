<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanJenisTesRequest;
use App\Models\JenisTes;

class JenisTesAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:admin']);
    }

    public function index()
    {
        $data = JenisTes::latest()->paginate(20);
        return view('admin.jenis_tes.index', compact('data'));
    }
    public function create()
    {
        return view('admin.jenis_tes.create');
    }
    public function store(SimpanJenisTesRequest $r)
    {
        JenisTes::create($r->validated());
        return redirect()->route('admin.jenis-tes.index')->with('sukses', 'Jenis tes dibuat.');
    }
    public function show(JenisTes $jenis_te)
    {
        return view('admin.jenis_tes.show', ['jenisTes' => $jenis_te]);
    }
    public function edit(JenisTes $jenis_te)
    {
        return view('admin.jenis_tes.edit', ['jenisTes' => $jenis_te]);
    }
    public function update(SimpanJenisTesRequest $r, JenisTes $jenis_te)
    {
        $jenis_te->update($r->validated());
        return redirect()->route('admin.jenis-tes.index')->with('sukses', 'Jenis tes diperbarui.');
    }
    public function destroy(JenisTes $jenis_te)
    {
        $jenis_te->delete();
        return back()->with('sukses', 'Jenis tes dihapus.');
    }
}

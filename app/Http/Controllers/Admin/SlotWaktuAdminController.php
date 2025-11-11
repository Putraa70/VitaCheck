<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SimpanSlotWaktuRequest;
use App\Models\{SlotWaktu, JenisTes};

class SlotWaktuAdminController extends Controller
{
    public function index()
    {
        $data = SlotWaktu::with('jenisTes')->latest()->paginate(15);
        return view('admin.slot_waktu.index', compact('data'));
    }

    public function create()
    {
        $jenisTes = JenisTes::pluck('nama', 'id');
        return view('admin.slot_waktu.create', compact('jenisTes'));
    }

    public function store(SimpanSlotWaktuRequest $request)
    {
        SlotWaktu::create($request->validated());
        return redirect()->route('admin.slot-waktu.index')->with('sukses', 'Slot waktu ditambahkan.');
    }

    public function show(SlotWaktu $slot_waktu)
    {
        return view('admin.slot_waktu.show', compact('slot_waktu'));
    }

    public function edit(SlotWaktu $slot_waktu)
    {
        $jenisTes = JenisTes::pluck('nama', 'id');
        return view('admin.slot_waktu.edit', compact('slot_waktu', 'jenisTes'));
    }

    public function update(SimpanSlotWaktuRequest $request, SlotWaktu $slot_waktu)
    {
        $slot_waktu->update($request->validated());
        return redirect()->route('admin.slot-waktu.index')->with('sukses', 'Slot waktu diperbarui.');
    }

    public function destroy(SlotWaktu $slot_waktu)
    {
        $slot_waktu->delete();
        return back()->with('sukses', 'Slot waktu dihapus.');
    }
}

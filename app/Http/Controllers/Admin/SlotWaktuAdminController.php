<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SlotWaktu;
use App\Models\JenisTes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SlotWaktuAdminController extends Controller
{
    public function index(Request $r)
    {
        $q = $r->query('q');
        $items = SlotWaktu::query()
            ->when($this->hasCol('jenis_tes_id'), fn($x) => $x->with('jenisTes:id,nama'))
            ->when($q, function ($x) use ($q) {
                $like = config('database.default') === 'pgsql' ? 'ILIKE' : 'like';
                if ($this->hasCol('jenis_tes_id')) {
                    $x->whereHas('jenisTes', fn($y) => $y->where('nama', $like, "%{$q}%"));
                }
                $x->orWhereDate('tanggal', $q);
            })
            ->orderBy('tanggal', 'asc')
            ->paginate(12);

        if ($r->query()) $items->appends($r->query());

        return view('admin.slot_waktu.index', compact('items'));
    }

    public function create()
    {
        $jenis = $this->hasCol('jenis_tes_id')
            ? JenisTes::orderBy('nama')->get(['id', 'nama'])
            : collect(); // kosong jika kolom tidak ada

        return view('admin.slot_waktu.create', compact('jenis'));
    }

    public function store(Request $r)
    {
        $rules = [
            'tanggal'   => ['required', 'date'],
            'kuota'     => ['required', 'integer', 'min:1'],
            'terpesan'  => ['nullable', 'integer', 'min:0'],
        ];
        if ($this->hasCol('mulai'))   $rules['mulai']   = ['required', 'date_format:H:i'];
        if ($this->hasCol('selesai')) $rules['selesai'] = ['required', 'date_format:H:i', 'after:mulai'];
        if ($this->hasCol('jenis_tes_id')) $rules['jenis_tes_id'] = ['required', 'uuid', 'exists:jenis_tes,id'];

        $data = $r->validate($rules);
        $data['terpesan'] = $data['terpesan'] ?? 0;

        SlotWaktu::create($data);

        return redirect()->route('admin.slot-waktu.index')->with('sukses', 'Slot waktu ditambahkan.');
    }

    public function show(SlotWaktu $slotWaktu)
    {
        if ($this->hasCol('jenis_tes_id')) $slotWaktu->load('jenisTes:id,nama');
        $item = $slotWaktu;
        return view('admin.slot_waktu.show', compact('item'));
    }

    public function edit(SlotWaktu $slotWaktu)
    {
        if ($this->hasCol('jenis_tes_id')) $slotWaktu->load('jenisTes:id,nama');
        $item = $slotWaktu;
        $jenis = $this->hasCol('jenis_tes_id')
            ? JenisTes::orderBy('nama')->get(['id', 'nama'])
            : collect();
        return view('admin.slot_waktu.edit', compact('item', 'jenis'));
    }

    public function update(Request $r, SlotWaktu $slotWaktu)
    {
        $rules = [
            'tanggal'   => ['required', 'date'],
            'kuota'     => ['required', 'integer', 'min:1'],
            'terpesan'  => ['nullable', 'integer', 'min:0'],
        ];
        if ($this->hasCol('mulai'))   $rules['mulai']   = ['required', 'date_format:H:i'];
        if ($this->hasCol('selesai')) $rules['selesai'] = ['required', 'date_format:H:i', 'after:mulai'];
        if ($this->hasCol('jenis_tes_id')) $rules['jenis_tes_id'] = ['required', 'uuid', 'exists:jenis_tes,id'];

        $data = $r->validate($rules);
        $data['terpesan'] = $data['terpesan'] ?? 0;

        $slotWaktu->update($data);

        return redirect()->route('admin.slot-waktu.index')->with('sukses', 'Slot waktu diperbarui.');
    }

    public function destroy(SlotWaktu $slotWaktu)
    {
        $slotWaktu->delete();
        return redirect()->route('admin.slot-waktu.index')->with('sukses', 'Slot waktu dihapus.');
    }

    private function hasCol(string $col): bool
    {
        static $cache = null;
        if ($cache === null) {
            $cache = Schema::hasTable('slot_waktu')
                ? Schema::getColumnListing('slot_waktu')
                : [];
        }
        return in_array($col, $cache, true);
    }
}

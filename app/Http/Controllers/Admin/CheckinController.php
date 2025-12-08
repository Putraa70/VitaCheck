<?php
// app/Http/Controllers/Admin/CheckinController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function scan(Request $r)
    {
        // dari scanner kirim payload JSON atau hanya kode pemesanan
        $kode = $r->input('kode');
        $p = Pemesanan::where('kode', $kode)->firstOrFail();

        if (!in_array($p->status, ['terkonfirmasi', 'terbayar'])) {
            return response()->json(['ok' => false, 'msg' => 'Belum terkonfirmasi/terbayar'], 422);
        }

        $p->update(['status' => 'check_in']);
        return response()->json(['ok' => true, 'msg' => 'Check-in berhasil', 'antrian' => $p->nomor_antrian]);
    }
}

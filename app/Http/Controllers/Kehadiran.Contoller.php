<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KehadiranContoller extends Controller
{
    public function index()
    {

        return view('kehadiran');
    }
    public function cek (request $request)
    {
        $kode = $request->input('kode_pemesanan');


}
}
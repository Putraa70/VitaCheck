<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramStudi;
use App\Models\Fakultas;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID FMIPA
        $fmipa = Fakultas::where('kode', 'FMIPA')->first();

        if (!$fmipa) {
            throw new \Exception("Fakultas FMIPA belum ada. Jalankan FakultasSeeder dulu.");
        }

        $data = [
            ['kode' => 'IK', 'nama' => 'Ilmu Komputer', 'fakultas_id' => $fmipa->id],
            ['kode' => 'MTK', 'nama' => 'Matematika',    'fakultas_id' => $fmipa->id],
            ['kode' => 'FIS', 'nama' => 'Fisika',        'fakultas_id' => $fmipa->id],
            ['kode' => 'KIM', 'nama' => 'Kimia',         'fakultas_id' => $fmipa->id],
            ['kode' => 'BIO', 'nama' => 'Biologi',       'fakultas_id' => $fmipa->id],
        ];

        foreach ($data as $item) {
            ProgramStudi::create($item);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'FMIPA', 'nama' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam'],
            ['kode' => 'FT',    'nama' => 'Fakultas Teknik'],
            ['kode' => 'FEB',   'nama' => 'Fakultas Ekonomi dan Bisnis'],
            ['kode' => 'FH',    'nama' => 'Fakultas Hukum'],
            ['kode' => 'FP',    'nama' => 'Fakultas Pertanian'],
            ['kode' => 'FKIP',  'nama' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['kode' => 'FISIP', 'nama' => 'Fakultas Ilmu Sosial dan Ilmu Politik'],
            ['kode' => 'FK',    'nama' => 'Fakultas Kedokteran'],
        ];

        foreach ($data as $item) {
            Fakultas::create($item);
        }
    }
}

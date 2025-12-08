<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            FakultasSeeder::class,
            ProgramStudiSeeder::class,
            // Tambahkan Faculty/Program/JenisTes/SlotWaktu seeder kalau perlu
        ]);
    }
}

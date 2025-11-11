<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@unila.ac.id'],
            [
                'name' => 'Admin Klinik',
                'password' => Hash::make('password123'),
                'peran' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}

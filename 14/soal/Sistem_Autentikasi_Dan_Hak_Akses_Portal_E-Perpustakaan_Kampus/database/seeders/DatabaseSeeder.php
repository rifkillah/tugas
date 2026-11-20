<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Pustakawan
        User::create([
            'name' => 'Rifkillah muliya al-faris (Pustakawan)',
            'email' => 'pustakawan@kampus.ac.id',
            'password' => Hash::make('password'),
            'role' => 'pustakawan',
        ]);

        // Akun Anggota (Mahasiswa)
        User::create([
            'name' => 'Rifkillah muliya al-faris (Anggota)',
            'email' => 'anggota@kampus.ac.id',
            'password' => Hash::make('password'),
            'role' => 'anggota',
        ]);
    }
}

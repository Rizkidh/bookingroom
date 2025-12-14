<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // PENTING: Gunakan firstOrCreate untuk menghindari duplikasi
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // Kunci yang akan dicek
            [
                'name' => 'Admin User',
                'role' => 'admin', // Role yang baru ditambahkan
                'password' => Hash::make('password'), // Ganti dengan password yang kuat
            ]
        );

        // Tambahkan user pegawai
        User::firstOrCreate(
            ['email' => 'pegawai@example.com'],
            [
                'name' => 'Pegawai User',
                'role' => 'pegawai',
                'password' => Hash::make('password'),
            ]
        );

        // ... tambahkan user lain jika perlu
    }
}
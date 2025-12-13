<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, // Untuk tabel users
            // Tambahkan Seeder Inventaris Anda di sini:
            InventoryItemSeeder::class, // Untuk tabel inventory_items
        ]);
    }
}
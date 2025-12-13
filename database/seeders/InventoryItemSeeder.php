<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryItemSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('inventory_items')->insert([
            // Laptop
            ['name' => 'Laptop Dell', 'total_stock' => 50, 'available_stock' => 45, 'damaged_stock' => 5, 'created_at' => now(), 'updated_at' => now()],
            // Monitor
            ['name' => 'Monitor LG', 'total_stock' => 70, 'available_stock' => 60, 'damaged_stock' => 10, 'created_at' => now(), 'updated_at' => now()],
            // Keyboard
            ['name' => 'Keyboard Logitech', 'total_stock' => 30, 'available_stock' => 30, 'damaged_stock' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
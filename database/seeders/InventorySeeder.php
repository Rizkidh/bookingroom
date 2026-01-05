<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 5 jenis barang jika belum ada
        $items = InventoryItem::factory()->count(5)->create();

        // Buat 50 unit untuk mengetes pagination
        foreach ($items as $item) {
            InventoryUnit::factory()->count(10)->create([
                'inventory_item_id' => $item->id,
            ]);
        }
    }
}

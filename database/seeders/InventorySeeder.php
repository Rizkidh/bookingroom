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
        // Buat 12 jenis barang jika belum ada (untuk test pagination > 10)
        $items = InventoryItem::factory()->count(12)->create();

        // Buat 15 unit per item untuk mengetes pagination unit
        foreach ($items as $item) {
            InventoryUnit::factory()->count(15)->create([
                'inventory_item_id' => $item->id,
            ]);
        }
    }
}

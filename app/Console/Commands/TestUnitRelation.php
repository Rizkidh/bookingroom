<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InventoryUnit;

class TestUnitRelation extends Command
{
    protected $signature = 'test:unit-relation {unit_id}';
    protected $description = 'Quick test if unit->item relationship works';

    public function handle()
    {
        $unitId = $this->argument('unit_id');
        
        $unit = InventoryUnit::find($unitId);

        if (!$unit) {
            $this->error("Unit not found: $unitId");
            return 1;
        }

        $this->info("Unit ID: {$unit->id}");
        $this->info("inventory_item_id: {$unit->inventory_item_id}");

        $item = $unit->item;

        if ($item) {
            $this->info("✅ Relationship WORKS!");
            $this->info("Item Name: {$item->name}");
            return 0;
        } else {
            $this->error("❌ Relationship returns NULL");
            return 1;
        }
    }
}

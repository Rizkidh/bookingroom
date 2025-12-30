<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InventoryUnit;
use App\Models\InventoryItem;

class DebugUnitScan extends Command
{
    protected $signature = 'debug:unit-scan {unit_id}';
    protected $description = 'Debug why unit scan might be failing';

    public function handle()
    {
        $unitId = $this->argument('unit_id');
        
        $this->info("=== Debugging Unit Scan for ID: $unitId ===\n");

        // 1. Check if unit exists
        $this->info("1. Checking if unit exists...");
        $unit = InventoryUnit::find($unitId);
        
        if (!$unit) {
            $this->error("   ❌ Unit NOT found in database");
            return;
        }
        
        $this->line("   ✅ Unit found");
        $this->line("      ID: {$unit->id}");
        $this->line("      inventory_item_id: {$unit->inventory_item_id}");
        $this->line("      serial_number: {$unit->serial_number}");
        $this->line("      condition_status: {$unit->condition_status}");
        $this->line("      qr_code: {$unit->qr_code}");

        // 2. Check if inventory_item_id is null
        $this->info("\n2. Checking inventory_item_id...");
        if (is_null($unit->inventory_item_id)) {
            $this->error("   ❌ inventory_item_id is NULL!");
            $this->line("   This is the problem - unit has no parent inventory");
            return;
        }
        $this->line("   ✅ inventory_item_id is set: {$unit->inventory_item_id}");

        // 3. Check if inventory item exists
        $this->info("\n3. Checking if parent inventory exists...");
        $inventory = InventoryItem::find($unit->inventory_item_id);
        
        if (!$inventory) {
            $this->error("   ❌ Parent inventory NOT found");
            $this->line("   Inventory with ID {$unit->inventory_item_id} does not exist");
            return;
        }
        
        $this->line("   ✅ Parent inventory found");
        $this->line("      ID: {$inventory->id}");
        $this->line("      Name: {$inventory->name}");

        // 4. Check via relationship
        $this->info("\n4. Checking via relationship...");
        $relatedInventory = $unit->item;
        
        if (!$relatedInventory) {
            $this->error("   ❌ Relationship returns NULL!");
            $this->line("   Data exists but relationship failed to load");
            $this->warn("   Possible causes:");
            $this->line("   - Foreign key mismatch");
            $this->line("   - Constraint violation");
            $this->line("   - Model relationship not defined correctly");
            return;
        }
        
        $this->line("   ✅ Relationship works");
        $this->line("      Name: {$relatedInventory->name}");

        // 5. Test with eager loading
        $this->info("\n5. Testing with eager loading...");
        $unitWithInventory = InventoryUnit::with('item')->find($unitId);
        
        if (!$unitWithInventory->item) {
            $this->error("   ❌ Eager loading failed!");
            return;
        }
        
        $this->line("   ✅ Eager loading works");
        $this->line("      Name: {$unitWithInventory->item->name}");

        $this->info("\n=== Summary ===");
        $this->line("✅ Unit scan should work");
        $this->line("   Unit: $unitId");
        $this->line("   Inventory: {$inventory->name} (ID: {$inventory->id})");
    }
}

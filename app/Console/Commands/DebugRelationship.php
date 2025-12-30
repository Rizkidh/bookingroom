<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InventoryUnit;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

class DebugRelationship extends Command
{
    protected $signature = 'debug:relationship {unit_id}';
    protected $description = 'Deep debug of InventoryUnit->item relationship';

    public function handle()
    {
        $unitId = $this->argument('unit_id');
        
        $this->info("=== Deep Relationship Debug for Unit: $unitId ===\n");

        // 1. Raw query test
        $this->info("1. Testing Raw SQL Query...");
        $rawResult = DB::table('inventory_units')
            ->where('id', $unitId)
            ->first();
        
        if (!$rawResult) {
            $this->error("   ❌ Unit not found in database");
            return;
        }
        
        $this->line("   ✅ Raw query successful");
        $this->line("      inventory_item_id from DB: {$rawResult->inventory_item_id}");

        // 2. Check if foreign key exists in inventory_items
        $this->info("\n2. Checking if referenced inventory exists...");
        $refExists = DB::table('inventory_items')
            ->where('id', $rawResult->inventory_item_id)
            ->exists();
        
        if ($refExists) {
            $this->line("   ✅ Referenced inventory exists");
        } else {
            $this->error("   ❌ Referenced inventory does NOT exist");
            $this->line("   This is a data integrity issue!");
            return;
        }

        // 3. Test Model::find() without relationships
        $this->info("\n3. Testing Model::find()...");
        $unit = InventoryUnit::find($unitId);
        
        if (!$unit) {
            $this->error("   ❌ Unit not found via Model");
            return;
        }
        
        $this->line("   ✅ Unit found via Model");
        $this->line("      inventory_item_id attribute: {$unit->inventory_item_id}");
        $this->line("      Type: " . gettype($unit->inventory_item_id));

        // 4. Check if method 'item' exists
        $this->info("\n4. Checking if item() method exists...");
        if (method_exists($unit, 'item')) {
            $this->line("   ✅ item() method exists");
        } else {
            $this->error("   ❌ item() method does NOT exist!");
            return;
        }

        // 5. Test calling the method directly
        $this->info("\n5. Testing item() method call...");
        try {
            $relation = $unit->item();
            $this->line("   ✅ item() method callable");
            $this->line("      Returns: " . get_class($relation));
        } catch (\Exception $e) {
            $this->error("   ❌ Error calling item()");
            $this->line("      " . $e->getMessage());
            return;
        }

        // 6. Test getting the actual model
        $this->info("\n6. Testing item property access...");
        try {
            $inventory = $unit->item;
            
            if ($inventory === null) {
                $this->error("   ❌ item property is NULL");
                
                // Try to debug why
                $this->warn("\n   Debugging why it's null:");
                
                // Check if the relationship query would return something
                $queryResult = $unit->item()->get();
                $this->line("      Query result count: " . $queryResult->count());
                
                if ($queryResult->count() === 0) {
                    $this->error("      ❌ Query returns no results");
                    $this->line("      This means the foreign key reference is broken");
                }
            } else {
                $this->line("   ✅ item property loaded");
                $this->line("      Name: {$inventory->name}");
                $this->line("      ID: {$inventory->id}");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Error accessing item");
            $this->line("      " . $e->getMessage());
            return;
        }

        // 7. Test with explicit foreign key
        $this->info("\n7. Testing manual relationship load...");
        try {
            $manualLoad = InventoryItem::find($unit->inventory_item_id);
            
            if ($manualLoad) {
                $this->line("   ✅ Manual load successful");
                $this->line("      Name: {$manualLoad->name}");
            } else {
                $this->error("   ❌ Manual load failed");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Error on manual load");
            $this->line("      " . $e->getMessage());
        }

        // 8. Check relationship query
        $this->info("\n8. Checking relationship query...");
        $relationQuery = $unit->item();
        $this->line("   Query: " . $relationQuery->toSql());
        $this->line("   Bindings: " . json_encode($relationQuery->getBindings()));

        $this->info("\n=== Diagnosis Complete ===");
    }
}

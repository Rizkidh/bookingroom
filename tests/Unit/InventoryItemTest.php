<?php

namespace Tests\Unit;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_inventory_item_can_be_created()
    {
        $item = InventoryItem::factory()->create([
            'name' => 'Test Item',
            'total_stock' => 10,
            'available_stock' => 8,
            'damaged_stock' => 2,
        ]);

        $this->assertDatabaseHas('inventory_items', [
            'id' => $item->id,
            'name' => 'Test Item',
            'total_stock' => 10,
            'available_stock' => 8,
            'damaged_stock' => 2,
        ]);
    }

    public function test_inventory_item_has_units_relationship()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $this->assertTrue($item->units->contains($unit));
        $this->assertEquals(1, $item->units->count());
    }

    public function test_inventory_item_stock_values_are_integers()
    {
        $item = InventoryItem::factory()->create([
            'total_stock' => 15,
            'available_stock' => 10,
            'damaged_stock' => 5,
        ]);

        $this->assertIsInt($item->total_stock);
        $this->assertIsInt($item->available_stock);
        $this->assertIsInt($item->damaged_stock);
    }

    public function test_inventory_item_can_be_updated()
    {
        $item = InventoryItem::factory()->create(['name' => 'Old Name']);

        $item->update(['name' => 'New Name']);

        $this->assertEquals('New Name', $item->fresh()->name);
    }

    public function test_inventory_item_can_be_deleted()
    {
        $item = InventoryItem::factory()->create();

        $item->delete();

        $this->assertSoftDeleted('inventory_items', ['id' => $item->id]);
    }
}


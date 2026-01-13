<?php

namespace Tests\Unit;

use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InventoryUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure qrcodes directory exists
        $qrcodeDir = public_path('qrcodes');
        if (!File::exists($qrcodeDir)) {
            File::makeDirectory($qrcodeDir, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Clean up QR code files
        $qrcodeDir = public_path('qrcodes');
        if (File::exists($qrcodeDir)) {
            File::cleanDirectory($qrcodeDir);
        }

        parent::tearDown();
    }

    public function test_inventory_unit_has_string_id()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $this->assertIsString($unit->id);
        $this->assertNotEmpty($unit->id);
    }

    public function test_inventory_unit_id_is_auto_generated()
    {
        $item = InventoryItem::factory()->create();

        $unit1 = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);
        $unit2 = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $this->assertNotEquals($unit1->id, $unit2->id);
        $this->assertIsString($unit1->id);
        $this->assertIsString($unit2->id);
    }

    public function test_inventory_unit_belongs_to_inventory_item()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $this->assertEquals($item->id, $unit->item->id);
        $this->assertInstanceOf(InventoryItem::class, $unit->item);
    }

    public function test_inventory_unit_has_valid_condition_status()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create([
            'inventory_item_id' => $item->id,
            'condition_status' => 'available',
        ]);

        $this->assertContains($unit->condition_status, [
            'available',
            'in_use',
            'maintenance',
            'damaged',
            'lost',
        ]);
    }

    public function test_inventory_unit_can_be_updated()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create([
            'inventory_item_id' => $item->id,
            'current_holder' => 'Old Holder',
        ]);

        $unit->update(['current_holder' => 'New Holder']);

        $this->assertEquals('New Holder', $unit->fresh()->current_holder);
    }

    public function test_inventory_unit_can_be_deleted()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $unit->delete();

        $this->assertSoftDeleted('inventory_units', ['id' => $unit->id]);
    }

    public function test_inventory_unit_serial_number_can_be_null()
    {
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create([
            'inventory_item_id' => $item->id,
            'serial_number' => null,
        ]);

        $this->assertNull($unit->serial_number);
    }
}


<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

    public function test_user_can_view_scan_page()
    {
        $user = User::factory()->create(['role' => 'pegawai']);

        $response = $this->actingAs($user)->get('/scan');

        $response->assertStatus(200);
        $response->assertViewIs('inventory_units.scan');
    }

    public function test_user_can_process_scan()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $response = $this->actingAs($user)
            ->post('/scan/process', [
                'barcode' => $unit->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_scan_fails_with_invalid_barcode()
    {
        $user = User::factory()->create(['role' => 'pegawai']);

        $response = $this->actingAs($user)
            ->post('/scan/process', [
                'barcode' => '99999',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_user_can_view_create_unit_form()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)
            ->get("/inventories/{$item->id}/units/create");

        $response->assertStatus(200);
        $response->assertViewIs('inventory_units.create');
    }

    public function test_pegawai_can_create_inventory_unit()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)
            ->post("/inventories/{$item->id}/units", [
                'serial_number' => 'SN123456',
                'condition_status' => 'available',
                'current_holder' => 'John Doe',
                'note' => 'Test unit',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inventory_units', [
            'inventory_item_id' => $item->id,
            'serial_number' => 'SN123456',
            'condition_status' => 'available',
            'current_holder' => 'John Doe',
        ]);
    }

    public function test_user_can_create_unit_with_photo()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        Storage::fake('public');
        $photo = UploadedFile::fake()->image('unit.jpg', 100, 100);

        $response = $this->actingAs($user)
            ->post("/inventories/{$item->id}/units", [
                'condition_status' => 'available',
                'current_holder' => 'John Doe',
                'photo' => $photo,
            ]);

        $response->assertRedirect();

        $unit = InventoryUnit::where('inventory_item_id', $item->id)->first();
        $this->assertNotNull($unit->photo);
    }

    public function test_user_cannot_create_unit_without_required_fields()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)
            ->post("/inventories/{$item->id}/units", []);

        $response->assertSessionHasErrors(['condition_status', 'current_holder']);
    }

    public function test_user_can_view_unit_detail()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $response = $this->actingAs($user)
            ->get("/inventories/{$item->id}/units/{$unit->id}");

        $response->assertStatus(200);
        $response->assertViewIs('inventory_units.show');
    }

    public function test_user_can_view_edit_unit_form()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $response = $this->actingAs($user)
            ->get("/inventories/{$item->id}/units/{$unit->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('inventory_units.edit');
    }

    public function test_pegawai_can_update_inventory_unit()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create([
            'inventory_item_id' => $item->id,
            'current_holder' => 'Old Holder',
        ]);

        $response = $this->actingAs($user)
            ->put("/inventories/{$item->id}/units/{$unit->id}", [
                'serial_number' => $unit->serial_number,
                'condition_status' => 'in_use',
                'current_holder' => 'New Holder',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inventory_units', [
            'id' => $unit->id,
            'current_holder' => 'New Holder',
            'condition_status' => 'in_use',
        ]);
    }

    public function test_admin_can_delete_inventory_unit()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $response = $this->actingAs($user)
            ->delete("/inventories/{$item->id}/units/{$unit->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('inventory_units', ['id' => $unit->id]);
    }

    public function test_pegawai_cannot_delete_inventory_unit()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $response = $this->actingAs($user)
            ->delete("/inventories/{$item->id}/units/{$unit->id}");

        $response->assertForbidden();
    }

    public function test_unauthorized_user_cannot_view_unit()
    {
        $user = User::factory()->create(['role' => 'other']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $response = $this->actingAs($user)
            ->get("/inventories/{$item->id}/units/{$unit->id}");

        $response->assertForbidden();
    }
}


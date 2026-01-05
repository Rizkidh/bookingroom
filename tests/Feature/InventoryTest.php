<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_inventory_index()
    {
        $user = User::factory()->create(['role' => 'admin']);
        InventoryItem::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/inventories');

        $response->assertStatus(200);
        $response->assertViewIs('inventories.index');
    }

    public function test_user_can_view_create_form()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/inventories/create');

        $response->assertStatus(200);
        $response->assertViewIs('inventories.create');
    }

    public function test_pegawai_can_create_inventory_item()
    {
        $user = User::factory()->create(['role' => 'pegawai']);

        $response = $this->actingAs($user)
            ->post('/inventories', [
                'name' => 'Proyektor',
            ]);

        $response->assertRedirect('/inventories');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inventory_items', [
            'name' => 'Proyektor',
            'total_stock' => 0,
            'available_stock' => 0,
            'damaged_stock' => 0,
        ]);
    }

    public function test_user_cannot_create_inventory_item_without_name()
    {
        $user = User::factory()->create(['role' => 'pegawai']);

        $response = $this->actingAs($user)
            ->post('/inventories', []);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_cannot_create_duplicate_inventory_item_name()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        InventoryItem::factory()->create(['name' => 'Existing Item']);

        $response = $this->actingAs($user)
            ->post('/inventories', [
                'name' => 'Existing Item',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_view_inventory_item()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)->get("/inventories/{$item->id}");

        $response->assertStatus(200);
        $response->assertViewIs('inventories.show');
    }

    public function test_user_can_view_edit_form()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)->get("/inventories/{$item->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('inventories.edit');
    }

    public function test_pegawai_can_update_inventory_item()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)
            ->put("/inventories/{$item->id}", [
                'name' => 'New Name',
            ]);

        $response->assertRedirect('/inventories');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('inventory_items', [
            'id' => $item->id,
            'name' => 'New Name',
        ]);
    }

    public function test_admin_can_delete_inventory_item()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)
            ->delete("/inventories/{$item->id}");

        $response->assertRedirect('/inventories');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('inventory_items', ['id' => $item->id]);
    }

    public function test_pegawai_cannot_delete_inventory_item()
    {
        $user = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $response = $this->actingAs($user)
            ->delete("/inventories/{$item->id}");

        $response->assertForbidden();
    }

    public function test_unauthorized_user_cannot_create_inventory_item()
    {
        $user = User::factory()->create(['role' => 'other']);

        $response = $this->actingAs($user)
            ->post('/inventories', [
                'name' => 'Test Item',
            ]);

        $response->assertForbidden();
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_inventory_item()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/inventories', [
                'name' => 'Proyektor',
                'total_stock' => 10,
                'available_stock' => 8,
                'damaged_stock' => 2,
            ]);

        $response->assertRedirect('/inventories');

        $this->assertDatabaseHas('inventory_items', [
            'name' => 'Proyektor',
            'total_stock' => 10,
            'available_stock' => 8,
            'damaged_stock' => 2,
        ]);
    }
}

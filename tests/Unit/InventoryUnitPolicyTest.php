<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\InventoryItem;
use App\Models\InventoryUnit;
use App\Policies\InventoryUnitPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryUnitPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected InventoryUnitPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new InventoryUnitPolicy();
    }

    public function test_admin_can_do_anything()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $this->assertTrue($this->policy->before($admin, 'any'));
        $this->assertTrue($this->policy->view($admin, $unit));
        $this->assertTrue($this->policy->create($admin));
        $this->assertTrue($this->policy->update($admin, $unit));
        $this->assertTrue($this->policy->delete($admin, $unit));
    }

    public function test_pegawai_can_view()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $result = $this->policy->view($pegawai, $unit);

        $this->assertTrue($result->allowed());
    }

    public function test_pegawai_can_create()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);

        $result = $this->policy->create($pegawai);

        $this->assertTrue($result->allowed());
    }

    public function test_pegawai_can_update()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $result = $this->policy->update($pegawai, $unit);

        $this->assertTrue($result->allowed());
    }

    public function test_pegawai_cannot_delete()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $result = $this->policy->delete($pegawai, $unit);

        $this->assertFalse($result->allowed());
        $this->assertStringContainsString('tidak memiliki izin', $result->message());
    }

    public function test_other_role_cannot_view()
    {
        $user = User::factory()->create(['role' => 'other']);
        $item = InventoryItem::factory()->create();
        $unit = InventoryUnit::factory()->create(['inventory_item_id' => $item->id]);

        $result = $this->policy->view($user, $unit);

        $this->assertFalse($result->allowed());
    }
}


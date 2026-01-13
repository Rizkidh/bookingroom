<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\InventoryItem;
use App\Policies\InventoryItemPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryItemPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected InventoryItemPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new InventoryItemPolicy();
    }

    public function test_admin_can_do_anything()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();

        $this->assertTrue($this->policy->before($admin, 'any'));
        $this->assertTrue($this->policy->viewAny($admin));
        $this->assertTrue($this->policy->view($admin, $item));
        $this->assertTrue($this->policy->create($admin));
        $this->assertTrue($this->policy->update($admin, $item));
        $this->assertTrue($this->policy->delete($admin, $item)->allowed());
    }

    public function test_pegawai_can_view_any()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $this->assertTrue($this->policy->viewAny($pegawai));
        $this->assertTrue($this->policy->view($pegawai, $item));
    }

    public function test_pegawai_can_create()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);

        $this->assertTrue($this->policy->create($pegawai));
    }

    public function test_pegawai_can_update()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $this->assertTrue($this->policy->update($pegawai, $item));
    }

    public function test_pegawai_cannot_delete()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);
        $item = InventoryItem::factory()->create();

        $result = $this->policy->delete($pegawai, $item);

        $this->assertFalse($result->allowed());
        $this->assertStringContainsString('tidak memiliki izin', $result->message());
    }

    public function test_other_role_cannot_create()
    {
        $user = User::factory()->create(['role' => 'other']);

        $this->assertFalse($this->policy->create($user));
    }
}


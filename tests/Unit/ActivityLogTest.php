<?php

namespace Tests\Unit;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_log_can_be_created()
    {
        $user = User::factory()->create();
        $log = ActivityLog::factory()->create([
            'user_id' => $user->id,
            'action' => 'CREATE',
            'model_type' => InventoryItem::class,
            'model_id' => '1',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'id' => $log->id,
            'action' => 'CREATE',
            'user_id' => $user->id,
        ]);
    }

    public function test_activity_log_belongs_to_user()
    {
        $user = User::factory()->create();
        $log = ActivityLog::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $log->user->id);
        $this->assertInstanceOf(User::class, $log->user);
    }

    public function test_activity_log_scope_by_model()
    {
        $item = InventoryItem::factory()->create();
        ActivityLog::factory()->create([
            'model_type' => InventoryItem::class,
            'model_id' => (string) $item->id,
        ]);
        ActivityLog::factory()->create([
            'model_type' => 'App\Models\OtherModel',
            'model_id' => '1',
        ]);

        $logs = ActivityLog::byModel(InventoryItem::class)->get();

        $this->assertGreaterThanOrEqual(1, $logs->count());
        $this->assertEquals(InventoryItem::class, $logs->first()->model_type);
    }

    public function test_activity_log_scope_by_action()
    {
        ActivityLog::factory()->create(['action' => 'CREATE']);
        ActivityLog::factory()->create(['action' => 'UPDATE']);
        ActivityLog::factory()->create(['action' => 'DELETE']);

        $logs = ActivityLog::byAction('CREATE')->get();

        $this->assertGreaterThanOrEqual(1, $logs->count());
        $this->assertEquals('CREATE', $logs->first()->action);
    }

    public function test_activity_log_scope_by_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        ActivityLog::factory()->create(['user_id' => $user1->id]);
        ActivityLog::factory()->create(['user_id' => $user2->id]);

        $logs = ActivityLog::byUser($user1->id)->get();

        $this->assertGreaterThanOrEqual(1, $logs->count());
        $this->assertEquals($user1->id, $logs->first()->user_id);
    }

    public function test_activity_log_scope_search()
    {
        ActivityLog::factory()->create([
            'description' => 'Created inventory item',
            'note' => 'Test note',
            'user_name' => 'John Doe',
        ]);
        ActivityLog::factory()->create([
            'description' => 'Updated something else',
            'user_name' => 'Jane Smith',
        ]);

        $logs = ActivityLog::search('John')->get();

        $this->assertGreaterThanOrEqual(1, $logs->count());
        $this->assertStringContainsString('John', $logs->first()->user_name);
    }

    public function test_activity_log_get_change_description()
    {
        $log = ActivityLog::factory()->create([
            'old_values' => ['name' => 'Old Name', 'stock' => 10],
            'new_values' => ['name' => 'New Name', 'stock' => 15],
        ]);

        $description = $log->getChangeDescription();

        $this->assertNotNull($description);
        $this->assertStringContainsString('name', $description);
        $this->assertStringContainsString('stock', $description);
    }

    public function test_activity_log_old_and_new_values_are_json()
    {
        $log = ActivityLog::factory()->create([
            'old_values' => ['key' => 'value'],
            'new_values' => ['key' => 'new_value'],
        ]);

        $this->assertIsArray($log->old_values);
        $this->assertIsArray($log->new_values);
    }
}


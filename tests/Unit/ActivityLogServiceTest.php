<?php

namespace Tests\Unit;

use App\Models\InventoryItem;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivityLogServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_create_creates_activity_log()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $item = InventoryItem::factory()->create();

        ActivityLogService::logCreate($item, 'Test note');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'CREATE',
            'model_type' => InventoryItem::class,
            'model_id' => (string) $item->id,
            'user_id' => $user->id,
            'note' => 'Test note',
        ]);
    }

    public function test_log_update_creates_activity_log()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $item = InventoryItem::factory()->create(['name' => 'Old Name']);
        $oldValues = $item->getAttributes();

        $item->update(['name' => 'New Name']);

        ActivityLogService::logUpdate($item, $oldValues, 'Updated name');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'UPDATE',
            'model_type' => InventoryItem::class,
            'model_id' => (string) $item->id,
            'user_id' => $user->id,
            'note' => 'Updated name',
        ]);
    }

    public function test_log_delete_creates_activity_log()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $item = InventoryItem::factory()->create();
        $attributes = $item->getAttributes();

        ActivityLogService::logDelete($item, 'Deleted item');

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'DELETE',
            'model_type' => InventoryItem::class,
            'model_id' => (string) $item->id,
            'user_id' => $user->id,
            'note' => 'Deleted item',
        ]);
    }

    public function test_log_create_without_note()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $item = InventoryItem::factory()->create();

        ActivityLogService::logCreate($item);

        $log = ActivityLog::where('model_id', (string) $item->id)
            ->whereNotNull('user_id') // Ensure we get the log from manual call if needed, or just specific one
            ->latest()
            ->first();
        $this->assertNull($log->note);
    }

    public function test_log_note_is_sanitized()
    {
        $user = User::factory()->create(['role' => 'admin']);
        Auth::login($user);

        $item = InventoryItem::factory()->create();

        ActivityLogService::logCreate($item, '<script>alert("xss")</script>Test note');

        $log = ActivityLog::where('model_id', (string) $item->id)
            ->where('note', 'like', '%Test note%')
            ->first();
        $this->assertStringNotContainsString('<script>', $log->note);
        $this->assertStringContainsString('Test note', $log->note);
    }

    public function test_get_model_logs_returns_paginated_results()
    {
        $item = InventoryItem::factory()->create();

        ActivityLog::factory()->count(5)->create([
            'model_type' => InventoryItem::class,
            'model_id' => (string) $item->id,
        ]);

        $logs = ActivityLogService::getModelLogs(InventoryItem::class, (string) $item->id, 10);

        $this->assertGreaterThanOrEqual(5, $logs->items());
    }

    public function test_get_user_logs_returns_paginated_results()
    {
        $user = User::factory()->create();

        ActivityLog::factory()->count(3)->create(['user_id' => $user->id]);

        $logs = ActivityLogService::getUserLogs($user->id, 10);

        $this->assertCount(3, $logs->items());
    }
}


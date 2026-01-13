<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ActivityLog;
use App\Models\InventoryItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_activity_logs_index()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::factory()->count(5)->create();

        $response = $this->actingAs($admin)->get('/activity-logs');

        $response->assertStatus(200);
        $response->assertViewIs('activity_logs.index');
    }

    public function test_admin_can_filter_logs_by_model_type()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::factory()->create([
            'model_type' => InventoryItem::class,
            'model_id' => '1',
        ]);
        ActivityLog::factory()->create([
            'model_type' => 'App\Models\OtherModel',
            'model_id' => '1',
        ]);

        $response = $this->actingAs($admin)
            ->get('/activity-logs?model_type=' . urlencode(InventoryItem::class));

        $response->assertStatus(200);
        $response->assertViewHas('logs');
    }

    public function test_admin_can_filter_logs_by_action()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::factory()->create(['action' => 'CREATE']);
        ActivityLog::factory()->create(['action' => 'UPDATE']);

        $response = $this->actingAs($admin)
            ->get('/activity-logs?action=CREATE');

        $response->assertStatus(200);
    }

    public function test_admin_can_search_logs()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::factory()->create([
            'description' => 'Created inventory item',
            'user_name' => 'John Doe',
        ]);

        $response = $this->actingAs($admin)
            ->get('/activity-logs?search=John');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_activity_log_detail()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $log = ActivityLog::factory()->create();

        $response = $this->actingAs($admin)
            ->get("/activity-logs/{$log->id}");

        $response->assertStatus(200);
    }

    public function test_admin_can_view_model_logs()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = InventoryItem::factory()->create();
        ActivityLog::factory()->count(3)->create([
            'model_type' => InventoryItem::class,
            'model_id' => (string) $item->id,
        ]);

        $response = $this->actingAs($admin)
            ->get("/activity-logs/model/" . urlencode(InventoryItem::class) . "/{$item->id}");

        $response->assertStatus(200);
        $response->assertViewIs('activity_logs.model_logs');
    }

    public function test_admin_can_export_activity_logs()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get('/activity-logs/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=utf-8');
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_can_export_filtered_logs()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ActivityLog::factory()->create([
            'action' => 'CREATE',
            'model_type' => InventoryItem::class,
        ]);
        ActivityLog::factory()->create(['action' => 'UPDATE']);

        $response = $this->actingAs($admin)
            ->get('/activity-logs/export?action=CREATE&model_type=' . urlencode(InventoryItem::class));

        $response->assertStatus(200);
    }

    public function test_pegawai_cannot_view_activity_logs()
    {
        $pegawai = User::factory()->create(['role' => 'pegawai']);

        $response = $this->actingAs($pegawai)->get('/activity-logs');

        $response->assertForbidden();
    }

    public function test_unauthorized_user_cannot_export_logs()
    {
        $user = User::factory()->create(['role' => 'other']);

        $response = $this->actingAs($user)
            ->get('/activity-logs/export');

        $response->assertForbidden();
    }
}


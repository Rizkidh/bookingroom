# Activity Log - API & Code Reference

Complete API documentation untuk Activity Log system.

---

## Models

### ActivityLog

#### Attributes
```php
$log->id // int - Primary key
$log->action // string - CREATE|UPDATE|DELETE
$log->model_type // string - Full model class name
$log->model_id // string - Model ID
$log->description // string|null - Human readable description
$log->old_values // array|null - JSON previous values
$log->new_values // array|null - JSON new values
$log->note // string|null - User catatan/remark
$log->user_id // int|null - User who did action
$log->user_name // string|null - User full name
$log->user_role // string|null - User role
$log->ip_address // string|null - IP address
$log->user_agent // string|null - Browser/device info
$log->created_at // datetime - Creation timestamp
$log->updated_at // datetime - Update timestamp
```

#### Relationships
```php
$log->user() // belongsTo(User::class)
```

#### Methods
```php
$log->getChangeDescription() // string|null - Get formatted changes
```

#### Scopes (Query Builders)
```php
// Filter by model type and optional ID
ActivityLog::byModel('App\Models\InventoryItem', 5)

// Filter by action
ActivityLog::byAction('CREATE')
ActivityLog::byAction('UPDATE')
ActivityLog::byAction('DELETE')

// Filter by user
ActivityLog::byUser(1)

// Filter by date range
ActivityLog::byDateRange($startDate, $endDate)

// Search in description, note, and user_name
ActivityLog::search('keyword')
```

#### Examples
```php
// Get all logs for specific item
$itemLogs = ActivityLog::byModel('App\Models\InventoryItem', 5)->latest()->get();

// Get user's activity
$userLogs = ActivityLog::byUser(auth()->id())->latest()->paginate(20);

// Get all updates
$updates = ActivityLog::byAction('UPDATE')->get();

// Search for notes containing 'rusak'
$damagedReports = ActivityLog::search('rusak')->latest()->get();

// Get activity from last 7 days
$recentActivity = ActivityLog::byDateRange(
 now()->subDays(7),
 now()
)->latest()->get();

// Combine multiple filters
$logs = ActivityLog::byModel('App\Models\InventoryUnit')
 ->byAction('UPDATE')
 ->search('damaged')
 ->latest()
 ->paginate(50);
```

---

## Services

### ActivityLogService

#### Static Methods

##### logCreate()
```php
ActivityLogService::logCreate(Model $model, ?string $note = null): ActivityLog|null
```
Logs model creation
```php
// Usage
ActivityLogService::logCreate($inventoryItem, "New item for IT dept");

// Returns
ActivityLog instance or null if error
```

##### logUpdate()
```php
ActivityLogService::logUpdate(
 Model $model,
 array $oldValues,
 ?string $note = null
): ActivityLog|null
```
Logs model update with old vs new values
```php
// Usage
ActivityLogService::logUpdate(
 $inventoryUnit,
 $oldValues, // Original attributes
 "Status changed due to damage"
);

// Note: Only tracks changed fields automatically
```

##### logDelete()
```php
ActivityLogService::logDelete(Model $model, ?string $note = null): ActivityLog|null
```
Logs model deletion
```php
// Usage
ActivityLogService::logDelete($inventoryItem, "Item no longer in stock");
```

##### getModelLogs()
```php
ActivityLogService::getModelLogs(
 string $modelType,
 ?string $modelId = null,
 int $limit = 50
): LengthAwarePaginator
```
Get paginated logs for specific model
```php
// Usage
$logs = ActivityLogService::getModelLogs(
 'App\Models\InventoryItem',
 5,
 100 // 100 per page
);

// Returns paginated collection
```

##### getUserLogs()
```php
ActivityLogService::getUserLogs(int $userId, int $limit = 50): LengthAwarePaginator
```
Get user's activity logs
```php
// Usage
$userActivity = ActivityLogService::getUserLogs(auth()->id(), 50);

// Returns paginated collection
```

#### Private Methods (Internal)
```php
ActivityLogService::sanitizeNote(?string $note): ?string
// Strips HTML tags from note (XSS protection)

ActivityLogService::getClientIp(): ?string
// Gets client IP (supports proxies & Cloudflare)

ActivityLogService::log(...): ActivityLog|null
// Main logging method (called by log*)
```

---

## Policies

### ActivityLogPolicy

Authorization rules for Activity Log access

```php
public function viewAny(User $user): bool
// Check if user can see activity logs
// Only: admin, supervisor
// Returns: bool

public function view(User $user, ActivityLog $activityLog): bool
// Check if user can see specific log
// Only: admin, supervisor
// Returns: bool

public function create(User $user): bool
// Check if user can create logs
// Always: false (auto-created)
// Returns: false

public function update(User $user, ActivityLog $activityLog): bool
// Check if user can update logs
// Always: false (read-only)
// Returns: false

public function delete(User $user, ActivityLog $activityLog): bool
// Check if user can delete logs
// Always: false (immutable)
// Returns: false
```

Usage in Controllers:
```php
$this->authorize('viewAny', ActivityLog::class); // Check list access
$this->authorize('view', $activityLog); // Check detail access

// Throws AuthorizationException if unauthorized
```

---

## Controllers

### ActivityLogController

#### index()
```php
public function index(Request $request): View
```
Display activity logs list with filtering

**Query Parameters:**
- `model_type` (optional) - Filter by model type
- `action` (optional) - Filter by action (CREATE/UPDATE/DELETE)
- `search` (optional) - Search in note/description/user_name

**Authorization:** `viewAny(ActivityLog)`

**Returns:** View with paginated logs (20 per page)

**Example Request:**
```
GET /activity-logs
GET /activity-logs?action=UPDATE
GET /activity-logs?model_type=App\Models\InventoryItem
GET /activity-logs?search=rusak
GET /activity-logs?model_type=App\Models\InventoryItem&action=UPDATE&search=damaged
```

#### show()
```php
public function show(ActivityLog $activityLog): string
```
Display single log detail (AJAX response)

**Returns:** HTML string (modal content)

**Example Request:**
```
GET /activity-logs/123
```

**Authorization:** `view($activityLog)`

#### getModelLogs()
```php
public function getModelLogs(string $modelType, string $modelId): View
```
Get logs for specific model

**URL Parameters:**
- `modelType` - Full model class name
- `modelId` - Model ID

**Example Request:**
```
GET /activity-logs/model/App\Models\InventoryItem/5
```

#### export()
```php
public function export(Request $request): StreamResponse
```
Export logs to CSV

**Query Parameters:** Same as index()

**Returns:** CSV file download

**Example Request:**
```
GET /activity-logs/export
GET /activity-logs/export?action=DELETE&model_type=App\Models\InventoryUnit
```

**Authorization:** `viewAny(ActivityLog)`

---

## Observers

### InventoryItemObserver

#### creating()
```php
public function creating(InventoryItem $model): void
// Initialize _oldValues for comparison
// Called: Before model inserted
```

#### created()
```php
public function created(InventoryItem $model): void
// Log creation with note from request
// Calls: ActivityLogService::logCreate()
// Called: After model inserted
```

#### updating()
```php
public function updating(InventoryItem $model): void
// Capture original attributes before update
// Called: Before model updated
```

#### updated()
```php
public function updated(InventoryItem $model): void
// Log update with old/new values and note
// Calls: ActivityLogService::logUpdate()
// Called: After model updated
```

#### deleting()
```php
public function deleting(InventoryItem $model): void
// Optional: Pre-delete logic
// Called: Before model deleted
```

#### deleted()
```php
public function deleted(InventoryItem $model): void
// Log deletion with note
// Calls: ActivityLogService::logDelete()
// Called: After model deleted
```

### InventoryUnitObserver

Same hooks as InventoryItemObserver PLUS:

#### updateItemStock() (Private)
```php
protected function updateItemStock(int $itemId): void
// Recalculate parent item stock counts
// Called in: created(), updated(), deleted()
```

---

## Routes

### Activity Log Routes

```php
// In routes/web.php

// List activity logs with filters
GET /activity-logs ActivityLogController@index activity-logs.index
 ?model_type=...
 ?action=...
 ?search=...

// View single log detail (AJAX)
GET /activity-logs/{id} ActivityLogController@show activity-logs.show

// Export logs to CSV
GET /activity-logs/export ActivityLogController@export activity-logs.export
 ?model_type=...
 ?action=...
 ?search=...

// Get logs for specific model
GET /activity-logs/model/{type}/{id} ActivityLogController@getModelLogs activity-logs.model-logs
```

All routes require authentication & authorization (admin/supervisor)

---

## Integration Points

### How to Use in Your Code

#### In Controller (After Model Save)
```php
// Manual logging (if not using observers)
ActivityLogService::logCreate($model, request()->input('note'));
```

#### In Blade Template
```php
@if(auth()->user()->role === 'admin' || auth()->user()->role === 'supervisor')
 <a href="{{ route('activity-logs.index') }}">View Activity Log</a>
@endif
```

#### In Middleware
```php
// Log specific events
middleware('activity_log')

// Track unusual activity
if (suspicious_action) {
 ActivityLogService::log(...);
}
```

#### In Event Listener
```php
// Listen to custom events
Event::listen(ItemCreated::class, function ($event) {
 ActivityLogService::logCreate($event->item, $event->note);
});
```

#### In Artisan Command
```php
// Log command execution
public function handle()
{
 // Do something
 ActivityLogService::logCreate($model, "Created via command");
}
```

---

## Testing Examples

### Unit Test
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ActivityLog;
use App\Models\InventoryItem;
use App\Services\ActivityLogService;

class ActivityLogServiceTest extends TestCase
{
 public function test_can_log_creation()
 {
 $item = InventoryItem::create([
 'name' => 'Test Item',
 'total_stock' => 0,
 'available_stock' => 0,
 'damaged_stock' => 0,
 ]);

 $log = ActivityLog::latest()->first();

 $this->assertEquals('CREATE', $log->action);
 $this->assertEquals('App\Models\InventoryItem', $log->model_type);
 $this->assertEquals($item->id, $log->model_id);
 }

 public function test_sanitizes_note()
 {
 $item = InventoryItem::create([
 'name' => 'Test Item',
 'total_stock' => 0,
 'available_stock' => 0,
 'damaged_stock' => 0,
 ]);

 ActivityLogService::logCreate($item, '<script>alert("xss")</script>');

 $log = ActivityLog::latest()->first();

 $this->assertNotContains('<script>', $log->note);
 }
}
```

### Feature Test
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ActivityLog;

class ActivityLogViewTest extends TestCase
{
 public function test_admin_can_view_activity_logs()
 {
 $admin = User::factory()->create(['role' => 'admin']);

 $response = $this->actingAs($admin)
 ->get('/activity-logs');

 $response->assertStatus(200);
 $response->assertViewHas('logs');
 }

 public function test_user_cannot_view_activity_logs()
 {
 $user = User::factory()->create(['role' => 'user']);

 $response = $this->actingAs($user)
 ->get('/activity-logs');

 $response->assertStatus(403); // Forbidden
 }
}
```

---

## Performance Tips

### Database Queries
```php
// DON'T - N+1 query
$logs = ActivityLog::all();
foreach ($logs as $log) {
 echo $log->user->name; // Extra query per log
}

// DO - Eager load
$logs = ActivityLog::with('user')->get();
foreach ($logs as $log) {
 echo $log->user->name; // No extra queries
}
```

### Pagination
```php
// Use pagination for large result sets
$logs = ActivityLog::latest()->paginate(20);

// Avoid fetching all
$logs = ActivityLog::all(); // Can be slow
```

### Caching
```php
// Cache model logs if accessed frequently
$logs = Cache::remember("model.logs.{$modelId}", 3600, function () {
 return ActivityLog::byModel($modelType, $modelId)->get();
});
```

### Indexes
Already included in migration:
```sql
INDEX activity_logs_model_type_model_id_index
INDEX activity_logs_action_index
INDEX activity_logs_user_id_index
INDEX activity_logs_created_at_index
```

---

## Security Best Practices

### In Controller
```php
// Always check authorization
$this->authorize('viewAny', ActivityLog::class);

// Never skip authorization
// GET /activity-logs (no auth check)
```

### In Service
```php
// Sanitize all user input
$note = strip_tags(trim($note));

// Don't store raw user input
// $log->note = $request->input('note');
```

### In Blade
```php
// Escape output
{{ $log->note }}

// Don't use unescaped content
{!! $log->note !!} // Only if sanitized
```

---

## Common Queries

```php
// Get latest 10 logs
$logs = ActivityLog::latest()->limit(10)->get();

// Get logs for today
$logs = ActivityLog::whereDate('created_at', today())->get();

// Get logs from specific user
$logs = ActivityLog::byUser(1)->get();

// Get delete operations
$logs = ActivityLog::byAction('DELETE')->get();

// Search in notes
$logs = ActivityLog::search('damaged')->get();

// Get with pagination
$logs = ActivityLog::latest()->paginate(20);

// Export to array
$array = $logs->toArray();

// Export to JSON
$json = $logs->toJson();

// Count
$count = ActivityLog::count();

// Check if any logs exist
if (ActivityLog::exists()) {
 // Do something
}
```

---

**Documentation Version:** 1.0
**Last Updated:** January 5, 2025
**Status:** Complete

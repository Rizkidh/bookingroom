# Activity Log (Audit Trail) - Dokumentasi Lengkap

## Ringkasan Fitur

Implementasi **Activity Log** pada sistem monitoring inventaris Laravel dengan fitur:
- Pencatatan otomatis CREATE, UPDATE, DELETE
- Input catatan manual (note/remark) dari user
- Penyimpanan data perubahan (old vs new values)
- Dashboard Activity Log dengan filter dan search
- Export ke CSV
- Keamanan (XSS protection, masking sensitif)
- Policy-based authorization (Admin/Supervisor only)

---

## Struktur File yang Dibuat/Dimodifikasi

### File Baru:
```
app/
 Models/
 ActivityLog.php # Model untuk activity logs
 Services/
 ActivityLogService.php # Service untuk logging logic
 Observers/
 InventoryItemObserver.php # Observer untuk InventoryItem
 Policies/
 ActivityLogPolicy.php # Policy untuk authorization
 Http/Controllers/
 ActivityLogController.php # Controller untuk Activity Log views

database/
 migrations/
 2025_01_05_000000_create_activity_logs_table.php

resources/views/
 activity_logs/
 index.blade.php # List view dengan filter & search
 show.blade.php # Detail modal dengan perubahan data
```

### File yang Dimodifikasi:
```
app/
 Observers/InventoryUnitObserver.php # Added activity logging
 Http/Controllers/
 InventoryController.php # Added note validation
 InventoryUnitController.php # Added note validation
 Providers/
 AuthServiceProvider.php # Registered ActivityLogPolicy
 AppServiceProvider.php # Registered InventoryItemObserver

resources/views/
 inventories/
 create.blade.php # Added note textarea
 edit.blade.php # Added note textarea
 inventory_units/
 create.blade.php # Added note textarea
 edit.blade.php # Added note textarea

routes/
 web.php # Added activity log routes
```

---

## Instalasi & Setup

### 1⃣ Jalankan Migration

```bash
php artisan migrate
```

Ini akan membuat tabel `activity_logs` dengan kolom:
- `id` - Primary key
- `action` - CREATE, UPDATE, DELETE
- `model_type` - Nama class model
- `model_id` - ID dari model
- `description` - Deskripsi human-readable
- `old_values` - JSON nilai sebelumnya
- `new_values` - JSON nilai sesudahnya
- `note` - Catatan dari user (nullable)
- `user_id` - ID user yang melakukan action
- `user_name` - Nama user
- `user_role` - Role user
- `ip_address` - IP address user
- `user_agent` - Browser user agent
- `created_at` - Timestamp
- `updated_at` - Timestamp

### 2⃣ Pastikan User Model Memiliki Role

Pastikan migration users table memiliki kolom `role`:

```php
// database/migrations/.../create_users_table.php
$table->string('role')->default('user'); // admin, supervisor, user
```

---

## Cara Kerja

### Activity Logging Flow

```
User Action (Create/Update/Delete)
 ↓
Controller validates input + receives 'note'
 ↓
Model saved/updated/deleted
 ↓
Observer hooks triggered (creating, updating, deleting)
 ↓
ActivityLogService::log*() called
 ↓
Data logged to activity_logs table
 ↓
Activity Log visible in dashboard
```

### Contoh: User Membuat Inventory Item

#### Form Input (resources/views/inventories/create.blade.php):
```html
<input type="text" name="name" required>
<textarea name="note" maxlength="500" placeholder="Alasan penambahan..."></textarea>
<button type="submit">Simpan</button>
```

#### Controller Validation (app/Http/Controllers/InventoryController.php):
```php
$validator = Validator::make($request->all(), [
 'name' => 'required|string|max:255|unique:inventory_items,name',
 'note' => 'nullable|string|max:500', // NEW
]);
```

#### Model Creation:
```php
InventoryItem::create([
 'name' => $request->name,
 'total_stock' => 0,
 'available_stock' => 0,
 'damaged_stock' => 0,
]);
```

#### Observer Logging (app/Observers/InventoryItemObserver.php):
```php
public function created(InventoryItem $inventoryItem): void
{
 $note = request()->input('note'); // Get note from request
 ActivityLogService::logCreate($inventoryItem, $note);
}
```

#### Result in activity_logs table:
| Field | Value |
|-------|-------|
| action | CREATE |
| model_type | App\Models\InventoryItem |
| model_id | 1 |
| description | Created InventoryItem (1) |
| old_values | null |
| new_values | {"name": "Laptop", "total_stock": 0, ...} |
| **note** | "Penambahan untuk ruang meeting A" |
| user_name | John Doe |
| user_role | admin |
| ip_address | 192.168.1.100 |

---

## Mengakses Activity Log

### 1⃣ Melalui Dashboard

Navigate ke `/activity-logs`

Fitur:
- List semua activity dengan pagination
- Filter by model type (InventoryItem, InventoryUnit)
- Filter by action (CREATE, UPDATE, DELETE)
- Search di catatan & nama user
- Lihat detail lengkap dengan modal

### 2⃣ Via API/Code

```php
// Get activity logs untuk specific model
$logs = ActivityLog::byModel(
 'App\Models\InventoryItem',
 $itemId
)->latest()->get();

// Get user activity
$logs = ActivityLog::byUser(auth()->id())->latest()->get();

// Search
$logs = ActivityLog::search('keyword')->get();

// Filter by date
$logs = ActivityLog::byDateRange($start, $end)->get();

// Export CSV
GET /activity-logs/export?model_type=App\Models\InventoryItem
```

---

## Keamanan & Authorization

### Authorization Rules

Hanya **admin** dan **supervisor** yang dapat:
- Melihat Activity Log
- Export data

Regular **users** tidak dapat:
- Mengakses `/activity-logs`
- Memodifikasi log (read-only)
- Menghapus log

### Implementasi (app/Policies/ActivityLogPolicy.php):

```php
public function viewAny(User $user): bool
{
 return in_array($user->role, ['admin', 'supervisor']);
}

public function view(User $user, ActivityLog $activityLog): bool
{
 return in_array($user->role, ['admin', 'supervisor']);
}
```

### Sanitasi Input

```php
// app/Services/ActivityLogService.php
private static function sanitizeNote(?string $note): ?string
{
 if (!$note) {
 return null;
 }

 return strip_tags(trim($note)); // Remove HTML tags (XSS protection)
}
```

---

## Contoh Activity Log Entry

### CREATE Action:
```
Waktu: 2025-01-05 14:30:25
Action: CREATE
Model: InventoryItem (ID: 5)
User: Admin User (admin)
Catatan: "Penambahan peralatan untuk divisi IT"
IP: 192.168.1.50
```

### UPDATE Action:
```
Waktu: 2025-01-05 15:45:10
Action: UPDATE
Model: InventoryUnit (ID: 00042)
User: Supervisor X (supervisor)
Catatan: "Perubahan status karena unit rusak dalam pengujian"

Perubahan Data:
- condition_status: available → damaged
- current_holder: John → Storage
```

### DELETE Action:
```
Waktu: 2025-01-05 16:20:33
Action: DELETE
Model: InventoryItem (ID: 3)
User: Admin User (admin)
Catatan: "Item dihapus karena tidak lagi diproduksi"
```

---

## API Reference

### ActivityLog Model

#### Relationships:
```php
$log->user(); // Belongs to User
```

#### Scopes:
```php
// Filter by model
ActivityLog::byModel('App\Models\InventoryItem', $id)

// Filter by action
ActivityLog::byAction('CREATE')

// Filter by user
ActivityLog::byUser($userId)

// Filter by date range
ActivityLog::byDateRange($startDate, $endDate)

// Search
ActivityLog::search('keyword')
```

#### Attributes:
```php
$log->id
$log->action // CREATE, UPDATE, DELETE
$log->model_type // Full class name
$log->model_id // String
$log->description // Human readable
$log->old_values // JSON
$log->new_values // JSON
$log->note // String
$log->user_id // Foreign key
$log->user_name // String
$log->user_role // String
$log->ip_address // String
$log->user_agent // String
$log->created_at // DateTime
$log->updated_at // DateTime
```

### ActivityLogService

```php
// Log creation
ActivityLogService::logCreate($model, $note = null);

// Log update
ActivityLogService::logUpdate($model, $oldValues, $note = null);

// Log deletion
ActivityLogService::logDelete($model, $note = null);

// Get model logs
ActivityLogService::getModelLogs($modelType, $modelId = null, $limit = 50);

// Get user logs
ActivityLogService::getUserLogs($userId, $limit = 50);
```

---

## Testing Activity Log

### 1⃣ Manual Test: Create Inventory Item

```bash
1. Navigate ke /inventories/create
2. Input:
 - Name: "Test Item"
 - Note: "Test penambahan untuk QA"
3. Click "Simpan Item"
4. Navigate ke /activity-logs
5. Verify log dengan:
 - Action: CREATE
 - Model: InventoryItem
 - Note: "Test penambahan untuk QA"
```

### 2⃣ Manual Test: Update Unit Status

```bash
1. Navigate ke inventory unit edit page
2. Change condition_status: available → damaged
3. Input Note: "Unit rusak karena jatuh saat pengiriman"
4. Click "Update Unit"
5. Check /activity-logs
6. Click "Lihat" untuk melihat old vs new values
```

### 3⃣ Database Check

```sql
-- Check latest logs
SELECT id, action, model_type, model_id, note, user_name, created_at
FROM activity_logs
ORDER BY created_at DESC
LIMIT 10;

-- Check specific model logs
SELECT * FROM activity_logs
WHERE model_type = 'App\\Models\\InventoryItem'
AND model_id = '5'
ORDER BY created_at DESC;
```

---

## Konfigurasi & Customization

### Mengubah Max Note Length

Edit migration & form validation:

```php
// database/migrations/.../create_activity_logs_table.php
$table->text('note', 1000)->nullable(); // Ubah ke 1000 karakter

// resources/views/inventories/create.blade.php
<textarea maxlength="1000" ...></textarea>

// app/Http/Controllers/InventoryController.php
'note' => 'nullable|string|max:1000',
```

### Menambah Field Logging

```php
// ActivityLogService::log()
$log = ActivityLog::create([
 'action' => $action,
 'model_type' => get_class($model),
 'model_id' => (string) $model->getKey(),
 'description' => $description,
 'old_values' => $oldValues,
 'new_values' => $newValues,
 'note' => $note,
 'user_id' => Auth::id(),
 'user_name' => Auth::user()->name,
 'user_role' => Auth::user()->role,
 'ip_address' => $this->getClientIp(),
 'user_agent' => Request::userAgent(),
 'custom_field' => 'value', // ADD HERE
]);
```

### Menonaktifkan Logging untuk Model Tertentu

```php
// app/Providers/AppServiceProvider.php
public function boot(): void
{
 // InventoryUnit::observe(InventoryUnitObserver::class); // Comment this
 InventoryItem::observe(InventoryItemObserver::class);
}
```

---

## Best Practices

### DO:
- Berikan catatan yang deskriptif dan informatif
- Gunakan untuk audit trail & investigasi masalah
- Reguler backup activity_logs table
- Archive logs lama (> 6 bulan) untuk performa
- Monitor IP address untuk deteksi aktivitas mencurigakan

### DON'T:
- Jangan modifikasi log setelah dibuat (read-only)
- Jangan simpan password atau data sensitif di note
- Jangan hapus log (retention policy)
- Jangan share activity log ke user biasa

---

## Performance Tips

### Database Indexes
Sudah tersedia di migration:
```sql
INDEX activity_logs_model_type_model_id_index
INDEX activity_logs_action_index
INDEX activity_logs_user_id_index
INDEX activity_logs_created_at_index
```

### Archive Old Logs
```php
// artisan command (create new)
ActivityLog::where('created_at', '<', now()->subMonths(6))
 ->delete();
```

### Pagination
Default 20 per page di Activity Log dashboard. Ubah di controller:
```php
$logs = $query->latest('created_at')->paginate(50); // Ubah 20 ke 50
```

---

## Troubleshooting

### Issue: Note tidak tersimpan
**Solusi:**
1. Pastikan form memiliki `<textarea name="note">`
2. Cek apakah controller menerima input: `request()->input('note')`
3. Cek observer trigger: tambahkan `dd()` di `created()`

### Issue: Akses denied ke Activity Log
**Solusi:**
1. Pastikan user memiliki role 'admin' atau 'supervisor'
2. Cek di users table: `SELECT role FROM users WHERE id = ?`
3. Pastikan policy registered di AuthServiceProvider

### Issue: Old values kosong saat UPDATE
**Solusi:**
1. Pastikan `updating()` observer dipanggil sebelum `updated()`
2. Store original values: `$model->_oldValues = $model->getOriginal()`
3. Cek getOriginal() method

---

## Support & Maintenance

### Monitoring
```sql
-- Check log size
SELECT COUNT(*) as total_logs,
 MIN(created_at) as oldest,
 MAX(created_at) as newest
FROM activity_logs;

-- Check which models logged
SELECT DISTINCT model_type, COUNT(*) as count
FROM activity_logs
GROUP BY model_type;
```

### Backup
```bash
# Backup activity logs
mysqldump -u user -p database_name activity_logs > backup_logs.sql
```

---

## Referensi Laravel

- [Observers](https://laravel.com/docs/eloquent#observers)
- [Policies](https://laravel.com/docs/authorization#creating-policies)
- [Query Builder](https://laravel.com/docs/queries)
- [Blade Templates](https://laravel.com/docs/blade)

---

**Dokumentasi dibuat:** 5 Januari 2025
**Versi Laravel:** 11+
**Status:** Production Ready

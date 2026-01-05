# Activity Log (Audit Trail) Feature

**Status:** **PRODUCTION READY**

Fitur lengkap Activity Log untuk sistem monitoring inventaris dengan pencatatan otomatis semua perubahan data dan catatan manual dari user.

---

## Quick Start (5 Menit)

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Test
1. Go to `/inventories/create`
2. Add item name and note
3. Submit
4. Visit `/activity-logs` (admin/supervisor only)
5. See your activity logged!

---

## Features

### Catatan Manual (Note/Remark)
- Input optional pada form create & edit
- Max 500 karakter
- Disimpan ke activity log
- Visible di dashboard

### Activity Log Dashboard
- List semua aktivitas dengan pagination
- Filter by model type (InventoryItem/InventoryUnit)
- Filter by action (CREATE/UPDATE/DELETE)
- Search by note/username
- Detail modal dengan old vs new values
- Export ke CSV

### Tracking Lengkap
- User info (name, role, ID)
- IP address & user agent
- Timestamp with seconds
- Old values vs new values (JSON)
- Complete audit trail

### Security
- XSS protection (strip HTML)
- Authorization (admin/supervisor only)
- Read-only logs (cannot modify/delete)
- IP tracking for anomaly detection

---

## What's Included

### Database
- Migration: `2025_01_05_000000_create_activity_logs_table.php`
- Table: `activity_logs` with 15 columns + indexes

### Models & Services
- `ActivityLog` model with scopes & relationships
- `ActivityLogService` with logging methods
- `ActivityLogPolicy` for authorization

### Observers
- `InventoryItemObserver` (new)
- `InventoryUnitObserver` (updated)
- Automatically logs all CRUD operations

### Controllers & Routes
- `ActivityLogController` with 4 actions
- Routes for viewing, exporting logs
- Authorization checks included

### Views
- Activity log list with filters & search
- Detail modal with side-by-side value comparison
- Responsive design with Tailwind CSS

### Forms (Updated)
- Inventory create/edit
- Unit create/edit
- All have optional note textarea

---

## Documentation Files

| File | Purpose |
|------|---------|
| `SETUP_GUIDE.md` | Quick start & checklist |
| `ACTIVITY_LOG_DOCUMENTATION.md` | Complete technical documentation |
| `IMPLEMENTATION_SUMMARY.md` | What was implemented |
| `SQL_QUERIES_REFERENCE.md` | Useful SQL queries |

---

## Technical Stack

- **Framework:** Laravel 11+
- **Database:** MySQL 5.7+
- **Frontend:** Tailwind CSS
- **Pattern:** Observer + Service + Policy

---

## Database Schema

```
activity_logs table:
 id (primary key)
 action (CREATE|UPDATE|DELETE)
 model_type (full class name)
 model_id (string)
 description (text)
 old_values (JSON) ← Track changes
 new_values (JSON) ← Track changes
 note (text nullable) ← User catatan
 user_id (foreign key)
 user_name (string)
 user_role (string)
 ip_address (string)
 user_agent (text)
 created_at (timestamp)
 updated_at (timestamp)
 Indexes for performance
```

---

## Usage Examples

### Creating with Note
```html
<!-- /inventories/create -->
<input name="name" required placeholder="Item name">
<textarea name="note" maxlength="500" placeholder="Alasan penambahan..."></textarea>
```

### Updating with Note
```html
<!-- /inventories/{id}/edit -->
<input name="name" value="{{ $inventory->name }}">
<textarea name="note" placeholder="Alasan perubahan..."></textarea>
```

### Viewing Activity Log
```
GET /activity-logs?action=UPDATE&search=rusak
```

### Exporting Data
```
GET /activity-logs/export?model_type=App\Models\InventoryItem
```

---

## Access Control

| Role | View Logs | Export |
|------|-----------|--------|
| Admin | Yes | Yes |
| Supervisor | Yes | Yes |
| User | No | No |

---

## Example Activity Log Entry

```

 Activity: Unit Status Changed

 Waktu: 2025-01-05 14:30:25
 Action: UPDATE
 Model: InventoryUnit (ID: 42)
 User: John Admin (admin)
 IP: 192.168.1.100

 CATATAN:
 "Unit rusak saat proses QA. Perlu
 dikirim ke vendor untuk repair"

 PERUBAHAN:
 condition_status:
 Sebelum: "available"
 Sesudah: "damaged"

 current_holder:
 Sebelum: "John"
 Sesudah: "Storage"

```

---

## Testing

### Manual Test Checklist
- [ ] Create inventory item with note
- [ ] Update item with note
- [ ] Create unit with note
- [ ] Update unit status with note
- [ ] Delete item/unit with note
- [ ] View activity log list
- [ ] Filter by action type
- [ ] Filter by model type
- [ ] Search by note content
- [ ] View detail in modal
- [ ] See old vs new values
- [ ] Export to CSV
- [ ] Verify non-admin can't access logs

### SQL Verification
```sql
-- Check latest logs
SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 10;

-- Check note field
SELECT action, note, user_name FROM activity_logs WHERE note IS NOT NULL;

-- Count by action
SELECT action, COUNT(*) FROM activity_logs GROUP BY action;
```

---

## Customization

### Change Note Max Length
```php
// Migration
$table->text('note', 1000)->nullable();

// Form validation
'note' => 'nullable|string|max:1000'

// Blade
<textarea maxlength="1000"></textarea>
```

### Add Custom Fields
```php
// Migration & Service
$table->string('custom_field')->nullable();
// Then update ActivityLogService::log()
```

### Customize Authorization
```php
// app/Policies/ActivityLogPolicy.php
public function viewAny(User $user): bool
{
 // Your logic here
 return $user->isAdmin();
}
```

---

## Important Notes

### Before Going Live
- [ ] Backup database
- [ ] Test all flows
- [ ] Verify user roles
- [ ] Check authorization policies
- [ ] Monitor log size
- [ ] Plan archival strategy

### Performance
- Indexes included in migration
- Pagination set to 20 per page
- JSON columns indexed
- Consider archiving logs > 6 months

### Backup Strategy
```bash
# Backup logs table
mysqldump -u user -p database activity_logs > logs_backup.sql

# Archive old logs (monthly)
DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

---

## Troubleshooting

### Issue: Migration fails
- Check MySQL version (5.7+)
- Verify database permissions
- Try: `php artisan migrate:refresh --seed`

### Issue: Notes not saving
- Check form has `<textarea name="note">`
- Check observer is registered
- Check controller validation

### Issue: Can't access activity log page
- Check user role: `SELECT role FROM users`
- Role must be 'admin' or 'supervisor'
- Check policy in AuthServiceProvider

### Issue: Old values empty
- Ensure observer `updating()` is called
- Check `_oldValues` storage in observer

See `SETUP_GUIDE.md` for more troubleshooting.

---

## Files Summary

### New Files (9)
```
 database/migrations/2025_01_05_000000_create_activity_logs_table.php
 app/Models/ActivityLog.php
 app/Services/ActivityLogService.php
 app/Observers/InventoryItemObserver.php
 app/Policies/ActivityLogPolicy.php
 app/Http/Controllers/ActivityLogController.php
 resources/views/activity_logs/index.blade.php
 resources/views/activity_logs/show.blade.php
 Documentation files (3)
```

### Updated Files (9)
```
 app/Observers/InventoryUnitObserver.php
 app/Http/Controllers/InventoryController.php
 app/Http/Controllers/InventoryUnitController.php
 app/Providers/AuthServiceProvider.php
 app/Providers/AppServiceProvider.php
 resources/views/inventories/create.blade.php
 resources/views/inventories/edit.blade.php
 resources/views/inventory_units/create.blade.php
 resources/views/inventory_units/edit.blade.php
 routes/web.php
```

---

## Support

### Need Help?
1. Check `SETUP_GUIDE.md` for quick answers
2. Read `ACTIVITY_LOG_DOCUMENTATION.md` for details
3. See `SQL_QUERIES_REFERENCE.md` for database queries
4. Check `IMPLEMENTATION_SUMMARY.md` for what was done

### Key Contacts
- **Feature Lead:** Activity Log System
- **Created:** January 5, 2025
- **Version:** 1.0
- **Status:** Production Ready

---

## Future Enhancements

Optional features to consider:
- [ ] Email alerts for DELETE actions
- [ ] GraphQL API for logs
- [ ] Real-time WebSocket updates
- [ ] Advanced full-text search
- [ ] Automated archival job
- [ ] Integration with external audit systems
- [ ] Bulk operations logging
- [ ] Undo/rollback capability

---

## License & Credits

Developed as part of Inventory Management System Monitoring Feature.

**Implementation Date:** January 5, 2025
**Framework:** Laravel 11+
**Database:** MySQL 5.7+
**Status:** **PRODUCTION READY**

---

** Happy Auditing! **

# Quick Start Guide - Activity Log Setup

## Checklist Setup (5 Menit)

- [ ] Run migration: `php artisan migrate`
- [ ] Verify routes: `php artisan route:list | grep activity`
- [ ] Check permissions: User role must be 'admin' or 'supervisor'
- [ ] Test create form: `/inventories/create` add note field
- [ ] View logs: `/activity-logs`

---

## What's Included

### Automatic Logging
- `CREATE` - When item/unit added with optional note
- `UPDATE` - When item/unit modified with optional note  
- `DELETE` - When item/unit deleted with optional note

### UI Components
- Form fields: Added `note` textarea to all create/edit forms
- Activity dashboard: Full list with filters and search
- Detail modal: Shows old vs new values + user info
- Export CSV: Download audit trail

### Security
- XSS protection (strip HTML tags from notes)
- Authorization checks (admin/supervisor only)
- Read-only logs (cannot modify/delete)
- IP & user agent tracking

---

## Form Changes

### Create Forms Now Have:
```html
<textarea name="note" maxlength="500" placeholder="Catatan..."></textarea>
```

Available on:
- Create Inventory Item (`/inventories/create`)
- Edit Inventory Item (`/inventories/{id}/edit`)
- Create Unit (`/inventories/{id}/units/create`)
- Edit Unit (`/inventories/{id}/units/{unit}/edit`)

---

## Key Routes

```
GET    /activity-logs              View all logs (list)
GET    /activity-logs/{id}         View single log detail
GET    /activity-logs/export       Download CSV
GET    /activity-logs/model/{type}/{id}  Get logs for specific model
```

Only accessible to admin/supervisor users.

---

## Database

Migration created: `database/migrations/2025_01_05_000000_create_activity_logs_table.php`

Columns: action, model_type, model_id, old_values, new_values, **note**, user info, IP, timestamp

---

## Quick Test

1. Go to `/inventories/create`
2. Add item name: "Test Item"
3. Add note: "Test note for QA"
4. Submit
5. Go to `/activity-logs`
6. Should see CREATE log with your note visible

---

## Activity Log Dashboard Features

| Feature | Details |
|---------|---------|
| Filter | By model type (InventoryItem/InventoryUnit) |
| Filter | By action (CREATE/UPDATE/DELETE) |
| Search | By note content or username |
| View Detail | See old vs new values in modal |
| Export | Download CSV with all logs |
| Pagination | 20 logs per page |

---

## For Developers

### Service Class Location
```php
App\Services\ActivityLogService
```

Usage:
```php
ActivityLogService::logCreate($model, $note);
ActivityLogService::logUpdate($model, $oldValues, $note);
ActivityLogService::logDelete($model, $note);
```

### Model Scopes
```php
ActivityLog::byModel($type, $id)
ActivityLog::byAction('CREATE')
ActivityLog::byUser($userId)
ActivityLog::search('keyword')
```

---

## User Access Control

| Role | Can View Logs | Can Export |
|------|--------------|-----------|
| Admin | Yes | Yes |
| Supervisor | Yes | Yes |
| User | No | No |

User role determined by `users.role` column.

---

## FAQs

**Q: Where are logs stored?**  
A: Database table `activity_logs`

**Q: Can users delete logs?**  
A: No, logs are read-only. Only visible to admin/supervisor.

**Q: How do I add custom fields to logs?**  
A: Edit `ActivityLogService::log()` method and extend migration.

**Q: Can I customize note message?**  
A: Yes, in form placeholder & validation (max 500 chars default).

**Q: How long are logs kept?**  
A: By default, forever. Consider archiving logs older than 6 months.

---

## Troubleshooting

**Logs not appearing?**
- Check Observer is registered in `AppServiceProvider`
- Verify model has observer: `Model::observe(Observer::class)`

**Can't access Activity Log page?**
- Check user role in database: `SELECT role FROM users WHERE id = 1`
- Must be 'admin' or 'supervisor'

**Note field not in form?**
- Check blade file includes textarea: `<textarea name="note">`
- Verify controller validates: `'note' => 'nullable|string|max:500'`

---

**Version:** 1.0  
**Date:** January 5, 2025  
**Status:** Ready to use

# Developer Checklist - Activity Log Implementation

Complete checklist untuk memverifikasi semua komponen Activity Log sudah berfungsi dengan baik.

---

## Pre-Launch Checklist

### Database & Migration
- [ ] Run `php artisan migrate`
- [ ] Verify `activity_logs` table exists
 ```sql
 SHOW TABLES LIKE 'activity_logs';
 DESCRIBE activity_logs;
 ```
- [ ] Check all columns are created
- [ ] Verify indexes are present
 ```sql
 SHOW INDEX FROM activity_logs;
 ```
- [ ] Test insert manually
 ```sql
 INSERT INTO activity_logs (action, model_type, model_id, created_at, updated_at)
 VALUES ('TEST', 'App\\Models\\Test', '1', NOW(), NOW());
 ```

### Models & Services
- [ ] Verify `ActivityLog` model exists at `app/Models/ActivityLog.php`
- [ ] Check model has correct table name: `protected $table = 'activity_logs'`
- [ ] Verify scopes exist:
 - [ ] `byModel()`
 - [ ] `byAction()`
 - [ ] `byUser()`
 - [ ] `byDateRange()`
 - [ ] `search()`
- [ ] Verify `ActivityLogService` exists at `app/Services/ActivityLogService.php`
- [ ] Check service methods exist:
 - [ ] `logCreate()`
 - [ ] `logUpdate()`
 - [ ] `logDelete()`
 - [ ] `getModelLogs()`
 - [ ] `getUserLogs()`
 - [ ] `sanitizeNote()` (private)

### Observers
- [ ] Verify `InventoryItemObserver` exists at `app/Observers/InventoryItemObserver.php`
- [ ] Verify `InventoryUnitObserver` exists at `app/Observers/InventoryUnitObserver.php`
- [ ] Check both have hooks:
 - [ ] `creating()` / `created()`
 - [ ] `updating()` / `updated()`
 - [ ] `deleting()` / `deleted()`
- [ ] Verify observers are registered in `AppServiceProvider::boot()`
 ```php
 InventoryItem::observe(InventoryItemObserver::class);
 InventoryUnit::observe(InventoryUnitObserver::class);
 ```

### Controllers & Policies
- [ ] Verify `ActivityLogController` exists at `app/Http/Controllers/ActivityLogController.php`
- [ ] Check controller methods:
 - [ ] `index()` with filter support
 - [ ] `show()` for modal
 - [ ] `getModelLogs()`
 - [ ] `export()` for CSV
- [ ] Verify `ActivityLogPolicy` exists at `app/Policies/ActivityLogPolicy.php`
- [ ] Check policy is registered in `AuthServiceProvider`
 ```php
 ActivityLog::class => ActivityLogPolicy::class,
 ```

### Routes
- [ ] Verify routes exist in `routes/web.php`:
 - [ ] `GET /activity-logs` → index
 - [ ] `GET /activity-logs/{id}` → show
 - [ ] `GET /activity-logs/export` → export
 - [ ] `GET /activity-logs/model/{type}/{id}` → getModelLogs
- [ ] Test routes: `php artisan route:list | grep activity`

### Views
- [ ] Verify `resources/views/activity_logs/index.blade.php` exists
- [ ] Verify `resources/views/activity_logs/show.blade.php` exists
- [ ] Check index has:
 - [ ] Filter form (model type, action, search)
 - [ ] Activity table with columns
 - [ ] Pagination
 - [ ] Modal for details
- [ ] Check show has:
 - [ ] Basic info section
 - [ ] User info section
 - [ ] Note display
 - [ ] Old vs new values
 - [ ] Close button

### Form Updates
- [ ] Verify note field in `resources/views/inventories/create.blade.php`
- [ ] Verify note field in `resources/views/inventories/edit.blade.php`
- [ ] Verify note field in `resources/views/inventory_units/create.blade.php`
- [ ] Verify note field in `resources/views/inventory_units/edit.blade.php`
- [ ] All note fields have:
 - [ ] `name="note"`
 - [ ] `maxlength="500"`
 - [ ] Placeholder text

### Controller Validation
- [ ] Check `InventoryController::store()` validates note
 ```php
 'note' => 'nullable|string|max:500'
 ```
- [ ] Check `InventoryController::update()` validates note
- [ ] Check `InventoryUnitController::store()` validates note
- [ ] Check `InventoryUnitController::update()` validates note

---

## Functional Testing

### Create Operations
- [ ] Navigate to `/inventories/create`
 - [ ] Form loads
 - [ ] Note field visible
 - [ ] Can submit with note
 - [ ] Activity log created
 - [ ] Log shows correct action: CREATE
 - [ ] Note appears in log
- [ ] Navigate to `/inventories/{id}/units/create`
 - [ ] Form loads
 - [ ] Note field visible
 - [ ] Can submit with note
 - [ ] Activity log created

### Update Operations
- [ ] Navigate to `/inventories/{id}/edit`
 - [ ] Form loads with note field
 - [ ] Can update name
 - [ ] Can add note
 - [ ] Submit creates UPDATE log
 - [ ] Log shows changed field
- [ ] Navigate to `/inventories/{id}/units/{unit}/edit`
 - [ ] Can change condition_status
 - [ ] Can add note
 - [ ] Log shows old vs new status

### Delete Operations
- [ ] Delete inventory item
 - [ ] Item removed from database
 - [ ] DELETE log created
 - [ ] Note visible in log

### Activity Log Dashboard
- [ ] Access `/activity-logs`
 - [ ] Page loads
 - [ ] Shows activity table
 - [ ] Pagination works
 - [ ] Filter by model type works
 - [ ] Filter by action works
 - [ ] Search works
- [ ] Click "Lihat" (View) button
 - [ ] Modal opens
 - [ ] Shows basic info
 - [ ] Shows user info
 - [ ] Shows note (if exists)
 - [ ] Shows old vs new values (if UPDATE)
 - [ ] Can close modal
- [ ] Export CSV
 - [ ] Click export button
 - [ ] CSV file downloads
 - [ ] File has correct data

---

## Security Testing

### Authorization
- [ ] Login as regular user
 - [ ] Cannot access `/activity-logs` (403)
 - [ ] Cannot see activity log in nav
- [ ] Login as admin user
 - [ ] Can access `/activity-logs`
 - [ ] Can view all logs
 - [ ] Can export

### XSS Protection
- [ ] Try submitting HTML in note:
 ```
 <script>alert('xss')</script>
 ```
 - [ ] HTML tags are stripped
 - [ ] Note stored without HTML
 - [ ] Display is safe

### Read-Only Verification
- [ ] Try to access PUT/PATCH on `/activity-logs/{id}`
 - [ ] Should get 405 Method Not Allowed
- [ ] Try to access DELETE on `/activity-logs/{id}`
 - [ ] Should get 405 Method Not Allowed

### IP Tracking
- [ ] Create activity
- [ ] Check database:
 ```sql
 SELECT ip_address FROM activity_logs ORDER BY id DESC LIMIT 1;
 ```
 - [ ] IP address is logged

---

## Debugging Tests

### Observer Trigger Test
Add this to a form's create action:
```php
dd('Creating model...');
```
Then in Observer:
```php
public function created(InventoryItem $item) {
 dd('Observer triggered!');
}
```
- [ ] Check that dd() is reached in Observer
- [ ] Check request()->input('note') is available

### Service Call Test
Add debugging to ActivityLogService:
```php
public static function logCreate($model, $note = null) {
 Log::info('logCreate called', ['model' => get_class($model), 'note' => $note]);
 // ...
}
```
Then check logs:
```bash
tail -f storage/logs/laravel.log
```
- [ ] Verify log message appears

### Database Insert Test
Check if logs are actually inserted:
```sql
SELECT COUNT(*) FROM activity_logs;
SELECT * FROM activity_logs ORDER BY id DESC LIMIT 1;
```
- [ ] Count increases after actions
- [ ] Latest log has correct data

---

## Data Verification

### Check Log Structure
```sql
SELECT * FROM activity_logs LIMIT 1 \G
```
- [ ] All columns populated correctly
- [ ] action is SET (CREATE, UPDATE, DELETE)
- [ ] model_type is full class name
- [ ] model_id is string
- [ ] old_values is JSON (for updates)
- [ ] new_values is JSON
- [ ] note is text or null
- [ ] user_name is populated
- [ ] user_role is populated
- [ ] ip_address is populated
- [ ] timestamps are correct

### Check JSON Format
```sql
SELECT JSON_PRETTY(new_values) FROM activity_logs LIMIT 1;
```
- [ ] JSON is valid
- [ ] Contains model attributes
- [ ] Can be parsed

### Check Note Storage
```sql
SELECT note FROM activity_logs WHERE note IS NOT NULL LIMIT 1;
```
- [ ] Note text is intact
- [ ] No HTML tags present
- [ ] Character encoding is correct

---

## Performance Testing

### Database Indexes
```sql
EXPLAIN SELECT * FROM activity_logs
WHERE model_type = 'App\\Models\\InventoryItem'
AND model_id = '5';
```
- [ ] Uses index (check "key" column)
- [ ] Rows examined is small

```sql
EXPLAIN SELECT * FROM activity_logs
WHERE action = 'UPDATE'
ORDER BY created_at DESC
LIMIT 20;
```
- [ ] Uses created_at index

### Query Performance
Time these queries:
```sql
SELECT COUNT(*) FROM activity_logs;
SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 20;
SELECT * FROM activity_logs WHERE action = 'CREATE' LIMIT 100;
```
- [ ] All complete in < 100ms with reasonable data
- [ ] Pagination doesn't slow down

### Large Data Test
Insert 100k rows:
```php
for ($i = 0; $i < 100000; $i++) {
 ActivityLog::create([...]);
}
```
- [ ] Insert completes
- [ ] Queries still fast
- [ ] Pagination still works

---

## Documentation Verification

- [ ] `ACTIVITY_LOG_README.md` exists
- [ ] `SETUP_GUIDE.md` exists
- [ ] `ACTIVITY_LOG_DOCUMENTATION.md` exists
- [ ] `IMPLEMENTATION_SUMMARY.md` exists
- [ ] `SQL_QUERIES_REFERENCE.md` exists
- [ ] `API_REFERENCE.md` exists
- [ ] All docs are readable
- [ ] All code examples are correct
- [ ] All instructions are clear

---

## Pre-Production Checklist

### Code Quality
- [ ] No PHP syntax errors: `php artisan tinker`
- [ ] No undefined variables
- [ ] No hardcoded credentials
- [ ] Comments explain complex logic
- [ ] Code follows PSR-12 standard
- [ ] No console.log in JS
- [ ] No dd() or dump() in production code

### Database
- [ ] Migration tested and verified
- [ ] Backup created
 ```bash
 mysqldump -u user -p db_name activity_logs > backup.sql
 ```
- [ ] Can rollback if needed
- [ ] No migration conflicts

### Authorization
- [ ] All endpoints check authorization
- [ ] Policy is properly implemented
- [ ] Regular users cannot access logs
- [ ] Admin/supervisor can access logs
- [ ] Read-only enforcement verified

### Error Handling
- [ ] No 500 errors on dashboard
- [ ] Graceful handling of empty results
- [ ] Modal errors are handled
- [ ] Export errors are handled

### Performance
- [ ] Page load time < 2 seconds
- [ ] Pagination loads quickly
- [ ] Search performs well
- [ ] Export doesn't timeout

---

## Deployment Steps

1. [ ] Backup production database
2. [ ] Review all changes
3. [ ] Run migration on staging first
4. [ ] Test on staging environment
5. [ ] Get team approval
6. [ ] Deploy to production
7. [ ] Run migration: `php artisan migrate`
8. [ ] Clear cache: `php artisan cache:clear`
9. [ ] Test critical flows
10. [ ] Monitor logs for errors

---

## Post-Launch Monitoring

### Daily
- [ ] Check for errors in logs
- [ ] Verify activity logs are being created
- [ ] Monitor database size growth

### Weekly
- [ ] Review activity for anomalies
- [ ] Check authorization is working
- [ ] Verify no security issues

### Monthly
- [ ] Analyze activity trends
- [ ] Plan archival if needed
- [ ] Review and optimize queries

---

## Troubleshooting Guide

### Problem: Logs not appearing
**Solution:**
1. Check observer is registered
2. Check model has correct table name
3. Check user is logged in
4. Run: `php artisan tinker` to test manually

### Problem: Note field not appearing
**Solution:**
1. Check form has textarea with name="note"
2. Check controller validates note
3. Clear view cache: `php artisan view:clear`
4. Check browser cache

### Problem: Can't access activity log page
**Solution:**
1. Check user role: `SELECT role FROM users WHERE id = ?`
2. Check policy is registered
3. Check route exists: `php artisan route:list | grep activity`

### Problem: Export not working
**Solution:**
1. Check file permissions on storage
2. Check memory limit: `php memory_limit` in php.ini
3. Test manually in tinker

### Problem: Old values are empty
**Solution:**
1. Check `updating()` hook is called
2. Check `_oldValues` is stored
3. Check `getOriginal()` method works
4. Run `dd()` in observer to debug

---

## Sign Off

- [ ] All items checked
- [ ] All tests pass
- [ ] Documentation complete
- [ ] Ready for production
- [ ] Team aware of changes

**Checked by:** _______________
**Date:** _______________
**Status:** READY FOR PRODUCTION

---

**Last Updated:** January 5, 2025
**Version:** 1.0
**Framework:** Laravel 11+

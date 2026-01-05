# Activity Log - SQL Query Examples

Kumpulan SQL queries berguna untuk monitoring dan audit kegiatan.

---

## Basic Statistics

### Total Activity Count
```sql
SELECT COUNT(*) as total_activities FROM activity_logs;
```

### Activity by Action Type
```sql
SELECT action, COUNT(*) as count
FROM activity_logs
GROUP BY action
ORDER BY count DESC;
```

### Activity by Model Type
```sql
SELECT
 SUBSTRING_INDEX(model_type, '\\', -1) as model_name,
 COUNT(*) as count
FROM activity_logs
GROUP BY model_type
ORDER BY count DESC;
```

### Most Recent Activities (Last 10)
```sql
SELECT
 id,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 model_id,
 user_name,
 note,
 created_at
FROM activity_logs
ORDER BY created_at DESC
LIMIT 10;
```

---

## User Activity

### Activities by Specific User
```sql
SELECT
 action,
 COUNT(*) as count
FROM activity_logs
WHERE user_name = 'John Doe'
GROUP BY action;
```

### User Activity Timeline
```sql
SELECT
 created_at,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 model_id,
 note
FROM activity_logs
WHERE user_id = 1
ORDER BY created_at DESC
LIMIT 50;
```

### Most Active Users
```sql
SELECT
 user_name,
 user_role,
 COUNT(*) as activity_count,
 MAX(created_at) as last_activity
FROM activity_logs
WHERE user_name IS NOT NULL
GROUP BY user_id
ORDER BY activity_count DESC
LIMIT 10;
```

### All Activities by Role
```sql
SELECT
 user_role,
 action,
 COUNT(*) as count
FROM activity_logs
WHERE user_role IS NOT NULL
GROUP BY user_role, action;
```

---

## Specific Model Tracking

### All Changes to Specific Item (e.g., Item ID 5)
```sql
SELECT
 id,
 action,
 user_name,
 old_values,
 new_values,
 note,
 created_at
FROM activity_logs
WHERE model_type = 'App\\Models\\InventoryItem'
 AND model_id = '5'
ORDER BY created_at DESC;
```

### All Units for Specific Item
```sql
SELECT
 action,
 model_id,
 user_name,
 note,
 created_at
FROM activity_logs
WHERE model_type = 'App\\Models\\InventoryUnit'
 AND old_values LIKE '%"inventory_item_id":"5"%'
 OR new_values LIKE '%"inventory_item_id":"5"%'
ORDER BY created_at DESC;
```

### All Deletions
```sql
SELECT
 id,
 model_type,
 model_id,
 old_values,
 user_name,
 note,
 created_at
FROM activity_logs
WHERE action = 'DELETE'
ORDER BY created_at DESC;
```

### All New Items/Units Created
```sql
SELECT
 model_type,
 model_id,
 user_name,
 new_values,
 note,
 created_at
FROM activity_logs
WHERE action = 'CREATE'
ORDER BY created_at DESC;
```

---

## Note-Based Queries

### Activities With Notes
```sql
SELECT
 id,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 user_name,
 note,
 created_at
FROM activity_logs
WHERE note IS NOT NULL
 AND note != ''
ORDER BY created_at DESC;
```

### Search for Specific Keyword in Notes
```sql
SELECT
 id,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 user_name,
 note,
 created_at
FROM activity_logs
WHERE note LIKE '%rusak%'
ORDER BY created_at DESC;
```

### Notes Containing "rusak" (damaged)
```sql
SELECT
 created_at,
 user_name,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 model_id,
 note
FROM activity_logs
WHERE note LIKE '%rusak%'
ORDER BY created_at DESC;
```

### Count Activities by Note Content (Status/Condition Changes)
```sql
SELECT
 note,
 COUNT(*) as count
FROM activity_logs
WHERE note IS NOT NULL
GROUP BY note
ORDER BY count DESC
LIMIT 20;
```

---

## Security & Audit

### Activities from Specific IP Address
```sql
SELECT
 created_at,
 user_name,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 model_id,
 user_agent
FROM activity_logs
WHERE ip_address = '192.168.1.100'
ORDER BY created_at DESC;
```

### All Deletions in Last 24 Hours
```sql
SELECT
 id,
 created_at,
 user_name,
 model_type,
 model_id,
 old_values,
 ip_address
FROM activity_logs
WHERE action = 'DELETE'
 AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
ORDER BY created_at DESC;
```

### Activities During Office Hours (Anomaly Detection)
```sql
SELECT
 created_at,
 user_name,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 note
FROM activity_logs
WHERE HOUR(created_at) NOT BETWEEN 8 AND 17
 AND DAYOFWEEK(created_at) BETWEEN 2 AND 6 -- Monday to Friday
ORDER BY created_at DESC;
```

### Bulk Deletion Detection
```sql
SELECT
 DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:00') as time_group,
 user_name,
 COUNT(*) as deletion_count
FROM activity_logs
WHERE action = 'DELETE'
GROUP BY DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:00'), user_id
HAVING deletion_count > 5
ORDER BY time_group DESC;
```

---

## Time-Based Reports

### Activity by Date
```sql
SELECT
 DATE(created_at) as date,
 action,
 COUNT(*) as count
FROM activity_logs
GROUP BY DATE(created_at), action
ORDER BY date DESC;
```

### Hourly Activity Distribution
```sql
SELECT
 HOUR(created_at) as hour,
 COUNT(*) as activity_count
FROM activity_logs
GROUP BY HOUR(created_at)
ORDER BY hour;
```

### Activity Last 7 Days
```sql
SELECT
 DATE(created_at) as date,
 COUNT(*) as total_activities,
 SUM(CASE WHEN action = 'CREATE' THEN 1 ELSE 0 END) as creates,
 SUM(CASE WHEN action = 'UPDATE' THEN 1 ELSE 0 END) as updates,
 SUM(CASE WHEN action = 'DELETE' THEN 1 ELSE 0 END) as deletes
FROM activity_logs
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
GROUP BY DATE(created_at)
ORDER BY date DESC;
```

### Monthly Summary
```sql
SELECT
 YEAR(created_at) as year,
 MONTH(created_at) as month,
 action,
 COUNT(*) as count
FROM activity_logs
GROUP BY YEAR(created_at), MONTH(created_at), action
ORDER BY year DESC, month DESC;
```

---

## Maintenance Queries

### Data Older Than 6 Months
```sql
SELECT COUNT(*) as old_records
FROM activity_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### Archive Logs Older Than 6 Months (BACKUP FIRST!)
```sql
-- First backup
INSERT INTO activity_logs_archive
SELECT * FROM activity_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);

-- Then delete
DELETE FROM activity_logs
WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### Database Size
```sql
SELECT
 ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
FROM information_schema.TABLES
WHERE table_name = 'activity_logs';
```

### Analyze Table Performance
```sql
ANALYZE TABLE activity_logs;
OPTIMIZE TABLE activity_logs;
```

---

## Advanced Queries

### Find Suspicious Activity (Multiple Changes in Short Time)
```sql
SELECT
 user_id,
 user_name,
 COUNT(*) as rapid_changes,
 MIN(created_at) as first_change,
 MAX(created_at) as last_change,
 TIMEDIFF(MAX(created_at), MIN(created_at)) as duration
FROM activity_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY user_id
HAVING COUNT(*) > 10
ORDER BY rapid_changes DESC;
```

### Track Specific Field Changes (e.g., condition_status)
```sql
SELECT
 id,
 action,
 model_id,
 JSON_EXTRACT(old_values, '$.condition_status') as old_status,
 JSON_EXTRACT(new_values, '$.condition_status') as new_status,
 note,
 created_at,
 user_name
FROM activity_logs
WHERE JSON_EXTRACT(old_values, '$.condition_status') IS NOT NULL
 OR JSON_EXTRACT(new_values, '$.condition_status') IS NOT NULL
ORDER BY created_at DESC;
```

### Find All Changes to Specific Field
```sql
SELECT
 model_id,
 JSON_EXTRACT(old_values, '$.current_holder') as old_holder,
 JSON_EXTRACT(new_values, '$.current_holder') as new_holder,
 user_name,
 note,
 created_at
FROM activity_logs
WHERE action = 'UPDATE'
 AND (JSON_EXTRACT(old_values, '$.current_holder') != JSON_EXTRACT(new_values, '$.current_holder')
 OR (JSON_EXTRACT(old_values, '$.current_holder') IS NOT NULL
 AND JSON_EXTRACT(new_values, '$.current_holder') IS NULL))
ORDER BY created_at DESC;
```

### Compare Values Before & After
```sql
SELECT
 id,
 user_name,
 JSON_PRETTY(old_values) as before,
 JSON_PRETTY(new_values) as after,
 note
FROM activity_logs
WHERE id = 123; -- Replace with log ID
```

---

## Report Queries

### Daily Activity Report
```sql
SELECT
 DATE(created_at) as date,
 COUNT(*) as total_actions,
 COUNT(DISTINCT user_id) as unique_users,
 COUNT(DISTINCT model_id) as affected_items,
 SUM(CASE WHEN note IS NOT NULL THEN 1 ELSE 0 END) as documented_actions
FROM activity_logs
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(created_at)
ORDER BY date DESC;
```

### User Compliance Report (Who Uses Notes)
```sql
SELECT
 user_name,
 COUNT(*) as total_actions,
 SUM(CASE WHEN note IS NOT NULL THEN 1 ELSE 0 END) as documented,
 ROUND(SUM(CASE WHEN note IS NOT NULL THEN 1 ELSE 0 END) / COUNT(*) * 100, 2) as documentation_rate
FROM activity_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
GROUP BY user_id
ORDER BY documentation_rate DESC;
```

### Model Activity Summary
```sql
SELECT
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 COUNT(*) as total_operations,
 SUM(CASE WHEN action = 'CREATE' THEN 1 ELSE 0 END) as creates,
 SUM(CASE WHEN action = 'UPDATE' THEN 1 ELSE 0 END) as updates,
 SUM(CASE WHEN action = 'DELETE' THEN 1 ELSE 0 END) as deletes,
 MAX(created_at) as last_activity
FROM activity_logs
GROUP BY model_type
ORDER BY total_operations DESC;
```

---

## Alert-Worthy Queries

### Detect Potential Data Loss (Multiple Deletes)
```sql
SELECT
 DATE(created_at) as date,
 user_name,
 COUNT(*) as delete_count,
 GROUP_CONCAT(model_id) as deleted_ids
FROM activity_logs
WHERE action = 'DELETE'
 AND created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY DATE(created_at), user_id
HAVING COUNT(*) > 3
ORDER BY delete_count DESC;
```

### Unusual Activity Patterns
```sql
SELECT
 user_name,
 action,
 COUNT(*) as count,
 GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(model_type, '\\', -1)) as models_affected,
 TIME_FORMAT(MAX(created_at), '%H:%i') as last_activity_time
FROM activity_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
GROUP BY user_id, action
HAVING COUNT(*) > 20
ORDER BY count DESC;
```

---

## Export-Friendly Queries

### Generate CSV-Ready Data
```sql
SELECT
 id,
 DATE_FORMAT(created_at, '%Y-%m-%d %H:%i:%s') as timestamp,
 action,
 SUBSTRING_INDEX(model_type, '\\', -1) as model,
 model_id,
 user_name,
 user_role,
 ip_address,
 note
FROM activity_logs
WHERE created_at >= '2025-01-01'
ORDER BY created_at DESC;
```

### Audit Report (Ready for PDF Export)
```sql
SELECT
 DATE(created_at) as date,
 user_name,
 user_role,
 COUNT(*) as total_actions,
 GROUP_CONCAT(DISTINCT action) as actions_performed,
 COUNT(DISTINCT model_id) as items_affected
FROM activity_logs
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
GROUP BY DATE(created_at), user_id
ORDER BY date DESC, total_actions DESC;
```

---

## Important Notes

1. **Always backup before executing DELETE queries**
 ```sql
 mysqldump -u user -p database_name activity_logs > backup.sql
 ```

2. **JSON extraction syntax varies by MySQL version**
 - MySQL 5.7+: `JSON_EXTRACT()`, `JSON_PRETTY()`
 - Use `JSON_UNQUOTE()` for clean output

3. **Performance considerations**
 - Add indexes: `CREATE INDEX idx_user_id ON activity_logs(user_id)`
 - Add indexes: `CREATE INDEX idx_created_at ON activity_logs(created_at)`
 - Already included in migration

4. **Escape backslashes in model_type**
 - In queries: `'App\\Models\\InventoryItem'`
 - In LIKE: `LIKE '%InventoryItem%'`

5. **Large result sets**
 - Use `LIMIT` to avoid memory issues
 - Use `DATE()` or `DATE_FORMAT()` for grouping

---

**Last Updated:** January 5, 2025
**MySQL Version:** 5.7+
**Format:** UTF-8 compatible

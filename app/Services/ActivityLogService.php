<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function logCreate(Model $model, ?string $note = null)
    {
        return self::log(
            action: 'CREATE',
            model: $model,
            newValues: $model->getAttributes(),
            oldValues: [],
            description: "Created " . class_basename($model) . " ({$model->getKey()})",
            note: $note
        );
    }

    public static function logUpdate(Model $model, array $oldValues, ?string $note = null)
    {
        $newValues = $model->getAttributes();
        $changes = [];

        foreach ($newValues as $key => $newValue) {
            if (isset($oldValues[$key]) && $oldValues[$key] !== $newValue) {
                $changes[$key] = $newValue;
            }
        }

        return self::log(
            action: 'UPDATE',
            model: $model,
            newValues: $changes,
            oldValues: $oldValues,
            description: "Updated " . class_basename($model) . " ({$model->getKey()})",
            note: $note
        );
    }

    public static function logDelete(Model $model, ?string $note = null)
    {
        return self::log(
            action: 'DELETE',
            model: $model,
            newValues: [],
            oldValues: $model->getAttributes(),
            description: "Deleted " . class_basename($model) . " ({$model->getKey()})",
            note: $note
        );
    }

    private static function log(
        string $action,
        Model $model,
        array $newValues = [],
        array $oldValues = [],
        ?string $description = null,
        ?string $note = null
    ) {
        try {
            $user = Auth::user();

            $note = $note ? self::sanitizeNote($note) : null;

            return ActivityLog::create([
                'action' => $action,
                'model_type' => get_class($model),
                'model_id' => (string) $model->getKey(),
                'description' => $description,
                'old_values' => !empty($oldValues) ? $oldValues : null,
                'new_values' => !empty($newValues) ? $newValues : null,
                'note' => $note,
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'user_role' => $user?->role ?? 'Unknown',
                'ip_address' => self::getClientIp(),
                'user_agent' => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to log activity', [
                'error' => $e->getMessage(),
                'model' => get_class($model),
            ]);

            return null;
        }
    }

    private static function sanitizeNote(?string $note): ?string
    {
        if (!$note) {
            return null;
        }

        return strip_tags(trim($note));
    }

    private static function getClientIp(): ?string
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    public static function getModelLogs($modelType, $modelId = null, $limit = 50)
    {
        $query = ActivityLog::byModel($modelType, $modelId);
        return $query->latest()->paginate($limit);
    }

    public static function getUserLogs($userId, $limit = 50)
    {
        return ActivityLog::byUser($userId)
            ->latest()
            ->paginate($limit);
    }
}

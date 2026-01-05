<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Model
{
    use HasFactory;
    protected $table = 'activity_logs';

    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'note',
        'user_id',
        'user_name',
        'user_role',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'json',
        'new_values' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get user associated with this activity log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter by model type
     */
    public function scopeByModel(Builder $query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query;
    }

    /**
     * Scope: Filter by action
     */
    public function scopeByAction(Builder $query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Filter by user
     */
    public function scopeByUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filter by date range
     */
    public function scopeByDateRange(Builder $query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Search in description and note
     */
    public function scopeSearch(Builder $query, $searchTerm)
    {
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('description', 'like', "%{$searchTerm}%")
              ->orWhere('note', 'like', "%{$searchTerm}%")
              ->orWhere('user_name', 'like', "%{$searchTerm}%");
        });
    }

    /**
     * Get the attribute change description
     */
    public function getChangeDescription()
    {
        if (!$this->old_values || !$this->new_values) {
            return null;
        }

        $changes = [];
        foreach ($this->new_values as $key => $newValue) {
            if (isset($this->old_values[$key]) && $this->old_values[$key] !== $newValue) {
                $changes[] = "{$key}: {$this->old_values[$key]} → {$newValue}";
            }
        }

        return count($changes) > 0 ? implode(', ', $changes) : null;
    }
}

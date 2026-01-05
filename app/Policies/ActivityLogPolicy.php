<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityLog;

class ActivityLogPolicy
{
    /**
     * Determine whether the user can view any activity logs.
     * Only admin can view
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view the activity log.
     */
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Activity logs cannot be modified (read-only)
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }

    public function delete(User $user, ActivityLog $activityLog): bool
    {
        return false;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ActivityLog;

class ActivityLogPolicy
{
    /**
     * Determine whether the user can view any activity logs.
     * Only admin/supervisor can view
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
    }

    /**
     * Determine whether the user can view the activity log.
     */
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return in_array($user->role, ['admin', 'supervisor']);
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

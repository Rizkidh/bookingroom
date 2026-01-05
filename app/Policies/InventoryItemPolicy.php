<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryItem;
use Illuminate\Auth\Access\Response;

class InventoryItemPolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        
        return null;
    }
    
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, InventoryItem $inventory)
    {
        return true;
    }

    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'pegawai']);
    }

    public function update(User $user, InventoryItem $inventory)
    {
        return in_array($user->role, ['admin', 'pegawai']);
    }

    public function delete(User $user, InventoryItem $inventory)
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Role Pegawai tidak memiliki izin untuk menghapus data inventaris.');
    }
}
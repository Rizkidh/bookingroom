<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryUnit;
use Illuminate\Auth\Access\Response;

class InventoryUnitPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null; 
    }
    
    public function view(User $user, InventoryUnit $unit): Response
    {
        return in_array($user->role, ['admin', 'pegawai'])
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat detail unit.');
    }

    public function create(User $user): Response
    {
        return in_array($user->role, ['admin', 'pegawai'])
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat unit baru.');
    }

    public function update(User $user, InventoryUnit $unit): Response
    {
        return in_array($user->role, ['admin', 'pegawai'])
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengedit unit.');
    }

    public function delete(User $user, InventoryUnit $unit): Response
    {
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Role Pegawai tidak memiliki izin untuk menghapus unit inventaris.');
    }
}
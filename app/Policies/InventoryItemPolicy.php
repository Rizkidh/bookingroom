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
            return true; // Beri akses penuh (Super Admin)
        }
        
        return null; // Lanjutkan ke pengecekan method spesifik (delete/update, dll.)
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
        // Perbaikan menggunakan Response::allow/deny untuk pesan yang lebih jelas
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Role Pegawai tidak memiliki izin untuk menghapus data inventaris.');
    }
}
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InventoryUnit;
use Illuminate\Auth\Access\Response;

class InventoryUnitPolicy
{
    /**
     * Berikan izin penuh kepada user dengan role 'admin' (Super Admin).
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null; 
    }
    
    /**
     * Tentukan apakah user bisa melihat unit (view).
     * Diizinkan untuk Admin (via before) dan Pegawai.
     */
    public function view(User $user, InventoryUnit $unit): Response|bool
    {
        return $user->role === 'pegawai'
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk melihat detail unit.');
    }

    /**
     * Tentukan apakah user bisa membuat unit baru (create).
     * Diizinkan untuk Admin (via before) dan Pegawai.
     */
    public function create(User $user): Response|bool
    {
        return $user->role === 'pegawai'
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk membuat unit baru.');
    }

    /**
     * Tentukan apakah user bisa mengedit/memperbarui unit (update).
     * Diizinkan untuk Admin (via before) dan Pegawai.
     */
    public function update(User $user, InventoryUnit $unit): Response|bool
    {
        return $user->role === 'pegawai'
            ? Response::allow()
            : Response::deny('Anda tidak memiliki izin untuk mengedit unit.');
    }

    /**
     * Tentukan apakah user bisa menghapus unit (delete).
     * HANYA diizinkan untuk Admin (Pegawai diblokir).
     */
    public function delete(User $user, InventoryUnit $unit): Response|bool
    {
        // Hanya Admin yang bisa delete. Jika Pegawai, otomatis deny.
        return $user->role === 'admin'
            ? Response::allow()
            : Response::deny('Role Pegawai tidak memiliki izin untuk menghapus unit inventaris.');
    }
}
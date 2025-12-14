<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Definisikan Permissions
        $permissions = [
            'create inventory',
            'edit inventory',
            'delete inventory',
            'view inventory',
            
            // Tambahkan permission untuk Unit Satuan jika diperlukan
            'create unit',
            'delete unit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. Definisikan Roles
        
        // Role 1: ADMIN (Full Access)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->givePermissionTo(Permission::all()); // Beri semua permission

        // Role 2: PELAKSANA (Create & Edit ONLY)
        $rolePelaksana = Role::firstOrCreate(['name' => 'pelaksana']);
        $rolePelaksana->givePermissionTo([
            'create inventory',
            'edit inventory',
            'view inventory',
            'create unit',
            // TIDAK mendapatkan 'delete inventory' dan 'delete unit'
        ]);

        // Opsional: Berikan role Pelaksana kepada user ID 1 (atau user tertentu)
        // $user = \App\Models\User::find(1);
        // if ($user) {
        //     $user->assignRole($rolePelaksana);
        // }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // 1. Create roles
        $adminRole = Role::create(['name' => 'superadmin', 'guard_name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'admin']);
        $cashierRole = Role::create(['name' => 'cashier', 'guard_name' => 'admin']);

        // 2. Create permissions
        $permissions = [
            'view products',
            'add products',
            'edit products',
            'view sales',
            'process sales',
            'manage admin',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        // 3. Assign permissions to rolesj
        $adminRole->givePermissionTo(Permission::all()); // admin gets all
        $managerRole->givePermissionTo(['view products', 'add products', 'view sales']);
        $cashierRole->givePermissionTo(['view products', 'view sales']);
    }
}

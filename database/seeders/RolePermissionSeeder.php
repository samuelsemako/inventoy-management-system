<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{

    public function run(): void
    {
        $permission = [
            'manage products',
            'manage sales',
            'manage staffs',
            'manage customers',
            'manage categories',
        ];

        foreach ($permission as $permission) {
            Permission::firstorcreate(['name' => $permission, 'guard_name' => 'admin']);
        }
    }
}

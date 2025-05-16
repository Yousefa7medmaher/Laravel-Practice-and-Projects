<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles if they don't exist
        $roles = ['admin', 'employee', 'customer'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create permissions if they don't exist
        $permissions = [
            'manage-employees',
            'manage-products',
            'manage-customer-credits'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to admin role
        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions([
            'manage-employees',
            'manage-products',
            'manage-customer-credits'
        ]);

        // Assign permissions to employee role
        $employeeRole = Role::findByName('employee');
        $employeeRole->syncPermissions([
            'manage-products',
            'manage-customer-credits'
        ]);
    }
} 
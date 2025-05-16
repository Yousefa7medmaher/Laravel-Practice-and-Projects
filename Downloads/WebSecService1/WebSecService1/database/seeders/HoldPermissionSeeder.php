<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HoldPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create hold_products permission if it doesn't exist
        if (!Permission::where('name', 'hold_products')->exists()) {
            Permission::create(['name' => 'hold_products']);
        }

        // Get the permission
        $holdPermission = Permission::where('name', 'hold_products')->first();

        // Get admin and employee roles
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        // Assign permission to both roles
        $adminRole->givePermissionTo($holdPermission);
        $employeeRole->givePermissionTo($holdPermission);
    }
} 
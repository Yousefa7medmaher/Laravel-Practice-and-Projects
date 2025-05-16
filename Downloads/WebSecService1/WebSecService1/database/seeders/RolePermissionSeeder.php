<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'admin' => 'Admin',
            'driver' => 'Driver',
            'customer' => 'Customer'
        ];

        foreach ($roles as $key => $name) {
            Role::firstOrCreate(['name' => $key]);
        }

        // Create permissions
        $permissions = [
            // Admin permissions
            'manage-users' => 'Manage Users',
            'manage-products' => 'Manage Products',
            'manage-orders' => 'Manage Orders',
            'manage-drivers' => 'Manage Drivers',
            'view-reports' => 'View Reports',
            'manage-settings' => 'Manage Settings',

            // Driver permissions
            'view-assigned-orders' => 'View Assigned Orders',
            'update-order-status' => 'Update Order Status',
            'view-delivery-history' => 'View Delivery History',
            'update-availability' => 'Update Availability',

            // Customer permissions
            'place-orders' => 'Place Orders',
            'view-own-orders' => 'View Own Orders',
            'track-orders' => 'Track Orders',
            'rate-orders' => 'Rate Orders'
        ];

        foreach ($permissions as $key => $name) {
            Permission::firstOrCreate(['name' => $key]);
        }

        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $driverRole = Role::findByName('driver');
        $customerRole = Role::findByName('customer');

        // Admin gets all permissions
        $adminRole->syncPermissions(array_keys($permissions));

        // Driver permissions
        $driverRole->syncPermissions([
            'view-assigned-orders',
            'update-order-status',
            'view-delivery-history',
            'update-availability'
        ]);

        // Customer permissions
        $customerRole->syncPermissions([
            'place-orders',
            'view-own-orders',
            'track-orders',
            'rate-orders'
        ]);
    }
} 
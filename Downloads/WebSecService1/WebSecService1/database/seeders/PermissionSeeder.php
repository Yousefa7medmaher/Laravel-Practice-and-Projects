<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use App\Models\Employee;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'manage-products']);
        Permission::create(['name' => 'manage-customer-credits']);

        // Create hold_products permission if it doesn't exist
        if (!Permission::where('name', 'hold_products')->exists()) {
            Permission::create(['name' => 'hold_products']);
        }

        // Give permission to employees
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $employee->givePermissionTo('hold_products');
        }
    }
} 
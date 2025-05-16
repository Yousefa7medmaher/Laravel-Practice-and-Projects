<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            $admin->assignRole('admin');
            $this->command->info('Admin role assigned successfully.');
        } else {
            $this->command->error('Admin user not found.');
        }
    }
} 
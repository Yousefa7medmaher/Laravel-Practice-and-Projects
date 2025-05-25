<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(10)->create([
            'role' => 'student',
        ]);

        User::factory()->count(5)->create([
            'role' => 'instructor',
        ]);


        User::factory()->count(3)->create([
            'role' => 'manager',
        ]);
    }
}

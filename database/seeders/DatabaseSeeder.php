<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student',
        ]);

        // Create a test instructor
        User::factory()->create([
            'name' => 'Test Instructor',
            'email' => 'instructor@example.com',
            'role' => 'instructor',
        ]);

        // Create a test manager
        User::factory()->create([
            'name' => 'Test Manager',
            'email' => 'manager@example.com',
            'role' => 'manager',
        ]);

        // Run the UserSeeder to create additional users
        $this->call(UserSeeder::class);

        // Run the CourseSeeder to create courses and related content
        $this->call(CourseSeeder::class);
    }
}

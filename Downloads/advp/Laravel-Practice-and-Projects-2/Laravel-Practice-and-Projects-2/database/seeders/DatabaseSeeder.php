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

        // Run the UserSeeder to create additional users
        $this->call(UserSeeder::class);

        // Run the SimpleCourseSeeder to create courses
        $this->call(SimpleCourseSeeder::class);

        // Run the StudentTestDataSeeder to create test data for student testing
        $this->call(StudentTestDataSeeder::class);
    }
}

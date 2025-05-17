<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimpleCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an instructor user if none exists
        $instructor = User::where('role', 'instructor')->first();
        
        if (!$instructor) {
            $instructor = User::create([
                'name' => 'Dr. John Smith',
                'email' => 'instructor@example.com',
                'password' => Hash::make('password123'),
                'role' => 'instructor',
            ]);
        }

        // Create sample courses
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. This course covers the basics of creating responsive websites and web applications.',
                'code' => 'WEB101',
                'instructor_id' => $instructor->id,
                'status' => 'active',
                'credit_hours' => 3,
            ],
            [
                'title' => 'Advanced JavaScript Programming',
                'description' => 'Dive deep into JavaScript programming with advanced concepts like closures, prototypes, async/await, and modern ES6+ features. Build complex applications with JavaScript frameworks.',
                'code' => 'JS201',
                'instructor_id' => $instructor->id,
                'status' => 'active',
                'credit_hours' => 4,
            ],
            [
                'title' => 'Database Design and SQL',
                'description' => 'Master database design principles and SQL query language. Learn how to create efficient database schemas, write complex queries, and optimize database performance.',
                'code' => 'DB101',
                'instructor_id' => $instructor->id,
                'status' => 'active',
                'credit_hours' => 3,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['code' => $course['code']],
                $course
            );
        }
    }
}

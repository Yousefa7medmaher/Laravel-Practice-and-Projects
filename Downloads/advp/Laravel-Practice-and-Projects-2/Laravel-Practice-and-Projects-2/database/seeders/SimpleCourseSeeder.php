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

        // Create sample courses (without instructor_id since it was removed)
        $courses = [
            [
                'title' => 'Introduction to Web Development',
                'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. This course covers the basics of creating responsive websites and web applications.',
                'code' => 'WEB101',
                'status' => 'active',
                'credit_hours' => 3,
            ],
            [
                'title' => 'Advanced JavaScript Programming',
                'description' => 'Dive deep into JavaScript programming with advanced concepts like closures, prototypes, async/await, and modern ES6+ features. Build complex applications with JavaScript frameworks.',
                'code' => 'JS201',
                'status' => 'active',
                'credit_hours' => 4,
            ],
            [
                'title' => 'Database Design and SQL',
                'description' => 'Master database design principles and SQL query language. Learn how to create efficient database schemas, write complex queries, and optimize database performance.',
                'code' => 'DB101',
                'status' => 'active',
                'credit_hours' => 3,
            ],
            [
                'title' => 'Python Programming Fundamentals',
                'description' => 'Start your programming journey with Python. Learn variables, data types, control structures, functions, and object-oriented programming concepts.',
                'code' => 'PY101',
                'status' => 'active',
                'credit_hours' => 3,
            ],
            [
                'title' => 'Data Structures and Algorithms',
                'description' => 'Master fundamental data structures and algorithms essential for computer science and software development. Includes arrays, linked lists, trees, graphs, and sorting algorithms.',
                'code' => 'CS201',
                'status' => 'active',
                'credit_hours' => 4,
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Learn to build mobile applications for iOS and Android using modern frameworks. Covers UI design, API integration, and app deployment.',
                'code' => 'MOB101',
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

<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Lecture;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Lab;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get instructors
        $instructors = User::where('role', 'instructor')->get();
        
        if ($instructors->isEmpty()) {
            // Create some instructors if none exist
            $instructors = User::factory()->count(3)->create([
                'role' => 'instructor',
            ]);
        }
        
        // Create courses
        foreach ($instructors as $instructor) {
            $courses = [];
            
            // Create 2 courses per instructor
            for ($i = 0; $i < 2; $i++) {
                $course = Course::create([
                    'title' => fake()->catchPhrase(),
                    'description' => fake()->paragraphs(3, true),
                    'code' => 'CS' . fake()->unique()->numberBetween(100, 999),
                    'instructor_id' => $instructor->id,
                    'status' => 'active',
                    'credit_hours' => fake()->numberBetween(1, 4),
                ]);
                
                $courses[] = $course;
                
                // Create lectures for each course
                for ($j = 0; $j < 5; $j++) {
                    Lecture::create([
                        'course_id' => $course->id,
                        'title' => 'Lecture ' . ($j + 1) . ': ' . fake()->sentence(),
                        'description' => fake()->paragraph(),
                        'content' => fake()->paragraphs(3, true),
                        'order' => $j,
                    ]);
                }
                
                // Create assignments for each course
                for ($j = 0; $j < 3; $j++) {
                    Assignment::create([
                        'course_id' => $course->id,
                        'title' => 'Assignment ' . ($j + 1),
                        'description' => fake()->paragraph(),
                        'instructions' => fake()->paragraphs(2, true),
                        'due_date' => now()->addDays(fake()->numberBetween(7, 30)),
                        'max_score' => fake()->numberBetween(50, 100),
                    ]);
                }
                
                // Create quizzes for each course
                for ($j = 0; $j < 2; $j++) {
                    $startTime = now()->addDays(fake()->numberBetween(5, 20));
                    Quiz::create([
                        'course_id' => $course->id,
                        'title' => 'Quiz ' . ($j + 1),
                        'description' => fake()->paragraph(),
                        'start_time' => $startTime,
                        'end_time' => (clone $startTime)->addHours(2),
                        'duration_minutes' => 60,
                        'max_score' => 100,
                        'is_published' => true,
                    ]);
                }
                
                // Create labs for each course
                for ($j = 0; $j < 2; $j++) {
                    Lab::create([
                        'course_id' => $course->id,
                        'title' => 'Lab ' . ($j + 1),
                        'description' => fake()->paragraph(),
                        'instructions' => fake()->paragraphs(2, true),
                        'due_date' => now()->addDays(fake()->numberBetween(10, 40)),
                        'max_score' => 100,
                    ]);
                }
            }
            
            // Get students
            $students = User::where('role', 'student')->inRandomOrder()->limit(10)->get();
            
            if ($students->isEmpty()) {
                // Create some students if none exist
                $students = User::factory()->count(10)->create([
                    'role' => 'student',
                ]);
            }
            
            // Enroll students in courses
            foreach ($courses as $course) {
                // Enroll 5-10 random students in each course
                $enrollCount = fake()->numberBetween(5, 10);
                $enrolledStudents = $students->random($enrollCount);
                
                foreach ($enrolledStudents as $student) {
                    $course->students()->attach($student->id, [
                        'status' => 'enrolled',
                        'enrolled_at' => now()->subDays(fake()->numberBetween(1, 30)),
                    ]);
                }
            }
        }
    }
}

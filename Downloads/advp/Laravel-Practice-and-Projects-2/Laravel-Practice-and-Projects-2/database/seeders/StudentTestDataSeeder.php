<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Lecture;
use App\Models\Lab;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific test student with known credentials
        $testStudent = User::updateOrCreate(
            ['email' => 'student@test.com'],
            [
                'name' => 'Test Student',
                'email' => 'student@test.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
            ]
        );

        // Get all available courses
        $courses = Course::all();

        if ($courses->count() > 0) {
            // Enroll the test student in the first 3 courses
            $coursesToEnroll = $courses->take(3);

            foreach ($coursesToEnroll as $course) {
                // Enroll student in course
                $testStudent->enrolledCourses()->syncWithoutDetaching([
                    $course->id => [
                        'status' => 'enrolled',
                        'enrolled_at' => Carbon::now()->subDays(rand(1, 30)),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                ]);

                // Create some lectures for this course
                $this->createLecturesForCourse($course);

                // Create some assignments for this course
                $this->createAssignmentsForCourse($course);

                // Create some quizzes for this course
                $this->createQuizzesForCourse($course);

                // Create some labs for this course
                $this->createLabsForCourse($course);
            }
        }
    }

    private function createLecturesForCourse($course)
    {
        $lectures = [
            [
                'title' => 'Introduction to ' . $course->title,
                'description' => 'Overview and introduction to the course topics',
                'content' => 'This lecture covers the basic concepts and overview of the course.',
                'order' => 1,
            ],
            [
                'title' => 'Fundamentals and Core Concepts',
                'description' => 'Deep dive into fundamental concepts',
                'content' => 'This lecture explores the core concepts in detail.',
                'order' => 2,
            ],
            [
                'title' => 'Practical Applications',
                'description' => 'Real-world applications and examples',
                'content' => 'This lecture demonstrates practical applications.',
                'order' => 3,
            ],
        ];

        foreach ($lectures as $lectureData) {
            Lecture::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $lectureData['title']
                ],
                array_merge($lectureData, ['course_id' => $course->id])
            );
        }
    }

    private function createAssignmentsForCourse($course)
    {
        $assignments = [
            [
                'title' => 'Assignment 1: Basic Concepts',
                'description' => 'Complete the basic concepts assignment for ' . $course->title,
                'instructions' => 'Follow the instructions provided in the course materials.',
                'due_date' => Carbon::now()->addDays(7),
                'max_score' => 100,
            ],
            [
                'title' => 'Assignment 2: Practical Exercise',
                'description' => 'Hands-on practical exercise for ' . $course->title,
                'instructions' => 'Apply the concepts learned in a practical scenario.',
                'due_date' => Carbon::now()->addDays(14),
                'max_score' => 150,
            ],
        ];

        foreach ($assignments as $assignmentData) {
            Assignment::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $assignmentData['title']
                ],
                array_merge($assignmentData, ['course_id' => $course->id])
            );
        }
    }

    private function createQuizzesForCourse($course)
    {
        $quizzes = [
            [
                'title' => 'Quiz 1: Knowledge Check',
                'description' => 'Test your understanding of basic concepts',
                'start_time' => Carbon::now()->addDays(3),
                'end_time' => Carbon::now()->addDays(5),
                'duration_minutes' => 30,
                'max_score' => 50,
                'is_published' => true,
            ],
            [
                'title' => 'Quiz 2: Advanced Topics',
                'description' => 'Assessment of advanced topic understanding',
                'start_time' => Carbon::now()->addDays(10),
                'end_time' => Carbon::now()->addDays(12),
                'duration_minutes' => 45,
                'max_score' => 75,
                'is_published' => true,
            ],
        ];

        foreach ($quizzes as $quizData) {
            Quiz::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $quizData['title']
                ],
                array_merge($quizData, ['course_id' => $course->id])
            );
        }
    }

    private function createLabsForCourse($course)
    {
        $labs = [
            [
                'title' => 'Lab 1: Hands-on Practice',
                'description' => 'Practical lab exercise for ' . $course->title,
                'instructions' => 'Complete the lab exercises as outlined in the lab manual.',
                'due_date' => Carbon::now()->addDays(21),
                'max_score' => 100,
            ],
        ];

        foreach ($labs as $labData) {
            Lab::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => $labData['title']
                ],
                array_merge($labData, ['course_id' => $course->id])
            );
        }
    }
}

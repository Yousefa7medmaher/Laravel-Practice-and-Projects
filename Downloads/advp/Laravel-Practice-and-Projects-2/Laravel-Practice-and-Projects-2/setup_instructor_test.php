<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Course;
use App\Models\CourseAssignment;

// Initialize Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Setting up instructor test data...\n";

// Create instructor if doesn't exist
$instructor = User::where('email', 'instructor@test.com')->first();
if (!$instructor) {
    $instructor = User::create([
        'name' => 'Test Instructor',
        'email' => 'instructor@test.com',
        'password' => bcrypt('password123'),
        'role' => 'instructor'
    ]);
    echo "✅ Created instructor: {$instructor->name}\n";
} else {
    echo "✅ Instructor already exists: {$instructor->name}\n";
}

// Get first course
$course = Course::first();
if (!$course) {
    echo "❌ No courses found in database\n";
    exit(1);
}

// Assign instructor to course
$assignment = CourseAssignment::where('course_id', $course->id)
    ->where('instructor_id', $instructor->id)
    ->first();

if (!$assignment) {
    // Get a manager to assign the instructor
    $manager = User::where('role', 'manager')->first();
    if (!$manager) {
        echo "❌ No manager found to assign instructor\n";
        exit(1);
    }

    CourseAssignment::create([
        'course_id' => $course->id,
        'instructor_id' => $instructor->id,
        'assigned_by' => $manager->id,
        'is_active' => true
    ]);
    echo "✅ Assigned instructor to course: {$course->title}\n";
} else {
    echo "✅ Instructor already assigned to course: {$course->title}\n";
}

echo "\n🎯 Test credentials:\n";
echo "   Email: instructor@test.com\n";
echo "   Password: password123\n";
echo "   Course ID: {$course->id}\n";
echo "   Course: {$course->title}\n";
echo "\n✅ Setup complete!\n";

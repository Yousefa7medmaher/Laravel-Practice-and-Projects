<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/courses', [CourseController::class, 'index']); // Public endpoint for listing courses
Route::get('/public/courses/{id}', [CourseController::class, 'show']); // Public endpoint for course details

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);  // Changed to POST for FormData
    Route::put('/profile', [AuthController::class, 'updateProfile']);  // Keep PUT for backward compatibility

    // Course routes - accessible by all authenticated users
    Route::get('/courses', [CourseController::class, 'index']);

    // Course routes - restricted by role
    Route::middleware('role:manager,instructor')->group(function () {
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    });

    // Course detail - accessible by all authenticated users
    Route::get('/courses/{id}', [CourseController::class, 'show']);

    // Lecture routes - accessible by all authenticated users
    Route::get('/courses/{courseId}/lectures', [LectureController::class, 'index']);
    Route::get('/courses/{courseId}/lectures/{id}', [LectureController::class, 'show']);

    // Lecture management - restricted by role
    Route::middleware('role:manager,instructor')->group(function () {
        Route::post('/courses/{courseId}/lectures', [LectureController::class, 'store']);
        Route::put('/courses/{courseId}/lectures/{id}', [LectureController::class, 'update']);
        Route::delete('/courses/{courseId}/lectures/{id}', [LectureController::class, 'destroy']);
    });

    // Assignment routes - accessible by all authenticated users
    Route::get('/courses/{courseId}/assignments', [AssignmentController::class, 'index']);
    Route::get('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'show']);

    // Assignment management - restricted by role
    Route::middleware('role:manager,instructor')->group(function () {
        Route::post('/courses/{courseId}/assignments', [AssignmentController::class, 'store']);
        Route::put('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'update']);
        Route::delete('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'destroy']);
    });

    // Quiz routes - accessible by all authenticated users
    Route::get('/courses/{courseId}/quizzes', [QuizController::class, 'index']);
    Route::get('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'show']);

    // Quiz management - restricted by role
    Route::middleware('role:manager,instructor')->group(function () {
        Route::post('/courses/{courseId}/quizzes', [QuizController::class, 'store']);
        Route::put('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'update']);
        Route::delete('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'destroy']);
    });

    // Lab routes - accessible by all authenticated users
    Route::get('/courses/{courseId}/labs', [LabController::class, 'index']);
    Route::get('/courses/{courseId}/labs/{id}', [LabController::class, 'show']);

    // Lab management - restricted by role
    Route::middleware('role:manager,instructor')->group(function () {
        Route::post('/courses/{courseId}/labs', [LabController::class, 'store']);
        Route::put('/courses/{courseId}/labs/{id}', [LabController::class, 'update']);
        Route::delete('/courses/{courseId}/labs/{id}', [LabController::class, 'destroy']);
    });

    // Enrollment routes - student operations
    Route::post('/courses/{courseId}/enroll', [EnrollmentController::class, 'enroll']);
    Route::post('/courses/{courseId}/unenroll', [EnrollmentController::class, 'unenroll']);
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);

    // Enrollment management - restricted by role
    Route::middleware('role:manager,instructor')->group(function () {
        Route::get('/courses/{courseId}/students', [EnrollmentController::class, 'courseStudents']);
        Route::put('/courses/{courseId}/students/{userId}', [EnrollmentController::class, 'updateEnrollmentStatus']);
    });

    // Dashboard routes - restricted to managers only
    Route::middleware('role:manager')->group(function () {
        Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
        Route::get('/instructors', [DashboardController::class, 'getInstructors']);
    });
});
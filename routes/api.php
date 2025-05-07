<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\EnrollmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);  // Changed to POST for FormData
    Route::put('/profile', [AuthController::class, 'updateProfile']);  // Keep PUT for backward compatibility

    // Course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    // Lecture routes
    Route::get('/courses/{courseId}/lectures', [LectureController::class, 'index']);
    Route::post('/courses/{courseId}/lectures', [LectureController::class, 'store']);
    Route::get('/courses/{courseId}/lectures/{id}', [LectureController::class, 'show']);
    Route::put('/courses/{courseId}/lectures/{id}', [LectureController::class, 'update']);
    Route::delete('/courses/{courseId}/lectures/{id}', [LectureController::class, 'destroy']);

    // Assignment routes
    Route::get('/courses/{courseId}/assignments', [AssignmentController::class, 'index']);
    Route::post('/courses/{courseId}/assignments', [AssignmentController::class, 'store']);
    Route::get('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'show']);
    Route::put('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'update']);
    Route::delete('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'destroy']);

    // Quiz routes
    Route::get('/courses/{courseId}/quizzes', [QuizController::class, 'index']);
    Route::post('/courses/{courseId}/quizzes', [QuizController::class, 'store']);
    Route::get('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'show']);
    Route::put('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'update']);
    Route::delete('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'destroy']);

    // Lab routes
    Route::get('/courses/{courseId}/labs', [LabController::class, 'index']);
    Route::post('/courses/{courseId}/labs', [LabController::class, 'store']);
    Route::get('/courses/{courseId}/labs/{id}', [LabController::class, 'show']);
    Route::put('/courses/{courseId}/labs/{id}', [LabController::class, 'update']);
    Route::delete('/courses/{courseId}/labs/{id}', [LabController::class, 'destroy']);

    // Enrollment routes
    Route::post('/courses/{courseId}/enroll', [EnrollmentController::class, 'enroll']);
    Route::post('/courses/{courseId}/unenroll', [EnrollmentController::class, 'unenroll']);
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);
    Route::get('/courses/{courseId}/students', [EnrollmentController::class, 'courseStudents']);
    Route::put('/courses/{courseId}/students/{userId}', [EnrollmentController::class, 'updateEnrollmentStatus']);
});
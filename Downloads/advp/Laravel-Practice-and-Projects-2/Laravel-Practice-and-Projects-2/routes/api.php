<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\InstructorAssignmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LabController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/courses', [CourseController::class, 'index']);
Route::get('/public/courses/{id}', [CourseController::class, 'show']);

// Protected routes with authentication
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);

    // Lecture routes
    Route::get('/courses/{courseId}/lectures', [LectureController::class, 'index']);
    Route::get('/courses/{courseId}/lectures/{id}', [LectureController::class, 'show']);

    // Assignment routes
    Route::get('/courses/{courseId}/assignments', [AssignmentController::class, 'index']);
    Route::get('/courses/{courseId}/assignments/{id}', [AssignmentController::class, 'show']);

    // Assignment submission routes
    Route::get('/assignments/{assignmentId}/submission', [AssignmentSubmissionController::class, 'getSubmission']);
    Route::post('/assignments/{assignmentId}/submit', [AssignmentSubmissionController::class, 'submitAssignment']);
    Route::get('/submissions/{submissionId}/files/{fileId}/download', [AssignmentSubmissionController::class, 'downloadFile'])
        ->name('api.submission.file.download');
    Route::delete('/submissions/{submissionId}/files/{fileId}', [AssignmentSubmissionController::class, 'deleteFile']);

    // User submission overview routes
    Route::get('/my-submissions', [AssignmentSubmissionController::class, 'getUserSubmissions']);
    Route::get('/submission-stats', [AssignmentSubmissionController::class, 'getSubmissionStats']);

    // Quiz routes
    Route::get('/courses/{courseId}/quizzes', [QuizController::class, 'index']);
    Route::get('/courses/{courseId}/quizzes/{id}', [QuizController::class, 'show']);

    // Lab routes
    Route::get('/courses/{courseId}/labs', [CourseController::class, 'getLabs']);
    Route::get('/courses/{courseId}/labs/{id}', [LabController::class, 'show']);

    // Material routes
    Route::get('/courses/{courseId}/materials', [CourseController::class, 'getMaterials']);

    // Enrollment routes
    Route::post('/courses/{courseId}/enroll', [EnrollmentController::class, 'enroll']);
    Route::post('/courses/{courseId}/unenroll', [EnrollmentController::class, 'unenroll']);
    Route::get('/my-courses', [EnrollmentController::class, 'myCourses']);

    // Student dashboard routes
    Route::prefix('student')->group(function () {
        Route::get('/enrolled-courses', [StudentDashboardController::class, 'getEnrolledCourses']);
        Route::get('/upcoming-assignments', [StudentDashboardController::class, 'getUpcomingAssignments']);
        Route::get('/upcoming-quizzes', [StudentDashboardController::class, 'getUpcomingQuizzes']);
        Route::get('/recent-activity', [StudentDashboardController::class, 'getRecentActivity']);
        Route::get('/gpa', [StudentDashboardController::class, 'getStudentGPA']);

        // Course access and content
        Route::get('/courses/{courseId}', [App\Http\Controllers\StudentCourseController::class, 'getCourseDetails']);
        Route::get('/courses/{courseId}/content', [App\Http\Controllers\StudentCourseController::class, 'getCourseContent']);
        Route::get('/courses/{courseId}/progress', [App\Http\Controllers\StudentCourseController::class, 'getCourseProgress']);

        // Assignments
        Route::get('/courses/{courseId}/assignments', [App\Http\Controllers\StudentAssignmentController::class, 'getCourseAssignments']);
        Route::get('/assignments/{assignmentId}', [App\Http\Controllers\StudentAssignmentController::class, 'getAssignment']);
        Route::post('/assignments/{assignmentId}/submit', [App\Http\Controllers\StudentAssignmentController::class, 'submitAssignment']);
        Route::get('/assignments/{assignmentId}/submission', [App\Http\Controllers\StudentAssignmentController::class, 'getSubmission']);

        // Quizzes
        Route::get('/courses/{courseId}/quizzes', [App\Http\Controllers\StudentQuizController::class, 'getCourseQuizzes']);
        Route::get('/quizzes/{quizId}', [App\Http\Controllers\StudentQuizController::class, 'getQuiz']);
        Route::post('/quizzes/{quizId}/start', [App\Http\Controllers\StudentQuizController::class, 'startQuiz']);
        Route::post('/quizzes/{quizId}/submit', [App\Http\Controllers\StudentQuizController::class, 'submitQuiz']);
        Route::get('/quizzes/{quizId}/attempts', [App\Http\Controllers\StudentQuizController::class, 'getQuizAttempts']);
        Route::get('/quizzes/{quizId}/results/{attemptId}', [App\Http\Controllers\StudentQuizController::class, 'getQuizResults']);

        // Lectures and Materials
        Route::get('/courses/{courseId}/lectures', [App\Http\Controllers\StudentContentController::class, 'getCourseLectures']);
        Route::get('/lectures/{lectureId}', [App\Http\Controllers\StudentContentController::class, 'getLecture']);
        Route::post('/lectures/{lectureId}/progress', [App\Http\Controllers\StudentContentController::class, 'updateLectureProgress']);
        Route::get('/courses/{courseId}/materials', [App\Http\Controllers\StudentContentController::class, 'getCourseMaterials']);
        Route::get('/materials/{materialId}/download', [App\Http\Controllers\StudentContentController::class, 'downloadMaterial']);
    });

    // Course content routes
    Route::prefix('course-content')->group(function () {
        Route::get('/courses/{courseId}/lectures', [CourseContentController::class, 'getLectures']);
        Route::get('/courses/{courseId}/lectures/{lectureId}', [CourseContentController::class, 'getLecture']);
        Route::get('/courses/{courseId}/materials', [CourseContentController::class, 'getMaterials']);
        Route::get('/courses/{courseId}/materials/{materialId}', [CourseContentController::class, 'getMaterial']);
        Route::get('/courses/{courseId}/all', [CourseContentController::class, 'getCourseContent']);
    });

    // OLD INSTRUCTOR CONTENT ROUTES - REMOVED (Controllers deleted)

    // NEW INSTRUCTOR CONTENT MANAGEMENT ROUTES - REBUILT FROM SCRATCH
    Route::prefix('instructor')->middleware(['auth:sanctum'])->group(function () {

        // ==================== LECTURES ====================
        Route::post('/courses/{courseId}/lectures', [App\Http\Controllers\InstructorContentController::class, 'createLecture']);
        Route::get('/lectures/{lectureId}', [App\Http\Controllers\InstructorContentController::class, 'getLecture']);
        Route::put('/lectures/{lectureId}', [App\Http\Controllers\InstructorContentController::class, 'updateLecture']);
        Route::delete('/lectures/{lectureId}', [App\Http\Controllers\InstructorContentController::class, 'deleteLecture']);
        Route::get('/lectures', [App\Http\Controllers\InstructorContentController::class, 'getLectures']);

        // ==================== ASSIGNMENTS ====================
        Route::post('/courses/{courseId}/assignments', [App\Http\Controllers\InstructorContentController::class, 'createAssignment']);
        Route::get('/assignments/{assignmentId}', [App\Http\Controllers\InstructorContentController::class, 'getAssignment']);
        Route::put('/assignments/{assignmentId}', [App\Http\Controllers\InstructorContentController::class, 'updateAssignment']);
        Route::delete('/assignments/{assignmentId}', [App\Http\Controllers\InstructorContentController::class, 'deleteAssignment']);
        Route::get('/assignments', [App\Http\Controllers\InstructorContentController::class, 'getAssignments']);

        // ==================== QUIZZES ====================
        Route::post('/courses/{courseId}/quizzes', [App\Http\Controllers\InstructorQuizLabController::class, 'createQuiz']);
        Route::get('/quizzes/{quizId}', [App\Http\Controllers\InstructorQuizLabController::class, 'getQuiz']);
        Route::put('/quizzes/{quizId}', [App\Http\Controllers\InstructorQuizLabController::class, 'updateQuiz']);
        Route::delete('/quizzes/{quizId}', [App\Http\Controllers\InstructorQuizLabController::class, 'deleteQuiz']);
        Route::get('/quizzes', [App\Http\Controllers\InstructorQuizLabController::class, 'getQuizzes']);

        // ==================== LABS ====================
        Route::post('/courses/{courseId}/labs', [App\Http\Controllers\InstructorQuizLabController::class, 'createLab']);
        Route::get('/labs/{labId}', [App\Http\Controllers\InstructorQuizLabController::class, 'getLab']);
        Route::put('/labs/{labId}', [App\Http\Controllers\InstructorQuizLabController::class, 'updateLab']);
        Route::delete('/labs/{labId}', [App\Http\Controllers\InstructorQuizLabController::class, 'deleteLab']);
        Route::get('/labs', [App\Http\Controllers\InstructorQuizLabController::class, 'getLabs']);

        // ==================== MATERIALS ====================
        Route::post('/courses/{courseId}/materials', [App\Http\Controllers\InstructorMaterialController::class, 'createMaterial']);
        Route::get('/materials/{materialId}', [App\Http\Controllers\InstructorMaterialController::class, 'getMaterial']);
        Route::put('/materials/{materialId}', [App\Http\Controllers\InstructorMaterialController::class, 'updateMaterial']);
        Route::delete('/materials/{materialId}', [App\Http\Controllers\InstructorMaterialController::class, 'deleteMaterial']);
        Route::get('/materials', [App\Http\Controllers\InstructorMaterialController::class, 'getMaterials']);
    });

    // Instructor Course Management (Assigned Courses Only)
    Route::prefix('instructor')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/assigned-courses', [App\Http\Controllers\InstructorCourseController::class, 'getAssignedCourses']);
        Route::get('/courses/{courseId}/details', [App\Http\Controllers\InstructorCourseController::class, 'getCourseDetails']);
        Route::get('/assignment-history', [App\Http\Controllers\InstructorCourseController::class, 'getAssignmentHistory']);
        Route::get('/dashboard-summary', [App\Http\Controllers\InstructorCourseController::class, 'getDashboardSummary']);

        // Course content management endpoints
        Route::get('/courses/{courseId}/lectures', [App\Http\Controllers\InstructorCourseController::class, 'getCourseLectures']);
        Route::get('/courses/{courseId}/assignments', [App\Http\Controllers\InstructorCourseController::class, 'getCourseAssignments']);
        Route::get('/courses/{courseId}/quizzes', [App\Http\Controllers\InstructorCourseController::class, 'getCourseQuizzes']);
        Route::get('/courses/{courseId}/labs', [App\Http\Controllers\InstructorCourseController::class, 'getCourseLabs']);
        Route::get('/courses/{courseId}/materials', [App\Http\Controllers\InstructorCourseController::class, 'getCourseMaterials']);

        // Additional course management endpoints (placeholder for future implementation)
        Route::get('/courses/{courseId}/students', [App\Http\Controllers\InstructorCourseController::class, 'getCourseStudents']);
        Route::get('/courses/{courseId}/activity', [App\Http\Controllers\InstructorCourseController::class, 'getCourseActivity']);
        Route::get('/courses/{courseId}/pending-tasks', [App\Http\Controllers\InstructorCourseController::class, 'getCoursePendingTasks']);
        Route::put('/courses/{courseId}/settings', [App\Http\Controllers\InstructorCourseController::class, 'updateCourseSettings']);

        // Content management
        Route::get('/recent-content', [App\Http\Controllers\InstructorDataController::class, 'getRecentContent']);

        // Test endpoint
        Route::get('/test', function (\Illuminate\Http\Request $request) {
            $user = $request->user();
            return response()->json([
                'status' => 'success',
                'message' => 'Instructor API is working',
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role
                ] : null,
                'timestamp' => now()
            ]);
        });
    });

    // Quiz question routes
    Route::prefix('quizzes')->group(function () {
        Route::get('/{quizId}/questions', [QuizQuestionController::class, 'index']);
        Route::get('/{quizId}/questions/{questionId}', [QuizQuestionController::class, 'show']);
    });

    // Quiz submission routes
    Route::post('/courses/{courseId}/quizzes/{quizId}/submit', [QuizController::class, 'submitQuiz']);

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount']);
        Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::delete('/{id}', [NotificationController::class, 'destroy']);
        Route::post('/', [NotificationController::class, 'store']); // For testing/admin
    });

    // Manager Routes - Comprehensive Dashboard and Management
    Route::middleware('auth:sanctum')->prefix('manager')->group(function () {
        Route::get('/dashboard-data', [App\Http\Controllers\ManagerDashboardController::class, 'getDashboardData']);
        Route::get('/students', [App\Http\Controllers\ManagerDashboardController::class, 'getAllStudents']);
        Route::get('/courses', [App\Http\Controllers\ManagerDashboardController::class, 'getAllCourses']);
        Route::get('/instructors', [App\Http\Controllers\ManagerDashboardController::class, 'getAllInstructors']);
        Route::get('/reports', [App\Http\Controllers\ManagerDashboardController::class, 'getReports']);
    });

    // Course Management Routes (Manager only)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/courses', [App\Http\Controllers\ManagerCourseController::class, 'store']);
        Route::put('/courses/{courseId}', [App\Http\Controllers\ManagerCourseController::class, 'update']);
        Route::delete('/courses/{courseId}', [App\Http\Controllers\ManagerCourseController::class, 'destroy']);
        Route::post('/courses/{courseId}/assign-instructor', [App\Http\Controllers\ManagerCourseController::class, 'assignInstructor']);
        Route::post('/instructors/{instructorId}/assign-courses', [App\Http\Controllers\ManagerCourseController::class, 'bulkAssignCourses']);
    });

    // Users API (for getting instructors, students, etc.)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [App\Http\Controllers\ManagerDashboardController::class, 'getUsersByRole']);
    });

    // Instructor Data API Routes
    Route::middleware(['auth:sanctum'])->prefix('instructor-data')->group(function () {
        Route::get('/', [App\Http\Controllers\InstructorDataController::class, 'getInstructorData']);
        Route::get('/{instructorId}', [App\Http\Controllers\InstructorDataController::class, 'getInstructorData']);
        Route::get('/{instructorId}/courses', [App\Http\Controllers\InstructorDataController::class, 'getInstructorCourses']);
        Route::get('/{instructorId}/content', [App\Http\Controllers\InstructorDataController::class, 'getInstructorContent']);
        Route::get('/{instructorId}/students', [App\Http\Controllers\InstructorDataController::class, 'getInstructorStudents']);
        Route::get('/{instructorId}/grading', [App\Http\Controllers\InstructorDataController::class, 'getInstructorGrading']);
        Route::get('/{instructorId}/statistics', [App\Http\Controllers\InstructorDataController::class, 'getInstructorStatistics']);
        Route::get('/{instructorId}/activity', [App\Http\Controllers\InstructorDataController::class, 'getInstructorActivity']);
    });

    // All Instructors Data (Manager only)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/instructors/all', [App\Http\Controllers\InstructorDataController::class, 'getAllInstructors']);
    });

    // Notification Testing Routes (for development/testing)
    Route::middleware('auth:sanctum')->prefix('test')->group(function () {
        Route::post('/notifications/create', [App\Http\Controllers\NotificationTestController::class, 'createTestNotifications']);
        Route::post('/notifications/course-assignment', [App\Http\Controllers\NotificationTestController::class, 'testCourseAssignmentWorkflow']);
        Route::get('/notifications/stats', [App\Http\Controllers\NotificationTestController::class, 'getNotificationStats']);
    });
});
<?php

use Illuminate\Support\Facades\Route;

// Apply web middleware group to all routes
Route::middleware(['web'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::get('/register', function () {
        return view('register');
    });

    Route::get('/auto-login', function () {
        return view('auto-login');
    });

    Route::get('/manager-auto-login', function () {
        return view('manager-auto-login');
    });

    Route::get('/role-login', function () {
        return view('role-based-login');
    });

    // Student-specific courses page (enrolled courses)
    Route::get('/courses', function () {
        return view('student.courses');
    });

    Route::get('/courses/{id}', function ($id) {
        return view('course-detail', ['courseId' => $id]);
    });

    // Assignment submission page
    Route::get('/assignment-submission', function () {
        return view('student.assignment-submission');
    });

    // Lecture viewing page
    Route::get('/student/lecture-view', function () {
        return view('student.lecture-view');
    });

    // Quiz taking page
    Route::get('/student/quiz-take', function () {
        return view('student.quiz-take');
    });

    // Dashboard route - redirect to student dashboard
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');

    // Student routes
    Route::prefix('student')->group(function () {
        Route::get('/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');

        Route::get('/courses', function () {
            return view('student.courses');
        })->name('student.courses');

        Route::get('/courses/{id}', function ($id) {
            return view('student.course-detail', ['course_id' => $id]);
        })->name('student.course-detail');

        Route::get('/courses/{courseId}/assignments/{assignmentId}', function ($courseId, $assignmentId) {
            return view('student.assignment-detail', ['course_id' => $courseId, 'assignment_id' => $assignmentId]);
        })->name('student.assignment-detail');

        Route::get('/courses/{courseId}/quizzes/{quizId}', function ($courseId, $quizId) {
            return view('student.quiz-detail', ['course_id' => $courseId, 'quiz_id' => $quizId]);
        })->name('student.quiz-detail');

        Route::get('/courses/{courseId}/lectures/{lectureId}', function ($courseId, $lectureId) {
            return view('student.lecture-detail', ['course_id' => $courseId, 'lecture_id' => $lectureId]);
        })->name('student.lecture-detail');

        Route::get('/course-enrollment', function () {
            return view('student.course-enrollment');
        })->name('student.course-enrollment');

        Route::get('/assignments', function () {
            return view('student.assignment-submission');
        })->name('student.assignments');

        Route::get('/profile', function () {
            return view('student.profile');
        })->name('student.profile');

        Route::get('/notifications', function () {
            return view('student.notifications');
        })->name('student.notifications');

        Route::get('/content-viewer', function () {
            return view('student.content-viewer');
        })->name('student.content-viewer');

        Route::get('/lecture-view', function () {
            return view('student.lecture-view');
        })->name('student.lecture-view');

        Route::get('/quiz-take', function () {
            return view('student.quiz-take');
        })->name('student.quiz-take');
    });

    // Notifications page
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'showNotificationsPage'])->name('notifications');

    // Instructor routes
    Route::prefix('instructor')->group(function () {
        Route::get('/dashboard', function () {
            return view('instructor.dashboard');
        })->name('instructor.dashboard');

        Route::get('/courses', function () {
            return view('instructor.courses');
        })->name('instructor.courses');

        Route::get('/courses/{id}', function ($id) {
            return view('instructor.course-detail', ['courseId' => $id]);
        })->name('instructor.course-detail');

        Route::get('/courses/{courseId}/manage', function ($courseId) {
            return view('instructor.course-management', ['courseId' => $courseId]);
        })->name('instructor.course-management');

        Route::get('/content', function () {
            return view('instructor.content');
        })->name('instructor.content');

        Route::get('/grading', function () {
            return view('instructor.grading');
        })->name('instructor.grading');

        Route::get('/analytics', function () {
            return view('instructor.analytics');
        })->name('instructor.analytics');

        Route::get('/notifications', function () {
            return view('instructor.notifications');
        })->name('instructor.notifications');

        Route::get('/content-delivery', function () {
            return view('instructor.content-delivery');
        })->name('instructor.content-delivery');
    });

    // Manager routes
    Route::prefix('manager')->group(function () {
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');

        Route::get('/courses', function () {
            return view('manager.courses');
        })->name('manager.courses');

        Route::get('/students', function () {
            return view('manager.students');
        })->name('manager.students');

        Route::get('/instructors', function () {
            return view('manager.instructors');
        })->name('manager.instructors');

        Route::get('/reports', function () {
            return view('manager.reports');
        })->name('manager.reports');

        Route::get('/api-test', function () {
            return view('manager.api-test');
        })->name('manager.api-test');

        Route::get('/notifications', function () {
            return view('manager.notifications');
        })->name('manager.notifications');
    });

    // Test routes
    Route::get('/test-assignment', function () {
        return view('test-assignment');
    })->name('test-assignment');

});

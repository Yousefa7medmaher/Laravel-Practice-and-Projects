<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

    Route::get('/courses', function () {
        return view('courses');
    });

    Route::get('/courses/{id}', function ($id) {
        return view('course-detail', ['courseId' => $id]);
    });

    // Dashboard route - we'll handle authentication in the controller
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/course_admin', function () {
        return view('course_admin');
    });
 
});

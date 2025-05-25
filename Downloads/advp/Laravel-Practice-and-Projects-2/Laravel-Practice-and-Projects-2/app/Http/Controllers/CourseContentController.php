<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Material;

class CourseContentController extends Controller
{
    /**
     * Get all lectures for a course.
     */
    public function getLectures($courseId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its content'
                    ], 403);
                }
            }

            // Get course with lectures
            $course = Course::with(['lectures' => function($query) {
                $query->where('is_published', true)->orderBy('order', 'asc');
            }])->find($courseId);

            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $course->lectures
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load lectures'
            ], 500);
        }
    }

    /**
     * Get a specific lecture.
     */
    public function getLecture($courseId, $lectureId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its content'
                    ], 403);
                }
            }

            // Get lecture with course information
            $lecture = Lecture::with('course')
                ->where('id', $lectureId)
                ->where('course_id', $courseId)
                ->where('is_published', true)
                ->first();

            if (!$lecture) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lecture not found or not available'
                ], 404);
            }

            // Add mock progress for demonstration
            $lectureData = $lecture->toArray();
            $lectureData['progress'] = rand(0, 100); // Mock progress
            $lectureData['resources'] = []; // Mock resources

            return response()->json([
                'status' => 'success',
                'data' => $lectureData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load lecture'
            ], 500);
        }
    }

    /**
     * Get course materials.
     */
    public function getMaterials($courseId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its materials'
                    ], 403);
                }
            }

            // Get course materials
            $materials = Material::where('course_id', $courseId)
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $materials
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load materials'
            ], 500);
        }
    }

    /**
     * Get a specific material.
     */
    public function getMaterial($courseId, $materialId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its materials'
                    ], 403);
                }
            }

            // Get specific material
            $material = Material::where('id', $materialId)
                ->where('course_id', $courseId)
                ->first();

            if (!$material) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Material not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $material
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load material'
            ], 500);
        }
    }

    /**
     * Get all course content (lectures, materials, etc.).
     */
    public function getCourseContent($courseId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its content'
                    ], 403);
                }
            }

            // Get course with all content
            $course = Course::with([
                'lectures' => function($query) {
                    $query->where('is_published', true)->orderBy('order', 'asc');
                },
                'materials' => function($query) {
                    $query->orderBy('order', 'asc');
                }
            ])->find($courseId);

            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'course' => $course,
                    'lectures' => $course->lectures,
                    'materials' => $course->materials
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load course content'
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\InstructorDataService;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lab;
use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\User;

class InstructorDataController extends Controller
{
    /**
     * Get comprehensive instructor data
     */
    public function getInstructorData(Request $request, $instructorId = null)
    {
        // If no instructor ID provided, use current user
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Instructors can only access their own data.'
            ], 403);
        }

        if ($currentUser->role !== 'manager' && $currentUser->role !== 'instructor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers and instructors can access instructor data.'
            ], 403);
        }

        try {
            $data = InstructorDataService::getInstructorCompleteData($instructorId);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Instructor not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor courses with detailed information
     */
    public function getInstructorCourses(Request $request, $instructorId = null)
    {
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $courses = InstructorDataService::getInstructorCourses($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor content (lectures, assignments, etc.)
     */
    public function getInstructorContent(Request $request, $instructorId = null)
    {
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $content = InstructorDataService::getInstructorContent($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $content
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent content created by instructor
     */
    public function getRecentContent(Request $request)
    {
        try {
            $instructorId = Auth::id();
            $limit = $request->get('limit', 10);

            // Get instructor's courses
            $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

            $recentContent = collect();

            // Get recent lectures
            $lectures = Lecture::whereIn('course_id', $courseIds)
                ->with('course:id,title')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    $item->type = 'lecture';
                    return $item;
                });

            // Get recent assignments
            $assignments = Assignment::whereIn('course_id', $courseIds)
                ->with('course:id,title')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    $item->type = 'assignment';
                    return $item;
                });

            // Get recent quizzes
            $quizzes = Quiz::whereIn('course_id', $courseIds)
                ->with('course:id,title')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    $item->type = 'quiz';
                    return $item;
                });

            // Get recent labs
            $labs = Lab::whereIn('course_id', $courseIds)
                ->with('course:id,title')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    $item->type = 'lab';
                    return $item;
                });

            // Combine and sort by creation date
            $recentContent = $lectures->concat($assignments)
                ->concat($quizzes)
                ->concat($labs)
                ->sortByDesc('created_at')
                ->take($limit)
                ->values();

            return response()->json([
                'status' => 'success',
                'data' => $recentContent
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve recent content',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor students
     */
    public function getInstructorStudents(Request $request, $instructorId = null)
    {
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $students = InstructorDataService::getInstructorStudents($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor grading information
     */
    public function getInstructorGrading(Request $request, $instructorId = null)
    {
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $grading = InstructorDataService::getInstructorGrading($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $grading
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor grading data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor statistics
     */
    public function getInstructorStatistics(Request $request, $instructorId = null)
    {
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $statistics = InstructorDataService::getInstructorStatistics($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor recent activity
     */
    public function getInstructorActivity(Request $request, $instructorId = null)
    {
        if (!$instructorId) {
            $instructorId = Auth::id();
        }

        // Check authorization
        $currentUser = Auth::user();
        if ($currentUser->role === 'instructor' && $currentUser->id != $instructorId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $activity = InstructorDataService::getInstructorRecentActivity($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $activity
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructor activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all instructors with their basic data (for managers)
     */
    public function getAllInstructors(Request $request)
    {
        // Check authorization - only managers can access this
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access all instructor data.'
            ], 403);
        }

        try {
            $instructors = User::with([
                'taughtCourses:id,title,code,instructor_id',
                'gradedSubmissions:id,graded_by',
                'courseAssignments.course:id,title,code'
            ])
            ->where('role', 'instructor')
            ->get()
            ->map(function ($instructor) {
                $stats = InstructorDataService::getInstructorStatistics($instructor->id);
                return [
                    'id' => $instructor->id,
                    'name' => $instructor->name,
                    'email' => $instructor->email,
                    'courses_count' => $instructor->taughtCourses->count(),
                    'active_courses' => $instructor->taughtCourses->where('status', 'active')->count(),
                    'graded_submissions' => $instructor->gradedSubmissions->count(),
                    'statistics' => $stats
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $instructors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve instructors data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

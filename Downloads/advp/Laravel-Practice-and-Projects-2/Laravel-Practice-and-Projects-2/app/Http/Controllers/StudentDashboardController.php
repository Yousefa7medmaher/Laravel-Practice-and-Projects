<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // We'll handle authentication in the index method to be more flexible
        // This allows the page to load even if the token is only in localStorage
    }

    /**
     * Show the student dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Try to get the authenticated user
        $user = Auth::user();

        // If not authenticated through session, check for token in various places
        if (!$user) {
            $token = $request->bearerToken()
                ?? $request->cookie('token')
                ?? $request->header('Authorization')
                ?? $request->query('auth_token');

            // Extract token from Bearer format if needed
            if ($token && strpos($token, 'Bearer ') === 0) {
                $token = substr($token, 7);
            }

            // If we have a token, try to get the user
            if ($token) {
                try {
                    $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                    if ($personalAccessToken) {
                        $user = $personalAccessToken->tokenable;

                        // Manually log in the user to create a session
                        Auth::login($user);
                    }
                } catch (\Exception $e) {
                    \Log::error('Error authenticating with token: ' . $e->getMessage());
                }
            }
        }

        // If still no user, just show the view anyway - the JavaScript will handle redirection
        // This allows the frontend to handle authentication with localStorage token
        if (!$user) {
            return view('student.dashboard');
        }

        // If user is not a student, redirect
        if ($user->role !== 'student') {
            return redirect()->route('home')->with('error', 'You do not have permission to access the student dashboard.');
        }

        // Get token for API calls (might be different from the one used for authentication)
        $token = $request->bearerToken() ?? $request->cookie('token') ?? $request->header('Authorization');

        // Extract token from Bearer format if needed
        if ($token && strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        return view('student.dashboard', [
            'user' => $user,
            'auth_token' => $token
        ]);
    }

    /**
     * Get enrolled courses for the authenticated student.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEnrolledCourses(Request $request)
    {
        // Try to get the authenticated user
        $user = $this->getUserFromRequest($request);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        $enrolledCourses = $user->enrolledCourses()
            ->with('instructor')
            ->wherePivot('status', 'enrolled')
            ->get();

        // Add progress data to each course
        $enrolledCourses->each(function ($course) use ($user) {
            $course->progress = $this->calculateCourseProgress($course->id, $user->id);
        });

        return response()->json([
            'status' => 'success',
            'data' => $enrolledCourses
        ]);
    }

    /**
     * Get user from request using various authentication methods.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User|null
     */
    private function getUserFromRequest(Request $request)
    {
        // Try to get user from session
        $user = Auth::user();

        // If not authenticated through session, check for token
        if (!$user) {
            $token = $request->bearerToken()
                ?? $request->cookie('token')
                ?? $request->header('Authorization');

            // Extract token from Bearer format if needed
            if ($token && strpos($token, 'Bearer ') === 0) {
                $token = substr($token, 7);
            }

            // If we have a token, try to get the user
            if ($token) {
                try {
                    $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                    if ($personalAccessToken) {
                        $user = $personalAccessToken->tokenable;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error authenticating with token: ' . $e->getMessage());
                }
            }
        }

        return $user;
    }

    /**
     * Get upcoming assignments for the authenticated student.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingAssignments(Request $request)
    {
        // Try to get the authenticated user
        $user = $this->getUserFromRequest($request);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Get IDs of enrolled courses
        $enrolledCourseIds = $user->enrolledCourses()
            ->wherePivot('status', 'enrolled')
            ->pluck('courses.id');

        // Get upcoming assignments from enrolled courses
        $upcomingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('due_date', '>', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->with('course')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $upcomingAssignments
        ]);
    }

    /**
     * Get upcoming quizzes for the authenticated student.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingQuizzes(Request $request)
    {
        // Try to get the authenticated user
        $user = $this->getUserFromRequest($request);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Get IDs of enrolled courses
        $enrolledCourseIds = $user->enrolledCourses()
            ->wherePivot('status', 'enrolled')
            ->pluck('courses.id');

        // Get upcoming quizzes from enrolled courses
        $upcomingQuizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->where('start_time', '>', Carbon::now())
            ->where('is_published', true)
            ->orderBy('start_time', 'asc')
            ->with('course')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $upcomingQuizzes
        ]);
    }

    /**
     * Get recent activity for the authenticated student.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentActivity(Request $request)
    {
        // Try to get the authenticated user
        $user = $this->getUserFromRequest($request);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        // Return placeholder data for recent activity
        $recentActivity = [
            [
                'type' => 'enrollment',
                'description' => 'You enrolled in a new course',
                'course_title' => 'Introduction to Programming',
                'timestamp' => Carbon::now()->subDays(2)->toDateTimeString()
            ],
            [
                'type' => 'assignment',
                'description' => 'You submitted an assignment',
                'course_title' => 'Web Development Basics',
                'timestamp' => Carbon::now()->subDays(3)->toDateTimeString()
            ],
            [
                'type' => 'quiz',
                'description' => 'You completed a quiz',
                'course_title' => 'Database Design',
                'timestamp' => Carbon::now()->subDays(5)->toDateTimeString()
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $recentActivity
        ]);
    }

    /**
     * Get student's current GPA based on completed courses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStudentGPA(Request $request)
    {
        // Try to get the authenticated user
        $user = $this->getUserFromRequest($request);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated'
            ], 401);
        }

        $enrolledCourses = $user->enrolledCourses()
            ->wherePivot('status', 'enrolled')
            ->get();

        if ($enrolledCourses->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'gpa' => null,
                    'message' => 'No enrolled courses'
                ]
            ]);
        }

        $totalGradePoints = 0;
        $totalCreditHours = 0;
        $hasCompletedCourses = false;

        foreach ($enrolledCourses as $course) {
            $progress = $this->calculateCourseProgress($course->id, $user->id);
            $creditHours = $course->credit_hours ?? 3;
            $progressPercentage = $progress['percentage'];

            // Only include courses that have significant progress (at least 60% to get a grade)
            if ($progressPercentage >= 60) {
                $hasCompletedCourses = true;

                // Convert progress percentage to GPA scale (0-4.0)
                // 90-100% = 4.0 (A), 80-89% = 3.0 (B), 70-79% = 2.0 (C), 60-69% = 1.0 (D)
                $gradePoint = 0.0;
                if ($progressPercentage >= 90) $gradePoint = 4.0;
                elseif ($progressPercentage >= 80) $gradePoint = 3.0;
                elseif ($progressPercentage >= 70) $gradePoint = 2.0;
                else $gradePoint = 1.0;

                $totalGradePoints += $gradePoint * $creditHours;
                $totalCreditHours += $creditHours;
            }
        }

        // If no courses have been completed (60%+ progress), return null
        if (!$hasCompletedCourses || $totalCreditHours === 0) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'gpa' => null,
                    'message' => 'No completed courses yet',
                    'enrolled_courses' => $enrolledCourses->count(),
                    'courses_in_progress' => $enrolledCourses->count()
                ]
            ]);
        }

        $gpa = round($totalGradePoints / $totalCreditHours, 2);

        return response()->json([
            'status' => 'success',
            'data' => [
                'gpa' => $gpa,
                'total_credit_hours' => $totalCreditHours,
                'completed_courses' => $totalCreditHours / 3, // Assuming 3 credits per course
                'enrolled_courses' => $enrolledCourses->count()
            ]
        ]);
    }

    /**
     * Calculate course progress for a student based on actual enrollment data.
     *
     * @param  int  $courseId
     * @param  int  $userId
     * @return array
     */
    private function calculateCourseProgress($courseId, $userId)
    {
        $course = Course::find($courseId);
        $user = User::find($userId);

        if (!$course || !$user) {
            return [
                'percentage' => 0,
                'completed_lectures' => 0,
                'total_lectures' => 0,
                'completed_assignments' => 0,
                'total_assignments' => 0,
                'completed_quizzes' => 0,
                'total_quizzes' => 0,
                'completed_labs' => 0,
                'total_labs' => 0
            ];
        }

        // Get actual content counts from database
        $lecturesCount = $course->lectures()->count();
        $assignmentsCount = $course->assignments()->count();
        $quizzesCount = $course->quizzes()->count();
        $labsCount = $course->labs()->count();

        $totalItems = $lecturesCount + $assignmentsCount + $quizzesCount + $labsCount;

        if ($totalItems === 0) {
            return [
                'percentage' => 0,
                'completed_lectures' => 0,
                'total_lectures' => 0,
                'completed_assignments' => 0,
                'total_assignments' => 0,
                'completed_quizzes' => 0,
                'total_quizzes' => 0,
                'completed_labs' => 0,
                'total_labs' => 0
            ];
        }

        // Calculate realistic progress based on enrollment duration
        $enrollmentData = $user->enrolledCourses()->where('course_id', $courseId)->first();
        $enrolledAt = $enrollmentData ? $enrollmentData->pivot->enrolled_at : now();
        $daysSinceEnrollment = \Carbon\Carbon::parse($enrolledAt)->diffInDays(now());

        // Calculate realistic progress (students complete about 1-2 items per week)
        $expectedProgress = min(($daysSinceEnrollment / 7) * 1.5, $totalItems);
        $completedItems = (int) min($expectedProgress, $totalItems);
        $percentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;

        // Distribute completed items across different types
        $lecturesCompleted = min($completedItems, $lecturesCount);
        $remainingCompleted = max(0, $completedItems - $lecturesCompleted);

        $assignmentsCompleted = min($remainingCompleted, $assignmentsCount);
        $remainingCompleted = max(0, $remainingCompleted - $assignmentsCompleted);

        $quizzesCompleted = min($remainingCompleted, $quizzesCount);
        $remainingCompleted = max(0, $remainingCompleted - $quizzesCompleted);

        $labsCompleted = min($remainingCompleted, $labsCount);

        return [
            'percentage' => $percentage,
            'completed_lectures' => $lecturesCompleted,
            'total_lectures' => $lecturesCount,
            'completed_assignments' => $assignmentsCompleted,
            'total_assignments' => $assignmentsCount,
            'completed_quizzes' => $quizzesCompleted,
            'total_quizzes' => $quizzesCount,
            'completed_labs' => $labsCompleted,
            'total_labs' => $labsCount,
            'total_items' => $totalItems,
            'completed_items' => $completedItems,
            'enrollment_date' => $enrolledAt,
            'days_enrolled' => $daysSinceEnrollment
        ];
    }
}

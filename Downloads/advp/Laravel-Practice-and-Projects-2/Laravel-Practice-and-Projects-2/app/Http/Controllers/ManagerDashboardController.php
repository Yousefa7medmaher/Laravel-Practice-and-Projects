<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\User;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\StudentEnrollment;
use App\Models\CourseAssignment;
use App\Models\UserActivityLog;
use App\Models\GradeHistory;
use App\Models\LearningAnalytics;

class ManagerDashboardController extends Controller
{
    /**
     * Get comprehensive dashboard data for managers
     */
    public function getDashboardData()
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access this data.'
            ], 403);
        }

        try {
            // Get overall statistics
            $stats = [
                'total_courses' => Course::count(),
                'total_students' => User::where('role', 'student')->count(),
                'total_instructors' => User::where('role', 'instructor')->count(),
                'total_assignments' => Assignment::count(),
                'total_submissions' => AssignmentSubmission::count(),
                'graded_submissions' => AssignmentSubmission::where('status', 'graded')->count(),
                'pending_submissions' => AssignmentSubmission::where('status', 'submitted')->count(),
            ];

            // Get course enrollment statistics
            $courseStats = Course::withCount([
                'students as students_count',
                'assignments',
                'lectures',
                'quizzes',
                'labs'
            ])->with(['instructor:id,name,email'])->get();

            // Get recent activity
            $recentActivity = UserActivityLog::with('user:id,name,role')
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();

            // Get top performing students
            $topStudents = User::where('role', 'student')
                ->withAvg('submissions as avg_grade', 'grade')
                ->orderBy('avg_grade', 'desc')
                ->limit(10)
                ->get();

            // Get instructor performance
            $instructorPerformance = User::where('role', 'instructor')
                ->withCount([
                    'gradedSubmissions as total_graded',
                    'assignedCourses as courses_assigned'
                ])
                ->with(['assignedCourses:id,title,code'])
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'stats' => $stats,
                    'course_stats' => $courseStats,
                    'recent_activity' => $recentActivity,
                    'top_students' => $topStudents,
                    'instructor_performance' => $instructorPerformance
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all students with their academic records
     */
    public function getAllStudents()
    {
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access this data.'
            ], 403);
        }

        try {
            $students = User::where('role', 'student')
                ->with([
                    'enrolledCourses:id,title,code,credit_hours',
                    'submissions.assignment.course:id,title,code'
                ])
                ->withCount('enrolledCourses as total_courses')
                ->withAvg('submissions as avg_grade', 'grade')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $students
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load students data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all courses with comprehensive data
     */
    public function getAllCourses()
    {
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access this data.'
            ], 403);
        }

        try {
            $courses = Course::with([
                'instructor:id,name,email',
                'students:id,name,email',
                'assignments:id,course_id,title,max_score',
                'lectures:id,course_id,title,duration',
                'quizzes:id,course_id,title,max_score',
                'labs:id,course_id,title,max_score'
            ])
            ->withCount([
                'students as students_count',
                'assignments',
                'lectures',
                'quizzes',
                'labs'
            ])
            ->get();

            return response()->json([
                'status' => 'success',
                'data' => $courses
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load courses data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get comprehensive reports
     */
    public function getReports(Request $request)
    {
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access this data.'
            ], 403);
        }

        try {
            $reportType = $request->get('type', 'overview');
            $startDate = $request->get('start_date', now()->subMonth());
            $endDate = $request->get('end_date', now());

            $reports = [];

            switch ($reportType) {
                case 'course_effectiveness':
                    $reports = $this->getCourseEffectivenessReport($startDate, $endDate);
                    break;
                case 'student_performance':
                    $reports = $this->getStudentPerformanceReport($startDate, $endDate);
                    break;
                case 'instructor_activity':
                    $reports = $this->getInstructorActivityReport($startDate, $endDate);
                    break;
                default:
                    $reports = $this->getOverviewReport($startDate, $endDate);
            }

            return response()->json([
                'status' => 'success',
                'data' => $reports
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate reports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all instructors with performance data
     */
    public function getAllInstructors()
    {
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access this data.'
            ], 403);
        }

        try {
            $instructors = User::where('role', 'instructor')
                ->withCount([
                    'gradedSubmissions as total_graded_count',
                    'assignedCourses as courses_assigned_count'
                ])
                ->with(['assignedCourses:id,title,code,credit_hours'])
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $instructors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load instructors data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(Request $request)
    {
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can access this data.'
            ], 403);
        }

        $role = $request->get('role', 'student');

        if (!in_array($role, ['student', 'instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid role specified'
            ], 400);
        }

        try {
            $users = User::where('role', $role);

            if ($role === 'instructor') {
                $users = $users->withCount([
                    'gradedSubmissions as total_graded_count',
                    'assignedCourses as courses_assigned_count'
                ])->with(['assignedCourses:id,title,code,credit_hours']);
            } elseif ($role === 'student') {
                $users = $users->with([
                    'enrolledCourses:id,title,code,credit_hours',
                    'submissions.assignment.course:id,title,code'
                ])
                ->withCount('enrolledCourses as total_courses')
                ->withAvg('submissions as avg_grade', 'grade');
            }

            $result = $users->get();

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load users data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private helper methods for reports
     */
    private function getCourseEffectivenessReport($startDate, $endDate)
    {
        return Course::with(['enrollments', 'assignments.submissions'])
            ->withCount('enrollments as total_enrollments')
            ->withAvg('submissions as avg_grade', 'grade')
            ->get()
            ->map(function ($course) {
                $submissions = $course->assignments->flatMap->submissions;
                $completionRate = $course->assignments->count() > 0 ?
                    ($submissions->count() / ($course->assignments->count() * $course->total_enrollments)) * 100 : 0;

                return [
                    'course' => $course,
                    'completion_rate' => round($completionRate, 2),
                    'avg_grade' => round($course->avg_grade ?? 0, 2),
                    'total_submissions' => $submissions->count()
                ];
            });
    }

    private function getStudentPerformanceReport($startDate, $endDate)
    {
        return User::where('role', 'student')
            ->with(['submissions' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withAvg('submissions as avg_grade', 'grade')
            ->withSum('submissions as total_coins', 'coins')
            ->withSum('submissions as total_meals', 'meals')
            ->get()
            ->map(function ($student) {
                return [
                    'student' => $student,
                    'performance_trend' => $this->calculatePerformanceTrend($student->submissions),
                    'engagement_score' => $this->calculateEngagementScore($student)
                ];
            });
    }

    private function getInstructorActivityReport($startDate, $endDate)
    {
        return User::where('role', 'instructor')
            ->withCount([
                'gradedSubmissions as graded_count' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('graded_at', [$startDate, $endDate]);
                },
                'assignedCourses as courses_count'
            ])
            ->with(['assignedCourses'])
            ->get()
            ->map(function ($instructor) {
                return [
                    'instructor' => $instructor,
                    'activity_score' => $this->calculateActivityScore($instructor),
                    'response_time' => $this->calculateAverageResponseTime($instructor)
                ];
            });
    }

    private function getOverviewReport($startDate, $endDate)
    {
        return [
            'period' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ],
            'summary' => [
                'total_courses' => Course::count(),
                'total_students' => User::where('role', 'student')->count(),
                'total_instructors' => User::where('role', 'instructor')->count(),
                'total_submissions' => AssignmentSubmission::whereBetween('created_at', [$startDate, $endDate])->count(),
                'avg_grade' => AssignmentSubmission::whereBetween('created_at', [$startDate, $endDate])->avg('grade') ?? 0
            ],
            'trends' => $this->calculatePlatformTrends($startDate, $endDate)
        ];
    }

    private function calculatePerformanceTrend($submissions)
    {
        if ($submissions->count() < 2) return 'stable';

        $recent = $submissions->sortByDesc('created_at')->take(5)->avg('grade');
        $older = $submissions->sortByDesc('created_at')->skip(5)->take(5)->avg('grade');

        if ($recent > $older + 5) return 'improving';
        if ($recent < $older - 5) return 'declining';
        return 'stable';
    }

    private function calculateEngagementScore($student)
    {
        $submissions = $student->submissions->count();
        $courses = $student->enrollments->count();

        if ($courses === 0) return 0;

        return min(100, ($submissions / ($courses * 5)) * 100); // Assuming 5 assignments per course average
    }

    private function calculateActivityScore($instructor)
    {
        $graded = $instructor->graded_count ?? 0;
        $courses = $instructor->courses_count ?? 0;

        if ($courses === 0) return 0;

        return min(100, ($graded / ($courses * 10)) * 100); // Assuming 10 submissions per course average
    }

    private function calculateAverageResponseTime($instructor)
    {
        // This would calculate average time between submission and grading
        // For now, return a placeholder
        return rand(1, 5) . ' days';
    }

    private function calculatePlatformTrends($startDate, $endDate)
    {
        // Calculate weekly trends for the period
        $weeks = [];
        $current = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        while ($current <= $end) {
            $weekStart = clone $current;
            $weekEnd = clone $current;
            $weekEnd->modify('+6 days');

            $weeks[] = [
                'week' => $weekStart->format('Y-m-d'),
                'submissions' => AssignmentSubmission::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'new_enrollments' => 0, // Would need enrollment tracking
                'active_users' => UserActivityLog::whereBetween('created_at', [$weekStart, $weekEnd])
                    ->distinct('user_id')->count('user_id')
            ];

            $current->modify('+7 days');
        }

        return $weeks;
    }
}

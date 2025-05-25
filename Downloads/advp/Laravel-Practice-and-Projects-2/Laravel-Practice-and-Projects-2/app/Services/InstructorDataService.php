<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Lecture;
use App\Models\Quiz;
use App\Models\Lab;
use App\Models\Material;
use App\Models\CourseAssignment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstructorDataService
{
    /**
     * Get comprehensive instructor data with all relationships
     */
    public static function getInstructorCompleteData($instructorId)
    {
        $instructor = User::with([
            'taughtCourses.students',
            'taughtCourses.assignments.submissions',
            'taughtCourses.lectures',
            'taughtCourses.quizzes',
            'taughtCourses.labs',
            'taughtCourses.materials',
            'gradedSubmissions.assignment.course',
            'courseAssignments.course',
            'notifications'
        ])->where('role', 'instructor')->find($instructorId);

        if (!$instructor) {
            return null;
        }

        return [
            'instructor' => $instructor,
            'courses' => self::getInstructorCourses($instructorId),
            'content' => self::getInstructorContent($instructorId),
            'students' => self::getInstructorStudents($instructorId),
            'grading' => self::getInstructorGrading($instructorId),
            'statistics' => self::getInstructorStatistics($instructorId),
            'recent_activity' => self::getInstructorRecentActivity($instructorId)
        ];
    }

    /**
     * Get all courses taught by instructor with detailed information
     * Only returns courses where instructor is actively assigned
     */
    public static function getInstructorCourses($instructorId)
    {
        return Course::with([
            'students',
            'assignments.submissions',
            'lectures',
            'quizzes',
            'labs',
            'materials'
        ])
        ->where('instructor_id', $instructorId)
        ->whereHas('courseAssignments', function ($query) use ($instructorId) {
            $query->where('instructor_id', $instructorId)
                  ->where('is_active', true);
        })
        ->get()
        ->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'code' => $course->code,
                'description' => $course->description,
                'status' => $course->status,
                'credit_hours' => $course->credit_hours,
                'student_count' => $course->students->count(),
                'content_stats' => [
                    'lectures' => $course->lectures->count(),
                    'assignments' => $course->assignments->count(),
                    'quizzes' => $course->quizzes->count(),
                    'labs' => $course->labs->count(),
                    'materials' => $course->materials->count(),
                ],
                'submission_stats' => [
                    'total_submissions' => $course->assignments->sum(function ($assignment) {
                        return $assignment->submissions->count();
                    }),
                    'pending_grading' => $course->assignments->sum(function ($assignment) {
                        return $assignment->submissions->where('status', 'submitted')->count();
                    }),
                    'graded' => $course->assignments->sum(function ($assignment) {
                        return $assignment->submissions->where('status', 'graded')->count();
                    }),
                ]
            ];
        });
    }

    /**
     * Get all content created by instructor
     * Only returns content from courses where instructor is actively assigned
     */
    public static function getInstructorContent($instructorId)
    {
        // Get active course IDs for this instructor
        $activeCourseIds = Course::where('instructor_id', $instructorId)
            ->whereHas('courseAssignments', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId)
                      ->where('is_active', true);
            })
            ->pluck('id');

        return [
            'lectures' => Lecture::with('course')
                ->where('instructor_id', $instructorId)
                ->whereIn('course_id', $activeCourseIds)
                ->orderBy('created_at', 'desc')
                ->get(),
            'assignments' => Assignment::with(['course', 'submissions'])
                ->where('instructor_id', $instructorId)
                ->whereIn('course_id', $activeCourseIds)
                ->orderBy('created_at', 'desc')
                ->get(),
            'quizzes' => Quiz::with('course')
                ->where('instructor_id', $instructorId)
                ->whereIn('course_id', $activeCourseIds)
                ->orderBy('created_at', 'desc')
                ->get(),
            'labs' => Lab::with('course')
                ->where('instructor_id', $instructorId)
                ->whereIn('course_id', $activeCourseIds)
                ->orderBy('created_at', 'desc')
                ->get(),
            'materials' => Material::with('course')
                ->where('instructor_id', $instructorId)
                ->whereIn('course_id', $activeCourseIds)
                ->orderBy('created_at', 'desc')
                ->get(),
        ];
    }

    /**
     * Get all students enrolled in instructor's courses
     * Only includes students from courses where instructor is actively assigned
     */
    public static function getInstructorStudents($instructorId)
    {
        // Get only active course IDs for this instructor
        $courses = Course::where('instructor_id', $instructorId)
            ->whereHas('courseAssignments', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId)
                      ->where('is_active', true);
            })
            ->pluck('id');

        return User::whereHas('enrolledCourses', function ($query) use ($courses) {
            $query->whereIn('course_id', $courses);
        })
        ->with(['enrolledCourses' => function ($query) use ($courses) {
            $query->whereIn('course_id', $courses)->withPivot('status', 'enrolled_at', 'final_grade');
        }])
        ->where('role', 'student')
        ->get()
        ->map(function ($student) use ($instructorId) {
            $submissions = AssignmentSubmission::whereHas('assignment.course', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })->where('user_id', $student->id)->get();

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'enrolled_courses' => $student->enrolledCourses->map(function ($course) {
                    return [
                        'id' => $course->id,
                        'title' => $course->title,
                        'code' => $course->code,
                        'status' => $course->pivot->status,
                        'enrolled_at' => $course->pivot->enrolled_at,
                        'final_grade' => $course->pivot->final_grade,
                    ];
                }),
                'submission_stats' => [
                    'total_submissions' => $submissions->count(),
                    'pending' => $submissions->where('status', 'submitted')->count(),
                    'graded' => $submissions->where('status', 'graded')->count(),
                    'average_grade' => $submissions->where('status', 'graded')->avg('grade'),
                ]
            ];
        });
    }

    /**
     * Get grading information for instructor
     */
    public static function getInstructorGrading($instructorId)
    {
        $pendingSubmissions = AssignmentSubmission::with(['assignment.course', 'user'])
            ->whereHas('assignment.course', function ($query) use ($instructorId) {
                $query->where('instructor_id', $instructorId);
            })
            ->where('status', 'submitted')
            ->orderBy('submitted_at', 'asc')
            ->get();

        $recentlyGraded = AssignmentSubmission::with(['assignment.course', 'user'])
            ->where('graded_by', $instructorId)
            ->where('status', 'graded')
            ->orderBy('graded_at', 'desc')
            ->take(20)
            ->get();

        return [
            'pending_submissions' => $pendingSubmissions,
            'recently_graded' => $recentlyGraded,
            'grading_stats' => [
                'total_pending' => $pendingSubmissions->count(),
                'total_graded' => AssignmentSubmission::where('graded_by', $instructorId)->count(),
                'average_grade_given' => AssignmentSubmission::where('graded_by', $instructorId)
                    ->where('status', 'graded')
                    ->avg('grade'),
                'total_meals_awarded' => AssignmentSubmission::where('graded_by', $instructorId)
                    ->sum('meals'),
                'total_coins_awarded' => AssignmentSubmission::where('graded_by', $instructorId)
                    ->sum('coins'),
            ]
        ];
    }

    /**
     * Get comprehensive instructor statistics
     */
    public static function getInstructorStatistics($instructorId)
    {
        $courses = Course::where('instructor_id', $instructorId);
        $totalStudents = User::whereHas('enrolledCourses', function ($query) use ($instructorId) {
            $query->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            });
        })->count();

        return [
            'courses' => [
                'total' => $courses->count(),
                'active' => $courses->where('status', 'active')->count(),
                'inactive' => $courses->where('status', 'inactive')->count(),
            ],
            'students' => [
                'total' => $totalStudents,
                'active_enrollments' => DB::table('course_user')
                    ->join('courses', 'course_user.course_id', '=', 'courses.id')
                    ->where('courses.instructor_id', $instructorId)
                    ->where('course_user.status', 'enrolled')
                    ->count(),
            ],
            'content' => [
                'lectures' => Lecture::where('instructor_id', $instructorId)->count(),
                'assignments' => Assignment::where('instructor_id', $instructorId)->count(),
                'quizzes' => Quiz::where('instructor_id', $instructorId)->count(),
                'labs' => Lab::where('instructor_id', $instructorId)->count(),
                'materials' => Material::where('instructor_id', $instructorId)->count(),
            ],
            'grading' => [
                'total_graded' => AssignmentSubmission::where('graded_by', $instructorId)->count(),
                'pending_grading' => AssignmentSubmission::whereHas('assignment.course', function ($query) use ($instructorId) {
                    $query->where('instructor_id', $instructorId);
                })->where('status', 'submitted')->count(),
                'average_grade' => AssignmentSubmission::where('graded_by', $instructorId)
                    ->where('status', 'graded')
                    ->avg('grade'),
                'total_meals' => AssignmentSubmission::where('graded_by', $instructorId)->sum('meals'),
                'total_coins' => AssignmentSubmission::where('graded_by', $instructorId)->sum('coins'),
            ],
            'activity' => [
                'course_assignments' => CourseAssignment::where('instructor_id', $instructorId)->count(),
                'active_assignments' => CourseAssignment::where('instructor_id', $instructorId)
                    ->where('is_active', true)->count(),
                'last_login' => null, // Can be implemented with user activity tracking
                'content_created_this_month' => self::getContentCreatedThisMonth($instructorId),
            ]
        ];
    }

    /**
     * Get recent activity for instructor
     */
    public static function getInstructorRecentActivity($instructorId)
    {
        $activities = [];

        // Recent content creation
        $recentLectures = Lecture::where('instructor_id', $instructorId)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recentLectures as $lecture) {
            $activities[] = [
                'type' => 'lecture_created',
                'description' => "Created lecture: {$lecture->title}",
                'date' => $lecture->created_at,
                'related_course' => $lecture->course->title ?? 'Unknown Course'
            ];
        }

        // Recent grading
        $recentGrading = AssignmentSubmission::where('graded_by', $instructorId)
            ->where('graded_at', '>=', Carbon::now()->subDays(30))
            ->with(['assignment.course', 'user'])
            ->orderBy('graded_at', 'desc')
            ->take(10)
            ->get();

        foreach ($recentGrading as $submission) {
            $activities[] = [
                'type' => 'submission_graded',
                'description' => "Graded {$submission->user->name}'s submission for {$submission->assignment->title}",
                'date' => $submission->graded_at,
                'related_course' => $submission->assignment->course->title ?? 'Unknown Course'
            ];
        }

        // Sort all activities by date
        usort($activities, function ($a, $b) {
            return $b['date'] <=> $a['date'];
        });

        return array_slice($activities, 0, 20);
    }

    /**
     * Get content created this month
     */
    private static function getContentCreatedThisMonth($instructorId)
    {
        $startOfMonth = Carbon::now()->startOfMonth();

        return [
            'lectures' => Lecture::where('instructor_id', $instructorId)
                ->where('created_at', '>=', $startOfMonth)->count(),
            'assignments' => Assignment::where('instructor_id', $instructorId)
                ->where('created_at', '>=', $startOfMonth)->count(),
            'quizzes' => Quiz::where('instructor_id', $instructorId)
                ->where('created_at', '>=', $startOfMonth)->count(),
            'labs' => Lab::where('instructor_id', $instructorId)
                ->where('created_at', '>=', $startOfMonth)->count(),
            'materials' => Material::where('instructor_id', $instructorId)
                ->where('created_at', '>=', $startOfMonth)->count(),
        ];
    }

    /**
     * Update instructor_id for existing content when courses are assigned
     */
    public static function updateContentInstructorIds($instructorId, $courseIds)
    {
        try {
            DB::transaction(function () use ($instructorId, $courseIds) {
                // Update lectures
                Lecture::whereIn('course_id', $courseIds)->update(['instructor_id' => $instructorId]);

                // Update assignments
                Assignment::whereIn('course_id', $courseIds)->update(['instructor_id' => $instructorId]);

                // Update quizzes
                Quiz::whereIn('course_id', $courseIds)->update(['instructor_id' => $instructorId]);

                // Update labs
                Lab::whereIn('course_id', $courseIds)->update(['instructor_id' => $instructorId]);

                // Update materials
                Material::whereIn('course_id', $courseIds)->update(['instructor_id' => $instructorId]);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to update content instructor IDs: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove instructor_id from content when courses are unassigned
     */
    public static function removeContentInstructorIds($courseIds)
    {
        try {
            DB::transaction(function () use ($courseIds) {
                // Remove instructor_id from lectures
                Lecture::whereIn('course_id', $courseIds)->update(['instructor_id' => null]);

                // Remove instructor_id from assignments
                Assignment::whereIn('course_id', $courseIds)->update(['instructor_id' => null]);

                // Remove instructor_id from quizzes
                Quiz::whereIn('course_id', $courseIds)->update(['instructor_id' => null]);

                // Remove instructor_id from labs
                Lab::whereIn('course_id', $courseIds)->update(['instructor_id' => null]);

                // Remove instructor_id from materials
                Material::whereIn('course_id', $courseIds)->update(['instructor_id' => null]);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to remove content instructor IDs: ' . $e->getMessage());
            return false;
        }
    }
}

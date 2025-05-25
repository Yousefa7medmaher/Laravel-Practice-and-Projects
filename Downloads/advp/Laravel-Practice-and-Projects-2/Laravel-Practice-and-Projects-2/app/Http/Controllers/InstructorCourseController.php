<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\AssignmentSubmission;
use App\Models\Lecture;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Lab;
use App\Models\Material;
use App\Services\InstructorDataService;

class InstructorCourseController extends Controller
{
    /**
     * Get all courses assigned to the current instructor
     */
    public function getAssignedCourses(Request $request)
    {
        $instructorId = Auth::id();
        $user = Auth::user();

        // Debug information
        \Log::info('InstructorCourseController::getAssignedCourses called', [
            'instructor_id' => $instructorId,
            'user_role' => $user->role,
            'user_name' => $user->name
        ]);

        // Allow both instructors and managers for testing
        if (!in_array($user->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only instructors and managers can access this endpoint.',
                'debug' => [
                    'user_role' => $user->role,
                    'user_id' => $instructorId
                ]
            ], 403);
        }

        try {
            // First, let's check if there are any courses at all for this instructor
            $directCourses = Course::where('instructor_id', $instructorId)->get();

            \Log::info('Direct courses found', [
                'instructor_id' => $instructorId,
                'direct_courses_count' => $directCourses->count(),
                'direct_courses' => $directCourses->pluck('title')->toArray()
            ]);

            // Check course assignments
            $courseAssignments = CourseAssignment::where('instructor_id', $instructorId)->get();

            \Log::info('Course assignments found', [
                'instructor_id' => $instructorId,
                'assignments_count' => $courseAssignments->count(),
                'active_assignments' => $courseAssignments->where('is_active', true)->count()
            ]);

            // Try the service method
            $courses = InstructorDataService::getInstructorCourses($instructorId);

            return response()->json([
                'status' => 'success',
                'data' => $courses,
                'debug' => [
                    'instructor_id' => $instructorId,
                    'user_role' => $user->role,
                    'direct_courses_count' => $directCourses->count(),
                    'assignments_count' => $courseAssignments->count(),
                    'service_courses_count' => count($courses)
                ],
                'message' => 'Assigned courses retrieved successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error in getAssignedCourses', [
                'instructor_id' => $instructorId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve assigned courses',
                'error' => $e->getMessage(),
                'debug' => [
                    'instructor_id' => $instructorId,
                    'user_role' => $user->role
                ]
            ], 500);
        }
    }

    /**
     * Get detailed information about a specific assigned course
     */
    public function getCourseDetails(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        // Verify user is an instructor
        if (Auth::user()->role !== 'instructor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only instructors can access this endpoint.'
            ], 403);
        }

        try {
            // Check if instructor is assigned to this course
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $course = Course::with([
                'students',
                'assignments.submissions',
                'lectures',
                'quizzes',
                'labs',
                'materials'
            ])
            ->where('id', $courseId)
            ->first();

            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found or you are not assigned to this course.'
                ], 404);
            }

            // Prepare detailed course data
            $courseData = [
                'id' => $course->id,
                'title' => $course->title,
                'code' => $course->code,
                'description' => $course->description,
                'status' => $course->status,
                'credit_hours' => $course->credit_hours,
                'student_count' => $course->students->count(),
                'students' => $course->students->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'enrollment_status' => $student->pivot->status,
                        'enrolled_at' => $student->pivot->enrolled_at,
                        'final_grade' => $student->pivot->final_grade,
                    ];
                }),
                'content' => [
                    'lectures' => $course->lectures->map(function ($lecture) {
                        return [
                            'id' => $lecture->id,
                            'title' => $lecture->title,
                            'description' => $lecture->description,
                            'duration' => $lecture->duration,
                            'order' => $lecture->order,
                            'is_published' => $lecture->is_published,
                            'created_at' => $lecture->created_at,
                        ];
                    }),
                    'assignments' => $course->assignments->map(function ($assignment) {
                        return [
                            'id' => $assignment->id,
                            'title' => $assignment->title,
                            'description' => $assignment->description,
                            'due_date' => $assignment->due_date,
                            'max_score' => $assignment->max_score,
                            'submission_count' => $assignment->submissions->count(),
                            'pending_grading' => $assignment->submissions->where('status', 'submitted')->count(),
                            'created_at' => $assignment->created_at,
                        ];
                    }),
                    'quizzes' => $course->quizzes->map(function ($quiz) {
                        return [
                            'id' => $quiz->id,
                            'title' => $quiz->title,
                            'description' => $quiz->description,
                            'start_time' => $quiz->start_time,
                            'end_time' => $quiz->end_time,
                            'duration_minutes' => $quiz->duration_minutes,
                            'max_score' => $quiz->max_score,
                            'is_published' => $quiz->is_published,
                            'created_at' => $quiz->created_at,
                        ];
                    }),
                    'labs' => $course->labs->map(function ($lab) {
                        return [
                            'id' => $lab->id,
                            'title' => $lab->title,
                            'description' => $lab->description,
                            'due_date' => $lab->due_date,
                            'max_score' => $lab->max_score,
                            'created_at' => $lab->created_at,
                        ];
                    }),
                    'materials' => $course->materials->map(function ($material) {
                        return [
                            'id' => $material->id,
                            'title' => $material->title,
                            'description' => $material->description,
                            'file_name' => $material->file_name,
                            'file_type' => $material->file_type,
                            'file_size' => $material->file_size,
                            'is_downloadable' => $material->is_downloadable,
                            'created_at' => $material->created_at,
                        ];
                    }),
                ],
                'statistics' => [
                    'total_content' => $course->lectures->count() + $course->assignments->count() +
                                     $course->quizzes->count() + $course->labs->count() + $course->materials->count(),
                    'total_submissions' => $course->assignments->sum(function ($assignment) {
                        return $assignment->submissions->count();
                    }),
                    'pending_grading' => $course->assignments->sum(function ($assignment) {
                        return $assignment->submissions->where('status', 'submitted')->count();
                    }),
                    'average_grade' => $course->assignments->flatMap(function ($assignment) {
                        return $assignment->submissions->where('status', 'graded');
                    })->avg('grade'),
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $courseData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve course details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course assignment history for instructor
     */
    public function getAssignmentHistory(Request $request)
    {
        $instructorId = Auth::id();

        // Verify user is an instructor
        if (Auth::user()->role !== 'instructor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only instructors can access this endpoint.'
            ], 403);
        }

        try {
            $assignments = CourseAssignment::with(['course', 'assignedBy'])
                ->where('instructor_id', $instructorId)
                ->orderBy('assigned_at', 'desc')
                ->get()
                ->map(function ($assignment) {
                    return [
                        'id' => $assignment->id,
                        'course' => [
                            'id' => $assignment->course->id,
                            'title' => $assignment->course->title,
                            'code' => $assignment->course->code,
                        ],
                        'assigned_by' => [
                            'id' => $assignment->assignedBy->id,
                            'name' => $assignment->assignedBy->name,
                        ],
                        'assigned_at' => $assignment->assigned_at,
                        'unassigned_at' => $assignment->unassigned_at,
                        'is_active' => $assignment->is_active,
                        'status' => $assignment->is_active ? 'Active' : 'Inactive',
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $assignments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve assignment history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get instructor dashboard summary
     */
    public function getDashboardSummary(Request $request)
    {
        $instructorId = Auth::id();

        // Verify user is an instructor
        if (Auth::user()->role !== 'instructor') {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied. Only instructors can access this endpoint.'
            ], 403);
        }

        try {
            $data = InstructorDataService::getInstructorCompleteData($instructorId);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Instructor data not found'
                ], 404);
            }

            // Create summary data
            $summary = [
                'instructor' => [
                    'id' => $data['instructor']->id,
                    'name' => $data['instructor']->name,
                    'email' => $data['instructor']->email,
                ],
                'courses' => [
                    'total' => count($data['courses']),
                    'active' => collect($data['courses'])->where('status', 'active')->count(),
                ],
                'students' => [
                    'total' => count($data['students']),
                ],
                'content' => [
                    'lectures' => $data['content']['lectures']->count(),
                    'assignments' => $data['content']['assignments']->count(),
                    'quizzes' => $data['content']['quizzes']->count(),
                    'labs' => $data['content']['labs']->count(),
                    'materials' => $data['content']['materials']->count(),
                ],
                'grading' => [
                    'pending' => $data['grading']['grading_stats']['total_pending'],
                    'completed' => $data['grading']['grading_stats']['total_graded'],
                ],
                'statistics' => $data['statistics'],
                'recent_activity' => array_slice($data['recent_activity'], 0, 5), // Last 5 activities
            ];

            return response()->json([
                'status' => 'success',
                'data' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve dashboard summary',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course lectures for instructor.
     */
    public function getCourseLectures(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $lectures = Lecture::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $lectures
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve lectures',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course assignments for instructor.
     */
    public function getCourseAssignments(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $assignments = Assignment::where('course_id', $courseId)
                ->withCount('submissions')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $assignments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve assignments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course quizzes for instructor.
     */
    public function getCourseQuizzes(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $quizzes = Quiz::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $quizzes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve quizzes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course labs for instructor.
     */
    public function getCourseLabs(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $labs = Lab::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $labs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve labs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course materials for instructor.
     */
    public function getCourseMaterials(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $materials = Material::where('course_id', $courseId)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $materials
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve materials',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course students for instructor.
     */
    public function getCourseStudents(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            // Get enrolled students with their grades and progress
            $students = $course->students()
                ->select('users.id', 'users.name', 'users.email', 'enrollments.created_at as enrolled_date')
                ->get()
                ->map(function ($student) use ($courseId) {
                    // Calculate current grade and progress
                    $submissions = AssignmentSubmission::whereHas('assignment', function ($query) use ($courseId) {
                        $query->where('course_id', $courseId);
                    })
                    ->where('student_id', $student->id)
                    ->where('status', 'graded')
                    ->get();

                    $totalGrade = $submissions->avg('grade') ?? 0;
                    $totalMeals = $submissions->sum('meals') ?? 0;
                    $totalCoins = $submissions->sum('coins') ?? 0;

                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'enrolled_date' => $student->enrolled_date,
                        'current_grade' => round($totalGrade, 2),
                        'total_meals' => $totalMeals,
                        'total_coins' => $totalCoins,
                        'submissions_count' => $submissions->count()
                    ];
                });

            // Calculate analytics
            $analytics = [
                'total_students' => $students->count(),
                'active_students' => $students->where('submissions_count', '>', 0)->count(),
                'average_grade' => $students->avg('current_grade') ?? 0
            ];

            return response()->json([
                'status' => 'success',
                'data' => $students,
                'analytics' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course activity for instructor.
     */
    public function getCourseActivity(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            // Mock activity data - in real implementation, this would come from an activity log
            $activities = [
                [
                    'id' => 1,
                    'type' => 'submission',
                    'description' => 'New assignment submission from John Doe',
                    'created_at' => now()->subHours(2)->format('Y-m-d H:i:s')
                ],
                [
                    'id' => 2,
                    'type' => 'grade',
                    'description' => 'Assignment graded for Jane Smith',
                    'created_at' => now()->subHours(4)->format('Y-m-d H:i:s')
                ],
                [
                    'id' => 3,
                    'type' => 'lecture',
                    'description' => 'New lecture "Introduction to Programming" added',
                    'created_at' => now()->subDays(1)->format('Y-m-d H:i:s')
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $activities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve activity',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending tasks for instructor.
     */
    public function getCoursePendingTasks(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $tasks = [];

            // Check for pending grading
            $pendingGrading = AssignmentSubmission::whereHas('assignment', function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->where('status', 'submitted')
            ->count();

            if ($pendingGrading > 0) {
                $tasks[] = [
                    'id' => 1,
                    'type' => 'grading',
                    'title' => 'Pending Grading',
                    'description' => "{$pendingGrading} submissions need grading"
                ];
            }

            // Check for overdue assignments
            $overdueAssignments = Assignment::where('course_id', $courseId)
                ->where('due_date', '<', now())
                ->whereDoesntHave('submissions')
                ->count();

            if ($overdueAssignments > 0) {
                $tasks[] = [
                    'id' => 2,
                    'type' => 'content',
                    'title' => 'Overdue Assignments',
                    'description' => "{$overdueAssignments} assignments are overdue"
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => $tasks
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pending tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update course settings for instructor.
     */
    public function updateCourseSettings(Request $request, $courseId)
    {
        $instructorId = Auth::id();

        try {
            // Check access
            $hasAccess = CourseAssignment::where('instructor_id', $instructorId)
                ->where('course_id', $courseId)
                ->where('is_active', true)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }

            $course = Course::find($courseId);
            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            // Validate input
            $request->validate([
                'title' => 'sometimes|string|max:255',
                'description' => 'sometimes|string'
            ]);

            // Update only allowed fields (instructors can't change core course data)
            if ($request->has('description')) {
                $course->description = $request->description;
            }

            $course->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Course settings updated successfully',
                'data' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update course settings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

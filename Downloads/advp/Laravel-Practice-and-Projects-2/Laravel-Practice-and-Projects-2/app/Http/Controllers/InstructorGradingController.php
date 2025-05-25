<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Quiz;
use App\Models\Lab;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\GradeHistory;

class InstructorGradingController extends Controller
{
    /**
     * Get all submissions for an assignment.
     */
    public function getAssignmentSubmissions($assignmentId)
    {
        // Check if user is an instructor or manager
        if (!in_array(Auth::user()->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only instructors and managers can view submissions.'
            ], 403);
        }

        // Verify assignment ownership
        $assignment = Assignment::whereHas('course', function($query) {
            $query->where('instructor_id', Auth::id());
        })->with(['course', 'submissions.user', 'submissions.files'])->find($assignmentId);

        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found or you do not have permission to view its submissions'
            ], 404);
        }

        // Format submissions data
        $submissions = $assignment->submissions->map(function($submission) {
            return [
                'id' => $submission->id,
                'student' => [
                    'id' => $submission->user->id,
                    'name' => $submission->user->name,
                    'email' => $submission->user->email
                ],
                'submission_text' => $submission->submission_text,
                'submitted_at' => $submission->submitted_at,
                'status' => $submission->status,
                'grade' => $submission->grade,
                'feedback' => $submission->feedback,
                'files_count' => $submission->files->count(),
                'files' => $submission->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'original_name' => $file->original_name,
                        'file_size' => $file->file_size,
                        'uploaded_at' => $file->created_at
                    ];
                })
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'assignment' => [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->description,
                    'due_date' => $assignment->due_date,
                    'max_score' => $assignment->max_score,
                    'course' => [
                        'id' => $assignment->course->id,
                        'title' => $assignment->course->title,
                        'code' => $assignment->course->code
                    ]
                ],
                'submissions' => $submissions,
                'statistics' => [
                    'total_submissions' => $submissions->count(),
                    'graded_submissions' => $submissions->where('grade', '!=', null)->count(),
                    'pending_submissions' => $submissions->where('grade', null)->count(),
                    'average_grade' => $submissions->where('grade', '!=', null)->avg('grade')
                ]
            ]
        ]);
    }

    /**
     * Grade a submission.
     */
    public function gradeSubmission(Request $request, $submissionId)
    {
        // Check if user is an instructor or manager
        if (!in_array(Auth::user()->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only instructors and managers can grade submissions.'
            ], 403);
        }

        // Verify submission ownership
        $submission = AssignmentSubmission::whereHas('assignment.course', function($query) {
            $query->where('instructor_id', Auth::id());
        })->with(['assignment', 'user'])->find($submissionId);

        if (!$submission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Submission not found or you do not have permission to grade it'
            ], 404);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'grade' => 'required|numeric|min:0|max:' . ($submission->assignment->max_score ?? 100),
            'feedback' => 'nullable|string|max:2000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Update submission with grade and feedback
        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'graded_at' => now(),
            'graded_by' => Auth::id(),
            'status' => 'graded'
        ]);

        // Log grade history for audit trail
        GradeHistory::logGrade(
            $submission->id,
            $submission->user_id,
            Auth::id(),
            $submission->assignment->course_id,
            $request->grade,
            $request->feedback,
            'graded'
        );

        // Log user activity
        UserActivityLog::logActivity(
            Auth::id(),
            'grade_submission',
            'assignment_submission',
            $submission->id,
            [
                'student_id' => $submission->user_id,
                'assignment_id' => $submission->assignment_id,
                'course_id' => $submission->assignment->course_id,
                'grade' => $request->grade,
                'previous_grade' => $submission->getOriginal('grade'),
                'action_type' => $submission->getOriginal('grade') ? 'update' : 'initial_grade'
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Submission graded successfully',
            'data' => [
                'submission_id' => $submission->id,
                'student_name' => $submission->user->name,
                'grade' => $submission->grade,
                'feedback' => $submission->feedback,
                'graded_at' => $submission->graded_at
            ]
        ]);
    }

    /**
     * Get course gradebook.
     */
    public function getCourseGradebook($courseId)
    {
        // Check if user is an instructor or manager
        if (!in_array(Auth::user()->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only instructors and managers can view gradebooks.'
            ], 403);
        }

        // Verify course ownership
        $course = Course::where('id', $courseId)
            ->where('instructor_id', Auth::id())
            ->with(['assignments', 'quizzes', 'labs'])
            ->first();

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found or you do not have permission to view its gradebook'
            ], 404);
        }

        // Get enrolled students
        $students = User::whereHas('enrolledCourses', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with([
            'assignmentSubmissions' => function($query) use ($courseId) {
                $query->whereHas('assignment', function($q) use ($courseId) {
                    $q->where('course_id', $courseId);
                });
            }
        ])->get();

        // Format gradebook data
        $gradebook = $students->map(function($student) use ($course) {
            $assignments = $course->assignments->map(function($assignment) use ($student) {
                $submission = $student->assignmentSubmissions->where('assignment_id', $assignment->id)->first();
                return [
                    'assignment_id' => $assignment->id,
                    'assignment_title' => $assignment->title,
                    'max_score' => $assignment->max_score,
                    'grade' => $submission ? $submission->grade : null,
                    'status' => $submission ? $submission->status : 'not_submitted',
                    'submitted_at' => $submission ? $submission->submitted_at : null
                ];
            });

            $totalPoints = $assignments->sum('max_score');
            $earnedPoints = $assignments->whereNotNull('grade')->sum('grade');
            $percentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;

            return [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email
                ],
                'assignments' => $assignments,
                'summary' => [
                    'total_points' => $totalPoints,
                    'earned_points' => $earnedPoints,
                    'percentage' => $percentage,
                    'letter_grade' => $this->calculateLetterGrade($percentage)
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'code' => $course->code
                ],
                'assignments' => $course->assignments->map(function($assignment) {
                    return [
                        'id' => $assignment->id,
                        'title' => $assignment->title,
                        'max_score' => $assignment->max_score,
                        'due_date' => $assignment->due_date
                    ];
                }),
                'students' => $gradebook,
                'statistics' => [
                    'total_students' => $students->count(),
                    'average_grade' => $gradebook->avg('summary.percentage'),
                    'highest_grade' => $gradebook->max('summary.percentage'),
                    'lowest_grade' => $gradebook->min('summary.percentage')
                ]
            ]
        ]);
    }

    /**
     * Get course analytics.
     */
    public function getCourseAnalytics($courseId)
    {
        // Check if user is an instructor or manager
        if (!in_array(Auth::user()->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only instructors and managers can view analytics.'
            ], 403);
        }

        // Verify course ownership
        $course = Course::where('id', $courseId)
            ->where('instructor_id', Auth::id())
            ->first();

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found or you do not have permission to view its analytics'
            ], 404);
        }

        // Get course statistics
        $enrolledStudents = User::whereHas('enrolledCourses', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->count();

        $assignments = Assignment::where('course_id', $courseId)->count();
        $quizzes = Quiz::where('course_id', $courseId)->count();
        $labs = Lab::where('course_id', $courseId)->count();

        $submissions = AssignmentSubmission::whereHas('assignment', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();

        $gradedSubmissions = $submissions->whereNotNull('grade');
        $averageGrade = $gradedSubmissions->avg('grade');

        // Grade distribution
        $gradeDistribution = [
            'A' => $gradedSubmissions->filter(function($s) { return $s->grade >= 90; })->count(),
            'B' => $gradedSubmissions->filter(function($s) { return $s->grade >= 80 && $s->grade < 90; })->count(),
            'C' => $gradedSubmissions->filter(function($s) { return $s->grade >= 70 && $s->grade < 80; })->count(),
            'D' => $gradedSubmissions->filter(function($s) { return $s->grade >= 60 && $s->grade < 70; })->count(),
            'F' => $gradedSubmissions->filter(function($s) { return $s->grade < 60; })->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'course_overview' => [
                    'enrolled_students' => $enrolledStudents,
                    'total_assignments' => $assignments,
                    'total_quizzes' => $quizzes,
                    'total_labs' => $labs
                ],
                'submission_statistics' => [
                    'total_submissions' => $submissions->count(),
                    'graded_submissions' => $gradedSubmissions->count(),
                    'pending_submissions' => $submissions->whereNull('grade')->count(),
                    'average_grade' => round($averageGrade, 2)
                ],
                'grade_distribution' => $gradeDistribution,
                'recent_activity' => $submissions->sortByDesc('created_at')->take(10)->map(function($submission) {
                    return [
                        'student_name' => $submission->user->name,
                        'assignment_title' => $submission->assignment->title,
                        'submitted_at' => $submission->submitted_at,
                        'status' => $submission->status
                    ];
                })->values()
            ]
        ]);
    }

    /**
     * Get enrolled students for a course.
     */
    public function getCourseStudents($courseId)
    {
        // Check if user is an instructor or manager
        if (!in_array(Auth::user()->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only instructors and managers can view student lists.'
            ], 403);
        }

        // Verify course ownership
        $course = Course::where('id', $courseId)
            ->where('instructor_id', Auth::id())
            ->first();

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found or you do not have permission to view its students'
            ], 404);
        }

        // Get enrolled students with their progress
        $students = User::whereHas('enrolledCourses', function($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->with([
            'assignmentSubmissions' => function($query) use ($courseId) {
                $query->whereHas('assignment', function($q) use ($courseId) {
                    $q->where('course_id', $courseId);
                });
            }
        ])->get();

        $studentsData = $students->map(function($student) use ($courseId) {
            $submissions = $student->assignmentSubmissions;
            $gradedSubmissions = $submissions->whereNotNull('grade');
            $averageGrade = $gradedSubmissions->avg('grade');

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'enrolled_at' => $student->enrolledCourses->where('id', $courseId)->first()->pivot->created_at ?? null,
                'progress' => [
                    'total_submissions' => $submissions->count(),
                    'graded_submissions' => $gradedSubmissions->count(),
                    'average_grade' => round($averageGrade, 2),
                    'letter_grade' => $this->calculateLetterGrade($averageGrade)
                ]
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'code' => $course->code
                ],
                'students' => $studentsData,
                'statistics' => [
                    'total_students' => $students->count(),
                    'average_grade' => $studentsData->avg('progress.average_grade'),
                    'active_students' => $studentsData->filter(function($s) {
                        return $s['progress']['total_submissions'] > 0;
                    })->count()
                ]
            ]
        ]);
    }

    /**
     * Get grade distribution for instructor's courses.
     */
    public function getGradeDistribution(Request $request)
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
            // Get all assigned courses for the instructor
            $assignedCourseIds = CourseAssignment::where('instructor_id', $instructorId)
                ->where('is_active', true)
                ->pluck('course_id');

            // Get all graded submissions from instructor's assigned courses
            $submissions = AssignmentSubmission::whereHas('assignment', function ($query) use ($assignedCourseIds) {
                $query->whereIn('course_id', $assignedCourseIds);
            })
            ->whereNotNull('grade')
            ->where('grade', '>', 0)
            ->get();

            // Calculate grade distribution
            $gradeDistribution = [
                'A' => 0, // 90-100
                'B' => 0, // 80-89
                'C' => 0, // 70-79
                'D' => 0, // 60-69
                'F' => 0  // 0-59
            ];

            $totalGrades = $submissions->count();

            foreach ($submissions as $submission) {
                $grade = $submission->grade;

                if ($grade >= 90) {
                    $gradeDistribution['A']++;
                } elseif ($grade >= 80) {
                    $gradeDistribution['B']++;
                } elseif ($grade >= 70) {
                    $gradeDistribution['C']++;
                } elseif ($grade >= 60) {
                    $gradeDistribution['D']++;
                } else {
                    $gradeDistribution['F']++;
                }
            }

            // Calculate percentages
            $gradePercentages = [];
            foreach ($gradeDistribution as $grade => $count) {
                $gradePercentages[$grade] = $totalGrades > 0 ? round(($count / $totalGrades) * 100, 1) : 0;
            }

            // Get course-specific grade distributions
            $courseDistributions = [];
            foreach ($assignedCourseIds as $courseId) {
                $course = Course::find($courseId);
                if (!$course) continue;

                $courseSubmissions = AssignmentSubmission::whereHas('assignment', function ($query) use ($courseId) {
                    $query->where('course_id', $courseId);
                })
                ->whereNotNull('grade')
                ->where('grade', '>', 0)
                ->get();

                $courseTotalGrades = $courseSubmissions->count();
                $courseGradeDistribution = [
                    'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
                ];

                foreach ($courseSubmissions as $submission) {
                    $grade = $submission->grade;

                    if ($grade >= 90) {
                        $courseGradeDistribution['A']++;
                    } elseif ($grade >= 80) {
                        $courseGradeDistribution['B']++;
                    } elseif ($grade >= 70) {
                        $courseGradeDistribution['C']++;
                    } elseif ($grade >= 60) {
                        $courseGradeDistribution['D']++;
                    } else {
                        $courseGradeDistribution['F']++;
                    }
                }

                $courseDistributions[] = [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'course_code' => $course->code,
                    'total_grades' => $courseTotalGrades,
                    'distribution' => $courseGradeDistribution,
                    'average_grade' => $courseSubmissions->avg('grade') ? round($courseSubmissions->avg('grade'), 2) : 0
                ];
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'overall_distribution' => [
                        'counts' => $gradeDistribution,
                        'percentages' => $gradePercentages,
                        'total_grades' => $totalGrades
                    ],
                    'course_distributions' => $courseDistributions,
                    'statistics' => [
                        'total_submissions' => $totalGrades,
                        'average_grade' => $submissions->avg('grade') ? round($submissions->avg('grade'), 2) : 0,
                        'highest_grade' => $submissions->max('grade') ?? 0,
                        'lowest_grade' => $submissions->min('grade') ?? 0,
                        'courses_count' => $assignedCourseIds->count()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve grade distribution',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate letter grade from percentage.
     */
    private function calculateLetterGrade($percentage)
    {
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }
}

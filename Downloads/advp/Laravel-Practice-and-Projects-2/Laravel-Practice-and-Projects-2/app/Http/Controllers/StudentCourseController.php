<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    /**
     * Get course details for enrolled student
     */
    public function getCourseDetails(Request $request, $courseId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check if student is enrolled in the course
        $enrollment = $user->enrolledCourses()->where('course_id', $courseId)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $course = Course::with(['instructor', 'lectures', 'assignments', 'quizzes'])
            ->find($courseId);

        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        // Calculate course progress
        $progress = $this->calculateCourseProgress($courseId, $user->id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'course' => $course,
                'enrollment' => $enrollment->pivot,
                'progress' => $progress
            ]
        ]);
    }

    /**
     * Get course content overview
     */
    public function getCourseContent(Request $request, $courseId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $courseId)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $course = Course::with([
            'lectures' => function($query) {
                $query->where('is_visible', true)->orderBy('created_at');
            },
            'assignments' => function($query) {
                $query->where('is_visible', true)->orderBy('due_date');
            },
            'quizzes' => function($query) {
                $query->where('is_published', true)->orderBy('start_time');
            }
        ])->find($courseId);

        return response()->json([
            'status' => 'success',
            'data' => [
                'lectures' => $course->lectures,
                'assignments' => $course->assignments,
                'quizzes' => $course->quizzes,
                'content_summary' => [
                    'total_lectures' => $course->lectures->count(),
                    'total_assignments' => $course->assignments->count(),
                    'total_quizzes' => $course->quizzes->count()
                ]
            ]
        ]);
    }

    /**
     * Get detailed course progress
     */
    public function getCourseProgress(Request $request, $courseId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $courseId)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $progress = $this->calculateDetailedProgress($courseId, $user->id);

        return response()->json([
            'status' => 'success',
            'data' => $progress
        ]);
    }

    /**
     * Calculate enhanced course progress with real tracking data
     */
    private function calculateCourseProgress($courseId, $userId)
    {
        $course = Course::find($courseId);

        $totalLectures = $course->lectures()->where('is_visible', true)->count();
        $totalAssignments = $course->assignments()->where('is_visible', true)->count();
        $totalQuizzes = $course->quizzes()->where('is_published', true)->count();

        // Calculate actual progress from tracking tables
        $completedLectures = $course->lectures()
            ->where('is_visible', true)
            ->whereHas('progress', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('completed', true);
            })->count();

        $attendedLectures = $course->lectures()
            ->where('is_visible', true)
            ->whereHas('progress', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('attended', true);
            })->count();

        $submittedAssignments = $course->assignments()
            ->where('is_visible', true)
            ->whereHas('submissions', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'submitted');
            })->count();

        $gradedAssignments = $course->assignments()
            ->where('is_visible', true)
            ->whereHas('submissions', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'graded');
            })->count();

        $completedQuizzes = $course->quizzes()
            ->where('is_published', true)
            ->whereHas('attempts', function($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'completed');
            })->count();

        // Calculate weighted progress (lectures 40%, assignments 40%, quizzes 20%)
        $lectureProgress = $totalLectures > 0 ? ($completedLectures / $totalLectures) * 40 : 0;
        $assignmentProgress = $totalAssignments > 0 ? ($submittedAssignments / $totalAssignments) * 40 : 0;
        $quizProgress = $totalQuizzes > 0 ? ($completedQuizzes / $totalQuizzes) * 20 : 0;

        $overallProgress = round($lectureProgress + $assignmentProgress + $quizProgress, 2);

        return [
            'overall_progress' => $overallProgress,
            'lectures' => [
                'total' => $totalLectures,
                'completed' => $completedLectures,
                'attended' => $attendedLectures,
                'progress_percentage' => $totalLectures > 0 ? round(($completedLectures / $totalLectures) * 100, 2) : 0
            ],
            'assignments' => [
                'total' => $totalAssignments,
                'submitted' => $submittedAssignments,
                'graded' => $gradedAssignments,
                'progress_percentage' => $totalAssignments > 0 ? round(($submittedAssignments / $totalAssignments) * 100, 2) : 0
            ],
            'quizzes' => [
                'total' => $totalQuizzes,
                'completed' => $completedQuizzes,
                'progress_percentage' => $totalQuizzes > 0 ? round(($completedQuizzes / $totalQuizzes) * 100, 2) : 0
            ]
        ];
    }

    /**
     * Calculate detailed progress with individual item status
     */
    private function calculateDetailedProgress($courseId, $userId)
    {
        $course = Course::with(['lectures', 'assignments', 'quizzes'])->find($courseId);

        $lectureProgress = $course->lectures->map(function($lecture) use ($userId) {
            return [
                'id' => $lecture->id,
                'title' => $lecture->title,
                'completed' => false, // TODO: Implement lecture progress tracking
                'progress_percentage' => 0
            ];
        });

        $assignmentProgress = $course->assignments->map(function($assignment) use ($userId) {
            $submission = $assignment->submissions()->where('user_id', $userId)->first();
            return [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'due_date' => $assignment->due_date,
                'submitted' => $submission ? true : false,
                'status' => $submission ? $submission->status : 'not_submitted',
                'grade' => $submission ? $submission->grade : null
            ];
        });

        $quizProgress = $course->quizzes->map(function($quiz) use ($userId) {
            // TODO: Implement quiz attempts tracking
            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'completed' => false,
                'attempts' => 0,
                'best_score' => null
            ];
        });

        return [
            'lectures' => $lectureProgress,
            'assignments' => $assignmentProgress,
            'quizzes' => $quizProgress
        ];
    }
}

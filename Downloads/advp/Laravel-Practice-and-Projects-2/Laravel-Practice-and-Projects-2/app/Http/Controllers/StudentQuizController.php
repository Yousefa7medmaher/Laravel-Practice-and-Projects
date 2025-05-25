<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StudentQuizController extends Controller
{
    /**
     * Get quizzes for a course
     */
    public function getCourseQuizzes(Request $request, $courseId)
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

        $quizzes = Quiz::where('course_id', $courseId)
            ->where('is_published', true)
            ->orderBy('start_time')
            ->get();

        // Add enhanced attempt information for each quiz
        $quizzes->each(function($quiz) use ($user) {
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $bestAttempt = $attempts->where('status', 'completed')->sortByDesc('score')->first();
            $latestAttempt = $attempts->first();
            $completedAttempts = $attempts->where('status', 'completed');

            $quiz->attempts_taken = $attempts->count();
            $quiz->completed_attempts = $completedAttempts->count();
            $quiz->can_attempt = $this->canAttemptQuiz($quiz, $user);
            $quiz->best_score = $bestAttempt ? $bestAttempt->score : null;
            $quiz->latest_score = $latestAttempt && $latestAttempt->status === 'completed' ? $latestAttempt->score : null;
            $quiz->is_available = $this->isQuizAvailable($quiz);

            // Set completion status
            if ($completedAttempts->count() > 0) {
                $quiz->completion_status = 'completed';
                $quiz->completion_badge = ['text' => 'Completed', 'color' => 'green', 'icon' => 'check-circle'];
                $quiz->completed_at = $bestAttempt ? $bestAttempt->completed_at : null;
                $quiz->formatted_completion_date = $bestAttempt ? $bestAttempt->formatted_completion_date : null;

                // Add performance level
                if ($bestAttempt) {
                    $quiz->performance_level = $bestAttempt->performance_level;
                }
            } elseif ($attempts->where('status', 'in_progress')->count() > 0) {
                $quiz->completion_status = 'in_progress';
                $quiz->completion_badge = ['text' => 'In Progress', 'color' => 'blue', 'icon' => 'clock'];
                $quiz->completed_at = null;
                $quiz->formatted_completion_date = null;
            } else {
                $quiz->completion_status = 'not_attempted';
                $quiz->completion_badge = ['text' => 'Not Attempted', 'color' => 'gray', 'icon' => 'circle'];
                $quiz->completed_at = null;
                $quiz->formatted_completion_date = null;
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $quizzes
        ]);
    }

    /**
     * Get specific quiz details
     */
    public function getQuiz(Request $request, $quizId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $quiz = Quiz::with(['course'])->find($quizId);

        if (!$quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found'
            ], 404);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $quiz->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        // Get attempt information
        $attempts = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $quiz->attempts = $attempts;
        $quiz->attempts_taken = $attempts->count();
        $quiz->can_attempt = $this->canAttemptQuiz($quiz, $user);
        $quiz->is_available = $this->isQuizAvailable($quiz);

        return response()->json([
            'status' => 'success',
            'data' => $quiz
        ]);
    }

    /**
     * Start a quiz attempt
     */
    public function startQuiz(Request $request, $quizId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $quiz = Quiz::find($quizId);

        if (!$quiz) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found'
            ], 404);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $quiz->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        // Check if can attempt
        if (!$this->canAttemptQuiz($quiz, $user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot attempt this quiz at this time'
            ], 400);
        }

        // Check if quiz is available
        if (!$this->isQuizAvailable($quiz)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz is not available at this time'
            ], 400);
        }

        // Check for existing active attempt
        $activeAttempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if ($activeAttempt) {
            return response()->json([
                'status' => 'success',
                'message' => 'Resuming existing attempt',
                'data' => $activeAttempt
            ]);
        }

        // Create new attempt
        $attemptNumber = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->count() + 1;

        $attempt = QuizAttempt::create([
            'quiz_id' => $quizId,
            'user_id' => $user->id,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'started_at' => now(),
            'expires_at' => now()->addMinutes($quiz->duration_minutes ?? 60),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz attempt started',
            'data' => $attempt
        ], 201);
    }

    /**
     * Submit quiz attempt
     */
    public function submitQuiz(Request $request, $quizId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        // Find active attempt
        $attempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            return response()->json([
                'status' => 'error',
                'message' => 'No active quiz attempt found'
            ], 404);
        }

        // Check if attempt has expired
        if ($attempt->expires_at && now() > $attempt->expires_at) {
            $attempt->update([
                'status' => 'expired',
                'completed_at' => now()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Quiz attempt has expired'
            ], 400);
        }

        // Validate answers
        $validator = Validator::make($request->all(), [
            'answers' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // For now, store answers and calculate a basic score
        // This would be enhanced with actual quiz questions and scoring logic
        $answers = $request->answers;
        $score = $this->calculateQuizScore($quizId, $answers);

        $attempt->update([
            'answers' => json_encode($answers),
            'score' => $score,
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz submitted successfully',
            'data' => [
                'attempt' => $attempt,
                'score' => $score
            ]
        ]);
    }

    /**
     * Get quiz attempts for a student
     */
    public function getQuizAttempts(Request $request, $quizId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $attempts = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $attempts
        ]);
    }

    /**
     * Get quiz results for a specific attempt
     */
    public function getQuizResults(Request $request, $quizId, $attemptId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $attempt = QuizAttempt::where('id', $attemptId)
            ->where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->with(['quiz'])
            ->first();

        if (!$attempt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz attempt not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $attempt
        ]);
    }

    /**
     * Check if student can attempt quiz
     */
    private function canAttemptQuiz($quiz, $user)
    {
        // Check if quiz is published
        if (!$quiz->is_published) {
            return false;
        }

        // Check attempt limits
        if ($quiz->max_attempts !== 'unlimited') {
            $attempts = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->count();

            if ($attempts >= (int)$quiz->max_attempts) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if quiz is available based on time restrictions
     */
    private function isQuizAvailable($quiz)
    {
        $now = now();

        if ($quiz->start_time && $now < $quiz->start_time) {
            return false;
        }

        if ($quiz->end_time && $now > $quiz->end_time) {
            return false;
        }

        return true;
    }

    /**
     * Calculate quiz score (basic implementation)
     */
    private function calculateQuizScore($quizId, $answers)
    {
        // This is a placeholder implementation
        // In a real system, this would compare answers against correct answers
        $quiz = Quiz::find($quizId);
        $maxScore = $quiz->max_score ?? 100;

        // For demo purposes, return a random score between 60-100
        return rand(60, $maxScore);
    }
}

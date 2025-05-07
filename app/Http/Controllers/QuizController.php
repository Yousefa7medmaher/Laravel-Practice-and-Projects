<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Display a listing of the quizzes for a course.
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $quizzes = $course->quizzes()->orderBy('start_time')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $quizzes
        ]);
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is authorized to add quizzes to the course
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can add quizzes.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'nullable|integer|min:1',
            'max_score' => 'nullable|integer|min:1',
            'is_published' => 'nullable|boolean',
        ]);

        // Create the quiz
        $quiz = $course->quizzes()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration_minutes' => $validated['duration_minutes'] ?? 60,
            'max_score' => $validated['max_score'] ?? 100,
            'is_published' => $validated['is_published'] ?? false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz created successfully',
            'data' => $quiz
        ], 201);
    }

    /**
     * Display the specified quiz.
     */
    public function show($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $quiz
        ]);
    }

    /**
     * Update the specified quiz in storage.
     */
    public function update(Request $request, $courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($id);
        
        // Check if user is authorized to update the quiz
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can update quizzes.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'duration_minutes' => 'nullable|integer|min:1',
            'max_score' => 'nullable|integer|min:1',
            'is_published' => 'nullable|boolean',
        ]);

        // Update the quiz
        $quiz->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz updated successfully',
            'data' => $quiz
        ]);
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $quiz = $course->quizzes()->findOrFail($id);
        
        // Check if user is authorized to delete the quiz
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can delete quizzes.'
            ], 403);
        }

        $quiz->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Quiz deleted successfully'
        ]);
    }
}

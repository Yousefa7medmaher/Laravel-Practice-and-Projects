<?php

namespace App\Http\Controllers;

use App\Models\CourseAssignment;
use App\Models\Lab;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstructorQuizLabController extends Controller
{
    /**
     * Verify instructor has access to course
     */
    private function hasAccessToCourse($courseId)
    {
        return CourseAssignment::where('course_id', $courseId)
            ->where('instructor_id', Auth::id())
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Verify instructor owns content item
     */
    private function hasAccessToContent($contentType, $contentId)
    {
        $model = $contentType === 'quizzes' ? Quiz::class : Lab::class;
        $content = $model::find($contentId);

        if (!$content) {
            return false;
        }

        return $this->hasAccessToCourse($content->course_id);
    }

    // ==================== QUIZZES ====================

    /**
     * Create a new quiz
     */
    public function createQuiz(Request $request, $courseId)
    {
        if (!$this->hasAccessToCourse($courseId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to manage this course'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:5|max:300',
            'max_score' => 'nullable|integer|min:1|max:1000',
            'max_attempts' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'instructions' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $quiz = Quiz::create([
                'course_id' => $courseId,
                'title' => $request->title,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes ?? 60,
                'max_score' => $request->max_score ?? 100,
                'max_attempts' => $request->max_attempts ?? '3',
                'start_time' => $request->start_time ?? now(),
                'end_time' => $request->end_time ?? now()->addDays(7),
                'instructions' => $request->instructions,
                'is_published' => $request->boolean('is_published', true),
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz created successfully',
                'data' => $quiz
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific quiz
     */
    public function getQuiz($quizId)
    {
        if (!$this->hasAccessToContent('quizzes', $quizId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found or access denied'
            ], 404);
        }

        $quiz = Quiz::with('course:id,title,code')->find($quizId);

        return response()->json([
            'status' => 'success',
            'data' => $quiz
        ]);
    }

    /**
     * Update a quiz
     */
    public function updateQuiz(Request $request, $quizId)
    {
        if (!$this->hasAccessToContent('quizzes', $quizId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:5|max:300',
            'max_score' => 'nullable|integer|min:1|max:1000',
            'max_attempts' => 'nullable|string',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'instructions' => 'nullable|string',
            'is_published' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $quiz = Quiz::find($quizId);

            $quiz->update([
                'title' => $request->title,
                'description' => $request->description,
                'duration_minutes' => $request->duration_minutes ?? $quiz->duration_minutes,
                'max_score' => $request->max_score ?? $quiz->max_score,
                'max_attempts' => $request->max_attempts ?? $quiz->max_attempts,
                'start_time' => $request->start_time ?? $quiz->start_time,
                'end_time' => $request->end_time ?? $quiz->end_time,
                'instructions' => $request->instructions,
                'is_published' => $request->boolean('is_published', $quiz->is_published),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz updated successfully',
                'data' => $quiz->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a quiz
     */
    public function deleteQuiz($quizId)
    {
        if (!$this->hasAccessToContent('quizzes', $quizId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Quiz not found or access denied'
            ], 404);
        }

        try {
            $quiz = Quiz::find($quizId);

            // Check if there are submissions
            if (method_exists($quiz, 'submissions') && $quiz->submissions()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete quiz with existing submissions'
                ], 400);
            }

            $quiz->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Quiz deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete quiz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all quizzes for instructor
     */
    public function getQuizzes()
    {
        try {
            $courseIds = CourseAssignment::where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->pluck('course_id');

            $quizzes = Quiz::whereIn('course_id', $courseIds)
                ->with('course:id,title,code')
                ->orderBy('start_time', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $quizzes
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch quizzes: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== LABS ====================

    /**
     * Create a new lab
     */
    public function createLab(Request $request, $courseId)
    {
        if (!$this->hasAccessToCourse($courseId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to manage this course'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'max_score' => 'nullable|integer|min:1|max:1000',
            'due_date' => 'nullable|date|after:now',
            'equipment' => 'nullable|string',
            'duration' => 'nullable|integer|min:30|max:480',
            'allow_late_submission' => 'boolean',
            'is_visible' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,txt|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Handle file upload
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('labs', $fileName, 'public');
            }

            $lab = Lab::create([
                'course_id' => $courseId,
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'max_score' => $request->max_score ?? 100,
                'due_date' => $request->due_date,
                'equipment' => $request->equipment,
                'duration' => $request->duration ?? 120,
                'allow_late_submission' => $request->boolean('allow_late_submission', false),
                'is_visible' => $request->boolean('is_visible', true),
                'file_path' => $filePath,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Lab created successfully',
                'data' => $lab
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create lab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific lab
     */
    public function getLab($labId)
    {
        if (!$this->hasAccessToContent('labs', $labId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lab not found or access denied'
            ], 404);
        }

        $lab = Lab::with('course:id,title,code')->find($labId);

        return response()->json([
            'status' => 'success',
            'data' => $lab
        ]);
    }

    /**
     * Update a lab
     */
    public function updateLab(Request $request, $labId)
    {
        if (!$this->hasAccessToContent('labs', $labId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lab not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'max_score' => 'nullable|integer|min:1|max:1000',
            'due_date' => 'nullable|date',
            'equipment' => 'nullable|string',
            'duration' => 'nullable|integer|min:30|max:480',
            'allow_late_submission' => 'boolean',
            'is_visible' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip,txt|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $lab = Lab::find($labId);

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($lab->file_path && Storage::exists($lab->file_path)) {
                    Storage::delete($lab->file_path);
                }

                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('labs', $fileName, 'public');
                $lab->file_path = $filePath;
            }

            $lab->update([
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'max_score' => $request->max_score ?? $lab->max_score,
                'due_date' => $request->due_date,
                'equipment' => $request->equipment,
                'duration' => $request->duration ?? $lab->duration,
                'allow_late_submission' => $request->boolean('allow_late_submission', $lab->allow_late_submission),
                'is_visible' => $request->boolean('is_visible', $lab->is_visible),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Lab updated successfully',
                'data' => $lab->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update lab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a lab
     */
    public function deleteLab($labId)
    {
        if (!$this->hasAccessToContent('labs', $labId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lab not found or access denied'
            ], 404);
        }

        try {
            $lab = Lab::find($labId);

            // Check if there are submissions
            if (method_exists($lab, 'submissions') && $lab->submissions()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete lab with existing submissions'
                ], 400);
            }

            // Delete associated file if exists
            if ($lab->file_path && Storage::exists($lab->file_path)) {
                Storage::delete($lab->file_path);
            }

            $lab->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Lab deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete lab: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all labs for instructor
     */
    public function getLabs()
    {
        try {
            $courseIds = CourseAssignment::where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->pluck('course_id');

            $labs = Lab::whereIn('course_id', $courseIds)
                ->with('course:id,title,code')
                ->orderBy('due_date', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $labs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch labs: ' . $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\Lab;
use App\Models\Lecture;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstructorContentController extends Controller
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
        $model = $this->getModelClass($contentType);
        $content = $model::find($contentId);

        if (!$content) {
            return false;
        }

        return $this->hasAccessToCourse($content->course_id);
    }

    /**
     * Get model class for content type
     */
    private function getModelClass($contentType)
    {
        $models = [
            'lectures' => Lecture::class,
            'assignments' => Assignment::class,
            'quizzes' => Quiz::class,
            'labs' => Lab::class,
        ];

        return $models[$contentType] ?? null;
    }

    // ==================== LECTURES ====================

    /**
     * Create a new lecture
     */
    public function createLecture(Request $request, $courseId)
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
            'content' => 'nullable|string',
            'objectives' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1|max:600',
            'scheduled_date' => 'nullable|date',
            'is_visible' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240'
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
                $filePath = $file->storeAs('lectures', $fileName, 'public');
            }

            $lecture = Lecture::create([
                'course_id' => $courseId,
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'objectives' => $request->objectives,
                'video_url' => $request->video_url,
                'duration' => $request->duration ?? 60,
                'scheduled_date' => $request->scheduled_date,
                'is_visible' => $request->boolean('is_visible', true),
                'file_path' => $filePath,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Lecture created successfully',
                'data' => $lecture
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create lecture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific lecture
     */
    public function getLecture($lectureId)
    {
        if (!$this->hasAccessToContent('lectures', $lectureId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture not found or access denied'
            ], 404);
        }

        $lecture = Lecture::with('course:id,title,code')->find($lectureId);

        return response()->json([
            'status' => 'success',
            'data' => $lecture
        ]);
    }

    /**
     * Update a lecture
     */
    public function updateLecture(Request $request, $lectureId)
    {
        if (!$this->hasAccessToContent('lectures', $lectureId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'objectives' => 'nullable|string',
            'video_url' => 'nullable|url',
            'duration' => 'nullable|integer|min:1|max:600',
            'scheduled_date' => 'nullable|date',
            'is_visible' => 'boolean',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $lecture = Lecture::find($lectureId);

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($lecture->file_path && Storage::exists($lecture->file_path)) {
                    Storage::delete($lecture->file_path);
                }

                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('lectures', $fileName, 'public');
                $lecture->file_path = $filePath;
            }

            $lecture->update([
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'objectives' => $request->objectives,
                'video_url' => $request->video_url,
                'duration' => $request->duration ?? $lecture->duration,
                'scheduled_date' => $request->scheduled_date,
                'is_visible' => $request->boolean('is_visible', $lecture->is_visible),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Lecture updated successfully',
                'data' => $lecture->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update lecture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a lecture
     */
    public function deleteLecture($lectureId)
    {
        if (!$this->hasAccessToContent('lectures', $lectureId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture not found or access denied'
            ], 404);
        }

        try {
            $lecture = Lecture::find($lectureId);

            // Delete associated file if exists
            if ($lecture->file_path && Storage::exists($lecture->file_path)) {
                Storage::delete($lecture->file_path);
            }

            $lecture->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Lecture deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete lecture: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all lectures for instructor
     */
    public function getLectures()
    {
        try {
            $courseIds = CourseAssignment::where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->pluck('course_id');

            $lectures = Lecture::whereIn('course_id', $courseIds)
                ->with('course:id,title,code')
                ->orderBy('scheduled_date', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $lectures
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch lectures: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== ASSIGNMENTS ====================

    /**
     * Create a new assignment
     */
    public function createAssignment(Request $request, $courseId)
    {
        if (!$this->hasAccessToCourse($courseId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to manage this course'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'max_score' => 'nullable|integer|min:1|max:1000',
            'due_date' => 'nullable|date|after:now',
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
                $filePath = $file->storeAs('assignments', $fileName, 'public');
            }

            $assignment = Assignment::create([
                'course_id' => $courseId,
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'max_score' => $request->max_score ?? 100,
                'due_date' => $request->due_date,
                'allow_late_submission' => $request->boolean('allow_late_submission', false),
                'is_visible' => $request->boolean('is_visible', true),
                'file_path' => $filePath,
                'created_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Assignment created successfully',
                'data' => $assignment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific assignment
     */
    public function getAssignment($assignmentId)
    {
        if (!$this->hasAccessToContent('assignments', $assignmentId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found or access denied'
            ], 404);
        }

        $assignment = Assignment::with('course:id,title,code')->find($assignmentId);

        return response()->json([
            'status' => 'success',
            'data' => $assignment
        ]);
    }

    /**
     * Update an assignment
     */
    public function updateAssignment(Request $request, $assignmentId)
    {
        if (!$this->hasAccessToContent('assignments', $assignmentId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found or access denied'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'max_score' => 'nullable|integer|min:1|max:1000',
            'due_date' => 'nullable|date',
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
            $assignment = Assignment::find($assignmentId);

            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($assignment->file_path && Storage::exists($assignment->file_path)) {
                    Storage::delete($assignment->file_path);
                }

                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('assignments', $fileName, 'public');
                $assignment->file_path = $filePath;
            }

            $assignment->update([
                'title' => $request->title,
                'description' => $request->description,
                'instructions' => $request->instructions,
                'max_score' => $request->max_score ?? $assignment->max_score,
                'due_date' => $request->due_date,
                'allow_late_submission' => $request->boolean('allow_late_submission', $assignment->allow_late_submission),
                'is_visible' => $request->boolean('is_visible', $assignment->is_visible),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Assignment updated successfully',
                'data' => $assignment->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an assignment
     */
    public function deleteAssignment($assignmentId)
    {
        if (!$this->hasAccessToContent('assignments', $assignmentId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found or access denied'
            ], 404);
        }

        try {
            $assignment = Assignment::find($assignmentId);

            // Check if there are submissions
            if ($assignment->submissions()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete assignment with existing submissions'
                ], 400);
            }

            // Delete associated file if exists
            if ($assignment->file_path && Storage::exists($assignment->file_path)) {
                Storage::delete($assignment->file_path);
            }

            $assignment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Assignment deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all assignments for instructor
     */
    public function getAssignments()
    {
        try {
            $courseIds = CourseAssignment::where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->pluck('course_id');

            $assignments = Assignment::whereIn('course_id', $courseIds)
                ->with('course:id,title,code')
                ->orderBy('due_date', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $assignments
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch assignments: ' . $e->getMessage()
            ], 500);
        }
    }
}

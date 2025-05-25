<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentAssignmentController extends Controller
{
    /**
     * Get assignments for a course
     */
    public function getCourseAssignments(Request $request, $courseId)
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

        $assignments = Assignment::where('course_id', $courseId)
            ->where('is_visible', true)
            ->with(['submissions' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('due_date')
            ->get();

        // Add enhanced submission status to each assignment
        $assignments->each(function($assignment) use ($user) {
            $submission = $assignment->submissions->first();

            if ($submission) {
                $assignment->submission_status = $submission->status;
                $assignment->submission_id = $submission->id;
                $assignment->is_late = $submission->is_late;
                $assignment->grade = $submission->grade;
                $assignment->feedback = $submission->feedback;
                $assignment->submitted_at = $submission->submitted_at;
                $assignment->graded_at = $submission->graded_at;
                $assignment->status_badge = $submission->status_badge;
                $assignment->formatted_submission_date = $submission->formatted_submission_date;
                $assignment->grade_display = $submission->grade_display;
                $assignment->is_overdue = $submission->isOverdue();
                $assignment->is_late_submission = $submission->isLateSubmission();
            } else {
                // Check if assignment is overdue without submission
                $isOverdue = $assignment->due_date && now() > $assignment->due_date;

                $assignment->submission_status = 'not_submitted';
                $assignment->submission_id = null;
                $assignment->is_late = false;
                $assignment->grade = null;
                $assignment->feedback = null;
                $assignment->submitted_at = null;
                $assignment->graded_at = null;
                $assignment->formatted_submission_date = 'Not submitted';
                $assignment->grade_display = null;
                $assignment->is_overdue = $isOverdue;
                $assignment->is_late_submission = false;

                if ($isOverdue) {
                    $assignment->status_badge = ['text' => 'Overdue', 'color' => 'red', 'icon' => 'exclamation-triangle'];
                } else {
                    $assignment->status_badge = ['text' => 'Not Submitted', 'color' => 'gray', 'icon' => 'circle'];
                }
            }

            $assignment->can_submit = $this->canSubmitAssignment($assignment, $user);
            unset($assignment->submissions); // Remove the submissions relation to clean up response
        });

        return response()->json([
            'status' => 'success',
            'data' => $assignments
        ]);
    }

    /**
     * Get specific assignment details
     */
    public function getAssignment(Request $request, $assignmentId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $assignment = Assignment::with(['course'])->find($assignmentId);

        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found'
            ], 404);
        }

        // Check enrollment in the course
        $enrollment = $user->enrolledCourses()->where('course_id', $assignment->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        // Get student's submission if exists
        $submission = AssignmentSubmission::where('assignment_id', $assignmentId)
            ->where('user_id', $user->id)
            ->first();

        $assignment->submission = $submission;
        $assignment->can_submit = $this->canSubmitAssignment($assignment, $user);
        $assignment->is_overdue = $assignment->due_date && now() > $assignment->due_date;

        return response()->json([
            'status' => 'success',
            'data' => $assignment
        ]);
    }

    /**
     * Submit assignment
     */
    public function submitAssignment(Request $request, $assignmentId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $assignment = Assignment::find($assignmentId);

        if (!$assignment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment not found'
            ], 404);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $assignment->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        // Check if student can submit
        if (!$this->canSubmitAssignment($assignment, $user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot submit this assignment at this time'
            ], 400);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,zip|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if submission already exists
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignmentId)
            ->where('user_id', $user->id)
            ->first();

        if ($existingSubmission && $existingSubmission->status === 'submitted') {
            return response()->json([
                'status' => 'error',
                'message' => 'Assignment already submitted'
            ], 400);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignment_submissions', $fileName, 'public');
        }

        // Check if submission is late
        $isLate = $assignment->due_date && now() > $assignment->due_date;

        if ($isLate && !$assignment->allow_late_submission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Late submissions are not allowed for this assignment'
            ], 400);
        }

        // Create or update submission
        $submissionData = [
            'assignment_id' => $assignmentId,
            'user_id' => $user->id,
            'submission_text' => $request->submission_text,
            'status' => 'submitted',
            'submitted_at' => now(),
            'is_late' => $isLate,
            'attempt_number' => 1, // For now, single attempt
        ];

        if ($filePath) {
            $submissionData['submission_data'] = json_encode(['file_path' => $filePath]);
        }

        if ($existingSubmission) {
            $existingSubmission->update($submissionData);
            $submission = $existingSubmission;
        } else {
            $submission = AssignmentSubmission::create($submissionData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment submitted successfully',
            'data' => $submission
        ], 201);
    }

    /**
     * Get student's submission for an assignment
     */
    public function getSubmission(Request $request, $assignmentId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $submission = AssignmentSubmission::where('assignment_id', $assignmentId)
            ->where('user_id', $user->id)
            ->with(['assignment'])
            ->first();

        if (!$submission) {
            return response()->json([
                'status' => 'error',
                'message' => 'No submission found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $submission
        ]);
    }

    /**
     * Check if student can submit assignment
     */
    private function canSubmitAssignment($assignment, $user)
    {
        // Check if assignment is visible
        if (!$assignment->is_visible) {
            return false;
        }

        // Check if already submitted and resubmission is not allowed
        $existingSubmission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingSubmission && $existingSubmission->status === 'submitted') {
            return false; // For now, no resubmissions allowed
        }

        // Check if past due date and late submissions not allowed
        if ($assignment->due_date && now() > $assignment->due_date && !$assignment->allow_late_submission) {
            return false;
        }

        return true;
    }
}

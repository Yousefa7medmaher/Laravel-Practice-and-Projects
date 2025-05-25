<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SubmissionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Services\NotificationService;
use Carbon\Carbon;

class AssignmentSubmissionController extends Controller
{
    /**
     * Get all submissions for a user (for dashboard/overview).
     */
    public function getUserSubmissions(Request $request)
    {
        try {
            $user = Auth::user();

            // Get all submissions for the user with assignment and course data
            $submissions = AssignmentSubmission::with(['assignment.course', 'files'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $submissionsData = $submissions->map(function($submission) {
                return [
                    'id' => $submission->id,
                    'assignment' => [
                        'id' => $submission->assignment->id,
                        'title' => $submission->assignment->title,
                        'due_date' => $submission->assignment->due_date,
                        'formatted_due_date' => $submission->assignment->formatted_due_date,
                        'max_score' => $submission->assignment->max_score,
                        'course' => [
                            'id' => $submission->assignment->course->id,
                            'title' => $submission->assignment->course->title,
                            'code' => $submission->assignment->course->code,
                        ]
                    ],
                    'status' => $submission->status,
                    'status_text' => $submission->status_text,
                    'submitted_at' => $submission->submitted_at,
                    'formatted_submitted_at' => $submission->formatted_submitted_at,
                    'grade' => $submission->grade,
                    'grade_percentage' => $submission->grade_percentage,
                    'letter_grade' => $submission->letter_grade,
                    'is_late' => $submission->is_late,
                    'files_count' => $submission->files->count(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $submissionsData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load submissions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get submission statistics for a user.
     */
    public function getSubmissionStats(Request $request)
    {
        try {
            $user = Auth::user();

            $stats = [
                'total_submissions' => AssignmentSubmission::where('user_id', $user->id)->count(),
                'submitted_count' => AssignmentSubmission::where('user_id', $user->id)->where('status', 'submitted')->count(),
                'graded_count' => AssignmentSubmission::where('user_id', $user->id)->where('status', 'graded')->count(),
                'draft_count' => AssignmentSubmission::where('user_id', $user->id)->where('status', 'draft')->count(),
                'late_submissions' => AssignmentSubmission::where('user_id', $user->id)->where('is_late', true)->count(),
                'average_grade' => AssignmentSubmission::where('user_id', $user->id)
                    ->whereNotNull('grade')
                    ->avg('grade'),
            ];

            // Calculate grade distribution
            $gradeDistribution = AssignmentSubmission::where('user_id', $user->id)
                ->whereNotNull('grade')
                ->selectRaw('
                    COUNT(CASE WHEN grade >= 90 THEN 1 END) as a_grades,
                    COUNT(CASE WHEN grade >= 80 AND grade < 90 THEN 1 END) as b_grades,
                    COUNT(CASE WHEN grade >= 70 AND grade < 80 THEN 1 END) as c_grades,
                    COUNT(CASE WHEN grade >= 60 AND grade < 70 THEN 1 END) as d_grades,
                    COUNT(CASE WHEN grade < 60 THEN 1 END) as f_grades
                ')
                ->first();

            $stats['grade_distribution'] = [
                'A' => $gradeDistribution->a_grades ?? 0,
                'B' => $gradeDistribution->b_grades ?? 0,
                'C' => $gradeDistribution->c_grades ?? 0,
                'D' => $gradeDistribution->d_grades ?? 0,
                'F' => $gradeDistribution->f_grades ?? 0,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load submission statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get submission for a specific assignment and user.
     */
    public function getSubmission(Request $request, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::with(['course'])->findOrFail($assignmentId);

            // Check if user is enrolled in the course
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $assignment->course_id)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access assignments'
                    ], 403);
                }
            }

            // Get user's submission for this assignment
            $submission = $assignment->getSubmissionForUser($user->id);

            if (!$submission) {
                // Create a draft submission if none exists
                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignmentId,
                    'user_id' => $user->id,
                    'status' => 'draft',
                    'attempt_number' => 1,
                ]);
            }

            // Load submission with files
            $submission->load(['files' => function($query) {
                $query->ordered();
            }]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'assignment' => [
                        'id' => $assignment->id,
                        'title' => $assignment->title,
                        'description' => $assignment->description,
                        'instructions' => $assignment->instructions,
                        'due_date' => $assignment->due_date,
                        'formatted_due_date' => $assignment->formatted_due_date,
                        'max_score' => $assignment->max_score,
                        'is_overdue' => $assignment->is_overdue,
                        'due_date_status' => $assignment->due_date_status,
                        'accepts_submissions' => $assignment->acceptsSubmissions(),
                        'course' => [
                            'id' => $assignment->course->id,
                            'title' => $assignment->course->title,
                            'code' => $assignment->course->code,
                        ]
                    ],
                    'submission' => [
                        'id' => $submission->id,
                        'status' => $submission->status,
                        'status_text' => $submission->status_text,
                        'submission_text' => $submission->submission_text,
                        'submitted_at' => $submission->submitted_at,
                        'formatted_submitted_at' => $submission->formatted_submitted_at,
                        'grade' => $submission->grade,
                        'grade_percentage' => $submission->grade_percentage,
                        'letter_grade' => $submission->letter_grade,
                        'feedback' => $submission->feedback,
                        'is_late' => $submission->is_late,
                        'attempt_number' => $submission->attempt_number,
                        'due_date_status' => $submission->due_date_status,
                        'files' => $submission->files->map(function($file) {
                            return [
                                'id' => $file->id,
                                'original_name' => $file->original_name,
                                'file_size' => $file->file_size,
                                'formatted_file_size' => $file->formatted_file_size,
                                'file_extension' => $file->file_extension,
                                'file_icon' => $file->file_icon,
                                'is_primary' => $file->is_primary,
                                'download_url' => $file->download_url,
                                'uploaded_at' => $file->created_at->format('M j, Y \a\t g:i A'),
                            ];
                        }),
                        'can_submit' => $this->canSubmit($assignment, $submission),
                        'can_resubmit' => $this->canResubmit($assignment, $submission),
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load assignment submission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit or update a submission.
     */
    public function submitAssignment(Request $request, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if user is enrolled in the course
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $assignment->course_id)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to submit assignments'
                    ], 403);
                }
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'submission_text' => 'nullable|string|max:10000',
                'files.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,zip,jpg,jpeg,png', // 10MB max
                'action' => 'required|in:save_draft,submit',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get or create submission
            $submission = $assignment->getSubmissionForUser($user->id);

            if (!$submission) {
                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignmentId,
                    'user_id' => $user->id,
                    'status' => 'draft',
                    'attempt_number' => 1,
                ]);
            }

            // Check if can submit
            if ($request->action === 'submit' && !$this->canSubmit($assignment, $submission)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot submit this assignment at this time'
                ], 400);
            }

            // Update submission
            $updateData = [
                'submission_text' => $request->submission_text,
            ];

            if ($request->action === 'submit') {
                $updateData['status'] = 'submitted';
                $updateData['submitted_at'] = Carbon::now();
                $updateData['is_late'] = $assignment->due_date && Carbon::now()->isAfter($assignment->due_date);
            }

            $submission->update($updateData);

            // Handle file uploads
            if ($request->hasFile('files')) {
                $this->handleFileUploads($request->file('files'), $submission);
            }

            // Send notification for successful submission (not for drafts)
            if ($request->action === 'submit') {
                try {
                    NotificationService::create([
                        'user_id' => $user->id,
                        'title' => 'Assignment Submitted Successfully',
                        'message' => "Your assignment \"{$assignment->title}\" has been submitted successfully. You will be notified when it's graded.",
                        'type' => 'assignment',
                        'priority' => 'normal',
                        'action_url' => "/assignment-submission?assignment={$assignment->id}",
                        'data' => [
                            'assignment_id' => $assignment->id,
                            'course_id' => $assignment->course_id,
                            'submission_id' => $submission->id,
                            'submitted_at' => $submission->submitted_at->toISOString(),
                        ]
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the submission
                    \Log::error('Failed to send assignment submission notification: ' . $e->getMessage());
                }
            }

            $message = $request->action === 'submit' ? 'Assignment submitted successfully!' : 'Draft saved successfully!';

            return response()->json([
                'status' => 'success',
                'message' => $message,
                'data' => [
                    'submission_id' => $submission->id,
                    'status' => $submission->status,
                    'submitted_at' => $submission->submitted_at,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a submission file.
     */
    public function downloadFile($submissionId, $fileId)
    {
        try {
            $user = Auth::user();
            $submission = AssignmentSubmission::with(['assignment.course'])->findOrFail($submissionId);
            $file = SubmissionFile::where('submission_id', $submissionId)->findOrFail($fileId);

            // Check permissions
            if ($user->role === 'student' && $submission->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to submission file'
                ], 403);
            }

            if ($user->role === 'instructor' && $submission->assignment->course->instructor_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access to submission file'
                ], 403);
            }

            // Check if file exists
            if (!$file->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File not found'
                ], 404);
            }

            return Storage::download($file->file_path, $file->original_name);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to download file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a submission file.
     */
    public function deleteFile($submissionId, $fileId)
    {
        try {
            $user = Auth::user();
            $submission = AssignmentSubmission::findOrFail($submissionId);
            $file = SubmissionFile::where('submission_id', $submissionId)->findOrFail($fileId);

            // Check permissions (only submission owner can delete files)
            if ($submission->user_id !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to delete this file'
                ], 403);
            }

            // Check if submission is still editable
            if (!in_array($submission->status, ['draft'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete files from submitted assignments'
                ], 400);
            }

            $file->delete(); // This will also delete the physical file

            return response()->json([
                'status' => 'success',
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle file uploads for a submission.
     */
    private function handleFileUploads($files, $submission)
    {
        foreach ($files as $index => $file) {
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $storedName = time() . '_' . Str::random(10) . '.' . $extension;
            $filePath = 'submissions/' . $submission->assignment_id . '/' . $submission->user_id . '/' . $storedName;

            // Store the file
            $file->storeAs('submissions/' . $submission->assignment_id . '/' . $submission->user_id, $storedName);

            // Create file record
            SubmissionFile::create([
                'submission_id' => $submission->id,
                'original_name' => $originalName,
                'stored_name' => $storedName,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_extension' => $extension,
                'is_primary' => $index === 0, // First file is primary
                'order' => $index,
            ]);
        }
    }

    /**
     * Check if user can submit assignment.
     */
    private function canSubmit($assignment, $submission)
    {
        // Can submit if it's a draft and assignment accepts submissions
        return $submission->status === 'draft' && $assignment->acceptsSubmissions();
    }

    /**
     * Check if user can resubmit assignment.
     */
    private function canResubmit($assignment, $submission)
    {
        // For now, no resubmissions allowed after submission
        // You can modify this logic based on your requirements
        return false;
    }
}

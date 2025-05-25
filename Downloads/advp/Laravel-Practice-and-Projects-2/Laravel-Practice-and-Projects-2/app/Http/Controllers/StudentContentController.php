<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Material;
use App\Models\Course;
use App\Models\LectureProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentContentController extends Controller
{
    /**
     * Get lectures for a course
     */
    public function getCourseLectures(Request $request, $courseId)
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

        $lectures = Lecture::where('course_id', $courseId)
            ->where('is_visible', true)
            ->orderBy('created_at')
            ->get();

        // Add enhanced progress information for each lecture
        $lectures->each(function($lecture) use ($user) {
            $progress = LectureProgress::where('lecture_id', $lecture->id)
                ->where('user_id', $user->id)
                ->first();

            if ($progress) {
                $lecture->progress = [
                    'completed' => $progress->completed,
                    'attended' => $progress->attended,
                    'progress_percentage' => $progress->progress_percentage,
                    'first_accessed' => $progress->first_accessed_at,
                    'last_accessed' => $progress->last_accessed_at,
                    'completed_at' => $progress->completed_at,
                    'total_duration' => $progress->total_duration,
                    'formatted_duration' => $progress->formatted_duration,
                    'attendance_status' => $progress->attendance_status,
                    'attendance_badge' => $progress->attendance_badge,
                ];
            } else {
                $lecture->progress = [
                    'completed' => false,
                    'attended' => false,
                    'progress_percentage' => 0,
                    'first_accessed' => null,
                    'last_accessed' => null,
                    'completed_at' => null,
                    'total_duration' => 0,
                    'formatted_duration' => '0 min',
                    'attendance_status' => 'not_started',
                    'attendance_badge' => ['text' => 'Not Started', 'color' => 'gray', 'icon' => 'circle'],
                ];
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $lectures
        ]);
    }

    /**
     * Get specific lecture details
     */
    public function getLecture(Request $request, $lectureId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $lecture = Lecture::with(['course'])->find($lectureId);

        if (!$lecture) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture not found'
            ], 404);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $lecture->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        // Check if lecture is visible
        if (!$lecture->is_visible) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture is not available'
            ], 403);
        }

        // Get or create progress record with enhanced tracking
        $progress = LectureProgress::where('lecture_id', $lectureId)
            ->where('user_id', $user->id)
            ->first();

        if ($progress) {
            // Update last accessed time
            $progress->last_accessed_at = now();
            $progress->save();
        } else {
            // Create new progress record
            $progress = LectureProgress::create([
                'lecture_id' => $lectureId,
                'user_id' => $user->id,
                'progress_percentage' => 0,
                'completed' => false,
                'attended' => false,
                'first_accessed_at' => now(),
                'last_accessed_at' => now(),
                'time_spent' => 0,
                'total_duration' => 0
            ]);
        }

        // Add enhanced progress data to lecture
        $lecture->progress = [
            'completed' => $progress->completed,
            'attended' => $progress->attended,
            'progress_percentage' => $progress->progress_percentage,
            'first_accessed' => $progress->first_accessed_at,
            'last_accessed' => $progress->last_accessed_at,
            'completed_at' => $progress->completed_at,
            'total_duration' => $progress->total_duration,
            'formatted_duration' => $progress->formatted_duration,
            'attendance_status' => $progress->attendance_status,
            'attendance_badge' => $progress->attendance_badge,
            'notes' => $progress->notes
        ];

        return response()->json([
            'status' => 'success',
            'data' => $lecture
        ]);
    }

    /**
     * Update lecture progress
     */
    public function updateLectureProgress(Request $request, $lectureId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $lecture = Lecture::find($lectureId);

        if (!$lecture) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lecture not found'
            ], 404);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $lecture->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'completed' => 'boolean'
        ]);

        // Enhanced progress tracking with attendance and time tracking
        $progressPercentage = $request->progress_percentage;
        $completed = $request->completed ?? ($progressPercentage >= 100);
        $timeSpent = $request->time_spent ?? 0;
        $notes = $request->notes;

        $progress = LectureProgress::where('lecture_id', $lectureId)
            ->where('user_id', $user->id)
            ->first();

        if ($progress) {
            $progress->updateProgress($progressPercentage, $timeSpent);
            if ($notes) {
                $progress->notes = $notes;
                $progress->save();
            }
        } else {
            $progress = LectureProgress::create([
                'lecture_id' => $lectureId,
                'user_id' => $user->id,
                'progress_percentage' => $progressPercentage,
                'completed' => $completed,
                'attended' => $progressPercentage >= 80,
                'first_accessed_at' => now(),
                'last_accessed_at' => now(),
                'completed_at' => $completed ? now() : null,
                'time_spent' => $timeSpent,
                'total_duration' => $timeSpent,
                'notes' => $notes
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Progress updated successfully',
            'data' => $progress
        ]);
    }

    /**
     * Get materials for a course
     */
    public function getCourseMaterials(Request $request, $courseId)
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

        $materials = Material::where('course_id', $courseId)
            ->where('is_visible', true)
            ->orderBy('created_at')
            ->get();

        // Add download information
        $materials->each(function($material) {
            $material->file_size_formatted = $this->formatFileSize($material->file_size ?? 0);
            $material->file_extension = pathinfo($material->file_path ?? '', PATHINFO_EXTENSION);
        });

        return response()->json([
            'status' => 'success',
            'data' => $materials
        ]);
    }

    /**
     * Download material
     */
    public function downloadMaterial(Request $request, $materialId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $material = Material::find($materialId);

        if (!$material) {
            return response()->json([
                'status' => 'error',
                'message' => 'Material not found'
            ], 404);
        }

        // Check enrollment
        $enrollment = $user->enrolledCourses()->where('course_id', $material->course_id)->first();

        if (!$enrollment) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course'
            ], 403);
        }

        // Check if material is visible
        if (!$material->is_visible) {
            return response()->json([
                'status' => 'error',
                'message' => 'Material is not available'
            ], 403);
        }

        // Check if file exists
        if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File not found'
            ], 404);
        }

        // Log download (optional - for analytics)
        // MaterialDownload::create([
        //     'material_id' => $materialId,
        //     'user_id' => $user->id,
        //     'downloaded_at' => now()
        // ]);

        // Return file download
        return Storage::disk('public')->download(
            $material->file_path,
            $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Format file size for display
     */
    private function formatFileSize($bytes)
    {
        if ($bytes == 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $factor), 2) . ' ' . $units[$factor];
    }
}

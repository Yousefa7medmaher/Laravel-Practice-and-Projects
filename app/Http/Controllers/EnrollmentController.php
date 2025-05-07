<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    /**
     * Enroll the authenticated user in a course.
     */
    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);
        $user = Auth::user();
        
        // Check if user is already enrolled
        if ($user->enrolledCourses()->where('course_id', $courseId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are already enrolled in this course.'
            ], 400);
        }
        
        // Check if course is active
        if ($course->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'This course is not currently available for enrollment.'
            ], 400);
        }
        
        // Enroll the user
        $user->enrolledCourses()->attach($courseId, [
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully enrolled in the course.',
            'data' => [
                'course' => $course,
                'enrollment_date' => now(),
            ]
        ]);
    }
    
    /**
     * Unenroll the authenticated user from a course.
     */
    public function unenroll($courseId)
    {
        $user = Auth::user();
        
        // Check if user is enrolled
        if (!$user->enrolledCourses()->where('course_id', $courseId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not enrolled in this course.'
            ], 400);
        }
        
        // Unenroll the user
        $user->enrolledCourses()->updateExistingPivot($courseId, [
            'status' => 'dropped',
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully unenrolled from the course.'
        ]);
    }
    
    /**
     * Get all courses the authenticated user is enrolled in.
     */
    public function myCourses()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()
            ->with('instructor')
            ->wherePivot('status', 'enrolled')
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $enrolledCourses
        ]);
    }
    
    /**
     * Get all students enrolled in a specific course.
     */
    public function courseStudents($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is authorized to view enrolled students
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can view enrolled students.'
            ], 403);
        }
        
        $students = $course->students()
            ->wherePivot('status', 'enrolled')
            ->get(['users.id', 'users.name', 'users.email', 'users.imgProfilePath', 'course_user.enrolled_at']);
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'course' => $course->only(['id', 'title', 'code']),
                'students' => $students,
                'total_students' => $students->count()
            ]
        ]);
    }
    
    /**
     * Update a student's enrollment status (for instructors/managers).
     */
    public function updateEnrollmentStatus(Request $request, $courseId, $userId)
    {
        $course = Course::findOrFail($courseId);
        $user = User::findOrFail($userId);
        
        // Check if user is authorized to update enrollment status
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can update enrollment status.'
            ], 403);
        }
        
        // Validate request data
        $validated = $request->validate([
            'status' => ['required', Rule::in(['enrolled', 'completed', 'dropped'])],
            'final_grade' => 'nullable|numeric|min:0|max:100',
        ]);
        
        // Check if user is enrolled
        if (!$user->enrolledCourses()->where('course_id', $courseId)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This user is not enrolled in this course.'
            ], 400);
        }
        
        // Update enrollment status
        $updateData = [
            'status' => $validated['status'],
        ];
        
        // Add completed_at if status is completed
        if ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
            
            // Add final grade if provided
            if (isset($validated['final_grade'])) {
                $updateData['final_grade'] = $validated['final_grade'];
            }
        }
        
        $user->enrolledCourses()->updateExistingPivot($courseId, $updateData);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Enrollment status updated successfully.'
        ]);
    }
}

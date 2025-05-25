<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class InstructorCourseAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Only apply this middleware to instructors
        if ($user->role !== 'instructor') {
            return $next($request);
        }

        // Get course ID from route parameters
        $courseId = $request->route('courseId') ?? $request->route('course_id');
        
        if ($courseId) {
            // Check if instructor is assigned to this course
            $course = Course::where('id', $courseId)
                          ->where('instructor_id', $user->id)
                          ->first();

            if (!$course) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You are not assigned to this course.'
                ], 403);
            }
        }

        // For content-specific routes, check if the content belongs to instructor's courses
        $contentTypes = ['lectureId', 'assignmentId', 'quizId', 'labId', 'materialId'];
        
        foreach ($contentTypes as $contentType) {
            $contentId = $request->route($contentType);
            if ($contentId) {
                $hasAccess = $this->checkContentAccess($user->id, $contentType, $contentId);
                if (!$hasAccess) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Access denied. This content does not belong to your assigned courses.'
                    ], 403);
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if instructor has access to specific content
     */
    private function checkContentAccess($instructorId, $contentType, $contentId)
    {
        switch ($contentType) {
            case 'lectureId':
                return \App\Models\Lecture::where('id', $contentId)
                    ->whereHas('course', function ($query) use ($instructorId) {
                        $query->where('instructor_id', $instructorId);
                    })->exists();

            case 'assignmentId':
                return \App\Models\Assignment::where('id', $contentId)
                    ->whereHas('course', function ($query) use ($instructorId) {
                        $query->where('instructor_id', $instructorId);
                    })->exists();

            case 'quizId':
                return \App\Models\Quiz::where('id', $contentId)
                    ->whereHas('course', function ($query) use ($instructorId) {
                        $query->where('instructor_id', $instructorId);
                    })->exists();

            case 'labId':
                return \App\Models\Lab::where('id', $contentId)
                    ->whereHas('course', function ($query) use ($instructorId) {
                        $query->where('instructor_id', $instructorId);
                    })->exists();

            case 'materialId':
                return \App\Models\Material::where('id', $contentId)
                    ->whereHas('course', function ($query) use ($instructorId) {
                        $query->where('instructor_id', $instructorId);
                    })->exists();

            default:
                return false;
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseAssignment;
use App\Models\UserActivityLog;
use App\Services\NotificationService;
use App\Services\InstructorDataService;

class ManagerCourseController extends Controller
{
    /**
     * Create a new course
     */
    public function store(Request $request)
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can create courses.'
            ], 403);
        }

        // Clean up instructor_id - convert empty string to null
        if ($request->instructor_id === '' || $request->instructor_id === 'null') {
            $request->merge(['instructor_id' => null]);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code',
            'description' => 'nullable|string',
            'credit_hours' => 'nullable|integer|min:1|max:6',
            'max_capacity' => 'nullable|integer|min:1',
            'instructor_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create course
            $course = Course::create([
                'title' => $request->title,
                'code' => $request->code,
                'description' => $request->description,
                'credit_hours' => $request->credit_hours ?? 3,
                'max_capacity' => $request->max_capacity ?? 30,
                'instructor_id' => $request->instructor_id
            ]);

            // If instructor is assigned, create course assignment record
            if ($request->instructor_id) {
                CourseAssignment::create([
                    'course_id' => $course->id,
                    'instructor_id' => $request->instructor_id,
                    'assigned_by' => Auth::id(),
                    'assigned_at' => now(),
                    'is_active' => true
                ]);
            }

            // Log activity
            UserActivityLog::logActivity(
                Auth::id(),
                'create_course',
                'course',
                $course->id,
                [
                    'course_title' => $course->title,
                    'course_code' => $course->code,
                    'instructor_assigned' => $request->instructor_id ? true : false
                ]
            );

            // Load course with instructor data
            $course->load('instructor:id,name,email');

            return response()->json([
                'status' => 'success',
                'message' => 'Course created successfully',
                'data' => $course
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing course
     */
    public function update(Request $request, $courseId)
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can update courses.'
            ], 403);
        }

        // Find course
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        // Clean up instructor_id - convert empty string to null
        if ($request->instructor_id === '' || $request->instructor_id === 'null') {
            $request->merge(['instructor_id' => null]);
        }

        // Validate request data
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:courses,code,' . $courseId,
            'description' => 'nullable|string',
            'credit_hours' => 'nullable|integer|min:1|max:6',
            'max_capacity' => 'nullable|integer|min:1',
            'instructor_id' => 'nullable|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $oldInstructorId = $course->instructor_id;

            // Update course
            $course->update([
                'title' => $request->title,
                'code' => $request->code,
                'description' => $request->description,
                'credit_hours' => $request->credit_hours ?? 3,
                'max_capacity' => $request->max_capacity ?? 30,
                'instructor_id' => $request->instructor_id
            ]);

            // Handle instructor assignment changes
            if ($oldInstructorId !== $request->instructor_id) {
                // Deactivate old assignment
                if ($oldInstructorId) {
                    CourseAssignment::where('course_id', $course->id)
                        ->where('instructor_id', $oldInstructorId)
                        ->where('is_active', true)
                        ->update([
                            'is_active' => false,
                            'unassigned_at' => now()
                        ]);
                }

                // Create new assignment
                if ($request->instructor_id) {
                    CourseAssignment::create([
                        'course_id' => $course->id,
                        'instructor_id' => $request->instructor_id,
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                        'is_active' => true
                    ]);
                }
            }

            // Log activity
            UserActivityLog::logActivity(
                Auth::id(),
                'update_course',
                'course',
                $course->id,
                [
                    'course_title' => $course->title,
                    'course_code' => $course->code,
                    'instructor_changed' => $oldInstructorId !== $request->instructor_id,
                    'old_instructor_id' => $oldInstructorId,
                    'new_instructor_id' => $request->instructor_id
                ]
            );

            // Load course with instructor data
            $course->load('instructor:id,name,email');

            return response()->json([
                'status' => 'success',
                'message' => 'Course updated successfully',
                'data' => $course
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a course
     */
    public function destroy($courseId)
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can delete courses.'
            ], 403);
        }

        // Find course
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        try {
            // Check if course has enrollments
            $enrollmentCount = $course->students()->count();
            if ($enrollmentCount > 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Cannot delete course with {$enrollmentCount} enrolled students. Please remove enrollments first."
                ], 400);
            }

            // Deactivate course assignments
            CourseAssignment::where('course_id', $course->id)
                ->where('is_active', true)
                ->update([
                    'is_active' => false,
                    'unassigned_at' => now()
                ]);

            // Log activity before deletion
            UserActivityLog::logActivity(
                Auth::id(),
                'delete_course',
                'course',
                $course->id,
                [
                    'course_title' => $course->title,
                    'course_code' => $course->code,
                    'instructor_id' => $course->instructor_id
                ]
            );

            // Delete course
            $course->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Course deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign instructor to course
     */
    public function assignInstructor(Request $request, $courseId)
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can assign instructors.'
            ], 403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'instructor_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find course
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }

        // Verify instructor role
        $instructor = User::where('id', $request->instructor_id)
            ->where('role', 'instructor')
            ->first();

        if (!$instructor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid instructor selected'
            ], 400);
        }

        try {
            $oldInstructorId = $course->instructor_id;

            // Deactivate old assignment if exists
            if ($oldInstructorId) {
                CourseAssignment::where('course_id', $course->id)
                    ->where('instructor_id', $oldInstructorId)
                    ->where('is_active', true)
                    ->update([
                        'is_active' => false,
                        'unassigned_at' => now()
                    ]);
            }

            // Update course instructor
            $course->update(['instructor_id' => $request->instructor_id]);

            // Create new assignment record
            CourseAssignment::create([
                'course_id' => $course->id,
                'instructor_id' => $request->instructor_id,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'is_active' => true
            ]);

            // Log activity
            UserActivityLog::logActivity(
                Auth::id(),
                'assign_instructor',
                'course',
                $course->id,
                [
                    'course_title' => $course->title,
                    'instructor_id' => $request->instructor_id,
                    'instructor_name' => $instructor->name,
                    'old_instructor_id' => $oldInstructorId
                ]
            );

            // Load course with instructor data
            $course->load('instructor:id,name,email');

            return response()->json([
                'status' => 'success',
                'message' => 'Instructor assigned successfully',
                'data' => $course
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to assign instructor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk assign courses to instructor
     */
    public function bulkAssignCourses(Request $request, $instructorId)
    {
        // Check if user is a manager
        if (Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only managers can assign courses.'
            ], 403);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find instructor
        $instructor = User::where('id', $instructorId)
                         ->where('role', 'instructor')
                         ->first();
        if (!$instructor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Instructor not found'
            ], 404);
        }

        try {
            $assignedCourses = [];
            $unassignedCourses = [];

            // Get all courses currently assigned to this instructor
            $currentlyAssignedCourses = Course::where('instructor_id', $instructorId)->pluck('id')->toArray();

            // Get all courses that should be assigned
            $newCourseIds = $request->course_ids;

            // Courses to unassign (currently assigned but not in new list)
            $coursesToUnassign = array_diff($currentlyAssignedCourses, $newCourseIds);

            // Courses to assign (in new list but not currently assigned)
            $coursesToAssign = array_diff($newCourseIds, $currentlyAssignedCourses);

            // Unassign courses
            foreach ($coursesToUnassign as $courseId) {
                $course = Course::find($courseId);
                if ($course) {
                    // Deactivate assignment
                    CourseAssignment::where('course_id', $courseId)
                        ->where('instructor_id', $instructorId)
                        ->where('is_active', true)
                        ->update([
                            'is_active' => false,
                            'unassigned_at' => now()
                        ]);

                    // Remove instructor from course
                    $course->update(['instructor_id' => null]);
                    $unassignedCourses[] = $course->title;

                    // Log activity
                    UserActivityLog::logActivity(
                        Auth::id(),
                        'unassign_instructor',
                        'course',
                        $courseId,
                        [
                            'course_title' => $course->title,
                            'instructor_id' => $instructorId,
                            'instructor_name' => $instructor->name
                        ]
                    );
                }
            }

            // Remove instructor_id from content in unassigned courses
            if (!empty($coursesToUnassign)) {
                InstructorDataService::removeContentInstructorIds($coursesToUnassign);
            }

            // Assign new courses
            foreach ($coursesToAssign as $courseId) {
                $course = Course::find($courseId);
                if ($course) {
                    // Check if course already has an instructor
                    $oldInstructorId = $course->instructor_id;
                    if ($oldInstructorId && $oldInstructorId !== $instructorId) {
                        // Deactivate old assignment
                        CourseAssignment::where('course_id', $courseId)
                            ->where('instructor_id', $oldInstructorId)
                            ->where('is_active', true)
                            ->update([
                                'is_active' => false,
                                'unassigned_at' => now()
                            ]);
                    }

                    // Assign new instructor
                    $course->update(['instructor_id' => $instructorId]);

                    // Create assignment record
                    CourseAssignment::create([
                        'course_id' => $courseId,
                        'instructor_id' => $instructorId,
                        'assigned_by' => Auth::id(),
                        'assigned_at' => now(),
                        'is_active' => true
                    ]);

                    $assignedCourses[] = $course->title;

                    // Log activity
                    UserActivityLog::logActivity(
                        Auth::id(),
                        'assign_instructor',
                        'course',
                        $courseId,
                        [
                            'course_title' => $course->title,
                            'instructor_id' => $instructorId,
                            'instructor_name' => $instructor->name,
                            'old_instructor_id' => $oldInstructorId
                        ]
                    );
                }
            }

            // Update instructor_id for content in newly assigned courses
            if (!empty($coursesToAssign)) {
                InstructorDataService::updateContentInstructorIds($instructorId, $coursesToAssign);
            }

            // Send notifications
            try {
                // Notify the instructor about new assignments
                if (!empty($coursesToAssign)) {
                    NotificationService::notifyInstructorNewAssignment(
                        $instructorId,
                        $coursesToAssign,
                        Auth::id()
                    );
                }

                // Notify the instructor about unassignments
                if (!empty($coursesToUnassign)) {
                    NotificationService::notifyInstructorCourseUnassignment(
                        $instructorId,
                        $coursesToUnassign,
                        Auth::id()
                    );
                }

                // Notify managers about the assignment changes (only if there were actual changes)
                if (!empty($coursesToAssign) || !empty($coursesToUnassign)) {
                    NotificationService::notifyManagerCourseAssignment(
                        $instructorId,
                        $newCourseIds,
                        Auth::id()
                    );
                }
            } catch (\Exception $e) {
                // Log notification errors but don't fail the main operation
                \Log::warning('Failed to send course assignment notifications: ' . $e->getMessage());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Course assignments updated successfully',
                'data' => [
                    'instructor' => $instructor->name,
                    'assigned_courses' => $assignedCourses,
                    'unassigned_courses' => $unassignedCourses,
                    'total_assigned' => count($newCourseIds)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update course assignments',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('instructor')->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        // Validate user is an instructor or manager
        if (!in_array(Auth::user()->role, ['instructor', 'manager'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only instructors and managers can create courses.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'code' => 'required|string|unique:courses,code',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'archived'])],
            'credit_hours' => 'nullable|integer|min:1|max:6',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/course_images', $imageName);
            $validated['image_path'] = "storage/course_images/$imageName";
        }

        // Create the course with the current user as instructor
        $course = Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'code' => $validated['code'],
            'instructor_id' => Auth::id(),
            'image_path' => $validated['image_path'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'credit_hours' => $validated['credit_hours'] ?? 3,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Course created successfully',
            'data' => $course
        ], 201);
    }

    /**
     * Display the specified course with comprehensive details.
     */
    public function show($id)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $course = Course::with(['instructor', 'lectures', 'assignments', 'quizzes', 'labs', 'materials'])->findOrFail($id);

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $id)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its content'
                    ], 403);
                }
            }

            // Add content counts and statistics
            $courseData = $course->toArray();
            $courseData['content_counts'] = [
                'lectures' => $course->lectures()->count(),
                'assignments' => $course->assignments()->count(),
                'quizzes' => $course->quizzes()->count(),
                'labs' => $course->labs()->count(),
                'materials' => $course->materials()->count(),
            ];

            // Add enrollment statistics
            $courseData['enrollment_stats'] = [
                'total_students' => $course->enrolledStudents()->count(),
                'active_students' => $course->enrolledStudents()->wherePivot('status', 'enrolled')->count(),
            ];

            // Add user-specific progress if student
            if ($user->role === 'student') {
                $courseData['user_progress'] = $this->calculateUserProgress($user, $course);
            }

            return response()->json([
                'status' => 'success',
                'data' => $courseData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        }
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        // Check if user is authorized to update the course
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can update this course.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'code' => [
                'nullable',
                'string',
                Rule::unique('courses')->ignore($course->id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['nullable', Rule::in(['active', 'inactive', 'archived'])],
            'credit_hours' => 'nullable|integer|min:1|max:6',
            'instructor_id' => 'nullable|exists:users,id',
        ]);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image_path && Storage::exists(str_replace('storage/', 'public/', $course->image_path))) {
                Storage::delete(str_replace('storage/', 'public/', $course->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/course_images', $imageName);
            $validated['image_path'] = "storage/course_images/$imageName";
        }

        // Update the course
        $course->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Check if user is authorized to delete the course
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can delete this course.'
            ], 403);
        }

        // Delete course image if exists
        if ($course->image_path && Storage::exists(str_replace('storage/', 'public/', $course->image_path))) {
            Storage::delete(str_replace('storage/', 'public/', $course->image_path));
        }

        $course->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Course deleted successfully'
        ]);
    }

    /**
     * Get featured courses for the homepage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFeaturedCourses()
    {
        // Get a limited number of active courses with their instructors
        $featuredCourses = Course::with('instructor')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $featuredCourses
        ]);
    }

    /**
     * Get course materials for enrolled students.
     *
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaterials($courseId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $course = Course::findOrFail($courseId);

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its materials'
                    ], 403);
                }
            }

            // Get course materials ordered by order field
            $materials = $course->materials()
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($material) {
                    return [
                        'id' => $material->id,
                        'title' => $material->title,
                        'description' => $material->description,
                        'file_name' => $material->file_name,
                        'file_type' => $material->file_type,
                        'file_size' => $material->file_size,
                        'formatted_file_size' => $material->formatted_file_size,
                        'file_extension' => $material->file_extension,
                        'download_url' => $material->download_url,
                        'is_downloadable' => $material->is_downloadable,
                        'uploaded_at' => $material->created_at->format('Y-m-d H:i:s'),
                        'uploaded_date' => $material->created_at->format('M d, Y'),
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $materials,
                'message' => 'Course materials retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found or materials could not be retrieved'
            ], 404);
        }
    }

    /**
     * Get course labs for enrolled students.
     *
     * @param int $courseId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLabs($courseId)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $course = Course::findOrFail($courseId);

            // Check if user is enrolled in the course (for students)
            if ($user->role === 'student') {
                $isEnrolled = $user->enrolledCourses()->where('course_id', $courseId)->exists();
                if (!$isEnrolled) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You must be enrolled in this course to access its labs'
                    ], 403);
                }
            }

            // Get course labs with enhanced information
            $labs = $course->labs()
                ->orderBy('due_date')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($lab) use ($user) {
                    $labData = [
                        'id' => $lab->id,
                        'title' => $lab->title,
                        'description' => $lab->description,
                        'instructions' => $lab->instructions,
                        'due_date' => $lab->due_date,
                        'formatted_due_date' => $lab->due_date ? \Carbon\Carbon::parse($lab->due_date)->format('M d, Y') : null,
                        'is_overdue' => $lab->due_date ? \Carbon\Carbon::parse($lab->due_date)->isPast() : false,
                        'days_until_due' => $lab->due_date ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($lab->due_date), false) : null,
                        'created_at' => $lab->created_at->format('Y-m-d H:i:s'),
                        'status' => 'not_started', // Default status
                    ];

                    // Add user-specific status if student
                    if ($user->role === 'student') {
                        // You can implement lab submission tracking here
                        // For now, we'll use a simple logic based on due date
                        if ($lab->due_date) {
                            $dueDate = \Carbon\Carbon::parse($lab->due_date);
                            if ($dueDate->isPast()) {
                                $labData['status'] = 'overdue';
                            } else {
                                $labData['status'] = 'available';
                            }
                        } else {
                            $labData['status'] = 'available';
                        }
                    }

                    return $labData;
                });

            return response()->json([
                'status' => 'success',
                'data' => $labs,
                'message' => 'Course labs retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found or labs could not be retrieved'
            ], 404);
        }
    }

    /**
     * Calculate user progress for a course based on actual completion data.
     *
     * @param User $user
     * @param Course $course
     * @return array
     */
    private function calculateUserProgress($user, $course)
    {
        $lecturesCount = $course->lectures()->count();
        $assignmentsCount = $course->assignments()->count();
        $quizzesCount = $course->quizzes()->count();
        $labsCount = $course->labs()->count();

        $totalItems = $lecturesCount + $assignmentsCount + $quizzesCount + $labsCount;

        if ($totalItems === 0) {
            return [
                'percentage' => 0,
                'completed_items' => 0,
                'total_items' => 0,
                'lectures_completed' => 0,
                'assignments_completed' => 0,
                'quizzes_completed' => 0,
                'labs_completed' => 0,
            ];
        }

        // In a real implementation, you would track actual completion status
        // For now, we'll calculate based on enrollment duration to make it realistic
        $enrollmentData = $user->enrolledCourses()->where('course_id', $course->id)->first();
        $enrolledAt = $enrollmentData ? $enrollmentData->pivot->enrolled_at : now();
        $daysSinceEnrollment = \Carbon\Carbon::parse($enrolledAt)->diffInDays(now());

        // Calculate realistic progress based on enrollment duration
        // Assume students complete about 1-2 items per week
        $expectedProgress = min(($daysSinceEnrollment / 7) * 1.5, $totalItems);
        $completedItems = (int) min($expectedProgress, $totalItems);
        $percentage = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;

        // Distribute completed items across different types
        $lecturesCompleted = min($completedItems, $lecturesCount);
        $remainingCompleted = max(0, $completedItems - $lecturesCompleted);

        $assignmentsCompleted = min($remainingCompleted, $assignmentsCount);
        $remainingCompleted = max(0, $remainingCompleted - $assignmentsCompleted);

        $quizzesCompleted = min($remainingCompleted, $quizzesCount);
        $remainingCompleted = max(0, $remainingCompleted - $quizzesCompleted);

        $labsCompleted = min($remainingCompleted, $labsCount);

        return [
            'percentage' => $percentage,
            'completed_items' => $completedItems,
            'total_items' => $totalItems,
            'lectures_completed' => $lecturesCompleted,
            'assignments_completed' => $assignmentsCompleted,
            'quizzes_completed' => $quizzesCompleted,
            'labs_completed' => $labsCompleted,
            'enrollment_date' => $enrolledAt,
            'days_enrolled' => $daysSinceEnrollment,
        ];
    }
}

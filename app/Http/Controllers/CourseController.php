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
            $path = $image->storeAs('public/course_images', $imageName);
            $validated['image_path'] = 'storage/course_images/' . $imageName;
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
     * Display the specified course.
     */
    public function show($id)
    {
        $course = Course::with(['instructor', 'lectures', 'assignments', 'quizzes', 'labs'])->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
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
            $path = $image->storeAs('public/course_images', $imageName);
            $validated['image_path'] = 'storage/course_images/' . $imageName;
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
}

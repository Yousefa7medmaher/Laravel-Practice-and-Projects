<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the assignments for a course.
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $assignments = $course->assignments()->orderBy('due_date')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $assignments
        ]);
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is authorized to add assignments to the course
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can add assignments.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'instructions' => 'nullable|string',
            'due_date' => 'required|date|after:now',
            'max_score' => 'nullable|integer|min:1',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/assignment_files', $fileName);
            $validated['file_path'] = 'storage/assignment_files/' . $fileName;
        }

        // Create the assignment
        $assignment = $course->assignments()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'instructions' => $validated['instructions'] ?? null,
            'due_date' => $validated['due_date'],
            'max_score' => $validated['max_score'] ?? 100,
            'file_path' => $validated['file_path'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment created successfully',
            'data' => $assignment
        ], 201);
    }

    /**
     * Display the specified assignment.
     */
    public function show($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $assignment = $course->assignments()->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $assignment
        ]);
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, $courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $assignment = $course->assignments()->findOrFail($id);
        
        // Check if user is authorized to update the assignment
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can update assignments.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'due_date' => 'nullable|date',
            'max_score' => 'nullable|integer|min:1',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($assignment->file_path && Storage::exists(str_replace('storage/', 'public/', $assignment->file_path))) {
                Storage::delete(str_replace('storage/', 'public/', $assignment->file_path));
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/assignment_files', $fileName);
            $validated['file_path'] = 'storage/assignment_files/' . $fileName;
        }

        // Update the assignment
        $assignment->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment updated successfully',
            'data' => $assignment
        ]);
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $assignment = $course->assignments()->findOrFail($id);
        
        // Check if user is authorized to delete the assignment
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can delete assignments.'
            ], 403);
        }

        // Delete assignment file if exists
        if ($assignment->file_path && Storage::exists(str_replace('storage/', 'public/', $assignment->file_path))) {
            Storage::delete(str_replace('storage/', 'public/', $assignment->file_path));
        }

        $assignment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Assignment deleted successfully'
        ]);
    }
}

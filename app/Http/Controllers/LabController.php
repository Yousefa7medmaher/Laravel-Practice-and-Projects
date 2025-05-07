<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LabController extends Controller
{
    /**
     * Display a listing of the labs for a course.
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $labs = $course->labs()->orderBy('created_at')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $labs
        ]);
    }

    /**
     * Store a newly created lab in storage.
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is authorized to add labs to the course
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can add labs.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'required|string',
            'due_date' => 'nullable|date|after:now',
            'max_score' => 'nullable|integer|min:1',
            'file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/lab_files', $fileName);
            $validated['file_path'] = 'storage/lab_files/' . $fileName;
        }

        // Create the lab
        $lab = $course->labs()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'],
            'due_date' => $validated['due_date'] ?? null,
            'max_score' => $validated['max_score'] ?? 100,
            'file_path' => $validated['file_path'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lab created successfully',
            'data' => $lab
        ], 201);
    }

    /**
     * Display the specified lab.
     */
    public function show($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $lab = $course->labs()->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $lab
        ]);
    }

    /**
     * Update the specified lab in storage.
     */
    public function update(Request $request, $courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $lab = $course->labs()->findOrFail($id);
        
        // Check if user is authorized to update the lab
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can update labs.'
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
            if ($lab->file_path && Storage::exists(str_replace('storage/', 'public/', $lab->file_path))) {
                Storage::delete(str_replace('storage/', 'public/', $lab->file_path));
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/lab_files', $fileName);
            $validated['file_path'] = 'storage/lab_files/' . $fileName;
        }

        // Update the lab
        $lab->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lab updated successfully',
            'data' => $lab
        ]);
    }

    /**
     * Remove the specified lab from storage.
     */
    public function destroy($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $lab = $course->labs()->findOrFail($id);
        
        // Check if user is authorized to delete the lab
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can delete labs.'
            ], 403);
        }

        // Delete lab file if exists
        if ($lab->file_path && Storage::exists(str_replace('storage/', 'public/', $lab->file_path))) {
            Storage::delete(str_replace('storage/', 'public/', $lab->file_path));
        }

        $lab->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lab deleted successfully'
        ]);
    }
}

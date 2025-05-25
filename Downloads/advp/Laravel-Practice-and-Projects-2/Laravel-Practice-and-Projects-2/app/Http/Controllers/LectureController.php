<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LectureController extends Controller
{
    /**
     * Display a listing of the lectures for a course.
     */
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        $lectures = $course->lectures()->orderBy('order')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $lectures
        ]);
    }

    /**
     * Store a newly created lecture in storage.
     */
    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is authorized to add lectures to the course
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can add lectures.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/lecture_files', $fileName);
            $validated['file_path'] = 'storage/lecture_files/' . $fileName;
        }

        // Set order to the end if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = $course->lectures()->max('order') + 1;
        }

        // Create the lecture
        $lecture = $course->lectures()->create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lecture created successfully',
            'data' => $lecture
        ], 201);
    }

    /**
     * Display the specified lecture.
     */
    public function show($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $lecture = $course->lectures()->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $lecture
        ]);
    }

    /**
     * Update the specified lecture in storage.
     */
    public function update(Request $request, $courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $lecture = $course->lectures()->findOrFail($id);
        
        // Check if user is authorized to update the lecture
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can update lectures.'
            ], 403);
        }

        // Validate request data
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'video_url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip|max:10240',
            'order' => 'nullable|integer|min:0',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($lecture->file_path && Storage::exists(str_replace('storage/', 'public/', $lecture->file_path))) {
                Storage::delete(str_replace('storage/', 'public/', $lecture->file_path));
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/lecture_files', $fileName);
            $validated['file_path'] = 'storage/lecture_files/' . $fileName;
        }

        // Update the lecture
        $lecture->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Lecture updated successfully',
            'data' => $lecture
        ]);
    }

    /**
     * Remove the specified lecture from storage.
     */
    public function destroy($courseId, $id)
    {
        $course = Course::findOrFail($courseId);
        $lecture = $course->lectures()->findOrFail($id);
        
        // Check if user is authorized to delete the lecture
        if (Auth::id() !== $course->instructor_id && Auth::user()->role !== 'manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Only the course instructor or managers can delete lectures.'
            ], 403);
        }

        // Delete lecture file if exists
        if ($lecture->file_path && Storage::exists(str_replace('storage/', 'public/', $lecture->file_path))) {
            Storage::delete(str_replace('storage/', 'public/', $lecture->file_path));
        }

        $lecture->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lecture deleted successfully'
        ]);
    }
}

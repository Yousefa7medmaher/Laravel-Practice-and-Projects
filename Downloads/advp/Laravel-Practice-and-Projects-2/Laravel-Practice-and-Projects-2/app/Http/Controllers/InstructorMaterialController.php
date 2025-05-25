<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssignment;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstructorMaterialController extends Controller
{
    /**
     * Upload a new material for a course
     */
    public function uploadMaterial(Request $request, $courseId)
    {
        try {
            // Verify instructor has access to this course
            $courseAssignment = CourseAssignment::where('course_id', $courseId)
                ->where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->first();

            if (!$courseAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to manage this course'
                ], 403);
            }

            // Validate request data
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'material_type' => 'required|string|in:reading,slides,reference,template,other',
                'is_visible' => 'boolean',
                'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,txt,jpg,jpeg,png|max:51200' // 50MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');

            // Get file size and type
            $fileSize = $file->getSize();
            $fileType = $file->getClientMimeType();

            // Create material
            $material = Material::create([
                'course_id' => $courseId,
                'title' => $request->title,
                'description' => $request->description,
                'material_type' => $request->material_type,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $fileSize,
                'file_type' => $fileType,
                'is_visible' => $request->boolean('is_visible', true),
                'uploaded_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Material uploaded successfully',
                'data' => $material
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific material
     */
    public function getMaterial($materialId)
    {
        try {
            $material = Material::findOrFail($materialId);
            
            // Verify instructor has access to this course
            $courseAssignment = CourseAssignment::where('course_id', $material->course_id)
                ->where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->first();

            if (!$courseAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to access this material'
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'data' => $material
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing material
     */
    public function updateMaterial(Request $request, $materialId)
    {
        try {
            // Find material and verify instructor access
            $material = Material::findOrFail($materialId);
            
            $courseAssignment = CourseAssignment::where('course_id', $material->course_id)
                ->where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->first();

            if (!$courseAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to edit this material'
                ], 403);
            }

            // Validate request data
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'material_type' => 'required|string|in:reading,slides,reference,template,other',
                'is_visible' => 'boolean',
                'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,txt,jpg,jpeg,png|max:51200' // 50MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload if present
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }

                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('materials', $fileName, 'public');
                
                // Update file-related fields
                $material->file_path = $filePath;
                $material->file_name = $file->getClientOriginalName();
                $material->file_size = $file->getSize();
                $material->file_type = $file->getClientMimeType();
            }

            // Update material
            $material->update([
                'title' => $request->title,
                'description' => $request->description,
                'material_type' => $request->material_type,
                'is_visible' => $request->boolean('is_visible', $material->is_visible),
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Material updated successfully',
                'data' => $material->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a material
     */
    public function deleteMaterial($materialId)
    {
        try {
            // Find material and verify instructor access
            $material = Material::findOrFail($materialId);
            
            $courseAssignment = CourseAssignment::where('course_id', $material->course_id)
                ->where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->first();

            if (!$courseAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to delete this material'
                ], 403);
            }

            // Delete associated file if exists
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            // Delete material
            $material->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Material deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all materials for instructor
     */
    public function getMaterials(Request $request)
    {
        try {
            $instructorId = Auth::id();
            
            // Get all courses assigned to this instructor
            $courseIds = CourseAssignment::where('instructor_id', $instructorId)
                ->where('is_active', true)
                ->pluck('course_id');

            // Get materials for these courses
            $materials = Material::whereIn('course_id', $courseIds)
                ->with(['course:id,title,code'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $materials
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch materials: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download a material file
     */
    public function downloadMaterial($materialId)
    {
        try {
            $material = Material::findOrFail($materialId);
            
            // Verify instructor has access to this course
            $courseAssignment = CourseAssignment::where('course_id', $material->course_id)
                ->where('instructor_id', Auth::id())
                ->where('is_active', true)
                ->first();

            if (!$courseAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You do not have permission to download this material'
                ], 403);
            }

            // Check if file exists
            if (!$material->file_path || !Storage::disk('public')->exists($material->file_path)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File not found'
                ], 404);
            }

            // Return file download
            return Storage::disk('public')->download($material->file_path, $material->file_name);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to download material: ' . $e->getMessage()
            ], 500);
        }
    }
}

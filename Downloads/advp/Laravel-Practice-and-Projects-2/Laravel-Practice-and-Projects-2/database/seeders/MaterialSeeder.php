<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create materials for each course
            $materials = [
                [
                    'title' => 'Course Syllabus',
                    'description' => 'Complete course syllabus with learning objectives and schedule',
                    'file_name' => 'syllabus.pdf',
                    'file_path' => 'storage/materials/syllabus.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024 * 500, // 500KB
                    'order' => 1,
                ],
                [
                    'title' => 'Lecture Slides - Week 1',
                    'description' => 'Introduction to the course and basic concepts',
                    'file_name' => 'week1_slides.pptx',
                    'file_path' => 'storage/materials/week1_slides.pptx',
                    'file_type' => 'pptx',
                    'file_size' => 1024 * 1024 * 2, // 2MB
                    'order' => 2,
                ],
                [
                    'title' => 'Reading List',
                    'description' => 'Required and recommended reading materials',
                    'file_name' => 'reading_list.pdf',
                    'file_path' => 'storage/materials/reading_list.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024 * 200, // 200KB
                    'order' => 3,
                ],
                [
                    'title' => 'Course Handbook',
                    'description' => 'Comprehensive guide to course policies and procedures',
                    'file_name' => 'handbook.pdf',
                    'file_path' => 'storage/materials/handbook.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024 * 1024 * 3, // 3MB
                    'order' => 4,
                ],
                [
                    'title' => 'Sample Code Repository',
                    'description' => 'ZIP file containing sample code and examples',
                    'file_name' => 'sample_code.zip',
                    'file_path' => 'storage/materials/sample_code.zip',
                    'file_type' => 'zip',
                    'file_size' => 1024 * 1024 * 5, // 5MB
                    'order' => 5,
                ],
                [
                    'title' => 'Reference Documentation',
                    'description' => 'Additional reference materials and documentation',
                    'file_name' => 'reference_docs.pdf',
                    'file_path' => 'storage/materials/reference_docs.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024 * 1024 * 1.5, // 1.5MB
                    'order' => 6,
                ],
            ];

            foreach ($materials as $materialData) {
                Material::create([
                    'course_id' => $course->id,
                    'title' => $materialData['title'],
                    'description' => $materialData['description'],
                    'file_name' => $materialData['file_name'],
                    'file_path' => $materialData['file_path'],
                    'file_type' => $materialData['file_type'],
                    'file_size' => $materialData['file_size'],
                    'download_url' => asset($materialData['file_path']),
                    'is_downloadable' => true,
                    'order' => $materialData['order'],
                ]);
            }
        }
    }
}

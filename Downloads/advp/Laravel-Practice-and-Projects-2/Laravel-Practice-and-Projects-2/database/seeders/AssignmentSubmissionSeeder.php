<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SubmissionFile;
use App\Models\User;
use Carbon\Carbon;

class AssignmentSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test student
        $student = User::where('email', 'student@test.com')->first();
        
        if (!$student) {
            $this->command->error('Test student not found. Please run UserSeeder first.');
            return;
        }

        // Get assignments from enrolled courses
        $enrolledCourses = $student->enrolledCourses()->get();
        
        foreach ($enrolledCourses as $course) {
            $assignments = $course->assignments()->get();
            
            foreach ($assignments as $assignment) {
                // Create different types of submissions for variety
                $submissionType = rand(1, 4);
                
                switch ($submissionType) {
                    case 1:
                        // Draft submission
                        $this->createDraftSubmission($assignment, $student);
                        break;
                    case 2:
                        // Submitted on time
                        $this->createSubmittedSubmission($assignment, $student, false);
                        break;
                    case 3:
                        // Late submission
                        $this->createSubmittedSubmission($assignment, $student, true);
                        break;
                    case 4:
                        // Graded submission
                        $this->createGradedSubmission($assignment, $student);
                        break;
                }
            }
        }

        $this->command->info('Assignment submissions seeded successfully!');
    }

    /**
     * Create a draft submission.
     */
    private function createDraftSubmission($assignment, $student)
    {
        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'submission_text' => 'This is a draft submission for ' . $assignment->title . '. I am still working on this assignment and will submit it soon.',
            'status' => 'draft',
            'attempt_number' => 1,
        ]);
    }

    /**
     * Create a submitted submission.
     */
    private function createSubmittedSubmission($assignment, $student, $isLate = false)
    {
        $submittedAt = $isLate 
            ? $assignment->due_date->addHours(rand(1, 48)) // 1-48 hours late
            : $assignment->due_date->subHours(rand(1, 24)); // 1-24 hours early

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'submission_text' => $this->generateSubmissionText($assignment),
            'status' => 'submitted',
            'submitted_at' => $submittedAt,
            'is_late' => $isLate,
            'attempt_number' => 1,
        ]);

        // Add a sample file
        $this->createSampleFile($submission, $assignment);
    }

    /**
     * Create a graded submission.
     */
    private function createGradedSubmission($assignment, $student)
    {
        $submittedAt = $assignment->due_date->subHours(rand(1, 24));
        $gradedAt = $submittedAt->addDays(rand(1, 7)); // Graded 1-7 days after submission
        $grade = rand(70, 100); // Random grade between 70-100

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $student->id,
            'submission_text' => $this->generateSubmissionText($assignment),
            'status' => 'graded',
            'submitted_at' => $submittedAt,
            'grade' => $grade,
            'feedback' => $this->generateFeedback($grade),
            'graded_by' => $assignment->course->instructor_id,
            'graded_at' => $gradedAt,
            'is_late' => false,
            'attempt_number' => 1,
        ]);

        // Add a sample file
        $this->createSampleFile($submission, $assignment);
    }

    /**
     * Create a sample file for submission.
     */
    private function createSampleFile($submission, $assignment)
    {
        $fileTypes = [
            ['name' => 'assignment_solution.pdf', 'type' => 'application/pdf', 'ext' => 'pdf', 'size' => rand(100000, 2000000)],
            ['name' => 'project_report.docx', 'type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'ext' => 'docx', 'size' => rand(50000, 1000000)],
            ['name' => 'source_code.zip', 'type' => 'application/zip', 'ext' => 'zip', 'size' => rand(200000, 5000000)],
        ];

        $fileType = $fileTypes[array_rand($fileTypes)];
        $storedName = time() . '_' . $fileType['name'];
        $filePath = 'submissions/' . $assignment->id . '/' . $submission->user_id . '/' . $storedName;

        SubmissionFile::create([
            'submission_id' => $submission->id,
            'original_name' => $fileType['name'],
            'stored_name' => $storedName,
            'file_path' => $filePath,
            'mime_type' => $fileType['type'],
            'file_size' => $fileType['size'],
            'file_extension' => $fileType['ext'],
            'is_primary' => true,
            'order' => 0,
        ]);
    }

    /**
     * Generate realistic submission text.
     */
    private function generateSubmissionText($assignment)
    {
        $texts = [
            "I have completed the assignment on {$assignment->title}. This submission includes my analysis and solutions to all the required problems. I have followed the guidelines provided and ensured that all requirements are met.",
            
            "This assignment on {$assignment->title} has been thoroughly researched and completed. I have included relevant examples and explanations to support my answers. Please find the detailed solution in the attached file.",
            
            "For this {$assignment->title} assignment, I have applied the concepts learned in class and provided comprehensive solutions. The work demonstrates my understanding of the subject matter and includes proper citations where applicable.",
            
            "I am submitting my completed work for {$assignment->title}. This assignment challenged me to think critically about the topic and I believe my solutions reflect a deep understanding of the material covered in the course.",
            
            "This submission for {$assignment->title} represents my best effort in addressing all the assignment requirements. I have double-checked my work and ensured accuracy in all calculations and explanations provided."
        ];

        return $texts[array_rand($texts)];
    }

    /**
     * Generate realistic feedback based on grade.
     */
    private function generateFeedback($grade)
    {
        if ($grade >= 90) {
            $feedbacks = [
                "Excellent work! Your submission demonstrates a thorough understanding of the concepts and shows great attention to detail. Keep up the outstanding effort!",
                "Outstanding submission! Your analysis is comprehensive and well-structured. The quality of your work exceeds expectations.",
                "Exceptional work! Your solutions are accurate and your explanations are clear and insightful. This is exactly what I was looking for."
            ];
        } elseif ($grade >= 80) {
            $feedbacks = [
                "Good work overall! Your submission shows solid understanding of the material. There are a few minor areas that could be improved, but the core concepts are well-grasped.",
                "Well done! Your work demonstrates good comprehension of the subject matter. Consider expanding on some of your explanations for even better results.",
                "Solid submission! You've addressed most of the requirements effectively. With a bit more detail in some areas, this could be excellent work."
            ];
        } else {
            $feedbacks = [
                "Your submission shows effort, but there are several areas that need improvement. Please review the feedback and consider resubmitting if allowed.",
                "You've made a good attempt, but some key concepts need to be better understood. I recommend reviewing the course materials and seeking help if needed.",
                "This work shows potential, but requires more development. Focus on the fundamental concepts and provide more detailed explanations."
            ];
        }

        return $feedbacks[array_rand($feedbacks)];
    }
}

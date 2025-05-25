<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create a notification for a user.
     */
    public static function create(array $data)
    {
        return Notification::create([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'data' => $data['data'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'icon' => $data['icon'] ?? null,
            'priority' => $data['priority'] ?? 'normal',
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }

    /**
     * Create notifications for multiple users.
     */
    public static function createForUsers(array $userIds, array $data)
    {
        $notifications = [];

        foreach ($userIds as $userId) {
            $notificationData = array_merge($data, ['user_id' => $userId]);
            $notifications[] = self::create($notificationData);
        }

        return $notifications;
    }

    /**
     * Notify when a new assignment is created.
     */
    public static function notifyNewAssignment(Assignment $assignment)
    {
        $course = $assignment->course;
        $enrolledStudents = $course->enrolledStudents()->pluck('users.id');

        return self::createForUsers($enrolledStudents->toArray(), [
            'title' => 'New Assignment: ' . $assignment->title,
            'message' => "A new assignment has been posted in {$course->title}. Due: " . $assignment->due_date->format('M j, Y'),
            'type' => 'assignment',
            'priority' => 'normal',
            'action_url' => "/assignment-submission?assignment={$assignment->id}",
            'data' => [
                'assignment_id' => $assignment->id,
                'course_id' => $course->id,
                'due_date' => $assignment->due_date->toISOString(),
            ]
        ]);
    }

    /**
     * Notify when a new quiz is created.
     */
    public static function notifyNewQuiz(Quiz $quiz)
    {
        $course = $quiz->course;
        $enrolledStudents = $course->enrolledStudents()->pluck('users.id');

        return self::createForUsers($enrolledStudents->toArray(), [
            'title' => 'New Quiz: ' . $quiz->title,
            'message' => "A new quiz is available in {$course->title}. Duration: {$quiz->duration} minutes.",
            'type' => 'quiz',
            'priority' => 'normal',
            'action_url' => "/student/quiz-take?course={$course->id}&quiz={$quiz->id}",
            'data' => [
                'quiz_id' => $quiz->id,
                'course_id' => $course->id,
                'duration' => $quiz->duration,
            ]
        ]);
    }

    /**
     * Notify when assignment is graded.
     */
    public static function notifyAssignmentGraded($submission)
    {
        $assignment = $submission->assignment;
        $course = $assignment->course;

        return self::create([
            'user_id' => $submission->user_id,
            'title' => 'Assignment Graded: ' . $assignment->title,
            'message' => "Your assignment in {$course->title} has been graded. Score: {$submission->grade}/{$assignment->max_score}",
            'type' => 'grade',
            'priority' => 'normal',
            'action_url' => "/assignment-submission?assignment={$assignment->id}",
            'data' => [
                'assignment_id' => $assignment->id,
                'course_id' => $course->id,
                'grade' => $submission->grade,
                'max_score' => $assignment->max_score,
            ]
        ]);
    }

    /**
     * Notify when quiz is graded.
     */
    public static function notifyQuizGraded($quizAttempt)
    {
        $quiz = $quizAttempt->quiz;
        $course = $quiz->course;

        return self::create([
            'user_id' => $quizAttempt->user_id,
            'title' => 'Quiz Graded: ' . $quiz->title,
            'message' => "Your quiz in {$course->title} has been graded. Score: {$quizAttempt->score}/{$quiz->max_score}",
            'type' => 'grade',
            'priority' => 'normal',
            'action_url' => "/student/quiz-take?course={$course->id}&quiz={$quiz->id}",
            'data' => [
                'quiz_id' => $quiz->id,
                'course_id' => $course->id,
                'score' => $quizAttempt->score,
                'max_score' => $quiz->max_score,
            ]
        ]);
    }

    /**
     * Notify when student enrolls in a course.
     */
    public static function notifyEnrollment(User $student, Course $course)
    {
        return self::create([
            'user_id' => $student->id,
            'title' => 'Successfully Enrolled: ' . $course->title,
            'message' => "You have successfully enrolled in {$course->title}. Welcome to the course!",
            'type' => 'course',
            'priority' => 'normal',
            'action_url' => "/courses/{$course->id}",
            'data' => [
                'course_id' => $course->id,
                'enrollment_date' => Carbon::now()->toISOString(),
            ]
        ]);
    }

    /**
     * Notify assignment due date reminder.
     */
    public static function notifyAssignmentDueReminder(Assignment $assignment, $daysUntilDue)
    {
        $course = $assignment->course;
        $enrolledStudents = $course->enrolledStudents()->pluck('users.id');

        $priority = $daysUntilDue <= 1 ? 'urgent' : ($daysUntilDue <= 3 ? 'high' : 'normal');
        $urgencyText = $daysUntilDue <= 1 ? 'Due Tomorrow!' : "Due in {$daysUntilDue} days";

        return self::createForUsers($enrolledStudents->toArray(), [
            'title' => "Assignment Reminder: {$assignment->title}",
            'message' => "{$urgencyText} - Don't forget to submit your assignment for {$course->title}.",
            'type' => 'assignment',
            'priority' => $priority,
            'action_url' => "/assignment-submission?assignment={$assignment->id}",
            'data' => [
                'assignment_id' => $assignment->id,
                'course_id' => $course->id,
                'days_until_due' => $daysUntilDue,
                'due_date' => $assignment->due_date->toISOString(),
            ]
        ]);
    }

    /**
     * Notify when new lecture is published.
     */
    public static function notifyNewLecture($lecture)
    {
        $course = $lecture->course;
        $enrolledStudents = $course->enrolledStudents()->pluck('users.id');

        return self::createForUsers($enrolledStudents->toArray(), [
            'title' => 'New Lecture: ' . $lecture->title,
            'message' => "A new lecture has been published in {$course->title}. Check it out now!",
            'type' => 'course',
            'priority' => 'normal',
            'action_url' => "/student/lecture-view?course={$course->id}&lecture={$lecture->id}",
            'data' => [
                'lecture_id' => $lecture->id,
                'course_id' => $course->id,
            ]
        ]);
    }

    /**
     * Notify system announcements.
     */
    public static function notifySystemAnnouncement(array $userIds, $title, $message, $priority = 'normal')
    {
        return self::createForUsers($userIds, [
            'title' => $title,
            'message' => $message,
            'type' => 'system',
            'priority' => $priority,
            'data' => [
                'announcement_type' => 'system',
                'created_at' => Carbon::now()->toISOString(),
            ]
        ]);
    }

    /**
     * Notify managers about course assignment changes
     */
    public static function notifyManagerCourseAssignment($instructorId, $courseIds, $assignedBy)
    {
        $instructor = User::find($instructorId);
        $assignedByUser = User::find($assignedBy);
        $managers = User::where('role', 'manager')->where('id', '!=', $assignedBy)->pluck('id')->toArray();

        if (!$instructor || !$assignedByUser || empty($managers)) {
            return false;
        }

        $courses = Course::whereIn('id', $courseIds)->get();
        $courseCount = count($courseIds);
        $courseNames = $courses->pluck('title')->take(3)->join(', ');

        if ($courseCount > 3) {
            $courseNames .= " and " . ($courseCount - 3) . " more";
        }

        $message = $courseCount === 0
            ? "All courses have been unassigned from instructor {$instructor->name} by {$assignedByUser->name}."
            : "Instructor {$instructor->name} course assignments updated by {$assignedByUser->name}. Currently assigned to {$courseCount} course(s): {$courseNames}.";

        return self::createForUsers($managers, [
            'title' => 'Instructor Assignment Updated',
            'message' => $message,
            'type' => 'course',
            'priority' => 'normal',
            'action_url' => "/manager/instructors",
            'data' => [
                'instructor_id' => $instructorId,
                'instructor_name' => $instructor->name,
                'course_ids' => $courseIds,
                'course_count' => $courseCount,
                'assigned_by' => $assignedBy,
                'assigned_by_name' => $assignedByUser->name,
                'action_type' => 'course_assignment_update'
            ]
        ]);
    }

    /**
     * Notify instructors about new course assignments
     */
    public static function notifyInstructorNewAssignment($instructorId, $courseIds, $assignedBy)
    {
        $assignedByUser = User::find($assignedBy);
        $courses = Course::whereIn('id', $courseIds)->get();

        if (!$assignedByUser || $courses->isEmpty()) {
            return false;
        }

        $courseNames = $courses->pluck('title')->join(', ');
        $courseCount = $courses->count();

        $message = $courseCount === 1
            ? "You have been assigned to teach: {$courseNames}. You can now access course materials and manage student activities."
            : "You have been assigned to teach {$courseCount} courses: {$courseNames}. You can now access all course materials and manage student activities.";

        return self::create([
            'user_id' => $instructorId,
            'title' => 'New Course Assignment',
            'message' => $message,
            'type' => 'course',
            'priority' => 'high',
            'action_url' => "/instructor/courses",
            'data' => [
                'course_ids' => $courseIds,
                'assigned_by' => $assignedBy,
                'assigned_by_name' => $assignedByUser->name,
                'action_type' => 'new_assignment'
            ]
        ]);
    }

    /**
     * Notify instructors about course unassignments
     */
    public static function notifyInstructorCourseUnassignment($instructorId, $courseIds, $unassignedBy)
    {
        $unassignedByUser = User::find($unassignedBy);
        $courses = Course::whereIn('id', $courseIds)->get();

        if (!$unassignedByUser || $courses->isEmpty()) {
            return false;
        }

        $courseNames = $courses->pluck('title')->join(', ');
        $courseCount = $courses->count();

        $message = $courseCount === 1
            ? "You have been unassigned from: {$courseNames}. You no longer have access to this course."
            : "You have been unassigned from {$courseCount} courses: {$courseNames}. You no longer have access to these courses.";

        return self::create([
            'user_id' => $instructorId,
            'title' => 'Course Unassignment',
            'message' => $message,
            'type' => 'warning',
            'priority' => 'normal',
            'action_url' => "/instructor/courses",
            'data' => [
                'course_ids' => $courseIds,
                'unassigned_by' => $unassignedBy,
                'unassigned_by_name' => $unassignedByUser->name,
                'action_type' => 'course_unassignment'
            ]
        ]);
    }

    /**
     * Notify managers about new student enrollments
     */
    public static function notifyManagerStudentEnrollment($studentId, $courseId)
    {
        $student = User::find($studentId);
        $course = Course::find($courseId);
        $managers = User::where('role', 'manager')->pluck('id')->toArray();

        if (!$student || !$course || empty($managers)) {
            return false;
        }

        return self::createForUsers($managers, [
            'title' => 'New Student Enrollment',
            'message' => "Student {$student->name} has enrolled in {$course->title}.",
            'type' => 'course',
            'priority' => 'normal',
            'action_url' => "/manager/students",
            'data' => [
                'student_id' => $studentId,
                'course_id' => $courseId,
                'action_type' => 'student_enrollment'
            ]
        ]);
    }

    /**
     * Notify instructors about new assignment submissions
     */
    public static function notifyInstructorSubmission($assignmentId, $studentId)
    {
        $assignment = Assignment::with('course')->find($assignmentId);
        $student = User::find($studentId);

        if (!$assignment || !$student || !$assignment->course->instructor_id) {
            return false;
        }

        return self::create([
            'user_id' => $assignment->course->instructor_id,
            'title' => 'New Assignment Submission',
            'message' => "Student {$student->name} has submitted assignment: {$assignment->title}",
            'type' => 'assignment',
            'priority' => 'normal',
            'action_url' => "/instructor/grading?assignment={$assignmentId}",
            'data' => [
                'assignment_id' => $assignmentId,
                'student_id' => $studentId,
                'course_id' => $assignment->course_id,
                'action_type' => 'assignment_submission'
            ]
        ]);
    }

    /**
     * Notify managers about system issues or important events
     */
    public static function notifyManagerSystemAlert($title, $message, $priority = 'high', $data = [])
    {
        $managers = User::where('role', 'manager')->pluck('id')->toArray();

        if (empty($managers)) {
            return false;
        }

        return self::createForUsers($managers, [
            'title' => $title,
            'message' => $message,
            'type' => 'system',
            'priority' => $priority,
            'action_url' => "/manager/dashboard",
            'data' => array_merge($data, ['action_type' => 'system_alert'])
        ]);
    }

    /**
     * Notify instructors about course-related updates
     */
    public static function notifyInstructorCourseUpdate($instructorId, $courseId, $title, $message, $actionUrl = null)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return false;
        }

        return self::create([
            'user_id' => $instructorId,
            'title' => $title,
            'message' => $message,
            'type' => 'course',
            'priority' => 'normal',
            'action_url' => $actionUrl ?: "/instructor/courses/{$courseId}",
            'data' => [
                'course_id' => $courseId,
                'action_type' => 'course_update'
            ]
        ]);
    }

    /**
     * Clean up expired notifications.
     */
    public static function cleanupExpiredNotifications()
    {
        return Notification::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * Get notification statistics for a user.
     */
    public static function getUserNotificationStats($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return null;
        }

        $notifications = $user->notifications();

        return [
            'total' => $notifications->count(),
            'unread' => $notifications->unread()->count(),
            'by_type' => [
                'assignment' => $notifications->ofType('assignment')->count(),
                'quiz' => $notifications->ofType('quiz')->count(),
                'grade' => $notifications->ofType('grade')->count(),
                'course' => $notifications->ofType('course')->count(),
                'system' => $notifications->ofType('system')->count(),
            ],
            'by_priority' => [
                'urgent' => $notifications->byPriority('urgent')->count(),
                'high' => $notifications->byPriority('high')->count(),
                'normal' => $notifications->byPriority('normal')->count(),
                'low' => $notifications->byPriority('low')->count(),
            ]
        ];
    }

    /**
     * Mark notifications as read for specific types.
     */
    public static function markTypeAsRead($userId, $type)
    {
        return Notification::where('user_id', $userId)
            ->where('type', $type)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => Carbon::now()
            ]);
    }

    /**
     * Create welcome notification for new users.
     */
    public static function notifyWelcome(User $user)
    {
        return self::create([
            'user_id' => $user->id,
            'title' => 'Welcome to EduPlatform!',
            'message' => "Welcome {$user->name}! We're excited to have you join our educational platform. Explore courses, complete assignments, and track your progress.",
            'type' => 'system',
            'priority' => 'normal',
            'action_url' => '/dashboard',
            'data' => [
                'welcome_message' => true,
                'user_role' => $user->role,
            ]
        ]);
    }

    /**
     * Notify when assignment submission is successful.
     */
    public static function notifyAssignmentSubmission($submission)
    {
        $assignment = $submission->assignment;
        $course = $assignment->course;

        return self::create([
            'user_id' => $submission->user_id,
            'title' => 'Assignment Submitted Successfully',
            'message' => "Your assignment \"{$assignment->title}\" has been submitted successfully for {$course->title}. You will be notified when it's graded.",
            'type' => 'assignment',
            'priority' => 'normal',
            'action_url' => "/assignment-submission?assignment={$assignment->id}",
            'data' => [
                'assignment_id' => $assignment->id,
                'course_id' => $course->id,
                'submission_id' => $submission->id,
                'submitted_at' => $submission->submitted_at->toISOString(),
            ]
        ]);
    }

    /**
     * Notify when quiz is completed.
     */
    public static function notifyQuizCompletion($userId, $quiz, $score, $maxScore, $percentage)
    {
        $course = $quiz->course;

        return self::create([
            'user_id' => $userId,
            'title' => 'Quiz Completed Successfully',
            'message' => "You have completed the quiz \"{$quiz->title}\" in {$course->title}. Score: {$score}/{$maxScore} ({$percentage}%)",
            'type' => 'quiz',
            'priority' => 'normal',
            'action_url' => "/student/quiz-take?course={$course->id}&quiz={$quiz->id}",
            'data' => [
                'quiz_id' => $quiz->id,
                'course_id' => $course->id,
                'score' => $score,
                'max_score' => $maxScore,
                'percentage' => $percentage,
                'completed_at' => Carbon::now()->toISOString(),
            ]
        ]);
    }

    /**
     * Notify when course is dropped/unenrolled.
     */
    public static function notifyUnenrollment(User $student, Course $course)
    {
        return self::create([
            'user_id' => $student->id,
            'title' => 'Course Dropped: ' . $course->title,
            'message' => "You have successfully dropped from {$course->title}. Your enrollment has been updated.",
            'type' => 'course',
            'priority' => 'normal',
            'action_url' => '/courses',
            'data' => [
                'course_id' => $course->id,
                'dropped_at' => Carbon::now()->toISOString(),
            ]
        ]);
    }

    /**
     * Notify when profile is updated.
     */
    public static function notifyProfileUpdate(User $user)
    {
        return self::create([
            'user_id' => $user->id,
            'title' => 'Profile Updated Successfully',
            'message' => 'Your profile information has been updated successfully.',
            'type' => 'success',
            'priority' => 'low',
            'action_url' => '/profile',
            'data' => [
                'updated_at' => Carbon::now()->toISOString(),
            ]
        ]);
    }

    /**
     * Notify when lecture is viewed/completed.
     */
    public static function notifyLectureProgress($userId, $lecture, $progressPercentage)
    {
        $course = $lecture->course;

        if ($progressPercentage >= 100) {
            return self::create([
                'user_id' => $userId,
                'title' => 'Lecture Completed: ' . $lecture->title,
                'message' => "Congratulations! You have completed the lecture \"{$lecture->title}\" in {$course->title}.",
                'type' => 'success',
                'priority' => 'low',
                'action_url' => "/student/lecture-view?course={$course->id}&lecture={$lecture->id}",
                'data' => [
                    'lecture_id' => $lecture->id,
                    'course_id' => $course->id,
                    'progress' => $progressPercentage,
                    'completed_at' => Carbon::now()->toISOString(),
                ]
            ]);
        }

        return null; // Don't send notification for partial progress
    }

    /**
     * Notify about important deadlines.
     */
    public static function notifyDeadlineReminder($userId, $title, $message, $actionUrl, $priority = 'high')
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => 'warning',
            'priority' => $priority,
            'action_url' => $actionUrl,
            'data' => [
                'reminder_type' => 'deadline',
                'created_at' => Carbon::now()->toISOString(),
            ]
        ]);
    }
}

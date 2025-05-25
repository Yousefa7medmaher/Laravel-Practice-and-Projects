<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Models\User;
use App\Models\Course;

class NotificationTestController extends Controller
{
    /**
     * Create test notifications for managers and instructors
     */
    public function createTestNotifications(Request $request)
    {
        try {
            // Get sample users
            $manager = User::where('role', 'manager')->first();
            $instructor = User::where('role', 'instructor')->first();
            $courses = Course::take(3)->get();

            if (!$manager || !$instructor || $courses->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Required test data not found (manager, instructor, or courses)'
                ], 400);
            }

            $notifications = [];

            // Test 1: Course assignment notification for instructor
            $notification1 = NotificationService::notifyInstructorNewAssignment(
                $instructor->id,
                $courses->pluck('id')->toArray(),
                $manager->id
            );
            $notifications[] = 'Instructor assignment notification created';

            // Test 2: Manager notification about course assignment
            $notification2 = NotificationService::notifyManagerCourseAssignment(
                $instructor->id,
                $courses->pluck('id')->toArray(),
                $manager->id
            );
            $notifications[] = 'Manager course assignment notification created';

            // Test 3: System alert for managers
            $notification3 = NotificationService::notifyManagerSystemAlert(
                'Test System Alert',
                'This is a test system alert to verify notification functionality.',
                'normal',
                ['test' => true]
            );
            $notifications[] = 'Manager system alert notification created';

            // Test 4: Course update for instructor
            $notification4 = NotificationService::notifyInstructorCourseUpdate(
                $instructor->id,
                $courses->first()->id,
                'Test Course Update',
                'This is a test course update notification.',
                '/instructor/courses/' . $courses->first()->id
            );
            $notifications[] = 'Instructor course update notification created';

            // Test 5: Assignment submission notification for instructor
            if ($courses->first()->assignments()->exists()) {
                $assignment = $courses->first()->assignments()->first();
                $student = User::where('role', 'student')->first();
                
                if ($assignment && $student) {
                    $notification5 = NotificationService::notifyInstructorSubmission(
                        $assignment->id,
                        $student->id
                    );
                    $notifications[] = 'Instructor assignment submission notification created';
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Test notifications created successfully',
                'data' => [
                    'notifications_created' => $notifications,
                    'manager_id' => $manager->id,
                    'instructor_id' => $instructor->id,
                    'course_ids' => $courses->pluck('id')->toArray()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create test notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test the bulk course assignment notification workflow
     */
    public function testCourseAssignmentWorkflow(Request $request)
    {
        try {
            $request->validate([
                'instructor_id' => 'required|exists:users,id',
                'course_ids' => 'required|array',
                'course_ids.*' => 'exists:courses,id'
            ]);

            $instructorId = $request->instructor_id;
            $courseIds = $request->course_ids;
            $managerId = Auth::id();

            // Simulate the notification workflow
            $results = [];

            // Test instructor new assignment notification
            $instructorNotification = NotificationService::notifyInstructorNewAssignment(
                $instructorId,
                $courseIds,
                $managerId
            );
            $results['instructor_notification'] = $instructorNotification ? 'Created' : 'Failed';

            // Test manager notification
            $managerNotification = NotificationService::notifyManagerCourseAssignment(
                $instructorId,
                $courseIds,
                $managerId
            );
            $results['manager_notification'] = $managerNotification ? 'Created' : 'Failed';

            // Get the created notifications for verification
            $instructor = User::find($instructorId);
            $recentInstructorNotifications = $instructor->notifications()
                ->where('created_at', '>=', now()->subMinutes(5))
                ->get();

            $managers = User::where('role', 'manager')->where('id', '!=', $managerId)->get();
            $recentManagerNotifications = [];
            foreach ($managers as $manager) {
                $notifications = $manager->notifications()
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->get();
                $recentManagerNotifications = array_merge($recentManagerNotifications, $notifications->toArray());
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Course assignment notification workflow tested',
                'data' => [
                    'workflow_results' => $results,
                    'instructor_notifications' => $recentInstructorNotifications,
                    'manager_notifications' => $recentManagerNotifications,
                    'test_parameters' => [
                        'instructor_id' => $instructorId,
                        'course_ids' => $courseIds,
                        'manager_id' => $managerId
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to test course assignment workflow',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notification statistics for testing
     */
    public function getNotificationStats()
    {
        try {
            $stats = [
                'total_notifications' => \App\Models\Notification::count(),
                'unread_notifications' => \App\Models\Notification::where('is_read', false)->count(),
                'notifications_by_type' => \App\Models\Notification::selectRaw('type, COUNT(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type'),
                'notifications_by_role' => [],
                'recent_notifications' => \App\Models\Notification::with('user:id,name,role')
                    ->where('created_at', '>=', now()->subHours(24))
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
            ];

            // Get notifications by user role
            $roleStats = \App\Models\Notification::join('users', 'notifications.user_id', '=', 'users.id')
                ->selectRaw('users.role, COUNT(*) as count')
                ->groupBy('users.role')
                ->pluck('count', 'role');
            
            $stats['notifications_by_role'] = $roleStats;

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get notification statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

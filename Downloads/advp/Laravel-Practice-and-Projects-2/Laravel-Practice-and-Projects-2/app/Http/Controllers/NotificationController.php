<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get all notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $perPage = $request->get('per_page', 20);
            $type = $request->get('type');
            $unreadOnly = $request->get('unread_only', false);

            $query = $user->notifications()
                ->active()
                ->orderBy('created_at', 'desc');

            // Filter by type if specified
            if ($type) {
                $query->ofType($type);
            }

            // Filter by read status if specified
            if ($unreadOnly) {
                $query->unread();
            }

            $notifications = $query->paginate($perPage);

            // Add computed attributes
            $notifications->getCollection()->transform(function ($notification) {
                $notification->time_ago = $notification->time_ago;
                $notification->default_icon = $notification->default_icon;
                $notification->color_class = $notification->color_class;
                return $notification;
            });

            return response()->json([
                'status' => 'success',
                'data' => $notifications
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load notifications'
            ], 500);
        }
    }

    /**
     * Get unread notification count.
     */
    public function getUnreadCount()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $count = $user->notifications()->unread()->active()->count();

            return response()->json([
                'status' => 'success',
                'data' => ['count' => $count]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to get notification count'
            ], 500);
        }
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $notification = $user->notifications()->find($id);

            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->markAsRead();

            return response()->json([
                'status' => 'success',
                'message' => 'Notification marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark notification as read'
            ], 500);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $user->notifications()->unread()->update([
                'is_read' => true,
                'read_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'All notifications marked as read'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to mark all notifications as read'
            ], 500);
        }
    }

    /**
     * Delete a notification.
     */
    public function destroy($id)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required'
                ], 401);
            }

            $user = Auth::user();
            $notification = $user->notifications()->find($id);

            if (!$notification) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Notification deleted'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete notification'
            ], 500);
        }
    }

    /**
     * Create a new notification (for testing or admin use).
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:info,success,warning,error,assignment,quiz,grade,course,system',
                'priority' => 'sometimes|in:low,normal,high,urgent',
                'action_url' => 'sometimes|string',
                'icon' => 'sometimes|string',
                'expires_at' => 'sometimes|date',
                'data' => 'sometimes|array'
            ]);

            $notification = Notification::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Notification created successfully',
                'data' => $notification
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create notification'
            ], 500);
        }
    }

    /**
     * Show the notifications page.
     */
    public function showNotificationsPage()
    {
        return view('student.notifications');
    }
}

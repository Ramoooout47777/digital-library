<?php
// app/Http/Controllers/Admin/AdminNotificationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $query = Notification::with(['user']);

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'LIKE', "%{$request->search}%")
                  ->orWhere('message', 'LIKE', "%{$request->search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_read', $request->status === 'read' ? 1 : 0);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            if ($request->user_id !== 'all') {
                $query->where('user_id', $request->user_id);
            }
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get all users for dropdown
        $users = \App\Models\User::orderBy('name')->get();

        // Statistics
        $stats = [
            'total' => Notification::count(),
            'unread' => Notification::where('is_read', false)->count(),
            'read' => Notification::where('is_read', true)->count(),
            'types' => Notification::distinct('type')->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'users', 'stats'));
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'type' => ['nullable', 'string', 'in:info,success,warning,error'],
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type ?? 'info',
            'is_read' => false,
        ];

        // If user_id is provided, send to specific user
        if ($request->user_id) {
            $data['user_id'] = $request->user_id;
            Notification::create($data);
        } else {
            // Send to all users
            $users = \App\Models\User::all();
            foreach ($users as $user) {
                Notification::create(array_merge($data, ['user_id' => $user->id]));
            }
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', __('admin.notification_sent') ?? 'Notification sent successfully');
    }

    /**
     * Mark a notification as read (AJAX).
     */
    public function markAsRead($id)
    {
        try {
            // Find notification with error handling
            $notification = Notification::find($id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }
            
            // Check if user has permission (optional)
            // if ($notification->user_id && $notification->user_id !== auth()->id()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized',
            //     ], 403);
            // }
            
            $notification->markAsRead();

            return response()->json([
                'success' => true,
                'message' => __('admin.notification_marked_read') ?? 'Notification marked as read',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as read: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark all notifications as read (AJAX).
     */
    public function markAllAsRead(Request $request)
    {
        try {
            Notification::where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => __('admin.all_notifications_marked_read') ?? 'All notifications marked as read',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all as read: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a notification (AJAX).
     */
    public function destroy($id)
    {
        try {
            // Find notification with error handling
            $notification = Notification::find($id);
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found',
                ], 404);
            }
            
            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => __('admin.notification_deleted') ?? 'Notification deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete all notifications (AJAX).
     */
    public function deleteAll(Request $request)
    {
        try {
            Notification::truncate();

            return response()->json([
                'success' => true,
                'message' => __('admin.all_notifications_deleted') ?? 'All notifications deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete all notifications: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get notifications for API (for frontend)
     */
    public function getNotifications(Request $request)
    {
        $userId = $request->user()?->id;
        
        $notifications = Notification::where('user_id', $userId)
            ->orWhereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $unreadCount = Notification::where('user_id', $userId)
            ->orWhereNull('user_id')
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }
}
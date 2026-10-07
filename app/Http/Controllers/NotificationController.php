<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get recent notifications for dropdown (AJAX).
     */
    public function getRecent(Request $request)
    {
        $userId = Auth::id();

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

    /**
     * Mark a notification as read (AJAX).
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user can only mark their own notifications
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => Notification::where('user_id', Auth::id())
                ->orWhereNull('user_id')
                ->where('is_read', false)
                ->count()
        ]);
    }

    /**
     * Mark all as read.
     */
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}

<?php
// app/Http/Controllers/Admin/AdminChatController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    /**
     * Display all chats for admin.
     */
    public function index()
    {
        $chats = Chat::with(['userOne', 'userTwo', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        $unreadCount = Message::where('is_read', false)
            ->count();

        return view('admin.chat.index', compact('chats', 'unreadCount'));
    }

    /**
     * Show chat details.
     */
    public function show(Chat $chat)
    {
        $otherUser = $chat->getOtherUser(Auth::id());

        // Mark all messages as read for admin
        Message::where('chat_id', $chat->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('chat_id', $chat->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        $users = User::where('id', '!=', Auth::id())
            ->where('is_admin', false)
            ->get(['id', 'name', 'email', 'avatar']);

        return view('admin.chat.show', compact('chat', 'messages', 'users', 'otherUser'));
    }

    /**
     * Send message as admin.
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        $userId = Auth::id();
        $receiverId = $chat->getOtherUser($userId)->id;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'type' => $request->hasFile('attachment') ? 'file' : 'text',
            'attachment' => $attachmentPath,
        ]);

        $chat->update(['last_message_at' => now()]);

        broadcast(new NewMessage($message))->toOthers();

        return redirect()->back()
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Create new chat from admin.
     */
    public function createChat(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $adminId = Auth::id();
        $userId = $request->user_id;

        // Check if chat exists
        $chat = Chat::where(function($query) use ($adminId, $userId) {
            $query->where('user_one', $adminId)->where('user_two', $userId);
        })->orWhere(function($query) use ($adminId, $userId) {
            $query->where('user_one', $userId)->where('user_two', $adminId);
        })->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_one' => $adminId,
                'user_two' => $userId,
                'last_message_at' => now(),
            ]);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $adminId,
            'receiver_id' => $userId,
            'message' => $request->message,
            'type' => 'text',
        ]);

        $chat->update(['last_message_at' => now()]);

        return redirect()->route('admin.chat.show', $chat)
            ->with('success', 'Chat started successfully!');
    }

    /**
     * Archive chat.
     */
    public function archive(Chat $chat)
    {
        $chat->update(['status' => 'archived']);
        return redirect()->back()
            ->with('success', 'Chat archived successfully!');
    }

    /**
     * Delete chat.
     */
    public function destroy(Chat $chat)
    {
        // Delete all messages and attachments
        foreach ($chat->messages as $message) {
            if ($message->attachment) {
                Storage::disk('public')->delete($message->attachment);
            }
        }
        
        $chat->delete();

        return redirect()->route('admin.chat.index')
            ->with('success', 'Chat deleted successfully!');
    }
}
<?php
// app/Http/Controllers/ChatController.php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Display chat list for user.
     */
    public function index()
    {
        $userId = Auth::id();
        
        $chats = Chat::forUser($userId)
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->paginate(10);

        // Count unread messages
        $unreadCount = Message::where('receiver_id', $userId)
            ->where('is_read', false)
            ->count();

        return view('chat.index', compact('chats', 'unreadCount'));
    }

    /**
     * Show chat conversation.
     */
    public function show(Chat $chat)
    {
        $userId = Auth::id();

        // Check if user is part of this chat
        if ($chat->user_one !== $userId && $chat->user_two !== $userId) {
            abort(403, 'You are not part of this conversation.');
        }

        // Get other user
        $otherUser = $chat->getOtherUser($userId);

        // Mark messages as read
        Message::where('chat_id', $chat->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where('chat_id', $chat->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.show', compact('chat', 'messages', 'otherUser'));
    }
    /**
     * Show form to create new conversation.
     */
    public function create()
    {
        // Get users except current user
        $users = User::where('id', '!=', Auth::id())
            ->where('is_admin', false) // Only show customers
            ->get(['id', 'name', 'email', 'avatar']);

        return view('chat.create', compact('users'));
    }


    /**
     * Start new conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
        ]);

        $userId = Auth::id();
        $receiverId = $request->receiver_id;

        // Check if chat exists
        $chat = Chat::where(function($query) use ($userId, $receiverId) {
                $query->where('user_one', $userId)->where('user_two', $receiverId);
            })->orWhere(function($query) use ($userId, $receiverId) {
                $query->where('user_one', $receiverId)->where('user_two', $userId);
            })->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_one' => $userId,
                'user_two' => $receiverId,
                'last_message_at' => now(),
            ]);
        }

        // Save attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
        }

        // Create message
        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'type' => $request->hasFile('attachment') ? 'file' : 'text',
            'attachment' => $attachmentPath,
        ]);

        // Update chat last message
        $chat->update(['last_message_at' => now()]);

        // Broadcast event
        broadcast(new NewMessage($message))->toOthers();

        return redirect()->route('chat.show', $chat)
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Send message to existing chat.
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
        ]);

        $userId = Auth::id();

        // Check if user is part of this chat
        if ($chat->user_one !== $userId && $chat->user_two !== $userId) {
            abort(403);
        }

        // Get receiver
        $receiverId = $chat->getOtherUser($userId)->id;

        // Save attachment
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('chat-attachments', 'public');
        }

        // Create message
        $message = Message::create([
            'chat_id' => $chat->id,
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'type' => $request->hasFile('attachment') ? 'file' : 'text',
            'attachment' => $attachmentPath,
        ]);

        // Update chat last message
        $chat->update(['last_message_at' => now()]);

        // Broadcast event
        broadcast(new NewMessage($message))->toOthers();

        return redirect()->back();
    }

    /**
     * Delete message.
     */
    public function deleteMessage(Message $message)
    {
        $userId = Auth::id();

        // Only sender can delete
        if ($message->sender_id !== $userId) {
            abort(403);
        }

        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return redirect()->back()
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Get unread count.
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $count]);
    }

    /**
     * Get chat users for new conversation.
     */
    public function getUsers(Request $request)
    {
        $search = $request->get('search', '');
        
        $users = User::where('id', '!=', Auth::id())
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->limit(20)
            ->get(['id', 'name', 'email', 'avatar']);

        return response()->json($users);
    }
}
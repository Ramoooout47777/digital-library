{{-- resources/views/chat/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Messages')

@push('styles')
<style>
    .chat-list-item {
        transition: all 0.3s ease;
    }
    .chat-list-item:hover {
        transform: translateX(4px);
    }
    .unread-badge {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold dark:text-slate-200 light:text-slate-800">
            <i class="fas fa-comments text-cyan-400 mr-2"></i>
            Messages
        </h1>
        <a href="{{ route('chat.create') }}" class="neu-button-primary px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-plus mr-2"></i> New Message
        </a>
    </div>

    @if(isset($unreadCount) && $unreadCount > 0)
        <div class="neu-card p-4 mb-4 border-cyan-500/20">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-cyan-400 animate-pulse"></span>
                <span class="text-sm dark:text-slate-300 light:text-slate-700">
                    You have <strong class="text-cyan-400">{{ $unreadCount }}</strong> unread message(s)
                </span>
            </div>
        </div>
    @endif

    @if($chats->count() > 0)
        <div class="space-y-3">
            @foreach($chats as $chat)
                @php
                    $otherUser = $chat->getOtherUser(Auth::id());
                    $hasUnread = $chat->hasUnreadMessages(Auth::id());
                @endphp
                <a href="{{ route('chat.show', $chat) }}" 
                   class="chat-list-item neu-card p-4 block {{ $hasUnread ? 'border-cyan-500/20' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <img src="{{ $otherUser->avatar ? asset('storage/' . $otherUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=3b82f6&color=fff&size=48' }}" 
                                     alt="{{ $otherUser->name }}" 
                                     class="w-12 h-12 rounded-full object-cover">
                                @if($hasUnread)
                                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-cyan-400 border-2 border-slate-900"></span>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-semibold dark:text-slate-200 light:text-slate-800">
                                    {{ $otherUser->name }}
                                </h4>
                                <p class="text-sm dark:text-slate-400 light:text-slate-500 truncate max-w-xs">
                                    {{ $chat->lastMessage->message ?? 'No messages yet' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs dark:text-slate-500 light:text-slate-500">
                                {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : 'N/A' }}
                            </span>
                            @if($hasUnread)
                                <span class="unread-badge block mt-1 px-2 py-0.5 text-xs rounded-full bg-cyan-500 text-white">
                                    New
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $chats->links() }}
        </div>
    @else
        <div class="neu-card p-12 text-center">
            <i class="fas fa-comments text-6xl dark:text-slate-700 light:text-slate-300 block mb-4"></i>
            <h3 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800">No Messages</h3>
            <p class="dark:text-slate-500 light:text-slate-500 mt-2">Start a new conversation.</p>
            <a href="{{ route('chat.create') }}" class="mt-4 inline-block neu-button-primary px-6 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i> New Message
            </a>
        </div>
    @endif
</div>
@endsection
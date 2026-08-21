{{-- resources/views/admin/chat/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Chat Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold dark:text-slate-200">
            <i class="fas fa-comments text-cyan-400 mr-2"></i>
            Chat Management
        </h1>
        <span class="neu-card px-4 py-2 text-sm">
            <i class="fas fa-circle text-cyan-400 mr-2"></i>
            {{ $unreadCount ?? 0 }} Unread
        </span>
    </div>

    @if($chats->count() > 0)
        <div class="neu-card overflow-hidden">
            <table class="w-full">
                <thead class="dark:bg-slate-800/50 light:bg-slate-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm">Customer</th>
                        <th class="px-4 py-3 text-left text-sm">Subject</th>
                        <th class="px-4 py-3 text-left text-sm">Status</th>
                        <th class="px-4 py-3 text-left text-sm">Last Message</th>
                        <th class="px-4 py-3 text-left text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chats as $chat)
                    @php($customer = $chat->getOtherUser(Auth::id()))
                    <tr class="border-t dark:border-slate-700/30">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $customer->avatar ? asset('storage/' . $customer->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=3b82f6&color=fff&size=32' }}" 
                                     alt="{{ $customer->name }}" 
                                     class="w-8 h-8 rounded-full">
                                <span>{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.chat.show', $chat) }}" class="text-cyan-400 hover:text-cyan-300 transition">
                                {{ $chat->lastMessage->message ?? 'No messages yet' }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($chat->status === 'active') bg-green-500/20 text-green-400
                                @elseif($chat->status === 'archived') bg-amber-500/20 text-amber-400
                                @else bg-red-500/20 text-red-400 @endif">
                                {{ $chat->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm dark:text-slate-400">
                            {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : 'N/A' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.chat.show', $chat) }}" 
                                   class="text-cyan-400 hover:text-cyan-300 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($chat->status !== 'archived')
                                    <form action="{{ route('admin.chat.archive', $chat) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $chats->links() }}
        </div>
    @else
        <div class="neu-card p-12 text-center">
            <i class="fas fa-comments text-6xl dark:text-slate-700 block mb-4"></i>
            <p class="dark:text-slate-500">No conversations yet.</p>
        </div>
    @endif
</div>
@endsection
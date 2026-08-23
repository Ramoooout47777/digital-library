{{-- resources/views/admin/chat/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Chat with ' . $otherUser->name)
@section('page-title', 'Chat with ' . $otherUser->name)

@push('styles')
<style>
    .chat-container {
        height: 500px;
        overflow-y: auto;
        scroll-behavior: smooth;
        padding: 1rem;
    }
    .chat-container::-webkit-scrollbar {
        width: 6px;
    }
    .chat-container::-webkit-scrollbar-track {
        background: rgba(51, 65, 85, 0.1);
        border-radius: 10px;
    }
    .chat-container::-webkit-scrollbar-thumb {
        background: rgba(56, 189, 248, 0.3);
        border-radius: 10px;
    }
    .message-bubble {
        max-width: 80%;
        word-wrap: break-word;
    }
    .message-in {
        align-self: flex-start;
    }
    .message-out {
        align-self: flex-end;
    }
    .message-in .bubble {
        background: #1e293b;
        border-radius: 18px 18px 18px 4px;
        padding: 0.75rem 1rem;
    }
    .light .message-in .bubble {
        background: #e8ecf1;
        color: #1e293b;
    }
    .message-out .bubble {
        background: linear-gradient(135deg, #38bdf8, #818cf8);
        border-radius: 18px 18px 4px 18px;
        padding: 0.75rem 1rem;
        color: white;
    }
    .message-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .message-date-divider {
        text-align: center;
        font-size: 0.75rem;
        color: #64748b;
        margin: 1rem 0;
        position: relative;
    }
    .message-date-divider::before,
    .message-date-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: rgba(51, 65, 85, 0.2);
    }
    .message-date-divider::before { left: 0; }
    .message-date-divider::after { right: 0; }
    .chat-user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.chat.index') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="chat-user-info">
                <img src="{{ $otherUser->avatar ? asset('storage/' . $otherUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=3b82f6&color=fff&size=40' }}" 
                     alt="{{ $otherUser->name }}" 
                     class="w-10 h-10 rounded-full border-2 border-cyan-500/30">
                <div>
                    <h2 class="text-xl font-bold text-main">{{ $otherUser->name }}</h2>
                    <p class="text-sm text-secondary">{{ $otherUser->email }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 text-xs rounded-full
                @if($chat->status == 'active') bg-emerald-500/20 text-emerald-400
                @elseif($chat->status == 'archived') bg-amber-500/20 text-amber-400
                @else bg-red-500/20 text-red-400 @endif">
                {{ ucfirst($chat->status) }}
            </span>
            @if($chat->status != 'archived')
                <form action="{{ route('admin.chat.archive', $chat) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="neu-button-gray px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                        <i class="fas fa-archive"></i> Archive
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.chat.destroy', $chat) }}" method="POST" class="inline"
                  onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="neu-button-danger px-3 py-1.5 rounded-lg text-sm flex items-center gap-1">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Messages -->
    <div class="neu-card p-0 overflow-hidden">
        <div class="chat-container" id="chatMessages">
            @php
                $lastDate = null;
            @endphp
            @foreach($messages as $message)
                @php
                    $currentDate = $message->created_at->format('Y-m-d');
                @endphp
                @if($lastDate !== $currentDate)
                    <div class="message-date-divider">
                        {{ $message->created_at->format('l, d F Y') }}
                    </div>
                    @php
                        $lastDate = $currentDate;
                    @endphp
                @endif
                
                <div class="flex items-start gap-3 mb-3 
                    {{ $message->sender_id === auth()->id() ? 'message-out' : 'message-in' }}">
                    
                    @if($message->sender_id !== auth()->id())
                        <img src="{{ $message->sender->avatar ? asset('storage/' . $message->sender->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($message->sender->name) . '&background=3b82f6&color=fff&size=36' }}" 
                             alt="{{ $message->sender->name }}" 
                             class="message-avatar">
                    @endif
                    
                    <div class="bubble message-bubble">
                        <p class="text-sm">{{ $message->message }}</p>
                        @if($message->attachment)
                            <a href="{{ asset('storage/' . $message->attachment) }}" 
                               target="_blank" 
                               class="text-xs text-cyan-400 hover:text-cyan-300 transition inline-block mt-1 flex items-center gap-1">
                                <i class="fas fa-paperclip"></i> 
                                View Attachment
                            </a>
                        @endif
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] opacity-60">{{ $message->created_at->format('H:i') }}</span>
                            @if($message->sender_id === auth()->id())
                                @if($message->is_read)
                                    <i class="fas fa-check-double text-cyan-400 text-[10px]"></i>
                                @else
                                    <i class="fas fa-check text-[10px] opacity-50"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    @if($message->sender_id === auth()->id())
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff&size=36' }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="message-avatar">
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Send Message -->
    <div class="neu-card p-4">
        <form action="{{ route('admin.chat.send', $chat) }}" method="POST" enctype="multipart/form-data" id="chatForm">
            @csrf
            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" name="message" id="messageInput"
                           placeholder="Type your reply..." 
                           class="neu-input w-full" required>
                </div>
                <div class="relative">
                    <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" 
                           class="hidden">
                    <label for="attachment" class="neu-button-gray cursor-pointer px-4 py-2 rounded-lg" title="Attach file">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <span id="fileName" class="absolute -bottom-6 left-0 text-xs text-muted truncate max-w-[100px]"></span>
                </div>
                <button type="submit" class="neu-button-primary px-6 py-2 rounded-lg">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
            @error('message')
                <p class="form-error mt-2">{{ $message }}</p>
            @enderror
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ─── Scroll to bottom ───
    const chatContainer = document.getElementById('chatMessages');
    chatContainer.scrollTop = chatContainer.scrollHeight;

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // ─── File name display ───
    document.getElementById('attachment').addEventListener('change', function() {
        const fileName = document.getElementById('fileName');
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = '';
        }
    });

    // ─── Enter key to send ───
    document.getElementById('messageInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chatForm').submit();
        }
    });

    // ─── Auto-refresh for new messages (every 10 seconds) ───
    // For production, use Laravel Echo with Pusher
    setInterval(function() {
        // Reload page to check for new messages
        // location.reload();
    }, 10000);
</script>
@endpush
@endsection
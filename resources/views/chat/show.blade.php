{{-- resources/views/chat/show.blade.php --}}
@extends('layouts.app')

@section('title', $otherUser->name)

@push('styles')
<style>
    .chat-container {
        height: 500px;
        overflow-y: auto;
        scroll-behavior: smooth;
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
    .typing-indicator {
        display: none;
        padding: 0.5rem 1rem;
        background: rgba(56, 189, 248, 0.1);
        border-radius: 18px;
        font-size: 0.875rem;
        color: #94a3b8;
    }
    .typing-indicator .dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #94a3b8;
        margin: 0 2px;
        animation: typing 1.4s infinite both;
    }
    .typing-indicator .dot:nth-child(1) { animation-delay: 0s; }
    .typing-indicator .dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator .dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-6px); }
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
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('chat.index') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div class="flex items-center gap-3">
                <img src="{{ $otherUser->avatar ? asset('storage/' . $otherUser->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) . '&background=3b82f6&color=fff&size=40' }}" 
                     alt="{{ $otherUser->name }}" 
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <h1 class="text-xl font-bold dark:text-slate-200 light:text-slate-800">
                        {{ $otherUser->name }}
                    </h1>
                    <p class="text-xs dark:text-slate-500 light:text-slate-500">
                        @if($otherUser->is_admin)
                            <span class="text-cyan-400">Admin</span>
                        @else
                            Customer
                        @endif
                        • {{ $chat->status_label }}
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isAdmin())
                <form action="{{ route('admin.chat.archive', $chat) }}" method="POST">
                    @csrf
                    <button type="submit" class="neu-button-gray px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-archive mr-2"></i> Archive
                    </button>
                </form>
                <form action="{{ route('admin.chat.destroy', $chat) }}" method="POST" 
                      onsubmit="return confirm('Delete this conversation?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="neu-button-danger px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Messages -->
    <div class="neu-card p-4 mb-4">
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
                    {{ $message->sender_id === Auth::id() ? 'message-out' : 'message-in' }}">
                    
                    @if($message->sender_id !== Auth::id())
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
                                {{ $message->type_label }} Attachment
                            </a>
                        @endif
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[10px] opacity-60">{{ $message->created_at->format('H:i') }}</span>
                            @if($message->sender_id === Auth::id())
                                @if($message->is_read)
                                    <i class="fas fa-check-double text-cyan-400 text-[10px]"></i>
                                @else
                                    <i class="fas fa-check text-[10px] opacity-50"></i>
                                @endif
                            @endif
                        </div>
                    </div>
                    
                    @if($message->sender_id === Auth::id())
                        <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=3b82f6&color=fff&size=36' }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="message-avatar">
                    @endif
                </div>
            @endforeach
            
            <!-- Typing Indicator -->
            <div class="typing-indicator" id="typingIndicator">
                <span>Typing</span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>
    </div>

    <!-- Send Message -->
    <div class="neu-card p-4">
        <form action="{{ route('chat.send', $chat) }}" method="POST" enctype="multipart/form-data" id="chatForm">
            @csrf
            <div class="flex gap-3">
                <div class="flex-1 relative">
                    <input type="text" name="message" id="messageInput"
                           placeholder="Type your message..." 
                           class="neu-input w-full pr-10" required>
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs dark:text-slate-500 light:text-slate-400" id="charCount">0/5000</span>
                </div>
                <div class="relative">
                    <input type="file" name="attachment" id="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" 
                           class="hidden">
                    <label for="attachment" class="neu-button-gray cursor-pointer px-4 py-2 rounded-lg" title="Attach file">
                        <i class="fas fa-paperclip"></i>
                    </label>
                    <span id="fileName" class="absolute -bottom-6 left-0 text-xs dark:text-slate-500 light:text-slate-500 truncate max-w-[100px]"></span>
                </div>
                <button type="submit" class="neu-button-primary px-6 py-2 rounded-lg" id="sendBtn">
                    <i class="fas fa-paper-plane"></i>
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

    // ─── Auto-scroll when new message arrives ───
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // ─── Character Counter ───
    const messageInput = document.getElementById('messageInput');
    const charCount = document.getElementById('charCount');
    
    messageInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = `${length}/5000`;
        if (length > 5000) {
            charCount.classList.add('text-red-400');
        } else {
            charCount.classList.remove('text-red-400');
        }
    });

    // ─── File name display ───
    document.getElementById('attachment').addEventListener('change', function() {
        const fileName = document.getElementById('fileName');
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = '';
        }
    });

    // ─── Typing Indicator ───
    let typingTimeout;
    messageInput.addEventListener('input', function() {
        const typing = document.getElementById('typingIndicator');
        typing.style.display = 'block';
        
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            typing.style.display = 'none';
        }, 2000);
    });

    // ─── Enter key to send ───
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('chatForm').submit();
        }
    });

    // ─── Real-time messages (Laravel Echo) ───
    // Uncomment if using Pusher
    /*
    Echo.private('chat.{{ $chat->id }}')
        .listen('NewMessage', (e) => {
            // Append new message
            // Scroll to bottom
            scrollToBottom();
        });
    */
</script>
@endpush
@endsection
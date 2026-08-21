{{-- resources/views/chat/create.blade.php --}}
@extends('layouts.app')

@section('title', 'New Message')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('chat.index') }}" class="text-cyan-400 hover:text-cyan-300 transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold dark:text-slate-200 light:text-slate-800">
            <i class="fas fa-plus-circle text-cyan-400 mr-2"></i>
            New Message
        </h1>
    </div>

    <div class="neu-card p-6 max-w-2xl mx-auto">
        <form action="{{ route('chat.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Select Recipient -->
            <div class="mb-4">
                <label class="form-label block dark:text-slate-400 light:text-slate-500 text-sm font-medium mb-2">
                    <i class="fas fa-user mr-2 text-cyan-400"></i>
                    {{ __('chat.recipient') ?? 'Recipient' }}
                </label>
                <select name="receiver_id" id="receiver_id" 
                        class="neu-input w-full @error('receiver_id') error @enderror" required>
                    <option value="">{{ __('chat.select_recipient') ?? 'Select a recipient...' }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('receiver_id')
                    <p class="form-error text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message -->
            <div class="mb-4">
                <label class="form-label block dark:text-slate-400 light:text-slate-500 text-sm font-medium mb-2">
                    <i class="fas fa-comment mr-2 text-cyan-400"></i>
                    {{ __('chat.message') ?? 'Message' }}
                </label>
                <textarea name="message" id="message" rows="5"
                          class="neu-input w-full @error('message') error @enderror"
                          placeholder="{{ __('chat.type_message') ?? 'Type your message here...' }}" required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="form-error text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1">
                    <span id="charCount">0</span>/5000
                </p>
            </div>

            <!-- Attachment -->
            <div class="mb-4">
                <label class="form-label block dark:text-slate-400 light:text-slate-500 text-sm font-medium mb-2">
                    <i class="fas fa-paperclip mr-2 text-cyan-400"></i>
                    {{ __('chat.attachment') ?? 'Attachment (Optional)' }}
                </label>
                <input type="file" name="attachment" id="attachment"
                       class="neu-input w-full @error('attachment') error @enderror"
                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1">
                    JPG, PNG, PDF, DOC, DOCX (Max 5MB)
                </p>
                @error('attachment')
                    <p class="form-error text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <div id="fileName" class="text-xs text-cyan-400 mt-1 hidden">
                    <i class="fas fa-file mr-1"></i> <span id="fileNameText"></span>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3">
                <button type="submit" class="neu-button-primary px-6 py-2.5 rounded-lg flex-1 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    {{ __('chat.send') ?? 'Send Message' }}
                </button>
                <a href="{{ route('chat.index') }}" class="neu-button px-6 py-2.5 rounded-lg">
                    {{ __('chat.cancel') ?? 'Cancel' }}
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ─── Character Counter ───
    const messageInput = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    messageInput.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        if (length > 5000) {
            charCount.classList.add('text-red-400');
        } else {
            charCount.classList.remove('text-red-400');
        }
    });

    // ─── File Name Display ───
    document.getElementById('attachment').addEventListener('change', function() {
        const fileDiv = document.getElementById('fileName');
        const fileNameText = document.getElementById('fileNameText');
        
        if (this.files && this.files[0]) {
            fileDiv.classList.remove('hidden');
            fileNameText.textContent = this.files[0].name;
        } else {
            fileDiv.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection
{{-- resources/views/profile/read-book.blade.php --}}
@extends('layouts.app')

@section('title', $purchase->book->title . ' - Reading')
@section('page-title', $purchase->book->title)

@push('styles')
<style>
    .reader-container {
        height: 85vh;
        background: #f8f4ec;
        border-radius: 12px;
        overflow: hidden;
    }
    .dark .reader-container {
        background: #1a1a2e;
    }
    .reader-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    .reader-toolbar {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .dark .reader-toolbar {
        background: rgba(30,30,50,0.95);
        border-bottom-color: rgba(255,255,255,0.05);
    }
    .book-info {
        border-right: 1px solid rgba(0,0,0,0.05);
        padding-right: 1rem;
    }
    .dark .book-info {
        border-right-color: rgba(255,255,255,0.05);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Toolbar -->
    <div class="reader-toolbar rounded-t-xl p-4 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            <a href="{{ route('profile.purchased-books') }}" class="text-cyan-400 hover:text-cyan-300 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="book-info">
                <h2 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm">{{ $purchase->book->title }}</h2>
                <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ $purchase->book->author->name ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('profile.download-book', $purchase) }}"
               class="px-4 py-2 text-sm rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition flex items-center gap-2">
                <i class="fas fa-download"></i> {{ __('profile.download') ?? 'Download' }}
            </a>
            @if($purchase->book)
                <a href="{{ route('books.show', $purchase->book) }}"
                   class="px-4 py-2 text-sm rounded-lg bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/30 transition flex items-center gap-2">
                    <i class="fas fa-info-circle"></i> {{ __('profile.book_details') ?? 'Book Details' }}
                </a>
            @endif
        </div>
    </div>

    <!-- PDF Viewer -->
    <div class="reader-container rounded-b-xl">
        @if($purchase->book->pdf_file)
            <iframe src="{{ route('books.read', $purchase->book) }}#toolbar=1&navpanes=1&scrollbar=1&view=FitH"
                    allowfullscreen>
            </iframe>
        @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <i class="fas fa-file-pdf text-6xl dark:text-slate-600 light:text-slate-300 mb-4"></i>
                    <p class="dark:text-slate-400 light:text-slate-500">{{ __('profile.pdf_not_available') ?? 'PDF not available' }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Keyboard shortcut: Escape to go back
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.location.href = '{{ route('profile.purchased-books') }}';
        }
    });
</script>
@endpush

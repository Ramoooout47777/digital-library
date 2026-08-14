{{-- resources/views/profile/purchased-books.blade.php --}}
@extends('layouts.app')

@section('title', __('profile.my_books') ?? 'My Books')
@section('page-title', __('profile.my_books') ?? 'My Books')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold dark:text-slate-200 light:text-slate-800">
            <i class="fas fa-book text-cyan-400 mr-2"></i> {{ __('profile.my_books') ?? 'My Books' }}
        </h1>
        <a href="{{ route('profile.index') }}" class="text-cyan-400 hover:text-cyan-300 transition">
            <i class="fas fa-arrow-left mr-1"></i> {{ __('profile.back_to_profile') ?? 'Back to Profile' }}
        </a>
    </div>

    @if($purchasedBooks->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($purchasedBooks as $purchase)
                <div class="neu-card p-3 animate-fade-in-up">
                    <div class="relative">
                        @if($purchase->book)
                            <a href="{{ route('books.show', $purchase->book) }}">
                        @endif
                            @if($purchase->book?->cover)
                                <img src="{{ asset('storage/' . $purchase->book->cover) }}" 
                                     alt="{{ $purchase->book->title }}" 
                                     class="w-full aspect-[3/4] object-cover rounded-lg">
                            @else
                                <div class="w-full aspect-[3/4] dark:bg-slate-700/50 light:bg-slate-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book dark:text-slate-500 light:text-slate-300 text-4xl"></i>
                                </div>
                            @endif
                        @if($purchase->book)
                            </a>
                        @endif
                        
                        <!-- Badge -->
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-500/80 text-white">
                                <i class="fas fa-check-circle mr-1"></i> {{ __('profile.purchased') ?? 'Purchased' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate">
                            {{ $purchase->book?->title ?? __('profile.book_unavailable') ?? 'Book unavailable' }}
                        </h4>
                        <p class="text-xs dark:text-slate-500 light:text-slate-500 truncate">
                            {{ $purchase->book?->author?->name ?? 'N/A' }}
                        </p>
                        
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs dark:text-slate-500 light:text-slate-500">
                                {{ $purchase->created_at->format('d/m/Y') }}
                            </span>
                            <div class="flex gap-1">
                                @if($purchase->book?->pdf_file)
                                    <a href="{{ route('profile.read-book', $purchase) }}" 
                                       class="px-3 py-1.5 text-xs rounded-lg bg-cyan-500/20 text-cyan-400 hover:bg-cyan-500/30 transition flex items-center gap-1">
                                        <i class="fas fa-book-open"></i> {{ __('profile.read') ?? 'Read' }}
                                    </a>
                                    <a href="{{ route('profile.download-book', $purchase) }}" 
                                       class="px-3 py-1.5 text-xs rounded-lg bg-emerald-500/20 text-emerald-400 hover:bg-emerald-500/30 transition flex items-center gap-1">
                                        <i class="fas fa-download"></i>
                                    </a>
                                @else
                                    <span class="text-xs dark:text-slate-500 light:text-slate-500">
                                        <i class="fas fa-file-pdf"></i> {{ __('profile.no_pdf') ?? 'No PDF' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $purchasedBooks->links() }}
        </div>
    @else
        <div class="neu-card p-12 text-center">
            <i class="fas fa-book text-6xl dark:text-slate-700 light:text-slate-300 block mb-4"></i>
            <h3 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800 mb-2">
                {{ __('profile.no_purchased_books') ?? 'No Purchased Books' }}
            </h3>
            <p class="dark:text-slate-500 light:text-slate-500">
                {{ __('profile.start_shopping_to_get_books') ?? 'Start shopping to get your books.' }}
            </p>
            <a href="{{ route('books.index') }}" class="mt-4 inline-block neu-button-primary px-6 py-2 rounded-lg">
                {{ __('profile.explore_books') ?? 'Explore Books' }}
            </a>
        </div>
    @endif
</div>
@endsection
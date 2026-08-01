{{-- resources/views/favorites/index.blade.php --}}
@extends('layouts.app')

@section('title', __('home.favorites') ?? 'My Favorites')
@section('page-title', __('home.favorites') ?? 'My Favorites')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900">
                <i class="fas fa-heart text-red-500 mr-3"></i>
                {{ __('home.favorites') ?? 'My Favorites' }}
            </h1>
            <p class="text-sm dark:text-slate-400 light:text-slate-600 mt-1">
                {{ $favorites->total() }} {{ __('home.books') ?? 'books' }} in your favorites
            </p>
        </div>
        <a href="{{ route('books.index') }}" class="neu-button px-4 py-2.5 text-sm rounded-xl flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            {{ __('home.browse_books') ?? 'Browse Books' }}
        </a>
    </div>

    <!-- Favorites Grid -->
    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($favorites as $book)
                <div class="neu-book group relative">
                    <!-- Book Cover -->
                    <a href="{{ route('books.show', $book) }}" class="block">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full aspect-[3/4] bg-slate-800/30 flex items-center justify-center">
                                <i class="fas fa-book dark:text-slate-600 light:text-slate-400 text-4xl"></i>
                            </div>
                        @endif
                    </a>
                    
                    <!-- Remove from favorites button -->
                    <form action="{{ route('favorites.destroy', $book) }}" method="POST" class="absolute top-3 right-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-9 h-9 rounded-full bg-red-500/90 hover:bg-red-600 text-white flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg"
                                onclick="return confirm('{{ __('home.remove_from_favorites_confirm') ?? 'Remove from favorites?' }}')"
                                title="{{ __('home.remove_from_favorites') ?? 'Remove from favorites' }}">
                            <i class="fas fa-heart text-sm"></i>
                        </button>
                    </form>
                    
                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex flex-col gap-1">
                        @if($book->is_free)
                            <span class="neu-badge neu-badge-free"><i class="fas fa-gift mr-1"></i> Free</span>
                        @endif
                        @if($book->is_featured)
                            <span class="neu-badge neu-badge-featured"><i class="fas fa-crown mr-1"></i> Featured</span>
                        @endif
                    </div>
                    
                    <!-- Book Info -->
                    <div class="p-3.5">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate">{{ $book->title }}</h4>
                        </a>
                        <p class="text-xs text-slate-500 dark:text-slate-400 light:text-slate-500 mt-0.5 truncate">{{ $book->author->name ?? 'N/A' }}</p>
                        <div class="flex justify-between items-center mt-2.5">
                            @if($book->is_free)
                                <span class="text-emerald-400 font-bold text-sm">{{ __('home.free') ?? 'Free' }}</span>
                            @else
                                <span class="text-cyan-400 font-bold text-sm">${{ number_format($book->final_price, 2) }}</span>
                            @endif
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-amber-400 text-xs"></i>
                                <span class="text-xs text-slate-500 dark:text-slate-400 light:text-slate-500">{{ number_format($book->average_rating, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $favorites->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="neu-card p-12 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 rounded-full bg-red-500/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-red-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800">{{ __('home.no_favorites') ?? 'No favorite books yet' }}</h3>
                <p class="dark:text-slate-400 light:text-slate-600 mt-2">{{ __('home.start_adding_favorites') ?? 'Start adding books to your favorites!' }}</p>
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    <a href="{{ route('books.index') }}" class="neu-button-primary px-6 py-3 rounded-xl flex items-center gap-2">
                        <i class="fas fa-book-open"></i>
                        {{ __('home.browse_books') ?? 'Browse Books' }}
                    </a>
                    <a href="{{ route('home') }}" class="neu-button px-6 py-3 rounded-xl flex items-center gap-2">
                        <i class="fas fa-home"></i>
                        {{ __('home.home') ?? 'Home' }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
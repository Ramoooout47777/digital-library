{{-- resources/views/authors/show.blade.php --}}
@extends('layouts.app')

@section('title', $author->name . ' - ' . config('app.name'))
@section('page-title', $author->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Author Header -->
    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center gap-6">
            @if($author->image)
                <img src="{{ asset('storage/' . $author->image) }}" 
                     alt="{{ $author->name }}" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-cyan-500/20">
            @else
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-500 flex items-center justify-center">
                    <span class="text-4xl font-bold text-white">{{ substr($author->name, 0, 2) }}</span>
                </div>
            @endif
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900">{{ $author->name }}</h1>
                @if($author->bio)
                    <p class="text-slate-600 dark:text-slate-400 light:text-slate-600 mt-2">{{ $author->bio }}</p>
                @endif
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-3">
                    <span class="text-sm dark:text-slate-400 light:text-slate-500">
                        <i class="fas fa-book mr-1"></i> {{ $books->total() }} {{ __('home.books') ?? 'សៀវភៅ' }}
                    </span>
                    @if($author->website)
                        <a href="{{ $author->website }}" target="_blank" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                            <i class="fas fa-globe mr-1"></i> {{ __('home.website') ?? 'វែបសាយ' }}
                        </a>
                    @endif
                    @if($author->email)
                        <a href="mailto:{{ $author->email }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                            <i class="fas fa-envelope mr-1"></i> {{ __('home.email') ?? 'អ៊ីមែល' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Books by Author -->
    @if($books->count() > 0)
        <h2 class="text-2xl font-bold dark:text-slate-100 light:text-slate-900 mb-6">
            {{ __('home.books_by_author') ?? 'សៀវភៅរបស់អ្នកនិពន្ធ' }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($books as $book)
                <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 group">
                    <a href="{{ route('books.show', $book) }}" class="block relative">
                        @if($book->cover)
                            <img src="{{ asset('storage/' . $book->cover) }}" 
                                 alt="{{ $book->title }}" 
                                 class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full aspect-[3/4] dark:bg-slate-700/50 light:bg-slate-100 flex items-center justify-center">
                                <i class="fas fa-book dark:text-slate-600 light:text-slate-300 text-4xl"></i>
                            </div>
                        @endif
                        @if($book->is_free)
                            <span class="absolute top-2 left-2 px-2 py-1 bg-emerald-500/90 text-white text-xs font-semibold rounded-full">
                                {{ __('home.free') ?? 'ឥតគិតថ្លៃ' }}
                            </span>
                        @endif
                    </a>
                    <div class="p-3.5">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate">{{ $book->title }}</h4>
                        </a>
                        <div class="flex justify-between items-center mt-2.5">
                            @if($book->is_free)
                                <span class="text-emerald-400 font-bold text-sm">{{ __('home.free') ?? 'ឥតគិតថ្លៃ' }}</span>
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
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-book-open text-4xl dark:text-slate-700 light:text-slate-300 block mb-3"></i>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">{{ __('home.no_books_found') ?? 'មិនមានសៀវភៅរបស់អ្នកនិពន្ធនេះ' }}</p>
        </div>
    @endif
</div>
@endsection
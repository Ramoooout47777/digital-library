{{-- resources/views/authors/index.blade.php --}}
@extends('layouts.app')

@section('title', __('home.authors') ?? 'អ្នកនិពន្ធ')
@section('page-title', __('home.authors') ?? 'អ្នកនិពន្ធ')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900">{{ __('home.authors') ?? 'អ្នកនិពន្ធ' }}</h1>
        <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">{{ $authors->total() }} {{ __('home.authors') ?? 'អ្នកនិពន្ធ' }}</p>
    </div>

    @if($authors->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($authors as $author)
                <a href="{{ route('authors.show', $author) }}" class="group">
                    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 text-center">
                        @if($author->image)
                            <img src="{{ asset('storage/' . $author->image) }}" 
                                 alt="{{ $author->name }}" 
                                 class="w-24 h-24 rounded-full object-cover mx-auto group-hover:scale-105 transition duration-300 border-2 border-cyan-500/20">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-500 flex items-center justify-center mx-auto group-hover:scale-105 transition duration-300">
                                <span class="text-2xl font-bold text-white">{{ substr($author->name, 0, 2) }}</span>
                            </div>
                        @endif
                        <h3 class="font-semibold dark:text-slate-200 light:text-slate-800 mt-3 text-sm">{{ $author->name }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 light:text-slate-500">
                            {{ $author->books_count }} {{ __('home.books') ?? 'សៀវភៅ' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $authors->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-user-edit text-4xl dark:text-slate-700 light:text-slate-300 block mb-3"></i>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">{{ __('home.no_authors') ?? 'មិនមានអ្នកនិពន្ធ' }}</p>
        </div>
    @endif
</div>
@endsection
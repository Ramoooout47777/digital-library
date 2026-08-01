{{-- resources/views/books/index.blade.php --}}
@extends('layouts.app')

@section('title', __('home.books') ?? 'សៀវភៅ')
@section('page-title', __('home.books') ?? 'សៀវភៅ')

@push('styles')
<style>
    /* ─── KEYFRAME ANIMATIONS ─── */
    
    /* 1. Fade In Up */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    /* 2. Scale In */
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.85);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .animate-scale-in {
        animation: scaleIn 0.6s ease-out forwards;
    }
    
    /* 3. Slide In Left */
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-60px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .animate-slide-left {
        animation: slideInLeft 0.6s ease-out forwards;
    }
    
    /* 4. Slide In Right */
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(60px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .animate-slide-right {
        animation: slideInRight 0.6s ease-out forwards;
    }
    
    /* 5. Pulse Glow */
    @keyframes pulseGlow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(56, 189, 248, 0.1);
        }
        50% {
            box-shadow: 0 0 40px rgba(56, 189, 248, 0.3);
        }
    }
    .animate-pulse-glow {
        animation: pulseGlow 3s ease-in-out infinite;
    }
    
    /* 6. Float */
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }
    .animate-float {
        animation: float 4s ease-in-out infinite;
    }
    
    /* 7. Shimmer */
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    .animate-shimmer {
        background: linear-gradient(90deg,
            rgba(56, 189, 248, 0.03) 0%,
            rgba(56, 189, 248, 0.1) 50%,
            rgba(56, 189, 248, 0.03) 100%
        );
        background-size: 200% 100%;
        animation: shimmer 4s ease-in-out infinite;
    }
    
    /* 8. Rotate */
    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    .animate-rotate {
        animation: rotate 8s linear infinite;
    }
    
    /* 9. Heart Beat */
    @keyframes heartBeat {
        0%, 100% {
            transform: scale(1);
        }
        14% {
            transform: scale(1.3);
        }
        28% {
            transform: scale(1);
        }
        42% {
            transform: scale(1.3);
        }
        70% {
            transform: scale(1);
        }
    }
    .animate-heart-beat {
        animation: heartBeat 1.2s ease-in-out infinite;
    }
    
    /* 10. Bounce In */
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
        }
    }
    .animate-bounce-in {
        animation: bounceIn 0.8s ease-out forwards;
    }
    
    /* 11. Blur In */
    @keyframes blurIn {
        from {
            opacity: 0;
            filter: blur(8px);
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            filter: blur(0);
            transform: scale(1);
        }
    }
    .animate-blur-in {
        animation: blurIn 0.7s ease-out forwards;
    }
    
    /* 12. Stagger Children */
    .stagger-children > * {
        opacity: 0;
    }
    .stagger-children > *:nth-child(1) { animation: fadeInUp 0.6s ease-out 0.05s forwards; }
    .stagger-children > *:nth-child(2) { animation: fadeInUp 0.6s ease-out 0.1s forwards; }
    .stagger-children > *:nth-child(3) { animation: fadeInUp 0.6s ease-out 0.15s forwards; }
    .stagger-children > *:nth-child(4) { animation: fadeInUp 0.6s ease-out 0.2s forwards; }
    .stagger-children > *:nth-child(5) { animation: fadeInUp 0.6s ease-out 0.25s forwards; }
    .stagger-children > *:nth-child(6) { animation: fadeInUp 0.6s ease-out 0.3s forwards; }
    .stagger-children > *:nth-child(7) { animation: fadeInUp 0.6s ease-out 0.35s forwards; }
    .stagger-children > *:nth-child(8) { animation: fadeInUp 0.6s ease-out 0.4s forwards; }
    .stagger-children > *:nth-child(9) { animation: fadeInUp 0.6s ease-out 0.45s forwards; }
    .stagger-children > *:nth-child(10) { animation: fadeInUp 0.6s ease-out 0.5s forwards; }
    .stagger-children > *:nth-child(11) { animation: fadeInUp 0.6s ease-out 0.55s forwards; }
    .stagger-children > *:nth-child(12) { animation: fadeInUp 0.6s ease-out 0.6s forwards; }
    .stagger-children > *:nth-child(13) { animation: fadeInUp 0.6s ease-out 0.65s forwards; }
    .stagger-children > *:nth-child(14) { animation: fadeInUp 0.6s ease-out 0.7s forwards; }
    .stagger-children > *:nth-child(15) { animation: fadeInUp 0.6s ease-out 0.75s forwards; }
    
    /* ─── Book Card Hover Effects ─── */
    .book-card-3d {
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        perspective: 800px;
    }
    .book-card-3d:hover {
        transform: translateY(-8px) rotateY(2deg) scale(1.02);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }
    
    .book-card-glow {
        position: relative;
        overflow: hidden;
    }
    .book-card-glow::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 50% 0%, rgba(56, 189, 248, 0.08), transparent 70%);
        opacity: 0;
        transition: opacity 0.5s ease;
        pointer-events: none;
    }
    .book-card-glow:hover::before {
        opacity: 1;
    }
    
    /* ─── Filter Section Animation ─── */
    .filter-section {
        animation: slideInLeft 0.6s ease-out forwards;
    }
    
    /* ─── Stat Cards Animation ─── */
    .stat-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* ─── Heart Pulse Animation ─── */
    @keyframes heart-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }
    .heart-pulse {
        animation: heart-pulse 0.3s ease-in-out;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- ============================================================ -->
    <!-- HEADER -->
    <!-- ============================================================ -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900">
                <i class="fas fa-book text-cyan-400 mr-3"></i>
                {{ __('home.books') ?? 'សៀវភៅ' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                <span class="animate-pulse-glow inline-block px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-medium mr-2">
                    {{ $books->total() }}
                </span>
                {{ __('home.books') ?? 'សៀវភៅ' }} {{ __('home.available') ?? 'មាន' }}
            </p>
        </div>
        
        <form action="{{ route('books.index') }}" method="GET" class="flex gap-2 animate-slide-right">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="{{ __('home.search_books') ?? 'ស្វែងរកសៀវភៅ...' }}" 
                   class="px-4 py-2 dark:bg-slate-800/50 light:bg-white border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 w-48 sm:w-64 transition-all duration-300 focus:scale-105">
            <button type="submit" class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- FILTERS -->
    <!-- ============================================================ -->
    <div class="filter-section dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 mb-6">
        <form action="{{ route('books.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('home.categories') ?? 'ប្រភេទ' }}</label>
                <select name="category_id" class="w-full px-3 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 transition-all duration-300 focus:scale-105">
                    <option value="">{{ __('home.all_categories') ?? 'ប្រភេទទាំងអស់' }}</option>
                    @foreach(\App\Models\Category::where('status', true)->get() as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="animate-fade-in-up" style="animation-delay: 0.2s;">
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('home.authors') ?? 'អ្នកនិពន្ធ' }}</label>
                <select name="author_id" class="w-full px-3 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 transition-all duration-300 focus:scale-105">
                    <option value="">{{ __('home.all_authors') ?? 'អ្នកនិពន្ធទាំងអស់' }}</option>
                    @foreach(\App\Models\Author::where('status', true)->get() as $author)
                        <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('home.price_range') ?? 'តម្លៃ' }}</label>
                <div class="flex gap-1">
                    <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="{{ __('home.min') ?? 'អប្បបរមា' }}" 
                           class="w-1/2 px-2 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 text-sm transition-all duration-300 focus:scale-105">
                    <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="{{ __('home.max') ?? 'អតិបរមា' }}" 
                           class="w-1/2 px-2 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 text-sm transition-all duration-300 focus:scale-105">
                </div>
            </div>
            
            <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('home.sort_by') ?? 'តម្រៀប' }}</label>
                <select name="sort" class="w-full px-3 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 transition-all duration-300 focus:scale-105">
                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('home.newest') ?? 'ថ្មីជាងគេ' }}</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>{{ __('home.title_asc') ?? 'ចំណងជើង ក-អ' }}</option>
                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>{{ __('home.price_low_high') ?? 'តម្លៃថោក-ថ្លៃ' }}</option>
                    <option value="-price" {{ request('sort') == '-price' ? 'selected' : '' }}>{{ __('home.price_high_low') ?? 'តម្លៃថ្លៃ-ថោក' }}</option>
                    <option value="views_count" {{ request('sort') == 'views_count' ? 'selected' : '' }}>{{ __('home.popular') ?? 'ពេញនិយម' }}</option>
                </select>
            </div>
            
            <div class="flex items-end gap-2 animate-fade-in-up" style="animation-delay: 0.5s;">
                <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                    <i class="fas fa-filter mr-2"></i>{{ __('home.filter') ?? 'ច្រោះ' }}
                </button>
                <a href="{{ route('books.index') }}" class="w-full bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 text-center">
                    <i class="fas fa-undo mr-2"></i>{{ __('home.reset') ?? 'កំណត់ឡើងវិញ' }}
                </a>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- BOOKS GRID -->
    <!-- ============================================================ -->
    @if($books->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 stagger-children">
            @foreach($books as $index => $book)
                <div class="book-card-3d book-card-glow dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 group"
                     style="animation-delay: {{ $index * 0.05 }}s;">
                    <div class="relative">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full aspect-[3/4] dark:bg-slate-700/50 light:bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-book dark:text-slate-600 light:text-slate-300 text-4xl animate-float"></i>
                                </div>
                            @endif
                        </a>
                        
                        <!-- Favorite Button -->
                        @auth
                            <button onclick="toggleFavorite({{ $book->id }}, this)" 
                                    class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg z-10"
                                    title="{{ auth()->user()->isFavorited($book->id) ? 'Remove from favorites' : 'Add to favorites' }}">
                                <i class="fas fa-heart text-sm 
                                    @if(auth()->user()->isFavorited($book->id)) 
                                        text-red-500 animate-heart-beat
                                    @else
                                        text-white/60 hover:text-red-400
                                    @endif
                                    transition-colors duration-300">
                                </i>
                            </button>
                        @endauth
                        
                        <!-- Badges -->
                        <div class="absolute top-2 left-2 flex flex-col gap-1">
                            @if($book->is_free)
                                <span class="px-2 py-1 bg-emerald-500/90 text-white text-xs font-semibold rounded-full animate-pulse-glow">
                                    <i class="fas fa-gift mr-1"></i>
                                    {{ __('home.free') ?? 'ឥតគិតថ្លៃ' }}
                                </span>
                            @endif
                            @if($book->is_featured)
                                <span class="px-2 py-1 bg-yellow-500/90 text-white text-xs font-semibold rounded-full">
                                    <i class="fas fa-star mr-1"></i> {{ __('home.featured') ?? 'ពិសេស' }}
                                </span>
                            @endif
                            @if($book->created_at->diffInDays(now()) < 7)
                                <span class="px-2 py-1 bg-blue-500/90 text-white text-xs font-semibold rounded-full">
                                    <i class="fas fa-sparkles mr-1"></i> New
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-3.5">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate group-hover:text-cyan-400 transition-colors duration-300">{{ $book->title }}</h4>
                        </a>
                        <p class="text-xs text-slate-500 dark:text-slate-400 light:text-slate-500 mt-0.5 truncate">{{ $book->author->name ?? 'N/A' }}</p>
                        <div class="flex justify-between items-center mt-2.5">
                            @if($book->is_free)
                                <span class="text-emerald-400 font-bold text-sm animate-pulse-glow">{{ __('home.free') ?? 'ឥតគិតថ្លៃ' }}</span>
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
        <div class="mt-8 animate-fade-in-up">
            {{ $books->appends(request()->query())->links() }}
        </div>
    @else
        <!-- No Books Found -->
        <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-12 text-center animate-bounce-in">
            <i class="fas fa-book-open text-6xl dark:text-slate-600 light:text-slate-300 block mb-4 animate-float"></i>
            <h3 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800">{{ __('home.no_books_found') ?? 'មិនមានសៀវភៅ' }}</h3>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500 mt-2">{{ __('home.try_different_search') ?? 'សូមសាកល្បងស្វែងរកផ្សេងទៀត' }}</p>
            <a href="{{ route('books.index') }}" 
               class="inline-block mt-4 px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                <i class="fas fa-undo mr-2"></i> {{ __('home.reset_filters') ?? 'កំណត់ឡើងវិញ' }}
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // ============================================================
    // TOGGLE FAVORITE FUNCTION
    // ============================================================
    function toggleFavorite(bookId, button) {
        const icon = button.querySelector('i');
        const isFavorited = icon.classList.contains('text-red-500');
        
        // Optimistic UI update
        if (isFavorited) {
            icon.classList.remove('text-red-500', 'animate-heart-beat');
            icon.classList.add('text-white/60');
            button.title = 'Add to favorites';
        } else {
            icon.classList.remove('text-white/60');
            icon.classList.add('text-red-500', 'animate-heart-beat');
            button.title = 'Remove from favorites';
        }
        
        // Add pulse animation
        icon.classList.add('heart-pulse');
        setTimeout(() => {
            icon.classList.remove('heart-pulse');
        }, 300);
        
        // Send request to server
        fetch('{{ route("favorites.toggle") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                book_id: bookId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // Revert on error
                if (isFavorited) {
                    icon.classList.remove('text-white/60');
                    icon.classList.add('text-red-500', 'animate-heart-beat');
                } else {
                    icon.classList.remove('text-red-500', 'animate-heart-beat');
                    icon.classList.add('text-white/60');
                }
                alert('Error toggling favorite');
            }
        })
        .catch(error => {
            // Revert on error
            if (isFavorited) {
                icon.classList.remove('text-white/60');
                icon.classList.add('text-red-500', 'animate-heart-beat');
            } else {
                icon.classList.remove('text-red-500', 'animate-heart-beat');
                icon.classList.add('text-white/60');
            }
            console.error('Error:', error);
            alert('Error toggling favorite');
        });
    }
</script>
@endpush
@endsection
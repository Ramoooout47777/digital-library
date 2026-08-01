{{-- resources/views/categories/show.blade.php --}}
@extends('layouts.app')

@section('title', $category->name . ' - ' . config('app.name'))
@section('page-title', $category->name)

@push('styles')
<style>
    /* ─── KEYFRAME ANIMATIONS ─── */
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(40px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.7s ease-out forwards;
    }
    
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.8) rotate(-2deg); }
        to { opacity: 1; transform: scale(1) rotate(0deg); }
    }
    .animate-scale-in {
        animation: scaleIn 0.5s ease-out forwards;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .animate-float {
        animation: float 5s ease-in-out infinite;
    }
    
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.05); }
        50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.15); }
    }
    .animate-pulse-glow {
        animation: pulseGlow 3s ease-in-out infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .animate-shimmer {
        background: linear-gradient(90deg,
            rgba(56, 189, 248, 0.03) 0%,
            rgba(56, 189, 248, 0.08) 50%,
            rgba(56, 189, 248, 0.03) 100%
        );
        background-size: 200% 100%;
        animation: shimmer 4s ease-in-out infinite;
    }
    
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-slide-left {
        animation: slideInLeft 0.5s ease-out forwards;
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(40px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .animate-slide-right {
        animation: slideInRight 0.5s ease-out forwards;
    }
    
    @keyframes blurIn {
        from { opacity: 0; filter: blur(8px); transform: scale(0.95); }
        to { opacity: 1; filter: blur(0); transform: scale(1); }
    }
    .animate-blur-in {
        animation: blurIn 0.6s ease-out forwards;
    }
    
    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.3); }
        50% { opacity: 1; transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); }
    }
    .animate-bounce-in {
        animation: bounceIn 0.7s ease-out forwards;
    }
    
    @keyframes heart-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }
    .heart-pulse {
        animation: heart-pulse 0.3s ease-in-out;
    }
    
    /* ─── Category Header ─── */
    .category-header-image {
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-header-image:hover {
        transform: scale(1.05) rotate(2deg);
    }
    
    /* ─── Sub Category Card ─── */
    .sub-category-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        perspective: 600px;
    }
    .sub-category-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }
    .sub-category-card .sub-icon {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sub-category-card:hover .sub-icon {
        transform: scale(1.1) rotate(-5deg);
    }
    
    /* ─── Book Card ─── */
    .book-card-3d {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        perspective: 800px;
    }
    .book-card-3d:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    /* ─── Sort Select ─── */
    .sort-select {
        transition: all 0.3s ease;
    }
    .sort-select:focus {
        transform: scale(1.02);
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.1);
    }
    
    /* ─── Stagger Children ─── */
    .stagger-children > * {
        opacity: 0;
    }
    .stagger-children > *:nth-child(1) { animation: fadeInUp 0.5s ease-out 0.05s forwards; }
    .stagger-children > *:nth-child(2) { animation: fadeInUp 0.5s ease-out 0.1s forwards; }
    .stagger-children > *:nth-child(3) { animation: fadeInUp 0.5s ease-out 0.15s forwards; }
    .stagger-children > *:nth-child(4) { animation: fadeInUp 0.5s ease-out 0.2s forwards; }
    .stagger-children > *:nth-child(5) { animation: fadeInUp 0.5s ease-out 0.25s forwards; }
    .stagger-children > *:nth-child(6) { animation: fadeInUp 0.5s ease-out 0.3s forwards; }
    .stagger-children > *:nth-child(7) { animation: fadeInUp 0.5s ease-out 0.35s forwards; }
    .stagger-children > *:nth-child(8) { animation: fadeInUp 0.5s ease-out 0.4s forwards; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- ============================================================ -->
    <!-- CATEGORY HEADER -->
    <!-- ============================================================ -->
    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 mb-8 animate-fade-in-up">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Category Image -->
            <div class="category-header-image flex-shrink-0">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="w-28 h-28 rounded-full object-cover border-4 border-cyan-500/20 shadow-lg shadow-cyan-500/10">
                @else
                    <div class="w-28 h-28 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-500 flex items-center justify-center shadow-lg shadow-cyan-500/20">
                        <i class="fas fa-tag text-white text-4xl animate-float"></i>
                    </div>
                @endif
            </div>
            
            <!-- Category Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900 animate-shimmer">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-slate-600 dark:text-slate-400 light:text-slate-600 mt-2 animate-blur-in">{{ $category->description }}</p>
                @endif
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 mt-3">
                    <span class="text-sm dark:text-slate-400 light:text-slate-500 animate-fade-in-up" style="animation-delay: 0.1s;">
                        <i class="fas fa-book mr-1 text-cyan-400"></i> 
                        {{ $books->total() }} {{ __('home.books') ?? 'សៀវភៅ' }}
                    </span>
                    @if($category->parent)
                        <span class="text-sm dark:text-slate-400 light:text-slate-500 animate-fade-in-up" style="animation-delay: 0.2s;">
                            <i class="fas fa-folder mr-1 text-cyan-400"></i> 
                            {{ __('home.parent_category') ?? 'ប្រភេទមេ' }}: 
                            <a href="{{ route('categories.show', $category->parent) }}" class="text-cyan-400 hover:text-cyan-300 transition hover:underline">
                                {{ $category->parent->name }}
                            </a>
                        </span>
                    @endif
                    @if($category->children->count() > 0)
                        <span class="text-sm dark:text-slate-400 light:text-slate-500 animate-fade-in-up" style="animation-delay: 0.3s;">
                            <i class="fas fa-folder-open mr-1 text-cyan-400"></i> 
                            {{ $category->children->count() }} {{ __('home.sub_categories') ?? 'ប្រភេទរង' }}
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Back Button -->
            <a href="{{ route('categories.index') }}" 
               class="flex-shrink-0 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg flex items-center gap-2 animate-slide-right">
                <i class="fas fa-arrow-left"></i>
                {{ __('home.back') ?? 'ត្រឡប់' }}
            </a>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SUB CATEGORIES -->
    <!-- ============================================================ -->
    @if($category->children->count() > 0)
        <div class="mb-8 animate-fade-in-up" style="animation-delay: 0.2s;">
            <h2 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800 mb-4">
                <i class="fas fa-folder-open mr-2 text-cyan-400"></i>
                {{ __('home.sub_categories') ?? 'ប្រភេទរង' }}
                <span class="text-sm text-slate-500 dark:text-slate-400 light:text-slate-500 ml-2">({{ $category->children->count() }})</span>
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($category->children as $child)
                    <a href="{{ route('categories.show', $child) }}" 
                       class="sub-category-card dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm hover:shadow-md transition p-4 text-center border dark:border-slate-700/50 light:border-slate-200/50 group animate-scale-in">
                        <div class="sub-icon">
                            @if($child->image)
                                <img src="{{ asset('storage/' . $child->image) }}" 
                                     alt="{{ $child->name }}" 
                                     class="w-16 h-16 rounded-full object-cover mx-auto group-hover:scale-105 transition duration-300 border-2 border-cyan-500/20">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto group-hover:scale-105 transition duration-300">
                                    <i class="fas fa-tag text-white text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="font-medium dark:text-slate-200 light:text-slate-800 mt-2 text-sm group-hover:text-cyan-400 transition-colors">{{ $child->name }}</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 light:text-slate-500">
                            {{ $child->books()->count() }} {{ __('home.books') ?? 'សៀវភៅ' }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- ============================================================ -->
    <!-- BOOKS IN CATEGORY -->
    <!-- ============================================================ -->
    @if($books->count() > 0)
        <div class="animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
                <h2 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800">
                    <i class="fas fa-book mr-2 text-cyan-400"></i>
                    {{ __('home.books_in_category') ?? 'សៀវភៅក្នុងប្រភេទនេះ' }}
                    <span class="text-sm text-slate-500 dark:text-slate-400 light:text-slate-500 ml-2">({{ $books->total() }})</span>
                </h2>
                
                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <span class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('home.sort_by') ?? 'តម្រៀប' }}</span>
                    <select onchange="window.location.href=this.value" 
                            class="sort-select px-3 py-1.5 text-sm border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg dark:bg-slate-800 light:bg-white dark:text-slate-200 light:text-slate-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all duration-300">
                        <option value="{{ route('categories.show', $category) }}?sort=newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                            {{ __('home.newest') ?? 'ថ្មីជាងគេ' }}
                        </option>
                        <option value="{{ route('categories.show', $category) }}?sort=popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                            {{ __('home.popular') ?? 'ពេញនិយម' }}
                        </option>
                        <option value="{{ route('categories.show', $category) }}?sort=price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                            {{ __('home.price_low_high') ?? 'តម្លៃថោក-ថ្លៃ' }}
                        </option>
                        <option value="{{ route('categories.show', $category) }}?sort=price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                            {{ __('home.price_high_low') ?? 'តម្លៃថ្លៃ-ថោក' }}
                        </option>
                    </select>
                </div>
            </div>
            
            <!-- Books Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 stagger-children">
                @foreach($books as $book)
                    <div class="book-card-3d dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 group">
                        <div class="relative">
                            <a href="{{ route('books.show', $book) }}" class="block">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" 
                                         alt="{{ $book->title }}" 
                                         class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500"
                                         loading="lazy">
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
                                            text-red-500
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
                                        <i class="fas fa-gift mr-1"></i> {{ __('home.free') ?? 'ឥតគិតថ្លៃ' }}
                                    </span>
                                @endif
                                @if($book->is_featured)
                                    <span class="px-2 py-1 bg-yellow-500/90 text-white text-xs font-semibold rounded-full">
                                        <i class="fas fa-star mr-1"></i> {{ __('home.featured') ?? 'ពិសេស' }}
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
            <div class="mt-8 animate-fade-in-up">
                {{ $books->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        <!-- No Books Message -->
        <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-12 text-center animate-bounce-in">
            <div class="max-w-md mx-auto">
                <div class="w-24 h-24 rounded-full bg-cyan-500/10 flex items-center justify-center mx-auto mb-4 animate-float">
                    <i class="fas fa-book-open text-cyan-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800">{{ __('home.no_books_found') ?? 'មិនមានសៀវភៅក្នុងប្រភេទនេះ' }}</h3>
                <p class="text-slate-500 dark:text-slate-400 light:text-slate-500 mt-2">{{ __('home.try_other_category') ?? 'សូមសាកល្បងប្រភេទផ្សេងទៀត' }}</p>
                <a href="{{ route('categories.index') }}" 
                   class="inline-block mt-4 px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('home.view_all_categories') ?? 'មើលប្រភេទទាំងអស់' }}
                </a>
            </div>
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
            icon.classList.remove('text-red-500');
            icon.classList.add('text-white/60');
            button.title = 'Add to favorites';
        } else {
            icon.classList.remove('text-white/60');
            icon.classList.add('text-red-500');
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
                    icon.classList.add('text-red-500');
                } else {
                    icon.classList.remove('text-red-500');
                    icon.classList.add('text-white/60');
                }
                alert('Error toggling favorite');
            }
        })
        .catch(error => {
            // Revert on error
            if (isFavorited) {
                icon.classList.remove('text-white/60');
                icon.classList.add('text-red-500');
            } else {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-white/60');
            }
            console.error('Error:', error);
            alert('Error toggling favorite');
        });
    }
</script>
@endpush
@endsection
{{-- resources/views/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', __('home.categories') ?? 'ប្រភេទ')
@section('page-title', __('home.categories') ?? 'ប្រភេទ')

@push('styles')
<style>
    /* ─── KEYFRAME ANIMATIONS ─── */
    
    /* 1. Fade In Up */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.7s ease-out forwards;
    }
    
    /* 2. Scale In with Rotation */
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.8) rotate(-3deg);
        }
        to {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }
    .animate-scale-in {
        animation: scaleIn 0.5s ease-out forwards;
    }
    
    /* 3. Float */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .animate-float {
        animation: float 5s ease-in-out infinite;
    }
    
    /* 4. Pulse Glow */
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.05); }
        50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.15); }
    }
    .animate-pulse-glow {
        animation: pulseGlow 3s ease-in-out infinite;
    }
    
    /* 5. Shimmer */
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
    
    /* 6. Blur In */
    @keyframes blurIn {
        from {
            opacity: 0;
            filter: blur(10px);
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            filter: blur(0);
            transform: scale(1);
        }
    }
    .animate-blur-in {
        animation: blurIn 0.6s ease-out forwards;
    }
    
    /* 7. Bounce In */
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
        animation: bounceIn 0.7s ease-out forwards;
    }
    
    /* 8. Slide In Left */
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .animate-slide-left {
        animation: slideInLeft 0.5s ease-out forwards;
    }
    
    /* 9. Slide In Right */
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .animate-slide-right {
        animation: slideInRight 0.5s ease-out forwards;
    }
    
    /* 10. Stagger Children */
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
    .stagger-children > *:nth-child(9) { animation: fadeInUp 0.5s ease-out 0.45s forwards; }
    .stagger-children > *:nth-child(10) { animation: fadeInUp 0.5s ease-out 0.5s forwards; }
    .stagger-children > *:nth-child(11) { animation: fadeInUp 0.5s ease-out 0.55s forwards; }
    .stagger-children > *:nth-child(12) { animation: fadeInUp 0.5s ease-out 0.6s forwards; }
    
    /* ─── Category Card Hover ─── */
    .category-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
        perspective: 600px;
    }
    .category-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    .category-card .category-icon {
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
    }
    .category-card .category-image {
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .category-card:hover .category-image {
        transform: scale(1.08);
    }
    
    /* ─── Search Animation ─── */
    .search-input {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .search-input:focus {
        transform: scale(1.02);
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.1);
    }
    
    /* ─── Counter Animation ─── */
    .counter-number {
        font-size: 2.8rem;
        font-weight: 900;
        letter-spacing: -0.04em;
        line-height: 1.1;
    }
    .dark .counter-number {
        background: linear-gradient(135deg, #f1f5f9, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .light .counter-number {
        background: linear-gradient(135deg, #0f172a, #475569);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* ─── Empty State ─── */
    .empty-state-icon {
        animation: float 6s ease-in-out infinite;
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
                <i class="fas fa-tags text-cyan-400 mr-3"></i>
                {{ __('home.categories') ?? 'ប្រភេទ' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                <span class="inline-block px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-medium mr-2 animate-pulse-glow">
                    {{ $categories->total() }}
                </span>
                {{ __('home.categories') ?? 'ប្រភេទ' }} {{ __('home.available') ?? 'មាន' }}
            </p>
        </div>
        
        <div class="flex gap-2">
            <div class="relative">
                <input type="text" id="search-input" 
                       value="{{ request('search') }}" 
                       placeholder="{{ __('home.search_categories') ?? 'ស្វែងរកប្រភេទ...' }}" 
                       class="search-input px-4 py-2 pl-10 dark:bg-slate-800/50 light:bg-white border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 w-48 sm:w-64 transition-all duration-300">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500"></i>
            </div>
            <button id="search-btn" class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                <i class="fas fa-search"></i>
            </button>
            <a href="{{ route('categories.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105">
                <i class="fas fa-undo"></i>
            </a>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CATEGORIES GRID -->
    <!-- ============================================================ -->
    @if($categories->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 stagger-children">
            @foreach($categories as $index => $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="category-card dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 group">
                    <div class="relative overflow-hidden aspect-square">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="category-image w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="category-image w-full h-full bg-gradient-to-br from-cyan-500/20 to-indigo-500/20 flex items-center justify-center group-hover:scale-110 transition duration-500">
                                <i class="fas fa-tag text-5xl text-cyan-400/50 animate-float"></i>
                            </div>
                        @endif
                        
                        <!-- Overlay Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                        
                        <!-- Books Count Badge -->
                        <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-black/40 backdrop-blur-sm text-white text-xs font-medium border border-white/10">
                            <i class="fas fa-book mr-1"></i> {{ $category->books_count ?? 0 }}
                        </div>
                        
                        <!-- Category Name -->
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-lg font-semibold text-white group-hover:text-cyan-300 transition-colors duration-300">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-white/70">
                                {{ $category->books_count ?? 0 }} {{ __('home.books') ?? 'សៀវភៅ' }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 animate-fade-in-up">
            {{ $categories->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-16 text-center animate-bounce-in">
            <div class="max-w-md mx-auto">
                <div class="empty-state-icon w-24 h-24 rounded-full bg-cyan-500/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-tags text-cyan-400 text-4xl"></i>
                </div>
                <h3 class="text-xl font-semibold dark:text-slate-200 light:text-slate-800">{{ __('home.no_categories') ?? 'មិនមានប្រភេទ' }}</h3>
                <p class="text-slate-500 dark:text-slate-400 light:text-slate-500 mt-2">{{ __('home.no_categories_message') ?? 'មិនទាន់មានប្រភេទសៀវភៅនៅឡើយ' }}</p>
                <a href="{{ route('books.index') }}" 
                   class="inline-block mt-4 px-6 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                    <i class="fas fa-book-open mr-2"></i> {{ __('home.browse_books') ?? 'ស្វែងរកសៀវភៅ' }}
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // ─── Live Search ───
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                window.location.href = '{{ route("categories.index") }}?search=' + encodeURIComponent(this.value);
            }
        });
    }
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const search = searchInput ? searchInput.value : '';
            window.location.href = '{{ route("categories.index") }}?search=' + encodeURIComponent(search);
        });
    }
</script>
@endpush
@endsection
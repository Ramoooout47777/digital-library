{{-- resources/views/books/show.blade.php --}}
@extends('layouts.app')

@section('title', $book->title . ' - ' . config('app.name'))
@section('page-title', $book->title)

@push('styles')
<style>
    /* ─── KEYFRAME ANIMATIONS ─── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.1); }
        50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.2); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    @keyframes heart-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.3); }
    }

    /* ─── PAGE FLIP / BOOK OPENING ANIMATIONS ─── */
    @keyframes bookOpen {
        0% {
            opacity: 0;
            transform: perspective(1200px) rotateY(20deg) scale(0.9);
            transform-origin: left center;
        }
        50% {
            opacity: 0.7;
            transform: perspective(1200px) rotateY(-5deg) scale(1.02);
            transform-origin: left center;
        }
        100% {
            opacity: 1;
            transform: perspective(1200px) rotateY(0deg) scale(1);
            transform-origin: left center;
        }
    }

    @keyframes bookClose {
        0% {
            opacity: 1;
            transform: perspective(1200px) rotateY(0deg) scale(1);
            transform-origin: left center;
        }
        100% {
            opacity: 0;
            transform: perspective(1200px) rotateY(-20deg) scale(0.9);
            transform-origin: left center;
        }
    }

    @keyframes pageFlipForward {
        0% {
            transform: perspective(1500px) rotateY(-15deg) scale(0.95);
            transform-origin: left center;
        }
        50% {
            transform: perspective(1500px) rotateY(5deg) scale(1.03);
            transform-origin: left center;
        }
        100% {
            transform: perspective(1500px) rotateY(0deg) scale(1);
            transform-origin: left center;
        }
    }

    @keyframes pageFlipBackward {
        0% {
            transform: perspective(1500px) rotateY(15deg) scale(0.95);
            transform-origin: right center;
        }
        50% {
            transform: perspective(1500px) rotateY(-5deg) scale(1.03);
            transform-origin: right center;
        }
        100% {
            transform: perspective(1500px) rotateY(0deg) scale(1);
            transform-origin: right center;
        }
    }

    @keyframes shadowSweep {
        0% { box-shadow: -30px 0 60px rgba(0,0,0,0.15); }
        100% { box-shadow: 0 0 0 rgba(0,0,0,0); }
    }

    .book-opening {
        animation: bookOpen 0.7s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .book-closing {
        animation: bookClose 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .page-flip-forward {
        animation: pageFlipForward 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .page-flip-backward {
        animation: pageFlipBackward 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .shadow-sweep {
        animation: shadowSweep 0.6s ease-out forwards;
    }

    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
    .animate-slide-left { animation: slideInLeft 0.5s ease-out forwards; }
    .animate-slide-right { animation: slideInRight 0.5s ease-out forwards; }
    .animate-pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
    .animate-float { animation: float 4s ease-in-out infinite; }
    .animate-shimmer {
        background: linear-gradient(90deg, rgba(56,189,248,0.03) 0%, rgba(56,189,248,0.08) 50%, rgba(56,189,248,0.03) 100%);
        background-size: 200% 100%;
        animation: shimmer 4s ease-in-out infinite;
    }
    .heart-pulse { animation: heart-pulse 0.3s ease-in-out; }

    .book-cover-3d {
        perspective: 800px;
        transform-style: preserve-3d;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .book-cover-3d:hover {
        transform: rotateY(4deg) rotateX(2deg) scale(1.02);
    }
    .btn-animated {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .btn-animated::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    .btn-animated:hover::after {
        transform: translateX(100%);
    }
    .btn-animated:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
    }
    .rating-star {
        transition: all 0.3s ease;
        cursor: default;
    }
    .rating-star:hover {
        transform: scale(1.2);
        color: #fbbf24;
    }
    .detail-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .detail-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    /* ─── PDF VIEWER ON BOOK PAGE ─── */
    .pdf-viewer-wrapper {
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform-origin: left center;
        perspective: 1200px;
    }

    .pdf-viewer-wrapper iframe {
        border-radius: 12px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
    }

    .dark .pdf-viewer-wrapper iframe {
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    }

    /* Book spine effect on PDF viewer */
    .pdf-viewer-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 40px;
        height: 100%;
        background: linear-gradient(to right, rgba(0,0,0,0.06), transparent);
        z-index: 2;
        pointer-events: none;
        border-radius: 12px 0 0 12px;
    }
    .dark .pdf-viewer-wrapper::before {
        background: linear-gradient(to right, rgba(255,255,255,0.05), transparent);
    }

    /* ─── LOCKED BOOK STYLES ─── */
    .locked-book-overlay {
        position: relative;
        background: linear-gradient(135deg, rgba(30, 58, 95, 0.95), rgba(26, 26, 46, 0.95));
        border-radius: 12px;
        padding: 3rem 2rem;
        text-align: center;
        min-height: 400px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(56, 189, 248, 0.2);
    }
    .dark .locked-book-overlay {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(26, 26, 46, 0.95));
    }
    .locked-book-overlay .lock-icon {
        font-size: 4rem;
        color: #38bdf8;
        margin-bottom: 1.5rem;
        animation: float 4s ease-in-out infinite;
    }
    .locked-book-overlay .lock-icon i {
        background: linear-gradient(135deg, #38bdf8, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .locked-book-overlay h3 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #f0e6d8;
        margin-bottom: 0.5rem;
    }
    .locked-book-overlay p {
        color: #94a3b8;
        max-width: 400px;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    .locked-book-overlay .price-tag {
        font-size: 2.5rem;
        font-weight: 700;
        color: #38bdf8;
        margin-bottom: 1.5rem;
        animation: pulseGlow 3s ease-in-out infinite;
    }
    .locked-book-overlay .btn-buy {
        background: linear-gradient(135deg, #38bdf8, #6366f1);
        color: white;
        padding: 0.75rem 2.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .locked-book-overlay .btn-buy:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 30px rgba(56, 189, 248, 0.3);
    }
    .locked-book-overlay .btn-buy i {
        -webkit-text-fill-color: white;
    }
    .locked-book-overlay .features {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
        color: #94a3b8;
        font-size: 0.9rem;
    }
    .locked-book-overlay .features span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .locked-book-overlay .features i {
        color: #38bdf8;
        -webkit-text-fill-color: #38bdf8;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- ============================================================ -->
    <!-- BOOK DETAIL PAGE WITH PDF VIEWER INTEGRATION -->
    <!-- ============================================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- ============================================================ -->
        <!-- LEFT COLUMN - Book Cover & Actions -->
        <!-- ============================================================ -->
        <div class="lg:col-span-1">
            <div class="book-cover-3d dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 animate-fade-in-up">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}"
                         alt="{{ $book->title }}"
                         class="w-full aspect-[3/4] object-cover rounded-lg shadow-lg transition-all duration-500 hover:scale-105">
                @else
                    <div class="w-full aspect-[3/4] dark:bg-slate-700/50 light:bg-slate-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book dark:text-slate-600 light:text-slate-300 text-6xl animate-float"></i>
                    </div>
                @endif
            </div>

            <!-- ACTIONS -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 mt-4 space-y-3 animate-slide-left">

                <!-- ============================================================ -->
                <!-- 1. Read Online - Conditional Based on Book Access -->
                <!-- ============================================================ -->
                @php
                    $canRead = false;

                    // Check if user can read the book
                    if ($book->is_free) {
                        $canRead = true; // Free books are always readable
                    } elseif (auth()->check()) {
                        // Check if user has purchased the book
                        if (auth()->user()->hasPurchased($book)) {
                            $canRead = true;
                        }
                    }
                @endphp

                @if($book->pdf_file && $canRead)
                    <!-- User can read the book (Free or Purchased) -->
                    <button onclick="togglePDFViewer()"
                            id="toggleReaderBtn"
                            class="btn-animated w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-book-open" id="readerIcon"></i>
                        <span id="readerBtnText">{{ __('book.read_online') ?? 'អានសៀវភៅ' }}</span>
                    </button>
                @elseif($book->pdf_file && !$canRead)
                    <!-- User cannot read - Show Buy button -->
                    <div class="relative">
                        <button disabled
                               class="w-full bg-slate-700/50 text-slate-400 font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 cursor-not-allowed">
                            <i class="fas fa-lock"></i>
                            {{ __('book.locked') ?? 'ចាក់សោរ' }}
                        </button>
                        <p class="text-xs text-center text-amber-400/70 mt-1">
                            <i class="fas fa-info-circle"></i>
                            {{ __('book.purchase_to_read') ?? 'ទិញសៀវភៅដើម្បីអាន' }}
                        </p>
                    </div>
                @endif

                <!-- 2. Add to Cart -->
                @auth
                    @if($book->stock > 0)
                        @if(!$book->is_free && !auth()->user()->hasPurchased($book))
                            <form action="{{ route('cart.add', $book) }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit"
                                        class="btn-animated w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                    <i class="fas fa-shopping-cart"></i>
                                    {{ __('book.add_to_cart') ?? 'បន្ថែមទៅកន្ត្រក' }}
                                </button>
                            </form>
                        @endif
                    @else
                        <div class="w-full bg-red-500/20 text-red-400 font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 border border-red-500/30 animate-pulse-glow">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ __('book.out_of_stock') ?? 'អស់ស្តុក' }}
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="btn-animated w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        {{ __('book.login_to_buy') ?? 'ចូលប្រើដើម្បីទិញ' }}
                    </a>
                @endauth

                <!-- 3. Buy Now -->
                @auth
                    @if($book->stock > 0)
                        @if(!$book->is_free && !auth()->user()->hasPurchased($book))
                            <form action="{{ route('orders.store') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="payment_method" value="cod">
                                <input type="hidden" name="shipping_address" value="{{ auth()->user()->address ?? '' }}">
                                <input type="hidden" name="shipping_method" value="standard">
                                <button type="submit"
                                        class="btn-animated w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                    <i class="fas fa-bolt"></i>
                                    {{ __('book.buy_now') ?? 'ទិញឥឡូវ' }}
                                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full">${{ number_format($book->final_price, 2) }}</span>
                                </button>
                            </form>
                        @endif
                    @endif
                @endauth

                <!-- 4. Favorite Button -->
                @auth
                    <button onclick="toggleFavorite({{ $book->id }}, this)"
                            class="btn-animated w-full bg-gradient-to-r from-pink-500 to-red-500 hover:from-pink-600 hover:to-red-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2
                                   @if(auth()->user()->isFavorited($book->id)) opacity-100 @else opacity-70 hover:opacity-100 @endif">
                        <i class="fas fa-heart"></i>
                        @if(auth()->user()->isFavorited($book->id))
                            {{ __('book.remove_from_favorites') ?? 'យកចេញពីចំណូលចិត្ត' }}
                        @else
                            {{ __('book.add_to_favorites') ?? 'បន្ថែមទៅចំណូលចិត្ត' }}
                        @endif
                    </button>
                @endauth

                <!-- 5. Preview -->
                @if($book->sample_pdf)
                    <a href="{{ route('books.preview', $book) }}"
                       target="_blank"
                       class="btn-animated w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-eye"></i>
                        {{ __('book.preview') ?? 'មើលសំណាក' }}
                    </a>
                @endif

                <!-- 6. Download -->
                @if($book->is_free && $book->pdf_file)
                    <a href="{{ route('books.download', $book) }}"
                       target="_blank"
                       download
                       class="btn-animated w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i>
                        {{ __('book.download_pdf') ?? 'ទាញយក PDF' }}
                    </a>
                @endif

                <!-- 7. Purchased Badge -->
                @auth
                    @if(!$book->is_free && auth()->user()->hasPurchased($book))
                        <div class="w-full bg-emerald-500/20 text-emerald-400 font-semibold py-3 px-4 rounded-lg flex items-center justify-center gap-2 border border-emerald-500/30 animate-pulse-glow">
                            <i class="fas fa-check-circle"></i>
                            {{ __('book.purchased') ?? 'បានទិញរួច' }}
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- RIGHT COLUMN - Book Details & PDF Viewer -->
        <!-- ============================================================ -->
        <div class="lg:col-span-3 space-y-6">

            <!-- Book Header -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-fade-in-up">
                <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900 animate-shimmer">{{ $book->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-2">
                    <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                        {{ __('book.by') ?? 'ដោយ' }}
                        <a href="{{ route('authors.show', $book->author) }}" class="text-cyan-400 hover:text-cyan-300 transition hover:underline">
                            {{ $book->author->name ?? 'N/A' }}
                        </a>
                    </p>
                    <span class="text-slate-600 dark:text-slate-500 light:text-slate-400">•</span>
                    <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                        <i class="fas fa-tag mr-1"></i>
                        <a href="{{ route('categories.show', $book->category) }}" class="text-cyan-400 hover:text-cyan-300 transition hover:underline">
                            {{ $book->category->name ?? 'N/A' }}
                        </a>
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 mt-4">
                    <span class="text-sm dark:text-slate-400 light:text-slate-500">
                        <i class="fas fa-language mr-1"></i> {{ strtoupper($book->language) }}
                    </span>
                    <span class="text-sm dark:text-slate-400 light:text-slate-500">
                        <i class="fas fa-file-alt mr-1"></i> {{ $book->pages }} {{ __('book.pages') ?? 'ទំព័រ' }}
                    </span>
                    <span class="text-sm dark:text-slate-400 light:text-slate-500">
                        <i class="fas fa-eye mr-1"></i> {{ number_format($book->views_count) }} {{ __('book.views') ?? 'ទស្សនា' }}
                    </span>
                    @if($book->stock > 0)
                        <span class="text-sm text-emerald-400 animate-pulse-glow">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('book.in_stock') ?? 'មានស្តុក' }}
                        </span>
                    @else
                        <span class="text-sm text-red-400">
                            <i class="fas fa-times-circle mr-1"></i> {{ __('book.out_of_stock') ?? 'អស់ស្តុក' }}
                        </span>
                    @endif
                </div>

                <!-- Rating -->
                <div class="flex items-center gap-3 mt-4">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($book->average_rating))
                                <i class="fas fa-star text-amber-400 rating-star"></i>
                            @else
                                <i class="far fa-star dark:text-slate-600 light:text-slate-300 rating-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm font-semibold dark:text-slate-200 light:text-slate-800">{{ number_format($book->average_rating, 1) }}</span>
                    <span class="text-sm dark:text-slate-400 light:text-slate-500">({{ $book->total_ratings }} {{ __('book.reviews') ?? 'ការវាយតម្លៃ' }})</span>
                </div>

                <!-- Price -->
                <div class="mt-4 pt-4 border-t dark:border-slate-700/50 light:border-slate-200/50">
                    @if($book->is_free)
                        <span class="text-3xl font-bold text-emerald-400 animate-pulse-glow">{{ __('book.free') ?? 'ឥតគិតថ្លៃ' }}</span>
                    @else
                        <span class="text-3xl font-bold text-cyan-400">${{ number_format($book->final_price, 2) }}</span>
                        @if($book->discount > 0)
                            <span class="text-sm text-slate-500 line-through ml-2">${{ number_format($book->price, 2) }}</span>
                            <span class="ml-2 px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-semibold animate-pulse-glow">
                                -{{ number_format($book->discount_percentage, 0) }}%
                            </span>
                        @endif
                    @endif
                    <span class="text-sm dark:text-slate-500 light:text-slate-500 ml-4">
                        <i class="fas fa-box mr-1"></i> {{ $book->stock }} {{ __('book.in_stock') ?? 'ក្នុងស្តុក' }}
                    </span>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- PDF VIEWER SECTION - Only for users who can read -->
            <!-- ============================================================ -->
            @if($canRead)
                <div id="pdfViewerSection" class="hidden">
                    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 animate-fade-in-up">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800">
                                <i class="fas fa-book-open mr-2 text-cyan-400"></i>
                                {{ __('book.reading') ?? 'កំពុងអាន' }}
                            </h3>
                            <button onclick="closePDFViewer()"
                                    class="text-red-400 hover:text-red-300 transition">
                                <i class="fas fa-times"></i> {{ __('book.close') ?? 'បិទ' }}
                            </button>
                        </div>

                        <!-- PDF Viewer with Page Flip Animation -->
                        <div class="pdf-viewer-wrapper" id="pdfViewerWrapper">
                            <div id="pdfContent" style="height: 75vh;">
                                @if($book->pdf_file)
                                    <iframe
                                        id="pdfIframe"
                                        src="{{ asset('storage/' . $book->pdf_file) }}#toolbar=1&navpanes=1&scrollbar=1&view=FitH"
                                        class="w-full h-full border-0"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center animate-float">
                                            <i class="fas fa-file-pdf text-6xl dark:text-slate-600 light:text-slate-300 mb-4"></i>
                                            <p class="dark:text-slate-400 light:text-slate-500">
                                                {{ __('book.pdf_not_available') ?? 'PDF មិនមានទេ' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- ============================================================ -->
                <!-- LOCKED BOOK OVERLAY - Show only Description -->
                <!-- ============================================================ -->
                <div class="locked-book-overlay animate-fade-in-up">
                    <div class="lock-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>{{ __('book.book_locked') ?? 'សៀវភៅត្រូវបានចាក់សោរ' }}</h3>
                    <p>{{ __('book.purchase_to_read_full') ?? 'សូមទិញសៀវភៅនេះដើម្បីអានមាតិកាពេញលេញ' }}</p>
                    <div class="price-tag">${{ number_format($book->final_price, 2) }}</div>
                    @auth
                        @if($book->stock > 0)
                            <form action="{{ route('orders.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="payment_method" value="cod">
                                <input type="hidden" name="shipping_address" value="{{ auth()->user()->address ?? '' }}">
                                <input type="hidden" name="shipping_method" value="standard">
                                <button type="submit" class="btn-buy">
                                    <i class="fas fa-shopping-cart"></i>
                                    {{ __('book.buy_now') ?? 'ទិញឥឡូវ' }}
                                </button>
                            </form>
                        @else
                            <div class="text-red-400 font-semibold">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ __('book.out_of_stock') ?? 'អស់ស្តុក' }}
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-buy">
                            <i class="fas fa-sign-in-alt"></i>
                            {{ __('book.login_to_buy') ?? 'ចូលប្រើដើម្បីទិញ' }}
                        </a>
                    @endauth

                    <div class="features">
                        <span><i class="fas fa-file-pdf"></i> {{ __('book.pdf_format') ?? 'ទម្រង់ PDF' }}</span>
                        <span><i class="fas fa-book-open"></i> {{ $book->pages }} {{ __('book.pages') ?? 'ទំព័រ' }}</span>
                        <span><i class="fas fa-download"></i> {{ __('book.instant_download') ?? 'ទាញយកភ្លាមៗ' }}</span>
                    </div>
                </div>
            @endif

            <!-- ============================================================ -->
            <!-- DESCRIPTION - Always Visible for All Books -->
            <!-- ============================================================ -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-3">
                    <i class="fas fa-align-left mr-2 text-cyan-400"></i>
                    {{ __('book.description') ?? 'ការពិពណ៌នា' }}
                </h3>
                @if($book->description)
                    <div class="dark:text-slate-300 light:text-slate-700 leading-relaxed prose prose-slate dark:prose-invert max-w-none">
                        {!! nl2br(e($book->description)) !!}
                    </div>
                @else
                    <p class="dark:text-slate-500 light:text-slate-400">{{ __('book.no_description') ?? 'មិនមានការពិពណ៌នា' }}</p>
                @endif
            </div>

            <!-- Book Details -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-3">
                    <i class="fas fa-info-circle mr-2 text-cyan-400"></i>
                    {{ __('book.details') ?? 'ព័ត៌មានលម្អិត' }}
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="detail-card dark:bg-slate-700/30 light:bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('book.isbn') ?? 'ISBN' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ $book->isbn ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card dark:bg-slate-700/30 light:bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('book.publisher') ?? 'គ្រឹះស្ថានបោះពុម្ព' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ $book->publisher->name ?? 'N/A' }}</p>
                    </div>
                    <div class="detail-card dark:bg-slate-700/30 light:bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('book.language') ?? 'ភាសា' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ strtoupper($book->language) }}</p>
                    </div>
                    <div class="detail-card dark:bg-slate-700/30 light:bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('book.pages') ?? 'ទំព័រ' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ number_format($book->pages) }}</p>
                    </div>
                    <div class="detail-card dark:bg-slate-700/30 light:bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('book.published') ?? 'បោះពុម្ពនៅ' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ $book->published_at ? $book->published_at->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div class="detail-card dark:bg-slate-700/30 light:bg-slate-50 p-3 rounded-lg">
                        <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('book.file_size') ?? 'ទំហំឯកសារ' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">
                            @if($book->file_size > 0)
                                {{ number_format($book->file_size / 1024, 2) }} MB
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Related Books -->
            @if(isset($relatedBooks) && $relatedBooks->count() > 0)
                <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                        <i class="fas fa-book-open mr-2 text-cyan-400"></i>
                        {{ __('book.related_books') ?? 'សៀវភៅដែលទាក់ទង' }}
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($relatedBooks as $index => $related)
                            <a href="{{ route('books.show', $related) }}"
                               class="group animate-fade-in-up" style="animation-delay: {{ $index * 0.1 }}s;">
                                <div class="dark:bg-slate-700/50 light:bg-slate-100 rounded-lg overflow-hidden transition-all duration-300 group-hover:shadow-lg group-hover:-translate-y-2">
                                    @if($related->cover)
                                        <img src="{{ asset('storage/' . $related->cover) }}"
                                             alt="{{ $related->title }}"
                                             class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full aspect-[3/4] dark:bg-slate-600/50 light:bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-book dark:text-slate-500 light:text-slate-400 text-3xl animate-float"></i>
                                        </div>
                                    @endif
                                    <div class="p-2">
                                        <p class="text-xs font-medium dark:text-slate-200 light:text-slate-800 truncate group-hover:text-cyan-400 transition-colors">{{ $related->title }}</p>
                                        <p class="text-xs dark:text-slate-400 light:text-slate-500 truncate">{{ $related->author->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // ─── PDF VIEWER TOGGLE WITH PAGE FLIP ───
    let isReaderOpen = false;

    function togglePDFViewer() {
        const section = document.getElementById('pdfViewerSection');
        const wrapper = document.getElementById('pdfViewerWrapper');
        const btnText = document.getElementById('readerBtnText');
        const icon = document.getElementById('readerIcon');

        if (!isReaderOpen) {
            // Open PDF Viewer with Book Opening Animation
            section.classList.remove('hidden');

            // Add opening animation to wrapper
            wrapper.classList.remove('book-closing');
            wrapper.classList.add('book-opening');

            // Update button
            btnText.textContent = '{{ __('book.close_reader') ?? 'បិទអ្នកអាន' }}';
            icon.className = 'fas fa-times';
            isReaderOpen = true;

        } else {
            // Close PDF Viewer with Book Closing Animation
            wrapper.classList.remove('book-opening');
            wrapper.classList.add('book-closing');

            setTimeout(() => {
                section.classList.add('hidden');
                wrapper.classList.remove('book-closing');
                isReaderOpen = false;

                // Update button
                btnText.textContent = '{{ __('book.read_online') ?? 'អានសៀវភៅ' }}';
                icon.className = 'fas fa-book-open';
            }, 500);
        }
    }

    function closePDFViewer() {
        if (isReaderOpen) {
            togglePDFViewer();
        }
    }

    // ─── KEYBOARD SHORTCUT ───
    document.addEventListener('keydown', function(e) {
        if (isReaderOpen && e.key === 'Escape') {
            closePDFViewer();
        }
    });

    // ─── TOGGLE FAVORITE ───
    function toggleFavorite(bookId, button) {
        const icon = button.querySelector('i');
        const isFavorited = icon.classList.contains('text-red-500');

        if (isFavorited) {
            icon.classList.remove('text-red-500');
            icon.classList.add('text-white/60');
            button.title = 'Add to favorites';
        } else {
            icon.classList.remove('text-white/60');
            icon.classList.add('text-red-500');
            button.title = 'Remove from favorites';
        }

        icon.classList.add('heart-pulse');
        setTimeout(() => {
            icon.classList.remove('heart-pulse');
        }, 300);

        fetch('{{ route("favorites.toggle") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ book_id: bookId })
        })
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush
@endsection

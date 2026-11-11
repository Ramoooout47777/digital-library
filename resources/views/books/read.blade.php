@extends('layouts.app')

@section('title', __('book.reading') . ': ' . $book->title)

@push('styles')
<style>
    /* Reader Layout Styles */
    .reader-header {
        transition: all 0.3s ease;
    }

    #pdf-viewer-container {
        width: 100%;
        height: calc(100vh - 190px);
        min-height: 500px;
        overflow: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 24px;
        position: relative;
        transition: background-color 0.3s ease;
        perspective: 1600px;
    }

    /* Themes */
    .theme-dark {
        background-color: #121827 !important;
    }
    .theme-mocha {
        background-color: #241c19 !important;
    }
    .theme-light {
        background-color: #f1f5f9 !important;
    }
    .theme-sepia {
        background-color: #fbf0d9 !important;
    }

    /* 3D Book Page Container & Spine Effect */
    .pdf-canvas-wrapper {
        position: relative;
        display: inline-block;
        transform-style: preserve-3d;
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5), 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 4px 12px 12px 4px;
        transition: transform 0.2s ease, box-shadow 0.3s ease;
    }

    .pdf-canvas-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 22px;
        background: linear-gradient(to right, rgba(0,0,0,0.22) 0%, rgba(0,0,0,0.08) 40%, transparent 100%);
        z-index: 10;
        pointer-events: none;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }

    .pdf-page-canvas {
        margin-bottom: 0px;
        border-radius: 4px 12px 12px 4px;
        background-color: #ffffff;
        transition: opacity 0.2s ease;
        display: block;
    }

    /* 3D Page Flip Animations */
    .flip-animate-next {
        animation: flip3dNext 0.55s cubic-bezier(0.34, 1.25, 0.64, 1) forwards;
    }

    .flip-animate-prev {
        animation: flip3dPrev 0.55s cubic-bezier(0.34, 1.25, 0.64, 1) forwards;
    }

    @keyframes flip3dNext {
        0% {
            transform: rotateY(0deg) scale(1);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5);
        }
        50% {
            transform: rotateY(-30deg) scale(0.97);
            box-shadow: -30px 20px 45px rgba(0, 0, 0, 0.65);
        }
        100% {
            transform: rotateY(0deg) scale(1);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5);
        }
    }

    @keyframes flip3dPrev {
        0% {
            transform: rotateY(0deg) scale(1);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5);
        }
        50% {
            transform: rotateY(30deg) scale(0.97);
            box-shadow: 30px 20px 45px rgba(0, 0, 0, 0.65);
        }
        100% {
            transform: rotateY(0deg) scale(1);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5);
        }
    }

    /* Interactive Page Corner Curl Hint */
    .page-corner-curl-right {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 55px;
        height: 55px;
        z-index: 25;
        cursor: pointer;
        background: linear-gradient(135deg, transparent 50%, rgba(0, 0, 0, 0.15) 50%, rgba(255, 255, 255, 0.5) 65%, transparent 72%);
        border-bottom-right-radius: 12px;
        transition: all 0.25s ease;
    }

    .page-corner-curl-right:hover {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, transparent 50%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.85) 65%, transparent 75%);
    }

    .page-corner-curl-left {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 55px;
        height: 55px;
        z-index: 25;
        cursor: pointer;
        background: linear-gradient(-135deg, transparent 50%, rgba(0, 0, 0, 0.15) 50%, rgba(255, 255, 255, 0.5) 65%, transparent 72%);
        border-bottom-left-radius: 4px;
        transition: all 0.25s ease;
    }

    .page-corner-curl-left:hover {
        width: 80px;
        height: 80px;
        background: linear-gradient(-135deg, transparent 50%, rgba(0, 0, 0, 0.3) 50%, rgba(255, 255, 255, 0.85) 65%, transparent 75%);
    }

    /* Controls Bar */
    .reader-controls {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        max-width: 95vw;
        border-radius: 16px;
    }

    /* Save Toast Animation */
    @keyframes fadeInOut {
        0% { opacity: 0; transform: translateY(10px); }
        15% { opacity: 1; transform: translateY(0); }
        85% { opacity: 1; transform: translateY(0); }
        100% { opacity: 0; transform: translateY(-10px); }
    }
    .toast-saved {
        animation: fadeInOut 2s ease forwards;
    }

    /* Fullscreen Mode Fixes */
    :fullscreen #pdf-viewer-container,
    :-webkit-full-screen #pdf-viewer-container {
        height: calc(100vh - 100px);
        padding: 16px;
    }
</style>
@endpush

@section('content')
@php
    $canDownload = $book->is_free || (auth()->check() && (auth()->user()->hasPurchased($book) || auth()->user()->isAdmin()));
@endphp

<div id="reader-root" class="max-w-7xl mx-auto pb-24">
    <!-- Reader Header Navigation -->
    <div class="reader-header flex flex-col md:flex-row justify-between items-center mb-4 gap-4 bg-slate-900/60 backdrop-blur-md p-4 rounded-2xl border border-slate-700/50">
        <div class="flex items-center gap-4 w-full md:w-auto">
            <a href="{{ route('books.show', $book) }}" class="neu-button w-10 h-10 flex items-center justify-center rounded-xl p-0 hover:scale-105 transition" title="{{ __('book.back_to_details') ?? 'Back' }}">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="overflow-hidden">
                <div class="flex items-center gap-2">
                    <h1 class="text-lg md:text-xl font-bold dark:text-white line-clamp-1 truncate">{{ $book->title }}</h1>
                    @if($book->type)
                        <span class="bg-indigo-500/20 text-indigo-300 text-[11px] px-2 py-0.5 rounded-lg border border-indigo-500/30 font-medium hidden sm:inline-flex items-center gap-1">
                            <i class="fas fa-bookmark text-indigo-400"></i>
                            {{ $book->type->name }}
                        </span>
                    @endif
                    @if(isset($isSample) && $isSample)
                        <span class="bg-amber-500/20 text-amber-300 text-[11px] px-2 py-0.5 rounded-lg border border-amber-500/30 font-medium inline-flex items-center gap-1">
                            <i class="fas fa-eye text-amber-400"></i>
                            Sample Preview
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 truncate">{{ $book->author->name ?? 'Unknown Author' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between md:justify-end gap-3 w-full md:w-auto">
            <!-- Theme Selectors -->
            <div class="flex items-center bg-slate-800/80 p-1 rounded-xl border border-slate-700">
                <button id="theme-dark-btn" onclick="setTheme('dark')" class="px-2.5 py-1 text-xs rounded-lg text-white bg-slate-700 transition flex items-center gap-1" title="Dark Theme">
                    <i class="fas fa-moon text-indigo-400"></i>
                    <span class="hidden sm:inline">{{ __('book.dark_theme') ?? 'Dark' }}</span>
                </button>
                <button id="theme-mocha-btn" onclick="setTheme('mocha')" class="px-2.5 py-1 text-xs rounded-lg text-amber-500 hover:text-amber-400 transition flex items-center gap-1" title="Mocha Theme">
                    <i class="fas fa-mug-hot text-amber-600"></i>
                    <span class="hidden sm:inline">Mocha</span>
                </button>
                <button id="theme-sepia-btn" onclick="setTheme('sepia')" class="px-2.5 py-1 text-xs rounded-lg text-amber-900 hover:text-amber-800 transition flex items-center gap-1" title="Sepia Theme">
                    <i class="fas fa-book-open text-amber-600"></i>
                    <span class="hidden sm:inline">{{ __('book.sepia_theme') ?? 'Sepia' }}</span>
                </button>
                <button id="theme-light-btn" onclick="setTheme('light')" class="px-2.5 py-1 text-xs rounded-lg text-slate-400 hover:text-slate-200 transition flex items-center gap-1" title="Light Theme">
                    <i class="fas fa-sun text-amber-400"></i>
                    <span class="hidden sm:inline">{{ __('book.light_theme') ?? 'Light' }}</span>
                </button>
            </div>

            <!-- Page Turn Sound Effect Toggle -->
            <button id="sound-toggle-btn" onclick="toggleSound()" class="neu-button px-3 py-1.5 text-xs rounded-xl text-slate-300 hover:text-cyan-400 transition flex items-center gap-1.5" title="Page Flip Sound Effect">
                <i class="fas fa-volume-up text-cyan-400" id="sound-icon"></i>
                <span class="hidden sm:inline">{{ __('book.sound') ?? 'Sound' }}</span>
            </button>

            <!-- Download Button (If authorized) -->
            @if($canDownload)
                <a href="{{ route('books.download', $book) }}" target="_blank" download class="neu-button px-3 py-2 text-xs flex items-center gap-1.5 rounded-xl text-emerald-400 hover:text-emerald-300" title="{{ __('book.download_pdf') ?? 'Download' }}">
                    <i class="fas fa-download"></i>
                    <span class="hidden sm:inline">{{ __('book.download') ?? 'PDF' }}</span>
                </a>
            @endif

            <!-- Other Books Drawer Button -->
            @if(isset($typeBooks) && count($typeBooks) > 0)
                <button onclick="toggleTypeBooksDrawer()" class="neu-button px-3 py-2 text-xs flex items-center gap-1.5 rounded-xl text-cyan-400 hover:text-cyan-300" title="Books of same type / category">
                    <i class="fas fa-layer-group"></i>
                    <span class="hidden sm:inline">{{ __('book.other_books') ?? 'Other Books' }}</span>
                    <span class="bg-cyan-500/20 px-1.5 py-0.5 rounded-full text-[10px] font-bold">{{ count($typeBooks) }}</span>
                </button>
            @endif

            <!-- Fullscreen Toggle -->
            <button id="fullscreen-btn" onclick="toggleFullscreen()" class="neu-button w-9 h-9 flex items-center justify-center rounded-xl text-xs" title="{{ __('book.fullscreen') ?? 'Fullscreen' }}">
                <i class="fas fa-expand"></i>
            </button>

            <!-- Progress Percentage & Badge -->
            <div class="bg-slate-800/80 border border-slate-700 px-3 py-1.5 rounded-xl text-xs flex items-center gap-2">
                <span class="text-slate-400 hidden sm:inline">{{ __('book.reading_progress') ?? 'Progress' }}:</span>
                <span class="font-bold text-cyan-400"><span id="progress-percent">{{ round($progress->percentage ?? 0) }}</span>%</span>
            </div>

            <span class="text-xs text-slate-300 bg-slate-800 border border-slate-700 px-3 py-1.5 rounded-xl font-medium">
                {{ __('book.page') ?? 'Page' }} <span id="current-page-num" class="text-cyan-400 font-bold">{{ $progress->current_page ?? 1 }}</span> / <span id="total-pages-num">--</span>
            </span>
        </div>
    </div>

    <!-- PDF Viewer Outer Container -->
    <div id="pdf-viewer-container" class="theme-dark rounded-2xl border border-slate-800 shadow-2xl relative">
        <!-- Top Reading Progress Indicator Bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-slate-800/80 z-30 overflow-hidden rounded-t-2xl">
            <div id="top-progress-bar" class="h-full bg-cyan-400 transition-all duration-300" style="width: {{ round($progress->percentage ?? 0) }}%"></div>
        </div>

        <!-- Loading Overlay -->
        <div id="pdf-loader" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/80 backdrop-blur-sm z-20 transition-opacity">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-cyan-500 border-t-transparent mb-3"></div>
            <p class="text-sm font-medium text-slate-300">{{ __('book.loading') ?? 'Loading PDF...' }}</p>
        </div>

        <!-- Render Target Canvas Wrapper with Page Corner Curls -->
        <div id="pdf-viewer" class="w-full flex justify-center items-center py-4">
            <div id="canvas-wrapper" class="pdf-canvas-wrapper">
                <!-- Page Corner Curl Controls -->
                <div class="page-corner-curl-right" onclick="onNextPage()" title="{{ __('book.next_page') ?? 'Flip to Next Page' }}"></div>
                <div class="page-corner-curl-left" onclick="onPrevPage('bottom')" title="{{ __('book.previous_page') ?? 'Flip to Previous Page' }}"></div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Controls Toolbar -->
<div class="reader-controls bg-slate-900/90 neu-card p-2.5 flex items-center gap-2 sm:gap-3 shadow-2xl border border-slate-700/60 backdrop-blur-xl">
    <!-- First & Prev -->
    <button id="first-page" onclick="goToFirstPage()" class="neu-button p-2 w-9 h-9 flex items-center justify-center rounded-xl text-xs hover:text-cyan-400" title="{{ __('book.first_page') ?? 'First Page' }}">
        <i class="fas fa-step-backward"></i>
    </button>
    <button id="prev-page" onclick="onPrevPage()" class="neu-button p-2 w-9 h-9 flex items-center justify-center rounded-xl text-xs hover:text-cyan-400" title="{{ __('book.previous_page') ?? 'Previous' }}">
        <i class="fas fa-chevron-left"></i>
    </button>

    <!-- Page Number Input & Slider Scrubber -->
    <div class="flex items-center gap-2 px-1">
        <input type="number" id="page-input" value="{{ $progress->current_page ?? 1 }}" min="1" class="neu-input w-14 text-center py-1 px-1.5 text-xs font-semibold rounded-lg bg-slate-800 text-cyan-300 border border-slate-700 focus:outline-none focus:border-cyan-500">
        <span class="text-slate-400 text-xs font-medium">/ <span id="total-pages-display">--</span></span>
        <input type="range" id="page-slider" value="{{ $progress->current_page ?? 1 }}" min="1" max="1" class="w-20 sm:w-28 accent-cyan-400 bg-slate-800 rounded-lg cursor-pointer hidden md:block">
    </div>

    <!-- Next & Last -->
    <button id="next-page" onclick="onNextPage()" class="neu-button p-2 w-9 h-9 flex items-center justify-center rounded-xl text-xs hover:text-cyan-400" title="{{ __('book.next_page') ?? 'Next' }}">
        <i class="fas fa-chevron-right"></i>
    </button>
    <button id="last-page" onclick="goToLastPage()" class="neu-button p-2 w-9 h-9 flex items-center justify-center rounded-xl text-xs hover:text-cyan-400" title="{{ __('book.last_page') ?? 'Last Page' }}">
        <i class="fas fa-step-forward"></i>
    </button>

    <div class="h-6 w-px bg-slate-700/80 mx-0.5 hidden sm:block"></div>

    <!-- Zoom & Fit Controls -->
    <div class="flex items-center gap-1">
        <button id="zoom-out" onclick="zoomOut()" class="neu-button p-2 w-8 h-8 flex items-center justify-center text-xs rounded-lg hover:text-cyan-400" title="{{ __('book.zoom_out') ?? 'Zoom Out' }}">
            <i class="fas fa-minus"></i>
        </button>
        <span id="zoom-percent" class="text-xs font-mono text-cyan-300 min-w-[42px] text-center font-bold">100%</span>
        <button id="zoom-in" onclick="zoomIn()" class="neu-button p-2 w-8 h-8 flex items-center justify-center text-xs rounded-lg hover:text-cyan-400" title="{{ __('book.zoom_in') ?? 'Zoom In' }}">
            <i class="fas fa-plus"></i>
        </button>
        <button id="fit-width" onclick="fitToWidth()" class="neu-button px-2 py-1.5 text-[11px] rounded-lg text-slate-300 hover:text-cyan-400 hidden sm:flex items-center gap-1" title="Fit to Width">
            <i class="fas fa-arrows-alt-h"></i>
            <span>{{ __('book.fit_page') ?? 'Fit' }}</span>
        </button>
    </div>
</div>

<!-- Save Progress Toast Notification -->
<div id="save-toast" class="fixed top-6 right-6 bg-slate-800/90 text-emerald-400 border border-emerald-500/40 px-4 py-2 rounded-xl text-xs font-semibold shadow-2xl flex items-center gap-2 pointer-events-none opacity-0 transition-opacity z-50">
    <i class="fas fa-check-circle"></i>
    <span>{{ __('book.progress_saved') ?? 'Progress Saved' }}</span>
</div>

<!-- Slide-out Drawer for Books of Same Type/Category -->
@if(isset($typeBooks) && count($typeBooks) > 0)
<div id="type-books-drawer" class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-slate-900/95 backdrop-blur-xl border-l border-slate-700/80 shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col">
    <!-- Drawer Header -->
    <div class="p-4 border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-cyan-500/20 border border-cyan-500/30 flex items-center justify-center text-cyan-400 text-sm">
                <i class="fas fa-book-open"></i>
            </div>
            <div>
                <h3 class="font-bold text-white text-sm line-clamp-1">
                    {{ $book->type->name ?? ($book->category->name ?? __('book.related_books')) }}
                </h3>
                <p class="text-[11px] text-slate-400">{{ __('book.books_of_type_desc') ?? 'Books of this type you can open & read' }}</p>
            </div>
        </div>
        <button onclick="toggleTypeBooksDrawer()" class="w-8 h-8 rounded-lg bg-slate-800 text-slate-400 hover:text-white flex items-center justify-center transition">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Drawer Book List -->
    <div class="flex-1 overflow-y-auto p-4 space-y-3">
        @foreach($typeBooks as $tb)
            @php
                $canReadTb = $tb->is_free || (auth()->check() && (auth()->user()->hasPurchased($tb) || auth()->user()->isAdmin()));
            @endphp
            <div class="bg-slate-800/60 border border-slate-700/60 hover:border-cyan-500/40 p-3 rounded-xl flex gap-3 transition group">
                <img src="{{ $tb->cover_url ?? asset('images/default-cover.png') }}" alt="{{ $tb->title }}" class="w-14 h-20 object-cover rounded-lg shadow-md flex-shrink-0">
                <div class="flex-1 min-w-0 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-1">
                            <h4 class="text-xs font-bold text-white group-hover:text-cyan-400 transition truncate">{{ $tb->title }}</h4>
                            @if($tb->is_free)
                                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 px-1.5 py-0.5 rounded border border-emerald-500/20">{{ __('book.free') ?? 'FREE' }}</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">{{ $tb->author->name ?? 'Author' }}</p>
                        @if($tb->type)
                            <span class="inline-block mt-1 text-[10px] text-indigo-300 bg-indigo-500/20 px-1.5 py-0.5 rounded border border-indigo-500/30">{{ $tb->type->name }}</span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-700/40">
                        <span class="text-[11px] font-semibold text-slate-300">
                            {{ $tb->is_free ? __('book.free') ?? 'Free' : $tb->formatted_final_price }}
                        </span>
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('books.show', $tb) }}" class="px-2 py-1 text-[10px] rounded-lg bg-slate-700 text-slate-300 hover:text-white transition">
                                {{ __('book.details') ?? 'Details' }}
                            </a>
                            @if($canReadTb)
                                <a href="{{ route('books.read', $tb) }}" class="px-2 py-1 text-[10px] font-semibold rounded-lg bg-cyan-500 text-slate-950 hover:bg-cyan-400 transition flex items-center gap-1">
                                    <i class="fas fa-book-reader"></i> {{ __('book.read') ?? 'Read' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<!-- Drawer Backdrop -->
<div id="drawer-backdrop" onclick="toggleTypeBooksDrawer()" class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm hidden transition-opacity"></div>
@endif
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    const pdfUrl = "{{ $pdfUrl }}";
    const bookId = "{{ $book->id }}";
    const initialPage = {{ $progress->current_page ?? 1 }};
    const updateProgressUrl = "{{ route('books.progress.update', $book) }}";

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    let pdfDoc = null;
    let pageNum = initialPage;
    let pageRendering = false;
    let pageNumPending = null;
    let scale = 1.2;
    let currentTheme = 'dark';
    let isFlipping = false;

    const canvasWrapper = document.getElementById('canvas-wrapper');
    const container = document.getElementById('pdf-viewer-container');
    const loader = document.getElementById('pdf-loader');
    const topProgressBar = document.getElementById('top-progress-bar');
    const pageSlider = document.getElementById('page-slider');

    // Create Canvas Element
    const canvas = document.createElement('canvas');
    canvas.className = 'pdf-page-canvas opacity-100 transition-opacity duration-200';
    const ctx = canvas.getContext('2d', { alpha: false });
    canvasWrapper.appendChild(canvas);

    /**
     * Render specified page number with High-DPI support
     */
    function renderPage(num) {
        if (!pdfDoc) return;
        pageRendering = true;
        canvas.classList.add('opacity-40');

        pdfDoc.getPage(num).then((page) => {
            const pixelRatio = window.devicePixelRatio || 1;
            const viewport = page.getViewport({ scale: scale });

            canvas.height = Math.floor(viewport.height * pixelRatio);
            canvas.width = Math.floor(viewport.width * pixelRatio);
            canvas.style.width = Math.floor(viewport.width) + "px";
            canvas.style.height = Math.floor(viewport.height) + "px";

            const transform = pixelRatio !== 1 ? [pixelRatio, 0, 0, pixelRatio, 0, 0] : null;

            const renderContext = {
                canvasContext: ctx,
                transform: transform,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);

            renderTask.promise.then(() => {
                pageRendering = false;
                canvas.classList.remove('opacity-40');
                if (loader) loader.style.display = 'none';

                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
                saveProgressDebounced(num);
            }).catch(err => {
                console.error("Render task error:", err);
                pageRendering = false;
                canvas.classList.remove('opacity-40');
            });
        });

        // Update UI counters, inputs, and progress bars
        document.getElementById('current-page-num').textContent = num;
        document.getElementById('page-input').value = num;

        if (pageSlider) pageSlider.value = num;

        if (pdfDoc.numPages) {
            const percent = Math.round((num / pdfDoc.numPages) * 100);
            document.getElementById('progress-percent').textContent = percent;
            if (topProgressBar) topProgressBar.style.width = percent + '%';
        }
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    /* Navigation Controls with 3D Page-Flip Animation & Sound */
    let soundEnabled = true;

    function playPageFlipSound() {
        if (!soundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const audioCtx = new AudioContext();
            const bufferSize = audioCtx.sampleRate * 0.12;
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);

            for (let i = 0; i < bufferSize; i++) {
                data[i] = (Math.random() * 2 - 1) * Math.exp(-i / (bufferSize * 0.25));
            }

            const noise = audioCtx.createBufferSource();
            noise.buffer = buffer;

            const filter = audioCtx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(900, audioCtx.currentTime);
            filter.frequency.exponentialRampToValueAtTime(120, audioCtx.currentTime + 0.12);

            const gain = audioCtx.createGain();
            gain.gain.setValueAtTime(0.18, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.12);

            noise.connect(filter);
            filter.connect(gain);
            gain.connect(audioCtx.destination);

            noise.start();
        } catch (e) {}
    }

    function toggleSound() {
        soundEnabled = !soundEnabled;
        const icon = document.getElementById('sound-icon');
        const btn = document.getElementById('sound-toggle-btn');
        if (soundEnabled) {
            icon.className = 'fas fa-volume-up text-cyan-400';
            btn.classList.remove('opacity-60');
        } else {
            icon.className = 'fas fa-volume-mute text-slate-500';
            btn.classList.add('opacity-60');
        }
    }

    function triggerPageFlipAnimation(direction = 'next') {
        playPageFlipSound();
        canvasWrapper.classList.remove('flip-animate-next', 'flip-animate-prev');
        void canvasWrapper.offsetWidth; // Force reflow
        canvasWrapper.classList.add(direction === 'next' ? 'flip-animate-next' : 'flip-animate-prev');

        setTimeout(() => {
            canvasWrapper.classList.remove('flip-animate-next', 'flip-animate-prev');
        }, 550);
    }

    function onPrevPage(scrollTargetPosition = 'top') {
        if (pageNum <= 1) return;
        triggerPageFlipAnimation('prev');
        pageNum--;
        queueRenderPage(pageNum);
        if (scrollTargetPosition === 'bottom') {
            setTimeout(() => { container.scrollTop = container.scrollHeight; }, 100);
        } else {
            container.scrollTop = 0;
        }
    }

    function onNextPage() {
        if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
        triggerPageFlipAnimation('next');
        pageNum++;
        queueRenderPage(pageNum);
        container.scrollTop = 0;
    }

    function goToFirstPage() {
        if (pageNum === 1) return;
        triggerPageFlipAnimation('prev');
        pageNum = 1;
        queueRenderPage(pageNum);
        container.scrollTop = 0;
    }

    function goToLastPage() {
        if (!pdfDoc || pageNum === pdfDoc.numPages) return;
        triggerPageFlipAnimation('next');
        pageNum = pdfDoc.numPages;
        queueRenderPage(pageNum);
        container.scrollTop = 0;
    }

    /* Page Input & Scrub Slider Events */
    const pageInputEl = document.getElementById('page-input');
    pageInputEl.addEventListener('change', function() {
        let val = parseInt(this.value);
        if (pdfDoc && val >= 1 && val <= pdfDoc.numPages) {
            pageNum = val;
            queueRenderPage(pageNum);
            container.scrollTop = 0;
        } else {
            this.value = pageNum;
        }
    });

    if (pageSlider) {
        pageSlider.addEventListener('input', function() {
            let val = parseInt(this.value);
            if (pdfDoc && val >= 1 && val <= pdfDoc.numPages) {
                pageNum = val;
                queueRenderPage(pageNum);
                container.scrollTop = 0;
            }
        });
    }

    /* Mouse Wheel Page Flipping on Scroll */
    container.addEventListener('wheel', function(e) {
        if (isFlipping || !pdfDoc) return;

        const scrollTop = container.scrollTop;
        const scrollHeight = container.scrollHeight;
        const clientHeight = container.clientHeight;
        const isAtBottom = (scrollTop + clientHeight) >= (scrollHeight - 15);
        const isAtTop = scrollTop <= 15;

        if (e.deltaY > 25 && isAtBottom && pageNum < pdfDoc.numPages) {
            isFlipping = true;
            onNextPage();
            setTimeout(() => { isFlipping = false; }, 350);
        } else if (e.deltaY < -25 && isAtTop && pageNum > 1) {
            isFlipping = true;
            onPrevPage('bottom');
            setTimeout(() => { isFlipping = false; }, 350);
        }
    }, { passive: true });

    /* Zoom Controls */
    function zoomIn() {
        if (scale >= 3.0) return;
        scale += 0.2;
        updateZoomDisplay();
        renderPage(pageNum);
    }

    function zoomOut() {
        if (scale <= 0.6) return;
        scale -= 0.2;
        updateZoomDisplay();
        renderPage(pageNum);
    }

    function fitToWidth() {
        if (!pdfDoc) return;
        pdfDoc.getPage(pageNum).then(page => {
            const viewport = page.getViewport({ scale: 1.0 });
            const containerWidth = container.clientWidth - 60;
            if (containerWidth > 200) {
                scale = containerWidth / viewport.width;
                updateZoomDisplay();
                renderPage(pageNum);
            }
        });
    }

    function updateZoomDisplay() {
        document.getElementById('zoom-percent').textContent = Math.round(scale * 100) + '%';
    }

    /* Theme Controls */
    function setTheme(theme) {
        currentTheme = theme;
        container.classList.remove('theme-dark', 'theme-mocha', 'theme-light', 'theme-sepia');
        container.classList.add('theme-' + theme);

        const darkBtn = document.getElementById('theme-dark-btn');
        const mochaBtn = document.getElementById('theme-mocha-btn');
        const sepiaBtn = document.getElementById('theme-sepia-btn');
        const lightBtn = document.getElementById('theme-light-btn');

        [darkBtn, mochaBtn, sepiaBtn, lightBtn].forEach(btn => {
            if (btn) btn.className = btn.className.replace('bg-slate-700 text-white', 'text-slate-400 hover:text-slate-200');
        });

        if (theme === 'dark' && darkBtn) darkBtn.className += ' bg-slate-700 text-white';
        if (theme === 'mocha' && mochaBtn) mochaBtn.className += ' bg-amber-900/40 text-amber-300';
        if (theme === 'sepia' && sepiaBtn) sepiaBtn.className += ' bg-amber-200/20 text-amber-900';
        if (theme === 'light' && lightBtn) lightBtn.className += ' bg-slate-200 text-slate-800';
    }

    /* Fullscreen Mode */
    function toggleFullscreen() {
        const root = document.getElementById('reader-root');
        if (!document.fullscreenElement) {
            if (root.requestFullscreen) root.requestFullscreen();
            else if (root.webkitRequestFullscreen) root.webkitRequestFullscreen();
            document.getElementById('fullscreen-btn').innerHTML = '<i class="fas fa-compress"></i>';
        } else {
            if (document.exitFullscreen) document.exitFullscreen();
            else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            document.getElementById('fullscreen-btn').innerHTML = '<i class="fas fa-expand"></i>';
        }
    }

    /* Debounced Save Reading Progress */
    let saveTimeout = null;
    let lastSavedPage = initialPage;

    function saveProgressDebounced(page) {
        if (page === lastSavedPage) return;
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(() => {
            saveProgress(page);
        }, 1000);
    }

    function saveProgress(page) {
        fetch(updateProgressUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_page: page,
                total_pages: pdfDoc ? pdfDoc.numPages : null
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                lastSavedPage = page;
                showSaveToast();
            }
        })
        .catch(err => console.error('Error saving progress:', err));
    }

    function showSaveToast() {
        const toast = document.getElementById('save-toast');
        toast.classList.remove('opacity-0');
        toast.classList.add('opacity-100');
        setTimeout(() => {
            toast.classList.remove('opacity-100');
            toast.classList.add('opacity-0');
        }, 2000);
    }

    /* Touch Gesture Support (Swipe Left/Right on Mobile) */
    let touchStartX = 0;
    let touchEndX = 0;

    container.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, false);

    container.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, false);

    function handleSwipe() {
        const swipeDistance = touchEndX - touchStartX;
        if (Math.abs(swipeDistance) > 60) {
            if (swipeDistance < 0) onNextPage(); // Swipe Left
            else onPrevPage(); // Swipe Right
        }
    }

    /* Keyboard Navigation Shortcuts */
    document.addEventListener('keydown', e => {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;

        switch(e.key) {
            case 'ArrowLeft':
            case 'PageUp':
            case 'a':
            case 'k':
                onPrevPage();
                break;
            case 'ArrowRight':
            case 'PageDown':
            case ' ':
            case 'd':
            case 'j':
                onNextPage();
                break;
            case 'ArrowDown':
                if (container.scrollTop + container.clientHeight >= container.scrollHeight - 10) {
                    onNextPage();
                } else {
                    container.scrollTop += 120;
                }
                break;
            case 'ArrowUp':
                if (container.scrollTop <= 10) {
                    onPrevPage('bottom');
                } else {
                    container.scrollTop -= 120;
                }
                break;
            case 'Home':
                goToFirstPage();
                break;
            case 'End':
                goToLastPage();
                break;
            case '+':
            case '=':
                zoomIn();
                break;
            case '-':
                zoomOut();
                break;
            case 'f':
            case 'F':
                toggleFullscreen();
                break;
        }
    });

    /* Load Document */
    pdfjsLib.getDocument(pdfUrl).promise.then((pdfDoc_) => {
        pdfDoc = pdfDoc_;
        document.getElementById('total-pages-num').textContent = pdfDoc.numPages;
        document.getElementById('total-pages-display').textContent = pdfDoc.numPages;
        if (pageSlider) {
            pageSlider.max = pdfDoc.numPages;
            pageSlider.value = pageNum;
        }

        const percent = Math.round((pageNum / pdfDoc.numPages) * 100);
        document.getElementById('progress-percent').textContent = percent;
        if (topProgressBar) topProgressBar.style.width = percent + '%';

        // Auto calculate scale to fit width on first load
        fitToWidth();
    }).catch(err => {
        console.error('Error loading PDF:', err);
        if (loader) loader.style.display = 'none';
        canvasWrapper.innerHTML = `<div class="p-12 text-center max-w-md my-auto">
            <i class="fas fa-exclamation-triangle text-5xl text-amber-500 mb-4 animate-bounce"></i>
            <h3 class="text-xl font-bold dark:text-white mb-2">{{ __('book.pdf_error') ?? 'Could not load PDF' }}</h3>
            <p class="text-sm text-slate-400 mb-6">The document file could not be retrieved or is corrupted.</p>
            <a href="${window.location.href}" class="neu-button px-5 py-2.5 text-xs text-cyan-400 inline-flex items-center gap-2">
                <i class="fas fa-redo"></i> Retry Loading
            </a>
        </div>`;
    });

    /* Other Books Drawer Toggle */
    function toggleTypeBooksDrawer() {
        const drawer = document.getElementById('type-books-drawer');
        const backdrop = document.getElementById('drawer-backdrop');
        if (!drawer) return;

        if (drawer.classList.contains('translate-x-full')) {
            drawer.classList.remove('translate-x-full');
            backdrop.classList.remove('hidden');
        } else {
            drawer.classList.add('translate-x-full');
            backdrop.classList.add('hidden');
        }
    }
</script>
@endpush

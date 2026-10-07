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
    }

    /* Themes */
    .theme-dark {
        background-color: #121827 !important;
    }
    .theme-light {
        background-color: #f1f5f9 !important;
    }
    .theme-sepia {
        background-color: #fbf0d9 !important;
    }

    .pdf-canvas-wrapper {
        position: relative;
        display: inline-block;
        transition: transform 0.2s ease;
    }

    .pdf-page-canvas {
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
        background-color: #ffffff;
        transition: opacity 0.2s ease;
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
                <h1 class="text-lg md:text-xl font-bold dark:text-white line-clamp-1 truncate">{{ $book->title }}</h1>
                <p class="text-xs text-slate-400 truncate">{{ $book->author->name ?? 'Unknown Author' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between md:justify-end gap-3 w-full md:w-auto">
            <!-- Theme Selectors -->
            <div class="flex items-center bg-slate-800/80 p-1 rounded-xl border border-slate-700">
                <button id="theme-dark-btn" onclick="setTheme('dark')" class="px-2.5 py-1 text-xs rounded-lg text-white bg-slate-700 transition flex items-center gap-1" title="Dark Theme">
                    <i class="fas fa-moon text-indigo-400"></i>
                    <span class="hidden sm:inline">Dark</span>
                </button>
                <button id="theme-sepia-btn" onclick="setTheme('sepia')" class="px-2.5 py-1 text-xs rounded-lg text-amber-900 hover:text-amber-800 transition flex items-center gap-1" title="Sepia Theme">
                    <i class="fas fa-book-open text-amber-600"></i>
                    <span class="hidden sm:inline">Sepia</span>
                </button>
                <button id="theme-light-btn" onclick="setTheme('light')" class="px-2.5 py-1 text-xs rounded-lg text-slate-400 hover:text-slate-200 transition flex items-center gap-1" title="Light Theme">
                    <i class="fas fa-sun text-amber-400"></i>
                    <span class="hidden sm:inline">Light</span>
                </button>
            </div>

            <!-- Download Button (If authorized) -->
            @if($canDownload)
                <a href="{{ route('books.download', $book) }}" target="_blank" download class="neu-button px-3 py-2 text-xs flex items-center gap-1.5 rounded-xl text-emerald-400 hover:text-emerald-300" title="{{ __('book.download_pdf') ?? 'Download' }}">
                    <i class="fas fa-download"></i>
                    <span class="hidden sm:inline">{{ __('book.download') ?? 'PDF' }}</span>
                </a>
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
    <div id="pdf-viewer-container" class="theme-dark rounded-2xl border border-slate-800 shadow-2xl">
        <!-- Loading Overlay -->
        <div id="pdf-loader" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/80 backdrop-blur-sm z-20 transition-opacity">
            <div class="animate-spin rounded-full h-12 w-12 border-4 border-cyan-500 border-t-transparent mb-3"></div>
            <p class="text-sm font-medium text-slate-300">{{ __('book.loading') ?? 'Loading PDF...' }}</p>
        </div>

        <!-- Render Target Canvas Wrapper -->
        <div id="pdf-viewer" class="w-full flex justify-center items-center">
            <div id="canvas-wrapper" class="pdf-canvas-wrapper"></div>
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

    <!-- Page Number Input -->
    <div class="flex items-center gap-1.5 px-1">
        <input type="number" id="page-input" value="{{ $progress->current_page ?? 1 }}" min="1" class="neu-input w-14 text-center py-1 px-1.5 text-xs font-semibold rounded-lg bg-slate-800 text-cyan-300 border border-slate-700 focus:outline-none focus:border-cyan-500">
        <span class="text-slate-400 text-xs font-medium">/ <span id="total-pages-display">--</span></span>
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
            <span>Fit</span>
        </button>
    </div>
</div>

<!-- Save Progress Toast Notification -->
<div id="save-toast" class="fixed top-6 right-6 bg-slate-800/90 text-emerald-400 border border-emerald-500/40 px-4 py-2 rounded-xl text-xs font-semibold shadow-2xl flex items-center gap-2 pointer-events-none opacity-0 transition-opacity z-50">
    <i class="fas fa-check-circle"></i>
    <span>Progress Saved</span>
</div>
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

    const canvasWrapper = document.getElementById('canvas-wrapper');
    const container = document.getElementById('pdf-viewer-container');
    const loader = document.getElementById('pdf-loader');

    // Create Canvas Element
    const canvas = document.createElement('canvas');
    canvas.className = 'pdf-page-canvas';
    const ctx = canvas.getContext('2d', { alpha: false });
    canvasWrapper.appendChild(canvas);

    /**
     * Render specified page number with High-DPI support
     */
    function renderPage(num) {
        if (!pdfDoc) return;
        pageRendering = true;

        pdfDoc.getPage(num).then((page) => {
            // High DPI support (Retina displays)
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
                if (loader) loader.style.display = 'none';

                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
                saveProgressDebounced(num);
            }).catch(err => {
                console.error("Render task error:", err);
                pageRendering = false;
            });
        });

        // Update UI counters
        document.getElementById('current-page-num').textContent = num;
        document.getElementById('page-input').value = num;

        if (pdfDoc.numPages) {
            const percent = Math.round((num / pdfDoc.numPages) * 100);
            document.getElementById('progress-percent').textContent = percent;
        }
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    /* Navigation Controls */
    function onPrevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }

    function onNextPage() {
        if (!pdfDoc || pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    }

    function goToFirstPage() {
        if (pageNum === 1) return;
        pageNum = 1;
        queueRenderPage(pageNum);
    }

    function goToLastPage() {
        if (!pdfDoc || pageNum === pdfDoc.numPages) return;
        pageNum = pdfDoc.numPages;
        queueRenderPage(pageNum);
    }

    document.getElementById('page-input').addEventListener('change', function() {
        let val = parseInt(this.value);
        if (pdfDoc && val >= 1 && val <= pdfDoc.numPages) {
            pageNum = val;
            queueRenderPage(pageNum);
        } else {
            this.value = pageNum;
        }
    });

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
        container.classList.remove('theme-dark', 'theme-light', 'theme-sepia');
        container.classList.add('theme-' + theme);

        const darkBtn = document.getElementById('theme-dark-btn');
        const sepiaBtn = document.getElementById('theme-sepia-btn');
        const lightBtn = document.getElementById('theme-light-btn');

        // Reset button active styles
        [darkBtn, sepiaBtn, lightBtn].forEach(btn => {
            btn.className = btn.className.replace('bg-slate-700 text-white', 'text-slate-400 hover:text-slate-200');
        });

        if (theme === 'dark') darkBtn.className += ' bg-slate-700 text-white';
        if (theme === 'sepia') sepiaBtn.className += ' bg-amber-200/20 text-amber-900';
        if (theme === 'light') lightBtn.className += ' bg-slate-200 text-slate-800';
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
        // Prevent shortcuts if typing in page number input or other inputs
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

        const percent = Math.round((pageNum / pdfDoc.numPages) * 100);
        document.getElementById('progress-percent').textContent = percent;

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
</script>
@endpush

@extends('layouts.app')

@section('title', 'Reading: ' . $book->title)

@push('styles')
<style>
    #pdf-viewer-container {
        width: 100%;
        height: calc(100vh - 200px);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: #1a1a1a;
        padding: 20px;
    }
    .pdf-page {
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.5);
    }
    .reader-controls {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        display: flex;
        gap: 10px;
        align-items: center;
        max-width: 95vw;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto pb-20">
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('books.show', $book) }}" class="neu-button w-10 h-10 flex items-center justify-center rounded-xl p-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold dark:text-white line-clamp-1">{{ $book->title }}</h1>
                <p class="text-xs dark:text-gray-400">{{ $book->author->name ?? 'Unknown Author' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="neu-card px-4 py-2 text-sm flex items-center gap-2">
                <span class="dark:text-gray-400">Progress:</span>
                <span class="font-bold text-cyan-400"><span id="progress-percent">{{ $progress->percentage ?? 0 }}</span>%</span>
            </div>
            <span class="text-sm dark:text-gray-400 bg-slate-800/50 px-3 py-1 rounded-lg">
                Page <span id="current-page-num" class="dark:text-white font-medium">{{ $progress->current_page ?? 1 }}</span> of <span id="total-pages-num">--</span>
            </span>
        </div>
    </div>

    <div id="pdf-viewer-container" class="neu-card-inset rounded-2xl">
        <div id="pdf-viewer" class="w-full flex justify-center"></div>
    </div>
</div>

<div class="reader-controls neu-card p-3 flex items-center gap-4 shadow-2xl border border-slate-700/50 backdrop-blur-md">
    <button id="prev-page" class="neu-button p-2 w-10 h-10 flex items-center justify-center rounded-xl">
        <i class="fas fa-chevron-left"></i>
    </button>
    <div class="flex items-center gap-2">
        <input type="number" id="page-input" value="{{ $progress->current_page ?? 1 }}" min="1" class="neu-input w-16 text-center py-1 px-2 rounded-lg">
        <span class="dark:text-gray-400 text-sm">/ <span id="total-pages-display">--</span></span>
    </div>
    <button id="next-page" class="neu-button p-2 w-10 h-10 flex items-center justify-center rounded-xl">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="h-8 w-px bg-slate-700 mx-1"></div>
    <div class="flex items-center gap-2">
        <button id="zoom-out" class="neu-button p-2 w-10 h-10 flex items-center justify-center text-xs rounded-xl">
            <i class="fas fa-minus"></i>
        </button>
        <span id="zoom-percent" class="text-xs font-mono dark:text-gray-400 min-w-[45px] text-center">100%</span>
        <button id="zoom-in" class="neu-button p-2 w-10 h-10 flex items-center justify-center text-xs rounded-xl">
            <i class="fas fa-plus"></i>
        </button>
    </div>
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

    const viewer = document.getElementById('pdf-viewer');
    const canvas = document.createElement('canvas');
    canvas.className = 'pdf-page rounded-lg shadow-2xl';
    const ctx = canvas.getContext('2d');
    viewer.appendChild(canvas);

    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then((page) => {
            const viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            const renderTask = page.render(renderContext);

            renderTask.promise.then(() => {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
                saveProgress(num);
            });
        });

        document.getElementById('current-page-num').textContent = num;
        document.getElementById('page-input').value = num;

        if (pdfDoc) {
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

    function onPrevPage() {
        if (pageNum <= 1) return;
        pageNum--;
        queueRenderPage(pageNum);
    }
    document.getElementById('prev-page').addEventListener('click', onPrevPage);

    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        queueRenderPage(pageNum);
    }
    document.getElementById('next-page').addEventListener('click', onNextPage);

    document.getElementById('page-input').addEventListener('change', function() {
        let val = parseInt(this.value);
        if (val >= 1 && val <= pdfDoc.numPages) {
            pageNum = val;
            queueRenderPage(pageNum);
        } else {
            this.value = pageNum;
        }
    });

    document.getElementById('zoom-in').addEventListener('click', () => {
        scale += 0.2;
        document.getElementById('zoom-percent').textContent = Math.round(scale * 100) + '%';
        renderPage(pageNum);
    });

    document.getElementById('zoom-out').addEventListener('click', () => {
        if (scale > 0.6) {
            scale -= 0.2;
            document.getElementById('zoom-percent').textContent = Math.round(scale * 100) + '%';
            renderPage(pageNum);
        }
    });

    let lastSavedPage = initialPage;
    function saveProgress(page) {
        if (page === lastSavedPage) return;

        fetch(updateProgressUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                current_page: page,
                total_pages: pdfDoc.numPages
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                lastSavedPage = page;
                console.log('Progress saved:', page);
            }
        })
        .catch(error => console.error('Error saving progress:', error));
    }

    pdfjsLib.getDocument(pdfUrl).promise.then((pdfDoc_) => {
        pdfDoc = pdfDoc_;
        document.getElementById('total-pages-num').textContent = pdfDoc.numPages;
        document.getElementById('total-pages-display').textContent = pdfDoc.numPages;

        const percent = Math.round((pageNum / pdfDoc.numPages) * 100);
        document.getElementById('progress-percent').textContent = percent;

        renderPage(pageNum);
    }).catch(err => {
        console.error('Error loading PDF:', err);
        viewer.innerHTML = `<div class="p-12 text-center">
            <i class="fas fa-exclamation-circle text-5xl text-red-500 mb-4"></i>
            <p class="text-xl font-bold dark:text-white mb-2">Failed to load document</p>
            <p class="text-gray-400">The file might be missing or corrupted.</p>
        </div>`;
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'ArrowLeft') onPrevPage();
        if (e.key === 'ArrowRight') onNextPage();
    });
</script>
@endpush

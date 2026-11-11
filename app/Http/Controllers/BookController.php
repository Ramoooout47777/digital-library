<?php
// app/Http/Controllers/BookController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $books = Book::with(['author', 'category'])
            ->available()
            ->when($request->search, fn ($q, $term) => $q->search($term))
            ->when($request->category_id, fn ($q, $id) => $q->byCategory($id))
            ->when($request->author_id, fn ($q, $id) => $q->byAuthor($id))
            ->when($request->price_min, fn ($q, $min) => $q->where('final_price', '>=', $min))
            ->when($request->price_max, fn ($q, $max) => $q->where('final_price', '<=', $max))
            ->when($request->boolean('free'), fn ($q) => $q->free())
            ->when($request->sort, function ($q, $sort) {
                match ($sort) {
                    'title' => $q->orderBy('title', 'asc'),
                    'price' => $q->orderBy('final_price', 'asc'),
                    '-price' => $q->orderBy('final_price', 'desc'),
                    'views_count' => $q->orderBy('views_count', 'desc'),
                    default => $q->orderBy('created_at', 'desc'),
                };
            }, fn ($q) => $q->orderBy('created_at', 'desc'))
            ->paginate(20)
            ->withQueryString();

        return view('books.index', compact('books'));
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        $book->incrementViews();
        $user = auth()->user();

        $hasPurchased = $user ? $book->isPurchasedBy($user) : false;
        $canRead = $book->is_free || $hasPurchased;
        $canBuy = $user && ! $book->is_free && ! $hasPurchased;
        $progress = $user ? $book->userProgress($user) : null;

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->available()
            ->limit(10)
            ->get();

        $reviews = $book->reviews()->with('user')->where('status', true)->latest()->paginate(10);

        return view('books.show', compact('book', 'relatedBooks', 'canRead', 'canBuy', 'reviews', 'progress'));
    }

    /**
     * Download book PDF.
     */
    public function download(Book $book)
    {
        $user = auth()->user();

        if (! $book->is_free && ! $user) {
            return redirect()->route('login')->with('error', 'Please login to download this book');
        }

        if (! $book->is_free && ! $book->canDownloadBy($user)) {
            abort(403, 'You need to purchase this book to download');
        }

        if (! $book->pdf_file || ! Storage::disk('public')->exists($book->pdf_file)) {
            abort(404, 'PDF file not found');
        }

        $book->incrementDownloads();
        $filePath = Storage::disk('public')->path($book->pdf_file);

        return response()->download($filePath, $book->slug . '.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $book->slug . '.pdf"',
        ]);
    }

    /**
     * Preview book PDF (sample).
     */
    public function preview(Book $book)
    {
        if (! $book->sample_pdf || ! Storage::disk('public')->exists($book->sample_pdf)) {
            abort(404, 'Preview not available');
        }

        return response()->file(Storage::disk('public')->path($book->sample_pdf), [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Read full book PDF.
     */
    public function read(Book $book)
    {
        $user = auth()->user();

        if (! $book->is_free && ! $user) {
            return redirect()->route('login')->with('error', 'Please login to read this book');
        }

        $hasPurchased = $user && ($book->isPurchasedBy($user) || $user->isAdmin());
        if (! $book->is_free && ! $hasPurchased) {
            abort(403, 'You need to purchase this book to read it');
        }

        $pdfFile = $book->pdf_file;
        $isSample = false;

        if ((! $pdfFile || ! Storage::disk('public')->exists($pdfFile)) && $book->sample_pdf && Storage::disk('public')->exists($book->sample_pdf)) {
            $pdfFile = $book->sample_pdf;
            $isSample = true;
        }

        if (! $pdfFile || ! Storage::disk('public')->exists($pdfFile)) {
            return redirect()->route('books.show', $book)
                ->with('error', __('book.pdf_not_available') ?? 'This book does not have a PDF document available for reading.');
        }

        $book->incrementViews();

        $progress = $user ? ReadingProgress::firstOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            ['current_page' => 1, 'total_pages' => $book->pages]
        ) : null;

        $book->load(['type', 'category', 'author']);

        $typeBooks = Book::with(['author', 'category', 'type'])
            ->where('id', '!=', $book->id)
            ->where(fn ($q) => $q->when($book->type_id, fn ($q2, $typeId) => $q2->where('type_id', $typeId))->orWhere('category_id', $book->category_id))
            ->available()
            ->limit(10)
            ->get();

        $pdfUrl = asset('storage/' . $pdfFile);

        return view('books.read', compact('book', 'progress', 'pdfUrl', 'typeBooks', 'isSample'));
    }

    /**
     * Update reading progress via AJAX.
     */
    public function updateProgress(Request $request, Book $book)
    {
        $user = auth()->user();
        if (! $user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'current_page' => 'required|integer|min:1',
            'total_pages' => 'nullable|integer|min:1',
        ]);

        $totalPages = $request->total_pages ?? $book->pages;

        $progress = ReadingProgress::updateOrCreate(
            ['user_id' => $user->id, 'book_id' => $book->id],
            [
                'current_page' => $request->current_page,
                'total_pages' => $totalPages,
                'percentage' => $totalPages > 0 ? ($request->current_page / $totalPages) * 100 : 0,
                'last_read_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

    /**
     * Store a book review.
     */
    public function storeReview(Request $request, Book $book)
    {
        $user = auth()->user();

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        if (! $book->is_free && ! $book->isPurchasedBy($user)) {
            return redirect()->back()->with('error', 'You must purchase this book to leave a review.');
        }

        $book->reviews()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => true,
            ]
        );

        $book->updateRating();

        return redirect()->back()->with('success', 'Thank you for your review!');
    }

    /**
     * AJAX Search for live autocomplete suggestions.
     */
    public function ajaxSearch(Request $request)
    {
        $term = trim($request->get('q', $request->get('search', '')));
        if (strlen($term) < 2) {
            return response()->json(['data' => []]);
        }

        $books = Book::with(['author', 'category'])
            ->available()
            ->search($term)
            ->limit(8)
            ->get();

        return response()->json([
            'data' => $books->map(fn ($book) => [
                'id' => $book->id,
                'title' => $book->title,
                'slug' => $book->slug,
                'author' => $book->author?->name ?? 'N/A',
                'category' => $book->category?->name ?? 'N/A',
                'cover_url' => $book->cover_url ?? asset('images/default-cover.png'),
                'price_text' => $book->is_free ? 'FREE' : '$' . number_format($book->final_price, 2),
                'is_free' => $book->is_free,
                'url' => route('books.show', $book),
            ]),
        ]);
    }
}

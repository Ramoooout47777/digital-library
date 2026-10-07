<?php
// app/Http/Controllers/BookController.php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index(Request $request)
    {
        $query = Book::with(['author', 'category'])
            ->available();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', "%{$request->search}%")
                  ->orWhere('description', 'LIKE', "%{$request->search}%")
                  ->orWhere('isbn', 'LIKE', "%{$request->search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by author
        if ($request->has('author_id') && $request->author_id) {
            $query->where('author_id', $request->author_id);
        }

        // Filter by price range
        if ($request->has('price_min') && $request->price_min) {
            $query->where('final_price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max) {
            $query->where('final_price', '<=', $request->price_max);
        }

        // Filter by free
        if ($request->has('free') && $request->free) {
            $query->where('is_free', true);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        switch ($sort) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'price':
                $query->orderBy('final_price', 'asc');
                break;
            case '-price':
                $query->orderBy('final_price', 'desc');
                break;
            case 'views_count':
                $query->orderBy('views_count', 'desc');
                break;
            case 'created_at':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $books = $query->paginate(20);

        return view('books.index', compact('books'));
    }

    /**
     * Display the specified book.
     */
    public function show(Book $book)
    {
        // Increment views
        $book->incrementViews();
        $canRead = $book->is_free || (auth()->check() && auth()->user()->hasPurchased($book));
        $canBuy = auth()->check() && ! $book->is_free && ! auth()->user()->hasPurchased($book);

        // Get reading progress
        $progress = null;
        if (auth()->check()) {
            $progress = $book->userProgress(auth()->user());
        }

        // Get related books
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->available()
            ->limit(10)
            ->get();

        // Load reviews
        $reviews = $book->reviews()->with('user')->where('status', true)->latest()->paginate(10);

        return view('books.show', compact('book', 'relatedBooks', 'canRead', 'canBuy', 'reviews', 'progress'));
    }

    /**
     * Download book PDF.
     */
    public function download(Book $book)
    {
        if (! $book->is_free && ! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to download this book');
        }

        if (! $book->is_free && ! auth()->user()->hasPurchased($book)) {
            abort(403, 'You need to purchase this book to download');
        }

        // Check if PDF exists
        if (!$book->pdf_file || !Storage::disk('public')->exists($book->pdf_file)) {
            abort(404, 'PDF file not found');
        }

        // Increment downloads
        $book->incrementDownloads();

        // Get the file path
        $filePath = Storage::disk('public')->path($book->pdf_file);

        // Return download response with proper headers
        return response()->download($filePath, $book->slug . '.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $book->slug . '.pdf"',
        ]);
    }

    /**
     * Preview book PDF (sample)
     */
    public function preview(Book $book)
    {
        $filePath = $book->sample_pdf;

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'Preview not available');
        }

        $path = Storage::disk('public')->path($filePath);

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Read full book PDF.
     */
    public function read(Book $book)
    {
        if (!$book->is_free && !auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to read this book');
        }

        if (!$book->is_free && !auth()->user()->hasPurchased($book) && !auth()->user()->isAdmin()) {
            abort(403, 'You need to purchase this book to read it');
        }

        // Check if PDF exists
        if (!$book->pdf_file || !Storage::disk('public')->exists($book->pdf_file)) {
            abort(404, 'Full PDF file not found');
        }

        // Increment views (reading counts as a view)
        $book->incrementViews();

        $progress = null;
        if (auth()->check()) {
            $progress = \App\Models\ReadingProgress::firstOrCreate(
                ['user_id' => auth()->id(), 'book_id' => $book->id],
                ['current_page' => 1, 'total_pages' => $book->pages]
            );
        }

        $pdfUrl = asset('storage/' . $book->pdf_file);

        return view('books.read', compact('book', 'progress', 'pdfUrl'));
    }

    /**
     * Update reading progress via AJAX.
     */
    public function updateProgress(Request $request, Book $book)
    {
        $request->validate([
            'current_page' => 'required|integer|min:1',
            'total_pages' => 'nullable|integer|min:1',
        ]);

        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $progress = \App\Models\ReadingProgress::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $book->id],
            [
                'current_page' => $request->current_page,
                'total_pages' => $request->total_pages ?? $book->pages,
                'percentage' => $request->total_pages ? ($request->current_page / $request->total_pages) * 100 : 0,
                'last_read_at' => now()
            ]
        );

        return response()->json([
            'success' => true,
            'progress' => $progress
        ]);
    }

    /**
     * Store a book review.
     */
    public function storeReview(Request $request, Book $book)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        if (!auth()->user()->hasPurchased($book) && !$book->is_free) {
            return redirect()->back()->with('error', 'You must purchase this book to leave a review.');
        }

        $book->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => true // Auto approve for now
            ]
        );

        // Update book average rating
        $book->updateRating();

        return redirect()->back()->with('success', 'Thank you for your review!');
    }
}

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

        // Get related books
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->available()
            ->limit(10)
            ->get();

        return view('books.show', compact('book', 'relatedBooks', 'canRead', 'canBuy'));
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

        if (!$book->is_free && !auth()->user()->hasPurchased($book)) {
            abort(403, 'You need to purchase this book to read it');
        }

        // Check if PDF exists
        if (!$book->pdf_file || !Storage::disk('public')->exists($book->pdf_file)) {
            abort(404, 'Full PDF file not found');
        }

        // Increment views (reading counts as a view)
        $book->incrementViews();

        $path = Storage::disk('public')->path($book->pdf_file);

        // Return file for inline viewing in browser
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

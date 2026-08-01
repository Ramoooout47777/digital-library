<?php
// app/Http/Middleware/EnsureUserCanAccessPdf.php

namespace App\Http\Middleware;

use Closure;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserCanAccessPdf
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get book ID from route
        $bookId = $request->route('book');
        
        if (!$bookId) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found',
            ], 404);
        }

        $book = Book::find($bookId);
        
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found',
            ], 404);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], 401);
        }

        $user = Auth::user();

        // Allow if book is free
        if ($book->is_free) {
            return $next($request);
        }

        // Check if user is admin
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has purchased the book
        $hasPurchased = $user->purchases()
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'You need to purchase this book to access the PDF',
                'error' => 'purchase_required',
                'book_id' => $book->id,
            ], 403);
        }

        return $next($request);
    }
}
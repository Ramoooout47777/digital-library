<?php
// app/Http/Controllers/FavoriteController.php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display user's favorite books
     */
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites()
            ->with(['author', 'category'])
            ->paginate(20);

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Add book to favorites
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($request->book_id);

        // Check if already favorited
        if ($user->favorites()->where('book_id', $book->id)->exists()) {
            return redirect()->back()->with('info', 'Book already in favorites');
        }

        $user->favorites()->attach($book->id);

        return redirect()->back()->with('success', 'Book added to favorites!');
    }

    /**
     * Remove book from favorites
     */
    public function destroy($bookId)
    {
        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $user->favorites()->detach($book->id);

        return redirect()->route('favorites.index')->with('success', 'Book removed from favorites!');
    }

    /**
     * Toggle favorite status
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($request->book_id);

        $isFavorited = $user->favorites()->toggle($book->id);

        return response()->json([
            'success' => true,
            'is_favorited' => !empty($isFavorited['attached']),
            'message' => !empty($isFavorited['attached']) 
                ? 'Added to favorites' 
                : 'Removed from favorites',
        ]);
    }

    /**
     * Check if book is favorited
     */
    public function check($bookId)
    {
        $user = Auth::user();
        $isFavorited = $user->favorites()->where('book_id', $bookId)->exists();

        return response()->json([
            'is_favorited' => $isFavorited,
        ]);
    }
}
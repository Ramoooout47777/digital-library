<?php
// app/Http/Controllers/AuthorController.php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of authors.
     */
    public function index(Request $request)
    {
        $authors = Author::withCount(['books' => function($query) {
            $query->available();
        }])
        ->where('status', true)
        ->get()
        ->filter(function($author) {
            return $author->books_count > 0;
        })
        ->sortByDesc('books_count')
        ->paginate(20);

        return view('authors.index', compact('authors'));
    }

    /**
     * Display the specified author with their books.
     */
    public function show(Author $author, Request $request)
    {
        // Get books by this author
        $books = $author->books()
            ->available()
            ->with(['category', 'publisher'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('authors.show', compact('author', 'books'));
    }
}
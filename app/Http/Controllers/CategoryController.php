<?php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount(['books' => function($query) {
            $query->available();
        }])
        ->where('status', true)
        ->whereHas('books', function($query) {
            $query->available();
        });

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $categories = $query->orderBy('books_count', 'desc')
            ->paginate(20);

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category with its books.
     */
    public function show(Category $category, Request $request)
    {
        // Load category with relationships
        $category->load(['parent', 'children']);
        
        // Load books count
        $category->loadCount(['books' => function($query) {
            $query->available();
        }]);
        
        // Get books in this category (paginated)
        $booksQuery = $category->books()
            ->available()
            ->with(['author', 'category']);

        // Apply sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $booksQuery->orderBy('views_count', 'desc');
                break;
            case 'price_low':
                $booksQuery->orderBy('final_price', 'asc');
                break;
            case 'price_high':
                $booksQuery->orderBy('final_price', 'desc');
                break;
            case 'newest':
            default:
                $booksQuery->orderBy('created_at', 'desc');
                break;
        }

        $books = $booksQuery->paginate(20);

        return view('categories.show', compact('category', 'books'));
    }
}
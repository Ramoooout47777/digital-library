<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Author;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        // ============================================================
        // POPULAR BOOKS
        // ============================================================
        $popularBooks = Book::with(['author', 'category'])
            ->available()
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // FREE BOOKS
        // ============================================================
        $freeBooks = Book::with(['author', 'category'])
            ->available()
            ->free()
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // ============================================================
        // CATEGORIES - FIXED FOR SQLITE
        // ============================================================
        // Method 1: Using Collection (Recommended for SQLite)
        $categories = Category::withAvailableBooksCount()
        ->where('status', true)
        ->get()
        ->filter(function($category) {
            return $category->books_count > 0;
        })
        ->sortByDesc('books_count')
        ->take(8)
        ->values();
        $categories = Category::withCount(['books' => function($query) {
            $query->available();
        }])
        ->where('status', true)
        ->whereHas('books', function($query) {
            $query->available();
        })
        ->orderBy('books_count', 'desc')
        ->limit(8)
        ->get();

        // Method 2: Using DB Query Builder (Alternative)
        /*
        $categories = DB::table('categories')
            ->select('categories.*', DB::raw('COUNT(books.id) as books_count'))
            ->leftJoin('books', function($join) {
                $join->on('categories.id', '=', 'books.category_id')
                    ->where('books.status', true)
                    ->where('books.stock', '>', 0)
                    ->whereNotNull('books.published_at')
                    ->where('books.published_at', '<=', now())
                    ->whereNull('books.deleted_at');
            })
            ->where('categories.status', true)
            ->groupBy('categories.id')
            ->having('books_count', '>', 0)
            ->orderBy('books_count', 'desc')
            ->limit(8)
            ->get();
        */

        // ============================================================
        // STATISTICS
        // ============================================================
        $stats = [
            'total_books' => Book::available()->count(),
            'total_users' => User::count(),
            'total_authors' => Author::count(),
            'total_orders' => Order::count(),
        ];

        // ============================================================
        // SETTINGS
        // ============================================================
        $settings = Setting::where('group', 'general')->pluck('value', 'key')->toArray();

        return view('home', compact('popularBooks', 'freeBooks', 'categories', 'stats', 'settings'));
    }

     // ============================================================
    // FIXED: Switch Language with Session
    // ============================================================
    public function switchLanguage($locale)
    {
        // Check if locale is valid
        $availableLocales = ['km', 'en', 'zh'];
        
        if (in_array($locale, $availableLocales)) {
            // Set session
            Session::put('locale', $locale);
            // Set app locale
            App::setLocale($locale);
        }
        
        // Redirect back to previous page
        return redirect()->back();
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return redirect()->route('home')
            ->with('success', __('home.subscription_success') ?? 'Subscription successful!');
    }
}
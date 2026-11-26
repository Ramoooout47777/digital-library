<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Models\Order;
use App\Models\Author;
use App\Models\Banner;
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
        // DISCOUNTED BOOKS
        // ============================================================
        $discountedBooks = Book::with(['author', 'category'])
            ->available()
            ->where('discount', '>', 0)
            ->whereColumn('price', '>', 'final_price')
            ->orderBy('discount', 'desc')
            ->limit(8)
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
        // CATEGORIES
        // ============================================================
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

        // ============================================================
        // SHOPPING CART COUNT
        // ============================================================
        $cartCount = 0;
        if (auth()->check()) {
            $cart = \App\Models\Cart::where('user_id', auth()->id())->first();
            $cartCount = $cart ? $cart->items()->count() : 0;
        } else {
            $cart = \App\Models\Cart::where('session_id', session()->getId())->first();
            $cartCount = $cart ? $cart->items()->count() : 0;
        }

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

        // ============================================================
        // BANNERS
        // ============================================================
        $banners = Banner::active()->byPosition('home')->ordered()->get();

        // ============================================================
        // CURRENTLY READING (if auth)
        // ============================================================
        $currentlyReading = collect();
        if (auth()->check()) {
            $currentlyReading = \App\Models\ReadingProgress::with('book.author')
                ->where('user_id', auth()->id())
                ->where('percentage', '<', 100)
                ->orderBy('last_read_at', 'desc')
                ->limit(4)
                ->get();
        }

        return view('home', compact('popularBooks', 'discountedBooks', 'freeBooks', 'categories', 'stats', 'settings', 'banners', 'currentlyReading', 'cartCount'));
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

    /**
     * Switch application theme (dark/mocha/sepia/light)
     */
    public function switchTheme($theme)
    {
        if (in_array($theme, ['dark', 'mocha', 'sepia', 'light'])) {
            session(['theme' => $theme]);
        }

        return response()->json(['success' => true]);
    }
}

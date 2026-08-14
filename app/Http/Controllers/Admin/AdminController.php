<?php
// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Set language
        $locale = $request->get('locale', session('locale', 'km'));
        App::setLocale($locale);
        session(['locale' => $locale]);

        // ============ STATISTICS ============
        $stats = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total'),
            'revenue_2026' => Order::where('status', 'completed')->whereYear('created_at', 2026)->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_categories' => Category::count(),
            'total_authors' => Author::count(),
            'total_publishers' => Publisher::count(),
            'total_views' => Book::sum('views_count'),
            'total_downloads' => Book::sum('downloads_count'),
        ];

        // ============ TOP CATEGORIES ============
        $topCategories = Category::withCount(['books' => function($query) {
                $query->whereNull('deleted_at');
            }])
            ->get()
            ->map(function($category) {
                // More accurate revenue calculation based on actual sales
                $revenue = DB::table('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->join('books', 'order_items.book_id', '=', 'books.id')
                    ->where('books.category_id', $category->id)
                    ->where('orders.status', 'completed')
                    ->sum('order_items.total');

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'books_count' => $category->books_count,
                    'revenue' => $revenue,
                ];
            })
            ->filter(function($category) {
                return $category['books_count'] > 0 || $category['revenue'] > 0;
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values();

        // ============ RECENT ORDERS ============
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        // ============ POPULAR BOOKS ============
        $popularBooks = Book::with(['category', 'author'])
            ->where('status', true)
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // ============ RECENT USERS ============
        $recentUsers = User::latest()->limit(5)->get();

        // ============ CHART DATA ============
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('strftime("%m", created_at) as month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $chartData[] = $monthlyRevenue[$monthKey] ?? 0;
        }

        return view('admin.dashboard', compact(
            'stats',
            'topCategories',
            'recentOrders',
            'popularBooks',
            'recentUsers',
            'chartData'
        ));
    }

    public function chartData(Request $request)
    {
        $period = $request->get('period', 'monthly');

        if ($period === 'weekly') {
            $data = Order::where('status', 'completed')
                ->whereDate('created_at', '>=', now()->subDays(7))
                ->selectRaw('DATE(created_at) as date, SUM(total) as total')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->pluck('total')
                ->toArray();
        } elseif ($period === 'yearly') {
            $data = Order::where('status', 'completed')
                ->whereYear('created_at', date('Y'))
                ->selectRaw('strftime("%m", created_at) as month, SUM(total) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total')
                ->toArray();
        } else {
            // Monthly
            $data = Order::where('status', 'completed')
                ->whereYear('created_at', date('Y'))
                ->selectRaw('strftime("%m", created_at) as month, SUM(total) as total')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total')
                ->toArray();
        }

        return response()->json($data);
    }

    public function switchLanguage($locale)
    {
        if (in_array($locale, ['km', 'en', 'zh'])) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        }

        return redirect()->back();
    }
}

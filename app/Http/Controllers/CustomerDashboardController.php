<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('home', [
            'orders' => $user->orders()->with('items.book')->latest()->limit(8)->get(),
            'purchases' => $user->purchases()->with('book.author')->active()->latest()->limit(8)->get(),
            'recommendedBooks' => Book::with('author')->available()->where('is_free', true)->latest()->limit(6)->get(),
            'stats' => [
                'orders' => $user->orders()->count(),
                'purchases' => $user->purchases()->active()->count(),
                'downloads' => $user->purchases()->active()->with('book')->get()->sum(fn ($purchase) => $purchase->book?->downloads_count ?? 0),
            ],
        ]);
    }
}

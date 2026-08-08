<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user statistics
        $ordersCount = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)->where('status', 'completed')->sum('total');
        $purchasedBooksCount = $user->purchases()->count();
        $reviewsCount = $user->reviews()->count();

        // Get recent purchased books
        $purchasedBooks = $user->purchases()->with('book.author')->latest()->limit(5)->get();

        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get favorite books
        $favoriteBooks = $user->favorites()->limit(6)->get();

        return view('profile.index', compact(
            'user', 'ordersCount', 'totalSpent', 'purchasedBooksCount', 'purchasedBooks',
            'reviewsCount', 'recentOrders', 'favoriteBooks'
        ));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->route('profile.index')
            ->with('success', __('profile.updated') ?? 'Profile updated successfully!');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars/' . $user->id, 'public');
        $user->update(['avatar' => $path]);

        return redirect()->route('profile.index')
            ->with('success', __('profile.avatar_updated') ?? 'Avatar updated successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => __('profile.current_password_incorrect') ?? 'Current password is incorrect.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.index')
            ->with('success', __('profile.password_updated') ?? 'Password updated successfully!');
    }
     public function purchasedBooks()
    {
        $purchasedBooks = Auth::user()->purchases()->with('book')->latest()->paginate(12);
        return view('profile.purchased-books', compact('purchasedBooks'));
    }

     /**
     * Read purchased book PDF.
     */
    public function readBook($purchaseId)
    {
        $purchase = Purchase::with('book')->findOrFail($purchaseId);

        // Check if user owns this book
        if ($purchase->user_id !== Auth::id()) {
            abort(403, 'You do not own this book.');
        }

        // Check if book has PDF
        if (!$purchase->book->pdf_file) {
            abort(404, 'PDF file not found.');
        }

        return view('profile.read-book', compact('purchase'));
    }

    /**
     * Download purchased book PDF.
     */
    public function downloadBook($purchaseId)
    {
        $purchase = Purchase::with('book')->findOrFail($purchaseId);

        // Check if user owns this book
        if ($purchase->user_id !== Auth::id()) {
            abort(403, 'You do not own this book.');
        }

        // Check if book has PDF
        if (!$purchase->book->pdf_file) {
            abort(404, 'PDF file not found.');
        }

        $filePath = storage_path('app/public/' . $purchase->book->pdf_file);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, $purchase->book->title . '.pdf');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

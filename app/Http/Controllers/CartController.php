<?php
// app/Http/Controllers/CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use App\Services\CouponService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }
    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', compact('cart'));
    }

    /**
     * Add a book to cart
     */
    public function add(Request $request, Book $book)
    {
        // Check if book is in stock
        if ($book->stock <= 0) {
            return redirect()->back()->with('error', 'This book is out of stock!');
        }

        // Check if user already purchased this book
        if (Auth::check() && auth()->user()->hasPurchased($book)) {
            return redirect()->back()->with('error', 'You already purchased this book!');
        }

        $cart = $this->getCart();
        $cart->addItem($book->id, $request->quantity ?? 1);

        return redirect()->route('cart.index')->with('success', 'Book added to cart successfully!');
    }
     /**
     * Apply coupon to cart.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => ['required', 'string'],
        ]);

        $cart = $this->getCart();
        $result = $this->couponService->applyCoupon($request->coupon_code, $cart->total);

        if ($result['valid']) {
            return redirect()->route('cart.index')
                ->with('coupon_success', $result['message'] . ' - $' . number_format($result['discount'], 2));
        }

        return redirect()->route('cart.index')
            ->with('coupon_error', $result['message']);
    }

    /**
     * Remove coupon from cart.
     */
    public function removeCoupon()
    {
        $this->couponService->removeCoupon();
        
        return redirect()->route('cart.index')
            ->with('success', 'Coupon removed successfully');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $itemId)
    {
        $cart = $this->getCart();
        $cart->updateItemQuantity($itemId, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove item from cart
     */
    public function remove($itemId)
    {
        $cart = $this->getCart();
        $cart->removeItem($itemId);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        $cart->clear();

        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }

    /**
     * Get or create cart for current user/session
     */
    protected function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return $cart;
    }
}
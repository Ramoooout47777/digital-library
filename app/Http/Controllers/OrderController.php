<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Services\CouponService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    protected $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * Show checkout page.
     */
    public function checkout()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        // Get current coupon
        $currentCoupon = $this->couponService->getCurrentCoupon();
        
        // Calculate discounted total
        $originalTotal = $cart->total;
        $discountedTotal = $this->couponService->getDiscountedTotal($originalTotal);
        $discountAmount = $originalTotal - $discountedTotal;

        return view('orders.checkout', compact('cart', 'currentCoupon', 'originalTotal', 'discountedTotal', 'discountAmount'));
    }


    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => ['required', 'string', 'max:500'],
            'payment_method' => ['required', 'string', 'in:cod,card,qr'],
            'shipping_method' => ['required', 'string', 'in:standard,express'],
        ]);

        $cart = $this->getCart();

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        return DB::transaction(function () use ($request, $cart) {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $cart->total,
                'total' => $cart->total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'order_status' => Order::STATUS_PENDING,
                'shipping_address' => $request->shipping_address,
                'shipping_method' => $request->shipping_method,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'book_title' => $item->book->title,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                ]);

                // Reduce stock
                $book = Book::find($item->book_id);
                $book->decrement('stock', $item->quantity);
            }

            // Clear cart
            $cart->clear();

            // If COD, complete order immediately
            if ($request->payment_method === 'cod') {
                $order->update([
                    'payment_status' => 'completed',
                    'status' => 'completed',
                    'order_status' => Order::STATUS_CONFIRMED,
                    'confirmed_at' => now(),
                    'completed_at' => now(),
                ]);

                // Create purchase records
                foreach ($order->items as $item) {
                    Purchase::create([
                        'user_id' => Auth::id(),
                        'book_id' => $item->book_id,
                        'order_id' => $order->id,
                        'price_paid' => $item->price,
                        'status' => 'active',
                        'expires_at' => now()->addYear(),
                    ]);
                }

                return redirect()->route('orders.show', $order)
                    ->with('success', 'Order placed successfully!');
            }

            return redirect()->route('orders.show', $order)
                ->with('info', 'Order created! Please complete payment.');
        });
    }

     /**
     * Display user's orders
     */
    public function index()
    {
        $orders = Order::with(['items.book'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a specific order
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.book']);
        return view('orders.show', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->order_status, [Order::STATUS_PENDING, Order::STATUS_CONFIRMED])) {
            return redirect()->back()->with('error', 'Cannot cancel this order.');
        }

        return DB::transaction(function () use ($order) {
            // Return stock
            foreach ($order->items as $item) {
                $book = Book::find($item->book_id);
                if ($book) {
                    $book->increment('stock', $item->quantity);
                }
            }

            $order->order_status = Order::STATUS_CANCELLED;
            $order->status = 'cancelled';
            $order->save();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order cancelled successfully.');
        });
    }

    /**
     * Get user's cart
     */
    protected function getCart()
    {
        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }
}
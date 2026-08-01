<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Book;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->orders()
            ->with('items.book')
            ->latest()
            ->paginate($request->integer('per_page', 12));

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $order = DB::transaction(function () use ($request) {
            $book = Book::available()->findOrFail($request->book_id);

            if ($request->user()->hasPurchased($book)) {
                return null;
            }

            $quantity = $request->integer('quantity', 1);
            $subtotal = (float) $book->final_price * $quantity;
            $couponDiscount = $this->couponDiscount($request->coupon_code, $subtotal);

            $order = Order::create([
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'discount_amount' => $couponDiscount,
                'total' => max(0, $subtotal - $couponDiscount),
                'payment_method' => $request->payment_method,
                'shipping_address' => $request->shipping_address,
                'coupon_code' => $request->coupon_code,
                'coupon_discount' => $couponDiscount,
                'order_status' => Order::STATUS_PENDING,
            ]);

            $order->items()->create([
                'book_id' => $book->id,
                'book_title' => $book->title,
                'quantity' => $quantity,
                'price' => $book->price,
                'discount' => $book->discount,
                'total' => $subtotal,
            ]);

            return $order->load('items.book');
        });

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'You already purchased this book',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'order' => $order,
                'payment_url' => route('orders.show', $order),
            ],
            'message' => 'Order created successfully',
        ], 201);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return response()->json(['data' => $order->load('items.book')]);
    }

    public function complete(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        DB::transaction(function () use ($order) {
            $order->update([
                'payment_status' => 'completed',
                'status' => 'completed',
                'order_status' => Order::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);

            foreach ($order->items as $item) {
                Purchase::updateOrCreate(
                    ['user_id' => $order->user_id, 'book_id' => $item->book_id],
                    ['order_id' => $order->id, 'price_paid' => $item->total, 'status' => 'active']
                );
            }
        });

        return response()->json([
            'success' => true,
            'data' => $order->fresh('items.book'),
            'message' => 'Order completed successfully',
        ]);
    }

    public function cancel(Request $request, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_if($order->status === 'completed', 422, 'Completed orders cannot be cancelled.');

        $order->update(['status' => 'cancelled']);

        return response()->json(['data' => $order->fresh('items.book')]);
    }

    private function couponDiscount(?string $code, float $subtotal): float
    {
        if (! $code) {
            return 0;
        }

        return Coupon::where('code', $code)->first()?->calculateDiscount($subtotal) ?? 0;
    }
}

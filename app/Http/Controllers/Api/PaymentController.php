<?php
// app/Http/Controllers/Api/PaymentController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Process payment for an order
     */
    public function process(Request $request, Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        // Check if order is already paid
        if ($order->payment_status === 'completed') {
            return $this->errorResponse('Order already paid', null, 400);
        }

        $request->validate([
            'payment_method' => ['required', 'string', 'in:card,bank_transfer,paypal'],
            'payment_data' => ['required', 'array'],
        ]);

        $result = $this->paymentService->processPayment($order, $request->payment_data);

        if ($result['success']) {
            return $this->successResponse([
                'order' => $order->load(['items', 'items.book']),
                'payment' => $result['payment'],
            ], 'Payment processed successfully');
        }

        return $this->errorResponse('Payment failed', null, 400);
    }

    /**
     * Handle payment webhook
     */
    public function webhook(Request $request)
    {
        // Verify webhook signature
        if (!$this->paymentService->verifyWebhook($request->all())) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Process webhook data
        // This depends on the payment gateway you're using
        
        return response()->json(['success' => true]);
    }

    /**
     * Get payment status
     */
    public function status(Order $order)
    {
        // Check if user owns this order
        if ($order->user_id !== auth()->id()) {
            return $this->errorResponse('Unauthorized', null, 403);
        }

        return $this->successResponse([
            'order_id' => $order->id,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
            'total' => $order->total,
        ]);
    }
}
<?php
// app/Http/Controllers/Admin/AdminOrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.book']);

        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'LIKE', "%{$request->search}%")
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'LIKE', "%{$request->search}%")
                        ->orWhere('email', 'LIKE', "%{$request->search}%");
                  });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.book', 'items.book.author']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,packed,shipped,delivered,completed,cancelled'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($request, $order) {
                // If status is cancelled, return stock
                if ($request->status === Order::STATUS_CANCELLED && $order->order_status !== Order::STATUS_CANCELLED) {
                    foreach ($order->items as $item) {
                        $book = $item->book;
                        if ($book) {
                            $book->increment('stock', $item->quantity);
                        }
                    }
                }

                // If status was cancelled and now is something else, reduce stock again
                if ($order->order_status === Order::STATUS_CANCELLED && $request->status !== Order::STATUS_CANCELLED) {
                    foreach ($order->items as $item) {
                        $book = $item->book;
                        if ($book) {
                            $book->decrement('stock', $item->quantity);
                        }
                    }
                }

                $order->order_status = $request->status;
                $order->updateTimestampsForStatus($request->status);

                // Update tracking number if provided
                if ($request->has('tracking_number')) {
                    $order->tracking_number = $request->tracking_number;
                }

                // Update main status for backward compatibility
                if ($request->status === Order::STATUS_COMPLETED || $request->status === Order::STATUS_DELIVERED) {
                    $order->status = 'completed';
                    $order->payment_status = 'completed';
                    $order->completed_at = now();
                } elseif ($request->status === Order::STATUS_CANCELLED) {
                    $order->status = 'cancelled';
                }

                $order->save();
            });

            return redirect()->route('admin.orders.show', $order)
                ->with('success', __('admin.order_status_updated') ?? 'Order status updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => ['required', 'in:pending,completed,failed,refunded'],
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', __('admin.payment_status_updated') ?? 'Payment status updated successfully');
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Order $order)
    {
        // Check if order can be deleted
        if ($order->status === 'completed') {
            return redirect()->route('admin.orders.index')
                ->with('error', __('admin.cannot_delete_completed') ?? 'Cannot delete completed order');
        }

        DB::transaction(function () use ($order) {
            // Return stock
            foreach ($order->items as $item) {
                $book = $item->book;
                if ($book) {
                    $book->increment('stock', $item->quantity);
                }
            }

            // Delete order items and order
            $order->items()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', __('admin.order_deleted') ?? 'Order deleted successfully');
    }

    /**
     * Export orders to CSV or PDF
     */
    public function export(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Check format
        $format = $request->get('format', 'csv');

        if ($format === 'pdf') {
            return $this->exportPDF($orders);
        }

        return $this->exportCSV($orders);
    }

    /**
     * Export orders to CSV
     */
    protected function exportCSV($orders)
    {
        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        $path = storage_path('app/public/exports/' . $filename);

        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');

        // Add UTF-8 BOM for Excel compatibility
        fputs($file, "\xEF\xBB\xBF");

        // Headers
        fputcsv($file, [
            'ID',
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Items Count',
            'Subtotal',
            'Discount',
            'Total',
            'Payment Method',
            'Payment Status',
            'Order Status',
            'Shipping Address',
            'Coupon Code',
            'Created At',
            'Completed At',
        ]);

        // Data
        foreach ($orders as $order) {
            fputcsv($file, [
                $order->id,
                $order->order_number,
                $order->user->name ?? 'N/A',
                $order->user->email ?? 'N/A',
                $order->items->count(),
                number_format($order->subtotal, 2),
                number_format($order->discount_amount, 2),
                number_format($order->total, 2),
                $order->payment_method ?? 'N/A',
                $order->payment_status,
                $order->status,
                $order->shipping_address ?? 'N/A',
                $order->coupon_code ?? 'N/A',
                $order->created_at->format('Y-m-d H:i:s'),
                $order->completed_at ? $order->completed_at->format('Y-m-d H:i:s') : '',
            ]);
        }

        fclose($file);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

    /**
     * Export orders to PDF
     */
    protected function exportPDF($orders)
    {
        // Install DomPDF: composer require barryvdh/laravel-dompdf
        // Or use this simple HTML export

        $html = $this->generatePDFHTML($orders);

        // For simple HTML export
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="orders_' . date('Y-m-d_His') . '.html"');
    }

    /**
     * Generate PDF HTML content
     */
    protected function generatePDFHTML($orders)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Orders Export</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                h1 { text-align: center; color: #333; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th { background: #4a5568; color: white; padding: 8px; text-align: left; }
                td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
                tr:hover { background: #f7fafc; }
                .total { font-weight: bold; }
                .status-pending { color: #d69e2e; }
                .status-completed { color: #38a169; }
                .status-cancelled { color: #e53e3e; }
                .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #718096; }
            </style>
        </head>
        <body>
            <h1>Orders Report</h1>
            <p>Generated: ' . date('d/m/Y H:i:s') . '</p>
            <p>Total Orders: ' . $orders->count() . '</p>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($orders as $index => $order) {
            $statusClass = 'status-' . $order->status;
            $html .= '
                    <tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . $order->order_number . '</td>
                        <td>' . ($order->user->name ?? 'N/A') . '</td>
                        <td>' . $order->items->count() . '</td>
                        <td>$' . number_format($order->subtotal, 2) . '</td>
                        <td>$' . number_format($order->discount_amount, 2) . '</td>
                        <td><strong>$' . number_format($order->total, 2) . '</strong></td>
                        <td>' . ucfirst($order->payment_status) . '</td>
                        <td class="' . $statusClass . '">' . ucfirst($order->status) . '</td>
                        <td>' . $order->created_at->format('d/m/Y H:i') . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                <p>Generated by ' . config('app.name') . ' | ' . date('Y') . '</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}

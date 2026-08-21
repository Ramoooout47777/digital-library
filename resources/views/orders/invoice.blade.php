<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Inter', sans-serif; color: #333; line-height: 1.6; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; font-size: 16px; }
        .invoice-header { display: table; width: 100%; border-bottom: 2px solid #38bdf8; padding-bottom: 20px; }
        .col { display: table-cell; vertical-align: top; }
        .text-right { text-align: right; }
        .company-info h1 { margin: 0; color: #38bdf8; font-size: 28px; }
        .invoice-details h2 { margin: 0; font-size: 24px; color: #64748b; }
        .section-title { font-weight: bold; background: #f8fafc; padding: 10px; margin-top: 30px; margin-bottom: 10px; border-left: 4px solid #38bdf8; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f1f5f9; text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; font-size: 14px; }
        td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        .totals-table { width: 40%; margin-left: auto; margin-top: 30px; }
        .totals-table td { border: none; padding: 5px 12px; }
        .grand-total { font-weight: bold; font-size: 18px; color: #38bdf8; border-top: 2px solid #e2e8f0 !important; padding-top: 10px !important; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .paid { background: #dcfce7; color: #166534; }
        .pending { background: #fef9c3; color: #854d0e; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <div class="col company-info">
                <h1>{{ config('app.name') }}</h1>
                <p>
                    {{ $settings['address'] ?? 'Phnom Penh, Cambodia' }}<br>
                    {{ $settings['contact_email'] ?? 'info@bookstore.com' }}<br>
                    {{ $settings['contact_phone'] ?? '+855 12 345 678' }}
                </p>
            </div>
            <div class="col invoice-details text-right">
                <h2>INVOICE</h2>
                <p>
                    <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Status:</strong> <span class="status-badge {{ $order->payment_status === 'completed' ? 'paid' : 'pending' }}">
                        {{ strtoupper($order->payment_status) }}
                    </span>
                </p>
            </div>
        </div>

        <div style="display: table; width: 100%; margin-top: 20px;">
            <div class="col" style="width: 50%;">
                <div class="section-title">BILL TO</div>
                <p>
                    <strong>{{ $order->user->name }}</strong><br>
                    {{ $order->user->email }}<br>
                    {{ $order->shipping_address }}
                </p>
            </div>
            <div class="col" style="width: 50%; padding-left: 20px;">
                <div class="section-title">PAYMENT METHOD</div>
                <p>
                    {{ strtoupper($order->payment_method) }}<br>
                    {{ $order->payment_status === 'completed' ? 'Paid on ' . $order->completed_at->format('M d, Y') : 'Payment Awaited' }}
                </p>
            </div>
        </div>

        <div class="section-title">ORDER ITEMS</div>
        <table>
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->book_title }}</td>
                        <td class="text-right">${{ number_format($item->price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td style="color: #166534;">Discount:</td>
                <td class="text-right" style="color: #166534;">-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td>Shipping:</td>
                <td class="text-right">$0.00</td>
            </tr>
            <tr class="grand-total">
                <td>Total Amount:</td>
                <td class="text-right">${{ number_format($order->total, 2) }}</td>
            </tr>
        </table>

        <div class="footer">
            <p>Thank you for your purchase from {{ config('app.name') }}!</p>
            <p>If you have any questions about this invoice, please contact our support team.</p>
        </div>
    </div>
</body>
</html>

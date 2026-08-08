{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', __('orders.order_details') . ' - ' . $order->order_number)
@section('page-title', __('orders.order_details'))

@push('styles')
<style>
    /* ─── KEYFRAME ANIMATIONS ─── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.1); }
        50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.2); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }

    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
    .animate-slide-right { animation: slideInRight 0.5s ease-out forwards; }
    .animate-pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
    .animate-shimmer {
        background: linear-gradient(90deg, rgba(56,189,248,0.03) 0%, rgba(56,189,248,0.08) 50%, rgba(56,189,248,0.03) 100%);
        background-size: 200% 100%;
        animation: shimmer 4s ease-in-out infinite;
    }
    .animate-float { animation: float 4s ease-in-out infinite; }

    /* ─── Status Badge ─── */
    .status-badge {
        padding: 6px 18px;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .status-badge:hover {
        transform: scale(1.05);
    }

    /* ─── Progress Bar ─── */
    .progress-bar {
        height: 6px;
        border-radius: 4px;
        background: #e2e8f0;
        overflow: hidden;
    }
    .dark .progress-bar {
        background: #1e293b;
    }
    .progress-bar .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #38bdf8, #818cf8);
        border-radius: 4px;
        transition: width 1.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ─── Status Steps ─── */
    .status-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        padding: 0 10px;
    }
    .status-steps::before {
        content: '';
        position: absolute;
        top: 16px;
        left: 30px;
        right: 30px;
        height: 2px;
        background: #e2e8f0;
        z-index: 0;
    }
    .dark .status-steps::before {
        background: #1e293b;
    }
    .status-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 1;
    }
    .status-step .step-dot {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        transition: all 0.5s ease;
        border: 3px solid #e2e8f0;
        background: #f8fafc;
        color: #94a3b8;
    }
    .dark .status-step .step-dot {
        border-color: #1e293b;
        background: #0f172a;
        color: #475569;
    }
    .status-step.active .step-dot {
        border-color: #38bdf8;
        background: #38bdf8;
        color: #0f172a;
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.3);
        animation: pulseGlow 2s ease-in-out infinite;
    }
    .status-step.completed .step-dot {
        border-color: #34d399;
        background: #34d399;
        color: #0f172a;
    }
    .status-step .step-label {
        font-size: 10px;
        margin-top: 8px;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: center;
    }
    .status-step.active .step-label {
        color: #38bdf8;
    }
    .status-step.completed .step-label {
        color: #34d399;
    }

    /* ─── Order Item ─── */
    .order-item {
        transition: all 0.3s ease;
    }
    .order-item:hover {
        background: rgba(56, 189, 248, 0.03);
        transform: translateX(4px);
    }

    /* ─── Summary Card ─── */
    .summary-card {
        position: sticky;
        top: 100px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- ============================================================ -->
    <!-- ORDER HEADER -->
    <!-- ============================================================ -->
    <div class="flex flex-wrap justify-between items-start gap-4 mb-6 animate-fade-in-up">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl font-bold dark:text-slate-100 light:text-slate-900">
                    {{ __('orders.order') ?? 'Order' }} #{{ $order->order_number }}
                </h1>
                <span class="status-badge {{ $order->status_badge }}">
                    <i class="fas {{ $order->status_icon }}"></i>
                    {{ $order->status_label }}
                </span>
            </div>
            <p class="text-sm dark:text-slate-500 light:text-slate-500 mt-1">
                <i class="far fa-calendar-alt mr-1"></i>
                {{ __('orders.placed_on') ?? 'Placed on' }}: {{ $order->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                {{ __('orders.back_to_orders') ?? 'Back to Orders' }}
            </a>
            @if($order->can_cancel)
                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 flex items-center gap-2"
                            onclick="return confirm('{{ __('orders.confirm_cancel') ?? 'Are you sure you want to cancel this order?' }}')">
                        <i class="fas fa-times-circle"></i>
                        {{ __('orders.cancel_order') ?? 'Cancel Order' }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ORDER PROGRESS -->
    <!-- ============================================================ -->
    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold dark:text-slate-200 light:text-slate-800">
                <i class="fas fa-chart-line text-cyan-400 mr-2"></i>
                {{ __('orders.order_progress') ?? 'Order Progress' }}
            </h3>
            <span class="text-sm dark:text-slate-400 light:text-slate-500">{{ $order->status_progress }}%</span>
        </div>

        <div class="progress-bar mb-6">
            <div class="progress-fill" style="width: {{ $order->status_progress }}%;"></div>
        </div>

        <div class="status-steps">
            @php
                $steps = ['pending', 'confirmed', 'processing', 'packed', 'shipped', 'delivered', 'completed'];
                $currentIndex = array_search($order->order_status, $steps);
            @endphp

            @foreach($steps as $index => $step)
                <div class="status-step {{ $index <= $currentIndex ? ($index == $currentIndex ? 'active' : 'completed') : '' }}">
                    <div class="step-dot">
                        @if($index < $currentIndex)
                            <i class="fas fa-check text-xs"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <span class="step-label">{{ ucfirst($step) }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- ORDER DETAILS GRID -->
    <!-- ============================================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- ============================================================ -->
        <!-- LEFT COLUMN - Order Items -->
        <!-- ============================================================ -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-shopping-bag text-cyan-400 mr-2"></i>
                    {{ __('orders.order_items') ?? 'Order Items' }}
                    <span class="text-sm text-slate-500 dark:text-slate-400 light:text-slate-500 ml-2">({{ $order->items->count() }})</span>
                </h3>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="order-item flex flex-wrap items-center gap-4 p-3 rounded-lg border dark:border-slate-700/50 light:border-slate-200/50">
                            <!-- Book Image -->
                            <div class="w-16 h-20 flex-shrink-0">
                                @if($item->book && $item->book->cover)
                                    <img src="{{ asset('storage/' . $item->book->cover) }}"
                                         alt="{{ $item->book_title }}"
                                         class="w-full h-full object-cover rounded-lg shadow-sm">
                                @else
                                    <div class="w-full h-full dark:bg-slate-700/50 light:bg-slate-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book dark:text-slate-600 light:text-slate-300 text-2xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Book Info -->
                            <div class="flex-1 min-w-[120px]">
                                <a href="{{ route('books.show', $item->book) }}" class="block">
                                    <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 hover:text-cyan-400 transition-colors">
                                        {{ $item->book_title }}
                                    </h4>
                                </a>
                                @if($item->book && $item->book->author)
                                    <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ $item->book->author->name }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-1">
                                    <p class="text-xs dark:text-slate-400 light:text-slate-500">
                                        {{ __('orders.quantity') ?? 'Qty' }}: {{ $item->quantity }}
                                    </p>
                                    @if($order->status === 'completed' && $item->book && $item->book->pdf_file)
                                        <a href="{{ route('books.read', $item->book) }}" target="_blank"
                                           class="text-xs text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1">
                                            <i class="fas fa-book-reader"></i> {{ __('orders.read_now') ?? 'Read Now' }}
                                        </a>
                                        <a href="{{ route('books.download', $item->book) }}"
                                           class="text-xs text-cyan-400 hover:text-cyan-300 font-semibold flex items-center gap-1">
                                            <i class="fas fa-download"></i> {{ __('orders.download') ?? 'Download' }}
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="text-right min-w-[80px]">
                                <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ __('orders.price') ?? 'Price' }}</p>
                                <p class="font-bold dark:text-slate-200 light:text-slate-800">${{ number_format($item->price, 2) }}</p>
                            </div>

                            <!-- Total -->
                            <div class="text-right min-w-[80px]">
                                <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ __('orders.total') ?? 'Total' }}</p>
                                <p class="font-bold text-cyan-400">${{ number_format($item->total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-truck text-cyan-400 mr-2"></i>
                    {{ __('orders.shipping_info') ?? 'Shipping Information' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ __('orders.shipping_address') ?? 'Shipping Address' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ $order->shipping_address ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ __('orders.shipping_method') ?? 'Shipping Method' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ ucfirst($order->shipping_method ?? 'Standard') }}</p>
                    </div>
                    @if($order->tracking_number)
                        <div>
                            <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ __('orders.tracking_number') ?? 'Tracking Number' }}</p>
                            <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ $order->tracking_number }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- RIGHT COLUMN - Order Summary -->
        <!-- ============================================================ -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Summary -->
            <div class="summary-card dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-slide-right">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-receipt text-cyan-400 mr-2"></i>
                    {{ __('orders.order_summary') ?? 'Order Summary' }}
                </h3>

                @if($order->payment_method === 'qr' && $order->payment_status === 'pending')
                    <div class="mb-6 p-4 border-2 border-dashed border-purple-500/30 rounded-2xl bg-purple-500/5 text-center">
                        <p class="text-xs font-medium text-purple-400 mb-3 uppercase tracking-wider">Scan to Pay</p>
                        <div class="p-3 bg-white rounded-xl shadow-lg mb-3 inline-block">
                            @php
                                $qrPath = \App\Models\Setting::where('key', 'payment_qr_code')->where('group', 'general')->value('value');
                            @endphp
                            @if($qrPath)
                                <img src="{{ asset('storage/' . $qrPath) }}" alt="Payment QR Code" class="w-40 h-48 object-contain">
                            @else
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('orders.show', $order)) }}"
                                     alt="Default QR Code" class="w-40 h-40">
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 leading-tight">
                            Please scan to complete your payment of <strong>${{ number_format($order->total, 2) }}</strong>.
                        </p>
                    </div>
                @endif

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.subtotal') ?? 'Subtotal' }}</span>
                        <span class="dark:text-slate-200 light:text-slate-800 font-medium">${{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-green-400">
                            <span>{{ __('orders.discount') ?? 'Discount' }}</span>
                            <span>-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif

                    @if($order->coupon_code)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.coupon') ?? 'Coupon' }}</span>
                            <span class="text-cyan-400 font-medium">{{ $order->coupon_code }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.shipping') ?? 'Shipping' }}</span>
                        <span class="text-emerald-400 font-medium">{{ __('orders.free') ?? 'Free' }}</span>
                    </div>

                    <div class="border-t dark:border-slate-700/50 light:border-slate-200/50 pt-3 mt-3">
                        <div class="flex justify-between font-bold text-lg">
                            <span class="dark:text-slate-200 light:text-slate-800">{{ __('orders.total') ?? 'Total' }}</span>
                            <span class="text-cyan-400 animate-pulse-glow">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-slide-right" style="animation-delay: 0.1s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-credit-card text-cyan-400 mr-2"></i>
                    {{ __('orders.payment_info') ?? 'Payment Information' }}
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.payment_method') ?? 'Payment Method' }}</span>
                        <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.payment_status') ?? 'Payment Status' }}</span>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($order->payment_status == 'completed') bg-green-100 text-green-800
                            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    @if($order->completed_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.completed_at') ?? 'Completed At' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->completed_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-slide-right" style="animation-delay: 0.2s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-clock text-cyan-400 mr-2"></i>
                    {{ __('orders.order_timeline') ?? 'Order Timeline' }}
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.placed') ?? 'Placed' }}</span>
                        <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    @if($order->confirmed_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.confirmed') ?? 'Confirmed' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->confirmed_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                    @if($order->processing_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.processing') ?? 'Processing' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->processing_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                    @if($order->packed_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.packed') ?? 'Packed' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->packed_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                    @if($order->shipped_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.shipped') ?? 'Shipped' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->shipped_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                    @if($order->delivered_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.delivered') ?? 'Delivered' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->delivered_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                    @if($order->completed_at)
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('orders.completed') ?? 'Completed' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">
                                {{ $order->completed_at->format('d/m/Y H:i') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-slide-right" style="animation-delay: 0.3s;">
                <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-bolt text-cyan-400 mr-2"></i>
                    {{ __('orders.quick_actions') ?? 'Quick Actions' }}
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('books.index') }}" class="block w-full bg-cyan-500 hover:bg-cyan-600 text-white text-center px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105">
                        <i class="fas fa-book-open mr-2"></i>
                        {{ __('orders.browse_books') ?? 'Browse Books' }}
                    </a>
                    <a href="{{ route('orders.index') }}" class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105">
                        <i class="fas fa-list mr-2"></i>
                        {{ __('orders.view_all_orders') ?? 'View All Orders' }}
                    </a>
                    @if($order->can_cancel)
                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 text-center"
                                    onclick="return confirm('{{ __('orders.confirm_cancel') ?? 'Are you sure you want to cancel this order?' }}')">
                                <i class="fas fa-times-circle mr-2"></i>
                                {{ __('orders.cancel_order') ?? 'Cancel Order' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- resources/views/orders/index.blade.php --}}
@extends('layouts.app')

@section('title', __('orders.title') ?? 'My Orders')
@section('page-title', __('orders.title') ?? 'My Orders')

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

    /* ─── Order Card ─── */
    .order-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .order-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.08);
    }

    /* ─── Status Badge ─── */
    .status-badge {
        transition: all 0.3s ease;
        padding: 4px 14px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-badge:hover {
        transform: scale(1.05);
    }

    /* ─── Progress Bar ─── */
    .progress-bar {
        height: 4px;
        border-radius: 4px;
        background: #e2e8f0;
        overflow: hidden;
        transition: all 0.5s ease;
    }
    .dark .progress-bar {
        background: #1e293b;
    }
    .progress-bar .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #38bdf8, #818cf8);
        border-radius: 4px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ─── Status Steps ─── */
    .status-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }
    .status-step::before {
        content: '';
        position: absolute;
        top: 16px;
        left: -50%;
        width: 100%;
        height: 2px;
        background: #e2e8f0;
        z-index: 0;
    }
    .dark .status-step::before {
        background: #1e293b;
    }
    .status-step:first-child::before {
        display: none;
    }
    .status-step .step-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        z-index: 1;
        transition: all 0.5s ease;
        border: 2px solid #e2e8f0;
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
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
    }
    .status-step.completed .step-dot {
        border-color: #34d399;
        background: #34d399;
        color: #0f172a;
    }
    .status-step .step-label {
        font-size: 10px;
        margin-top: 6px;
        color: #94a3b8;
        font-weight: 500;
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

    /* ─── Filter Section ─── */
    .filter-select {
        transition: all 0.3s ease;
    }
    .filter-select:focus {
        transform: scale(1.02);
        box-shadow: 0 0 30px rgba(56, 189, 248, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- ============================================================ -->
    <!-- HEADER -->
    <!-- ============================================================ -->
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8 animate-fade-in-up">
        <div>
            <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900">
                <i class="fas fa-shopping-bag text-cyan-400 mr-3"></i>
                {{ __('orders.title') ?? 'My Orders' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                <span class="inline-block px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-medium mr-2">
                    {{ $orders->total() }}
                </span>
                {{ __('orders.total_orders') ?? 'total orders' }}
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('books.index') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 flex items-center gap-2">
                <i class="fas fa-book-open"></i>
                <span class="hidden sm:inline">{{ __('orders.browse_books') ?? 'Browse Books' }}</span>
            </a>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- FILTERS -->
    <!-- ============================================================ -->
    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 mb-6 animate-fade-in-up" style="animation-delay: 0.1s;">
        <form action="{{ route('orders.index') }}" method="GET" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('orders.status') ?? 'Status' }}</label>
                <select name="status"
                        class="filter-select w-full px-3 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 transition-all duration-300 focus:scale-105">
                    <option value="">{{ __('orders.all_status') ?? 'All Status' }}</option>
                    @foreach(\App\Models\Order::getStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('orders.payment_status') ?? 'Payment Status' }}</label>
                <select name="payment_status"
                        class="filter-select w-full px-3 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 transition-all duration-300 focus:scale-105">
                    <option value="">{{ __('orders.all_payment') ?? 'All Payment' }}</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('orders.pending') ?? 'Pending' }}</option>
                    <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>{{ __('orders.completed') ?? 'Completed' }}</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>{{ __('orders.failed') ?? 'Failed' }}</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>{{ __('orders.refunded') ?? 'Refunded' }}</option>
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('orders.date_range') ?? 'Date Range' }}</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800 transition-all duration-300 focus:scale-105">
            </div>

            <!-- Filter Buttons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20">
                    <i class="fas fa-filter mr-2"></i>{{ __('orders.filter') ?? 'Filter' }}
                </button>
                <a href="{{ route('orders.index') }}" class="w-full bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 text-center">
                    <i class="fas fa-undo mr-2"></i>{{ __('orders.reset') ?? 'Reset' }}
                </a>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- ORDERS LIST -->
    <!-- ============================================================ -->
    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $index => $order)
                <div class="order-card dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-scale-in"
                     style="animation-delay: {{ $index * 0.05 }}s;">

                    <!-- Order Header -->
                    <div class="flex flex-wrap justify-between items-start gap-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800">
                                    {{ $order->order_number }}
                                </h3>
                                <span class="status-badge {{ $order->status_badge }}">
                                    <i class="fas {{ $order->status_icon }}"></i>
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <p class="text-sm dark:text-slate-500 light:text-slate-500 mt-1">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-cyan-400">${{ number_format($order->total, 2) }}</p>
                            <p class="text-xs dark:text-slate-500 light:text-slate-500">
                                {{ $order->items->count() }} {{ __('orders.items') ?? 'items' }}
                            </p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs dark:text-slate-500 light:text-slate-500 mb-1">
                            <span>{{ __('orders.order_progress') ?? 'Order Progress' }}</span>
                            <span>{{ $order->status_progress }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $order->status_progress }}%;"></div>
                        </div>
                    </div>

                    <!-- Status Steps -->
                    <div class="mt-4 flex justify-between">
                        @php
                            $steps = ['pending', 'confirmed', 'processing', 'packed', 'shipped', 'delivered', 'completed'];
                            $currentIndex = array_search($order->order_status, $steps);
                        @endphp

                        @foreach($steps as $index => $step)
                            <div class="status-step {{ $index <= $currentIndex ? ($index == $currentIndex ? 'active' : 'completed') : '' }}">
                                <div class="step-dot">
                                    @if($index < $currentIndex)
                                        <i class="fas fa-check text-xs"></i>
                                    @elseif($index == $currentIndex)
                                        {{ $index + 1 }}
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <span class="step-label">{{ ucfirst($step) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Items Preview -->
                    <div class="mt-4 pt-4 border-t dark:border-slate-700/50 light:border-slate-200/50">
                        <div class="flex flex-wrap gap-2">
                            @foreach($order->items->take(3) as $item)
                                <div class="flex items-center gap-2 dark:bg-slate-700/30 light:bg-slate-100 px-3 py-1.5 rounded-lg">
                                    @if($item->book && $item->book->cover)
                                        <img src="{{ asset('storage/' . $item->book->cover) }}"
                                             alt="{{ $item->book_title }}"
                                             class="w-8 h-10 object-cover rounded">
                                    @endif
                                    <div>
                                        <p class="text-xs font-medium dark:text-slate-200 light:text-slate-800">{{ Str::limit($item->book_title, 15) }}</p>
                                        <p class="text-[10px] dark:text-slate-500 light:text-slate-500">{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                            @if($order->items->count() > 3)
                                <div class="flex items-center dark:bg-slate-700/30 light:bg-slate-100 px-3 py-1.5 rounded-lg">
                                    <span class="text-xs dark:text-slate-400 light:text-slate-500">+{{ $order->items->count() - 3 }} {{ __('orders.more') ?? 'more' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 pt-4 border-t dark:border-slate-700/50 light:border-slate-200/50 flex flex-wrap gap-2">
                        <a href="{{ route('orders.show', $order) }}"
                           class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300 hover:scale-105 text-sm flex items-center gap-2">
                            <i class="fas fa-eye"></i> {{ __('orders.view_details') ?? 'View Details' }}
                        </a>

                        @if($order->can_cancel)
                            <form action="{{ route('orders.cancel', $order) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg transition-all duration-300 hover:scale-105 text-sm flex items-center gap-2"
                                        onclick="return confirm('{{ __('orders.confirm_cancel') ?? 'Are you sure you want to cancel this order?' }}')">
                                    <i class="fas fa-times-circle"></i> {{ __('orders.cancel_order') ?? 'Cancel Order' }}
                                </button>
                            </form>
                        @endif

                        @if($order->tracking_number)
                            <a href="#" class="px-4 py-2 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 rounded-lg transition-all duration-300 hover:scale-105 text-sm flex items-center gap-2">
                                <i class="fas fa-box"></i> {{ __('orders.track_order') ?? 'Track Order' }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 animate-fade-in-up">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @else
        <!-- ============================================================ -->
        <!-- EMPTY STATE -->
        <!-- ============================================================ -->
        <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-16 text-center animate-fade-in-up">
            <div class="max-w-md mx-auto">
                <div class="w-28 h-28 rounded-full bg-cyan-500/10 flex items-center justify-center mx-auto mb-4 animate-float">
                    <i class="fas fa-shopping-bag text-cyan-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold dark:text-slate-200 light:text-slate-800">{{ __('orders.no_orders') ?? 'No Orders Yet' }}</h3>
                <p class="text-slate-500 dark:text-slate-400 light:text-slate-500 mt-2">{{ __('orders.no_orders_message') ?? 'You haven\'t placed any orders yet.' }}</p>
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    <a href="{{ route('books.index') }}"
                       class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 flex items-center gap-2">
                        <i class="fas fa-book-open"></i>
                        {{ __('orders.start_shopping') ?? 'Start Shopping' }}
                    </a>
                    <a href="{{ route('home') }}"
                       class="px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white rounded-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-home"></i>
                        {{ __('orders.go_home') ?? 'Go Home' }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

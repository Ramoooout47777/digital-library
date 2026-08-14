{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('title', __('cart.title') ?? 'Shopping Cart')
@section('page-title', __('cart.title') ?? 'Shopping Cart')

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
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
    .animate-slide-right { animation: slideInRight 0.5s ease-out forwards; }
    .animate-pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
    .animate-float { animation: float 4s ease-in-out infinite; }
    
    /* ─── Cart Item ─── */
    .cart-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .cart-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }
    .cart-item-remove {
        transition: all 0.3s ease;
    }
    .cart-item-remove:hover {
        transform: scale(1.1) rotate(90deg);
        color: #ef4444;
    }
    
    /* ─── Quantity Input ─── */
    .qty-btn {
        transition: all 0.2s ease;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
    }
    .qty-btn:hover {
        transform: scale(1.05);
    }
    .qty-btn:active {
        transform: scale(0.95);
    }
    
    /* ─── Summary Card ─── */
    .summary-card {
        position: sticky;
        top: 100px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* ─── Empty State ─── */
    .empty-state-icon {
        animation: float 6s ease-in-out infinite;
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
                <i class="fas fa-shopping-cart text-cyan-400 mr-3"></i>
                {{ __('cart.title') ?? 'Shopping Cart' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                <span class="inline-block px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 text-xs font-medium mr-2" id="cart-count">
                    {{ $cart->items_count ?? 0 }}
                </span>
                {{ __('cart.items') ?? 'items' }} {{ __('cart.in_your_cart') ?? 'in your cart' }}
            </p>
        </div>
        
        <div class="flex gap-2">
            @if($cart && $cart->items->count() > 0)
                <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 flex items-center gap-2"
                            onclick="return confirm('{{ __('cart.confirm_clear') ?? 'Are you sure you want to clear your cart?' }}')">
                        <i class="fas fa-trash-alt"></i>
                        <span class="hidden sm:inline">{{ __('cart.clear_cart') ?? 'Clear Cart' }}</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('books.index') }}" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="hidden sm:inline">{{ __('cart.continue_shopping') ?? 'Continue Shopping' }}</span>
            </a>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- CART CONTENT -->
    <!-- ============================================================ -->
    @if($cart && $cart->items->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- ============================================================ -->
            <!-- CART ITEMS -->
            <!-- ============================================================ -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart->items as $index => $item)
                    <div class="cart-item dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-4 flex flex-wrap items-center gap-4 animate-scale-in"
                         style="animation-delay: {{ $index * 0.05 }}s;">
                        
                        <!-- Book Image -->
                        <div class="w-20 h-28 flex-shrink-0">
                            @if($item->book && $item->book->cover)
                                <img src="{{ asset('storage/' . $item->book->cover) }}" 
                                     alt="{{ $item->book->title }}" 
                                     class="w-full h-full object-cover rounded-lg shadow-sm hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full dark:bg-slate-700/50 light:bg-slate-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book dark:text-slate-600 light:text-slate-300 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Book Info -->
                        <div class="flex-1 min-w-[120px]">
                            @if($item->book)
                                <a href="{{ route('books.show', $item->book) }}" class="block">
                            @else
                                <div class="block">
                            @endif
                                    <h3 class="font-semibold dark:text-slate-200 light:text-slate-800 hover:text-cyan-400 transition-colors">
                                        {{ $item->book->title ?? $item->book_title ?? 'Book' }}
                                    </h3>
                            @if($item->book)
                                </a>
                            @else
                                </div>
                            @endif
                            <p class="text-sm dark:text-slate-500 light:text-slate-500">{{ $item->book?->author->name ?? 'N/A' }}</p>
                            <p class="text-sm font-semibold text-cyan-400 mt-1">${{ number_format($item->price, 2) }}</p>
                        </div>
                        
                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-2">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="updateQuantity(this, -1)" 
                                        class="qty-btn dark:bg-slate-700/50 light:bg-slate-100 hover:dark:bg-slate-600 hover:light:bg-slate-200 text-slate-600 dark:text-slate-300">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                       min="1" max="99" 
                                       class="w-14 text-center dark:bg-slate-800 light:bg-white border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg dark:text-slate-200 light:text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-cyan-500"
                                       onchange="this.form.submit()">
                                <button type="button" onclick="updateQuantity(this, 1)" 
                                        class="qty-btn dark:bg-slate-700/50 light:bg-slate-100 hover:dark:bg-slate-600 hover:light:bg-slate-200 text-slate-600 dark:text-slate-300">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </form>
                        </div>
                        
                        <!-- Total & Remove -->
                        <div class="text-right min-w-[100px]">
                            <p class="font-bold dark:text-slate-200 light:text-slate-800 text-lg">
                                ${{ number_format($item->total, 2) }}
                            </p>
                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="cart-item-remove text-slate-400 hover:text-red-500 transition text-sm flex items-center gap-1 ml-auto"
                                        onclick="return confirm('{{ __('cart.confirm_remove') ?? 'Remove this item?' }}')">
                                    <i class="fas fa-times"></i>
                                    <span class="hidden sm:inline">{{ __('cart.remove') ?? 'Remove' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- ============================================================ -->
            <!-- ORDER SUMMARY -->
            <!-- ============================================================ -->
            <div class="lg:col-span-1">
                <div class="summary-card dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-slide-right">
                    <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800 mb-4">
                        <i class="fas fa-receipt text-cyan-400 mr-2"></i>
                        {{ __('cart.order_summary') ?? 'Order Summary' }}
                    </h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('cart.subtotal') ?? 'Subtotal' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">${{ number_format($cart->total, 2) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('cart.shipping') ?? 'Shipping' }}</span>
                            <span class="text-emerald-400 font-medium">{{ __('cart.free') ?? 'Free' }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('cart.tax') ?? 'Tax' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800 font-medium">$0.00</span>
                        </div>
                        
                        <div class="border-t dark:border-slate-700/50 light:border-slate-200/50 pt-3 mt-3">
                            <div class="flex justify-between font-bold text-lg">
                                <span class="dark:text-slate-200 light:text-slate-800">{{ __('cart.total') ?? 'Total' }}</span>
                                <span class="text-cyan-400 animate-pulse-glow">${{ number_format($cart->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Checkout Button -->
                    @auth
                        <a href="{{ route('checkout') }}" 
                           class="block w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 text-center mt-4">
                            <i class="fas fa-arrow-right mr-2"></i>
                            {{ __('cart.proceed_to_checkout') ?? 'Proceed to Checkout' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 text-center mt-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('cart.login_to_checkout') ?? 'Login to Checkout' }}
                        </a>
                    @endauth
                    
                    <a href="{{ route('books.index') }}" 
                       class="block text-center text-sm dark:text-slate-500 light:text-slate-500 hover:text-cyan-400 transition mt-3">
                        <i class="fas fa-arrow-left mr-1"></i> {{ __('cart.continue_shopping') ?? 'Continue Shopping' }}
                    </a>
                </div>
            </div>
        </div>


<!-- Add this to your cart index view -->

<!-- Coupon Section -->
<div class="neu-card p-6 mb-4 animate-fade-in-up">
    <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
        <i class="fas fa-ticket-alt text-cyan-400 mr-2"></i>
        {{ __('cart.coupon') ?? 'Coupon' }}
    </h3>
    
    @if(session()->has('coupon_code'))
        <!-- Show applied coupon -->
        <div class="flex items-center justify-between p-3 bg-green-500/10 border border-green-500/20 rounded-lg">
            <div>
                <p class="text-sm font-medium text-green-400">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ __('cart.coupon_applied') ?? 'Coupon applied!' }}
                </p>
                <p class="text-sm dark:text-slate-300 light:text-slate-700">
                    {{ __('cart.code') ?? 'Code' }}: <strong>{{ session('coupon_code') }}</strong>
                    <span class="text-green-400 ml-2">- ${{ number_format(session('coupon_discount', 0), 2) }}</span>
                </p>
            </div>
            <form action="{{ route('cart.remove-coupon') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-400 hover:text-red-300 transition text-sm">
                    <i class="fas fa-times"></i> {{ __('cart.remove') ?? 'Remove' }}
                </button>
            </form>
        </div>
    @else
        <!-- Coupon form -->
        <form action="{{ route('cart.apply-coupon') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="coupon_code" 
                   placeholder="{{ __('cart.enter_coupon') ?? 'Enter coupon code' }}" 
                   class="flex-1 px-4 py-2 dark:bg-slate-800/50 light:bg-white border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800">
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition hover:scale-105">
                <i class="fas fa-check"></i> {{ __('cart.apply') ?? 'Apply' }}
            </button>
        </form>
        @if(session('coupon_error'))
            <p class="text-red-400 text-sm mt-2">{{ session('coupon_error') }}</p>
        @endif
        @if(session('coupon_success'))
            <p class="text-green-400 text-sm mt-2">{{ session('coupon_success') }}</p>
        @endif
    @endif
</div>

<!-- Update Order Summary -->
<div class="summary-card dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 animate-slide-right">
    <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800 mb-4">
        <i class="fas fa-receipt text-cyan-400 mr-2"></i>
        {{ __('cart.order_summary') ?? 'Order Summary' }}
    </h3>
    
    <div class="space-y-3 text-sm">
        <div class="flex justify-between">
            <span class="dark:text-slate-400 light:text-slate-600">{{ __('cart.subtotal') ?? 'Subtotal' }}</span>
            <span class="dark:text-slate-200 light:text-slate-800 font-medium">${{ number_format($cart->total, 2) }}</span>
        </div>
        
        @if(session()->has('coupon_discount') && session('coupon_discount') > 0)
            <div class="flex justify-between text-green-400">
                <span>{{ __('cart.discount') ?? 'Discount' }}</span>
                <span>-${{ number_format(session('coupon_discount'), 2) }}</span>
            </div>
        @endif
        
        <div class="flex justify-between">
            <span class="dark:text-slate-400 light:text-slate-600">{{ __('cart.shipping') ?? 'Shipping' }}</span>
            <span class="text-emerald-400 font-medium">{{ __('cart.free') ?? 'Free' }}</span>
        </div>
        
        @if(session()->has('coupon_discount'))
            <div class="border-t dark:border-slate-700/50 light:border-slate-200/50 pt-3 mt-3">
                <div class="flex justify-between font-bold text-lg">
                    <span class="dark:text-slate-200 light:text-slate-800">{{ __('cart.total') ?? 'Total' }}</span>
                    <span class="text-cyan-400 animate-pulse-glow">${{ number_format(max(0, $cart->total - session('coupon_discount')), 2) }}</span>
                </div>
            </div>
        @else
            <div class="border-t dark:border-slate-700/50 light:border-slate-200/50 pt-3 mt-3">
                <div class="flex justify-between font-bold text-lg">
                    <span class="dark:text-slate-200 light:text-slate-800">{{ __('cart.total') ?? 'Total' }}</span>
                    <span class="text-cyan-400 animate-pulse-glow">${{ number_format($cart->total, 2) }}</span>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Checkout Button -->
    @auth
        <a href="{{ route('checkout') }}" 
           class="block w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 text-center mt-4">
            <i class="fas fa-arrow-right mr-2"></i>
            {{ __('cart.proceed_to_checkout') ?? 'Proceed to Checkout' }}
        </a>
    @else
        <a href="{{ route('login') }}" 
           class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 text-center mt-4">
            <i class="fas fa-sign-in-alt mr-2"></i>
            {{ __('cart.login_to_checkout') ?? 'Login to Checkout' }}
        </a>
    @endauth
    
    <a href="{{ route('books.index') }}" 
       class="block text-center text-sm dark:text-slate-500 light:text-slate-500 hover:text-cyan-400 transition mt-3">
        <i class="fas fa-arrow-left mr-1"></i> {{ __('cart.continue_shopping') ?? 'Continue Shopping' }}
    </a>
</div>

    @else
        <!-- ============================================================ -->
        <!-- EMPTY CART -->
        <!-- ============================================================ -->
        <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-16 text-center animate-fade-in-up">
            <div class="max-w-md mx-auto">
                <div class="empty-state-icon w-28 h-28 rounded-full bg-cyan-500/10 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-cart text-cyan-400 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-semibold dark:text-slate-200 light:text-slate-800">{{ __('cart.empty_title') ?? 'Your Cart is Empty' }}</h3>
                <p class="text-slate-500 dark:text-slate-400 light:text-slate-500 mt-2">{{ __('cart.empty_message') ?? 'Looks like you haven\'t added any books to your cart yet.' }}</p>
                <div class="flex flex-wrap justify-center gap-3 mt-6">
                    <a href="{{ route('books.index') }}" 
                       class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 flex items-center gap-2">
                        <i class="fas fa-book-open"></i>
                        {{ __('cart.start_shopping') ?? 'Start Shopping' }}
                    </a>
                    <a href="{{ route('home') }}" 
                       class="px-6 py-3 bg-slate-500 hover:bg-slate-600 text-white rounded-lg transition-all duration-300 hover:scale-105 flex items-center gap-2">
                        <i class="fas fa-home"></i>
                        {{ __('cart.go_home') ?? 'Go Home' }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // ============================================================
    // UPDATE QUANTITY
    // ============================================================
    function updateQuantity(btn, delta) {
        const form = btn.closest('form');
        const input = form.querySelector('input[name="quantity"]');
        let val = parseInt(input.value) + delta;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
        form.submit();
    }
    
    // ============================================================
    // AUTO SUBMIT ON ENTER
    // ============================================================
    document.querySelectorAll('input[name="quantity"]').forEach(function(input) {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    });
</script>
@endpush
@endsection
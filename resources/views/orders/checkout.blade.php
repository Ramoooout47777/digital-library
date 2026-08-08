{{-- resources/views/orders/checkout.blade.php --}}
@extends('layouts.app')

@section('title', __('checkout.title') ?? 'Checkout')
@section('page-title', __('checkout.title') ?? 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold dark:text-slate-100 light:text-slate-900">
                <i class="fas fa-credit-card text-cyan-400 mr-3"></i>
                {{ __('checkout.title') ?? 'Checkout' }}
            </h1>
            <p class="text-slate-500 dark:text-slate-400 light:text-slate-500">
                {{ $cart->items->count() }} {{ __('checkout.items') ?? 'items' }} {{ __('checkout.in_your_cart') ?? 'in your cart' }}
            </p>
        </div>
        <a href="{{ route('cart.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition hover:scale-105 flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> {{ __('checkout.back_to_cart') ?? 'Back to Cart' }}
        </a>
    </div>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- ============================================================ -->
            <!-- CHECKOUT FORM -->
            <!-- ============================================================ -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shipping Address -->
                <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6">
                    <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                        <i class="fas fa-map-marker-alt text-cyan-400 mr-2"></i>
                        {{ __('checkout.shipping_address') ?? 'Shipping Address' }}
                    </h3>
                    <div>
                        <label class="block text-sm font-medium dark:text-slate-300 light:text-slate-700 mb-1">{{ __('checkout.address') ?? 'Address' }}</label>
                        <textarea name="shipping_address" rows="3"
                                  class="w-full px-4 py-2 dark:bg-slate-900/50 light:bg-slate-100 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-slate-200 light:text-slate-800"
                                  required>{{ old('shipping_address', auth()->user()->address ?? '') }}</textarea>
                        @error('shipping_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6">
                    <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                        <i class="fas fa-credit-card text-cyan-400 mr-2"></i>
                        {{ __('checkout.payment_method') ?? 'Payment Method' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                        <label class="payment-option flex items-center gap-3 p-3 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg cursor-pointer hover:border-cyan-500 transition">
                            <input type="radio" name="payment_method" value="cod" onchange="togglePaymentDetails('cod')" checked>
                            <div>
                                <i class="fas fa-money-bill-wave text-green-500"></i>
                                <span class="text-sm font-medium">{{ __('checkout.cash_on_delivery') ?? 'Cash on Delivery' }}</span>
                            </div>
                        </label>
                        <label class="payment-option flex items-center gap-3 p-3 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg cursor-pointer hover:border-cyan-500 transition">
                            <input type="radio" name="payment_method" value="card" onchange="togglePaymentDetails('card')">
                            <div>
                                <i class="fas fa-credit-card text-blue-500"></i>
                                <span class="text-sm font-medium">{{ __('checkout.credit_card') ?? 'Credit Card' }}</span>
                            </div>
                        </label>
                        <label class="payment-option flex items-center gap-3 p-3 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg cursor-pointer hover:border-cyan-500 transition">
                            <input type="radio" name="payment_method" value="qr" onchange="togglePaymentDetails('qr')">
                            <div>
                                <i class="fas fa-qrcode text-purple-500"></i>
                                <span class="text-sm font-medium">{{ __('checkout.qr_payment') ?? 'QR Payment' }}</span>
                            </div>
                        </label>
                    </div>

                    <!-- QR Payment Details -->
                    <div id="qr-details" class="hidden p-6 border-2 border-dashed border-purple-500/30 rounded-2xl bg-purple-500/5 text-center animate-scale-in">
                        <div class="max-w-xs mx-auto">
                            <p class="text-sm font-medium text-purple-400 mb-4 uppercase tracking-wider">Scan to Pay</p>
                            <div class="p-4 bg-white rounded-2xl shadow-xl mb-4 inline-block">
                                @php
                                    $qrPath = \App\Models\Setting::where('key', 'payment_qr_code')->where('group', 'general')->value('value');
                                @endphp
                                @if($qrPath)
                                    <img src="{{ asset('storage/' . $qrPath) }}" alt="Payment QR Code" class="w-48 h-48 mx-auto object-contain">
                                @else
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(route('home')) }}"
                                         alt="Default QR Code" class="w-48 h-48 mx-auto">
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                Please scan this QR code with your banking app to complete the payment. After successful payment, click "Place Order".
                            </p>
                        </div>
                    </div>

                    <!-- Card Payment Details (Placeholder) -->
                    <div id="card-details" class="hidden p-6 border-2 border-dashed border-blue-500/30 rounded-2xl bg-blue-500/5 animate-scale-in">
                        <div class="flex items-center gap-4 text-blue-400 mb-4">
                            <i class="fas fa-lock"></i>
                            <span class="text-sm font-medium uppercase tracking-wider">Secure Card Payment</span>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            You will be redirected to our secure payment gateway to complete your transaction.
                        </p>
                    </div>

                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Shipping Method -->
                <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6">
                    <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                        <i class="fas fa-truck text-cyan-400 mr-2"></i>
                        {{ __('checkout.shipping_method') ?? 'Shipping Method' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg cursor-pointer hover:border-cyan-500 transition">
                            <input type="radio" name="shipping_method" value="standard" checked>
                            <div>
                                <p class="text-sm font-medium">{{ __('checkout.standard') ?? 'Standard' }}</p>
                                <p class="text-xs text-slate-500">{{ __('checkout.standard_desc') ?? '3-5 business days' }}</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border dark:border-slate-700/50 light:border-slate-200/50 rounded-lg cursor-pointer hover:border-cyan-500 transition">
                            <input type="radio" name="shipping_method" value="express">
                            <div>
                                <p class="text-sm font-medium">{{ __('checkout.express') ?? 'Express' }}</p>
                                <p class="text-xs text-slate-500">{{ __('checkout.express_desc') ?? '1-2 business days' }}</p>
                            </div>
                        </label>
                    </div>
                    @error('shipping_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- ORDER SUMMARY -->
            <!-- ============================================================ -->
            <div class="lg:col-span-1">
                <div class="dark:bg-slate-800/50 light:bg-white rounded-xl shadow-sm overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6 sticky top-24">
                    <h3 class="font-semibold text-lg dark:text-slate-200 light:text-slate-800 mb-4">
                        <i class="fas fa-receipt text-cyan-400 mr-2"></i>
                        {{ __('checkout.order_summary') ?? 'Order Summary' }}
                    </h3>

                    <div class="space-y-3 max-h-60 overflow-y-auto mb-4">
                        @foreach($cart->items as $item)
                            <div class="flex items-center gap-3 py-2 border-b dark:border-slate-700/50 light:border-slate-200/50">
                                @if($item->book && $item->book->cover)
                                    <img src="{{ asset('storage/' . $item->book->cover) }}"
                                         alt="{{ $item->book->title }}"
                                         class="w-12 h-16 object-cover rounded">
                                @endif
                                <div class="flex-1">
                                    <p class="text-sm font-medium dark:text-slate-200 light:text-slate-800">{{ $item->book->title ?? 'Book' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <p class="text-sm font-bold text-cyan-400">${{ number_format($item->total, 2) }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 text-sm border-t dark:border-slate-700/50 light:border-slate-200/60 pt-4 mt-4">
                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('checkout.subtotal') ?? 'Subtotal' }}</span>
                            <span class="dark:text-slate-200 light:text-slate-800">${{ number_format($originalTotal, 2) }}</span>
                        </div>

                        @if($discountAmount > 0)
                            <div class="flex justify-between text-green-500">
                                <span>{{ __('checkout.discount') ?? 'Discount' }}</span>
                                <span>-${{ number_format($discountAmount, 2) }}</span>
                            </div>
                        @endif

                        @if($currentCoupon)
                            <div class="flex justify-between text-cyan-400 text-xs">
                                <span>{{ __('checkout.coupon') ?? 'Coupon' }} ({{ $currentCoupon['code'] }})</span>
                                @php
                                    $couponData = $currentCoupon['coupon'];
                                    $val = is_array($couponData) ? $couponData['discount_value'] : $couponData->discount_value;
                                    $type = is_array($couponData) ? $couponData['discount_type'] : $couponData->discount_type;
                                @endphp
                                <span>-{{ $val }}{{ $type === 'percentage' ? '%' : '$' }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="dark:text-slate-400 light:text-slate-600">{{ __('checkout.shipping') ?? 'Shipping' }}</span>
                            <span class="text-emerald-400">{{ __('checkout.free') ?? 'Free' }}</span>
                        </div>

                        <div class="flex justify-between font-bold text-lg border-t dark:border-slate-800/40 light:border-slate-200/60 pt-4 mt-2">
                            <span class="dark:text-slate-200 light:text-slate-800">{{ __('checkout.total') ?? 'Total' }}</span>
                            <span class="text-cyan-400">${{ number_format($discountedTotal, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-lg shadow-cyan-500/20 mt-4">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ __('checkout.place_order') ?? 'Place Order' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function togglePaymentDetails(method) {
        const qrDetails = document.getElementById('qr-details');
        const cardDetails = document.getElementById('card-details');

        qrDetails.classList.add('hidden');
        cardDetails.classList.add('hidden');

        if (method === 'qr') {
            qrDetails.classList.remove('hidden');
        } else if (method === 'card') {
            cardDetails.classList.remove('hidden');
        }
    }
</script>
@endpush

@push('styles')
<style>
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-scale-in {
        animation: scaleIn 0.3s ease-out forwards;
    }
    .payment-option input:checked + div span {
        color: #06b6d4; /* cyan-500 */
    }
</style>
@endpush

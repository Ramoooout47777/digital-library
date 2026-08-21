{{-- resources/views/admin/orders/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.order_details') . ' - ' . $order->order_number)
@section('page-title', __('admin.order_details'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Order Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status == 'completed') bg-green-100 text-green-800
                        @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ __('admin.' . $order->status) }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($order->payment_status == 'completed') bg-green-100 text-green-800
                        @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                        @elseif($order->payment_status == 'refunded') bg-gray-100 text-gray-800
                        @endif">
                        {{ __('admin.' . $order->payment_status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-user mr-2 text-blue-500"></i>
                {{ __('admin.customer_information') ?? 'ព័ត៌មានអតិថិជន' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.name') }}</p>
                    <p class="font-medium">{{ $order->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.email') }}</p>
                    <p class="font-medium">{{ $order->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.phone') }}</p>
                    <p class="font-medium">{{ $order->user->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('admin.shipping_address') }}</p>
                    <p class="font-medium">{{ $order->shipping_address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-shopping-bag mr-2 text-blue-500"></i>
                {{ __('admin.order_items') ?? 'ធាតុការកម្មង់' }}
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.book') }}</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.quantity') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('admin.price') }}</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">{{ __('admin.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($item->book && $item->book->cover)
                                            <img src="{{ asset('storage/' . $item->book->cover) }}"
                                                 alt="{{ $item->book_title }}"
                                                 class="w-12 h-16 object-cover rounded">
                                        @else
                                            <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                                <i class="fas fa-book text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $item->book_title }}</p>
                                            @if($item->book)
                                                <p class="text-xs text-gray-500">{{ $item->book->author->name ?? 'N/A' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-right font-semibold">${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-medium">{{ __('admin.subtotal') }}</td>
                            <td class="px-4 py-3 text-right font-medium">${{ number_format($order->subtotal, 2) }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-medium text-green-600">{{ __('admin.discount') }}</td>
                                <td class="px-4 py-3 text-right font-medium text-green-600">-${{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                        @endif
                        @if($order->coupon_code)
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-medium">{{ __('admin.coupon') }}</td>
                                <td class="px-4 py-3 text-right font-medium">{{ $order->coupon_code }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">{{ __('admin.total') }}</td>
                            <td class="px-4 py-3 text-right font-bold text-lg text-blue-600">${{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column - Actions -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Order Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-tasks mr-2 text-blue-500"></i>
                {{ __('admin.order_status') }}
            </h3>

            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
                    <select name="status" class="form-input w-full">
                        @foreach(App\Models\Order::getStatuses() as $key => $label)
                            <option value="{{ $key }}" {{ $order->order_status == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(in_array($order->order_status, ['packed', 'shipped']))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.tracking_number') ?? 'លេខតាមដាន' }}</label>
                        <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}"
                               class="form-input w-full" placeholder="Ex: ABC123456789">
                    </div>
                @endif

                <button type="submit" class="btn btn-primary w-full">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.update') }}
                </button>
            </form>
        </div>

        <!-- Payment Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-credit-card mr-2 text-blue-500"></i>
                {{ __('admin.payment_status') }}
            </h3>

            <form action="{{ route('admin.orders.update-payment', $order) }}" method="POST" class="space-y-3">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.payment_status') }}</label>
                    <select name="payment_status" class="form-input w-full">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                        <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>{{ __('admin.completed') }}</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>{{ __('admin.failed') }}</option>
                        <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>{{ __('admin.refunded') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.update') }}
                </button>
            </form>
        </div>

        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                {{ __('admin.payment_information') ?? 'ព័ត៌មានការទូទាត់' }}
            </h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('admin.payment_method') }}</span>
                    <span class="font-medium">{{ $order->payment_method ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('admin.payment_status') }}</span>
                    <span class="font-medium">{{ __('admin.' . $order->payment_status) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('admin.total') }}</span>
                    <span class="font-bold text-blue-600">${{ number_format($order->total, 2) }}</span>
                </div>
                @if($order->coupon_code)
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.coupon') }}</span>
                        <span class="font-medium text-green-600">{{ $order->coupon_code }}</span>
                    </div>
                @endif
                @if($order->completed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.completed_at') ?? 'បានបញ្ចប់នៅ' }}</span>
                        <span class="font-medium">{{ $order->completed_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-bolt mr-2 text-blue-500"></i>
                {{ __('admin.quick_actions') }}
            </h3>
            <div class="space-y-2">
                @if(in_array($order->status, ['pending', 'processing']))
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success w-full" onclick="return confirm('{{ __('admin.confirm_complete') ?? 'តើអ្នកចង់បញ្ចប់ការកម្មង់នេះ?' }}')">
                            <i class="fas fa-check-circle mr-2"></i> {{ __('admin.complete_order') ?? 'បញ្ចប់ការកម្មង់' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-danger w-full" onclick="return confirm('{{ __('admin.confirm_cancel') ?? 'តើអ្នកចង់បោះបង់ការកម្មង់នេះ?' }}')">
                            <i class="fas fa-times-circle mr-2"></i> {{ __('admin.cancel_order') ?? 'បោះបង់ការកម្មង់' }}
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-primary w-full">
                    <i class="fas fa-file-invoice mr-2"></i> {{ __('admin.download_invoice') ?? 'Download Invoice' }}
                </a>

                <a href="{{ route('admin.orders.index') }}" class="btn btn-gray w-full">
                    <i class="fas fa-arrow-left mr-2"></i> {{ __('admin.back_to_list') }}
                </a>

                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline w-full">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full" onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                        <i class="fas fa-trash mr-2"></i> {{ __('admin.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

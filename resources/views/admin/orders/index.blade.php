{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.orders_menu'))
@section('page-title', __('admin.orders_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.orders.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-file-export mr-2"></i>
            {{ __('admin.export') }}
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-clock mr-2"></i>
            {{ __('admin.pending_orders') }}
            @php
                $pendingCount = \App\Models\Order::where('status', 'pending')->count();
            @endphp
            @if($pendingCount > 0)
                <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
    </div>

    <form action="{{ route('admin.orders.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('admin.search') }}..."
               class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>{{ __('admin.processing') }}</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>{{ __('admin.completed') }}</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{ __('admin.cancelled') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.payment_status') }}</label>
            <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>{{ __('admin.completed') }}</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>{{ __('admin.failed') }}</option>
                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>{{ __('admin.refunded') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.date_range') ?? 'កាលបរិច្ឆេទ' }}</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.order_number') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.customer') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.total') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.payment_status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.date') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm">{{ $order->id }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($order->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $order->user->name ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</span>
                            @if($order->discount_amount > 0)
                                <span class="text-xs text-green-600 block">- ${{ number_format($order->discount_amount, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->payment_status == 'completed') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                @elseif($order->payment_status == 'refunded') bg-gray-100 text-gray-800
                                @endif">
                                {{ __('admin.' . $order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ $order->status_badge }}">
                                <i class="fas {{ $order->status_icon }} mr-1"></i>
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-blue-600 hover:text-blue-800 transition"
                                   title="{{ __('admin.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(in_array($order->status, ['pending', 'processing']))
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="text-green-600 hover:text-green-800 transition"
                                                title="{{ __('admin.complete') ?? 'បញ្ចប់' }}"
                                                onclick="return confirm('{{ __('admin.confirm_complete') ?? 'តើអ្នកចង់បញ្ចប់ការកម្មង់នេះ?' }}')">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition"
                                                title="{{ __('admin.cancel') ?? 'បោះបង់' }}"
                                                onclick="return confirm('{{ __('admin.confirm_cancel') ?? 'តើអ្នកចង់បោះបង់ការកម្មង់នេះ?' }}')">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_orders') ?? 'មិនមានការកម្មង់' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t flex justify-between items-center flex-wrap gap-2">
        <div class="text-sm text-gray-500">
            {{ __('admin.showing') }} {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }}
            {{ __('admin.of') }} {{ $orders->total() }} {{ __('admin.items') }}
        </div>
        {{ $orders->links() }}
    </div>
</div>

<!-- Order Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_orders') }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? $orders->total() }}</p>
    </div>
    <div class="bg-yellow-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.pending') }}</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.completed') }}</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['completed'] ?? 0 }}</p>
    </div>
    <div class="bg-red-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.cancelled') }}</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] ?? 0 }}</p>
    </div>
</div>
@endsection

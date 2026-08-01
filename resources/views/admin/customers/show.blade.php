{{-- resources/views/admin/customers/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.customer_details') ?? 'ព័ត៌មានលម្អិតអតិថិជន')
@section('page-title', __('admin.customer_details') ?? 'ព័ត៌មានលម្អិតអតិថិជន')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Customer Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Avatar -->
            <div class="flex flex-col items-center">
                <img src="{{ $customer->avatar ? asset('storage/' . $customer->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=3b82f6&color=fff&size=120' }}" 
                     alt="{{ $customer->name }}" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">
                <h3 class="text-xl font-bold text-gray-800 mt-3">{{ $customer->name }}</h3>
                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                <div class="mt-2 flex gap-2">
                    @foreach($customer->roles as $role)
                        <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">{{ $role->name }}</span>
                    @endforeach
                    <span class="px-2 py-1 rounded-full text-xs {{ $customer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $customer->is_active ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </div>
            </div>

            <!-- Statistics -->
            <div class="mt-6 pt-6 border-t">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.total_orders') }}</span>
                        <span class="font-bold text-blue-600">{{ $customer->orders_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.total_spent') }}</span>
                        <span class="font-bold text-green-600">${{ number_format($customer->total_spent ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.average_order') ?? 'មធ្យមការកម្មង់' }}</span>
                        <span class="font-bold">${{ number_format($customer->orders_count > 0 ? ($customer->total_spent / $customer->orders_count) : 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.last_order') }}</span>
                        {{-- === FIXED: Check if last_order_date exists before calling format() === --}}
                        <span class="font-medium">
                            @if(isset($customer->last_order_date) && $customer->last_order_date)
                                {{ $customer->last_order_date->format('d/m/Y') }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.registered_at') }}</span>
                        <span class="font-medium">{{ $customer->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.last_login') }}</span>
                        <span class="font-medium">
                            {{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'N/A' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.phone') }}</span>
                        <span class="font-medium">{{ $customer->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.address') }}</span>
                        <span class="font-medium">{{ $customer->address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 pt-6 border-t space-y-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>{{ __('admin.edit') }}
                </a>
                
                @if($customer->id != auth()->id() && $customer->orders_count == 0)
                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-4 py-2 rounded-lg transition"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                            <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.customers.index') }}" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Customer Orders -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="font-semibold text-lg text-gray-800">
                    <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>
                    {{ __('admin.customer_orders') ?? 'ការកម្មង់របស់អតិថិជន' }}
                </h3>
                <span class="text-sm text-gray-500">
                    {{ __('admin.total') }}: {{ $customer->orders_count ?? 0 }}
                </span>
            </div>
            
            @if(isset($customer->orders) && $customer->orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2 text-left">{{ __('admin.order_number') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.date') }}</th>
                                <th class="px-3 py-2 text-right">{{ __('admin.total') }}</th>
                                <th class="px-3 py-2 text-center">{{ __('admin.status') }}</th>
                                <th class="px-3 py-2 text-center">{{ __('admin.payment_status') }}</th>
                                <th class="px-3 py-2 text-center">{{ __('admin.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($customer->orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 font-medium">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:underline">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2 text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-3 py-2 text-right font-semibold">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($order->status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @endif">
                                            {{ __('admin.' . $order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            @if($order->payment_status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                            @elseif($order->payment_status == 'refunded') bg-gray-100 text-gray-800
                                            @endif">
                                            {{ __('admin.' . $order->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_orders') }}</p>
            @endif
        </div>

        <!-- Customer Reviews -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-star mr-2 text-yellow-500"></i>
                {{ __('admin.customer_reviews') ?? 'ការវាយតម្លៃរបស់អតិថិជន' }}
            </h3>
            
            @if(isset($customer->reviews) && $customer->reviews->count() > 0)
                <div class="space-y-3">
                    @foreach($customer->reviews as $review)
                        <div class="border-b last:border-0 pb-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.books.show', $review->book) }}" class="font-medium text-gray-800 hover:text-blue-500">
                                            {{ $review->book->title ?? 'N/A' }}
                                        </a>
                                        <span class="text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_reviews') }}</p>
            @endif
        </div>
    </div>
</div>
@endsection
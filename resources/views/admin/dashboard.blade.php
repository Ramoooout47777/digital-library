{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.dashboard'))
@section('page-title', __('admin.dashboard'))

@section('content')
<!-- Quick Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Books -->
    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition duration-200 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('admin.total_books') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_books']) }}</p>
                <p class="text-xs text-green-500 mt-1">
                    <i class="fas fa-arrow-up"></i> 
                    {{ number_format(($stats['total_books'] > 0 ? ($stats['total_books'] / max(1, $stats['total_books'] - 10)) * 100 : 0), 1) }}%
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-book text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition duration-200 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('admin.total_users') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_users']) }}</p>
                <p class="text-xs text-green-500 mt-1">
                    <i class="fas fa-arrow-up"></i> 
                    {{ number_format(($stats['total_users'] > 0 ? ($stats['total_users'] / max(1, $stats['total_users'] - 5)) * 100 : 0), 1) }}%
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition duration-200 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('admin.total_orders') }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_orders']) }}</p>
                <p class="text-xs text-yellow-500 mt-1">
                    <i class="fas fa-clock"></i> 
                    {{ number_format($stats['pending_orders']) }} {{ __('admin.pending_orders') }}
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition duration-200 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">{{ __('admin.total_revenue') }}</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($stats['total_revenue'], 2) }}</p>
                <p class="text-xs text-green-500 mt-1">
                    <i class="fas fa-arrow-up"></i> 12.5%
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_categories') }}</p>
        <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['total_categories']) }}</p>
        <a href="{{ route('admin.categories.index') }}" class="text-xs text-indigo-500 hover:underline">គ្រប់គ្រង</a>
    </div>
    
    <div class="bg-gradient-to-r from-pink-50 to-pink-100 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_authors') }}</p>
        <p class="text-2xl font-bold text-pink-600">{{ number_format($stats['total_authors']) }}</p>
        <a href="{{ route('admin.authors.index') }}" class="text-xs text-pink-500 hover:underline">គ្រប់គ្រង</a>
    </div>
    
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_publishers') }}</p>
        <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_publishers']) }}</p>
        <a href="{{ route('admin.publishers.index') }}" class="text-xs text-orange-500 hover:underline">គ្រប់គ្រង</a>
    </div>
    
    <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_sales') }}</p>
        <p class="text-2xl font-bold text-teal-600">{{ number_format($stats['total_orders']) }}</p>
        <span class="text-xs text-gray-400">+12%</span>
    </div>
    
    <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.views') }}</p>
        <p class="text-2xl font-bold text-red-600">{{ number_format($stats['total_views'] ?? 0) }}</p>
        <span class="text-xs text-gray-400">+8%</span>
    </div>
    
    <div class="bg-gradient-to-r from-cyan-50 to-cyan-100 rounded-xl p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.downloads') }}</p>
        <p class="text-2xl font-bold text-cyan-600">{{ number_format($stats['total_downloads'] ?? 0) }}</p>
        <span class="text-xs text-gray-400">+15%</span>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">{{ __('admin.total_revenue') }} - {{ date('Y') }}</h3>
            <div class="flex gap-2">
                <button onclick="changeChartPeriod('weekly')" class="px-3 py-1 text-xs rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200">សប្តាហ៍</button>
                <button onclick="changeChartPeriod('monthly')" class="px-3 py-1 text-xs rounded-lg bg-blue-500 text-white">ខែ</button>
                <button onclick="changeChartPeriod('yearly')" class="px-3 py-1 text-xs rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">ឆ្នាំ</button>
            </div>
        </div>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Categories Chart -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">{{ __('admin.top_categories') }}</h3>
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-500 hover:underline">{{ __('admin.view_all') }}</a>
        </div>
        <div class="h-64">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Top Categories List -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">{{ __('admin.top_categories') }}</h3>
            <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-500 hover:underline">{{ __('admin.view_all') }}</a>
        </div>
        <div class="space-y-3">
            @forelse($topCategories as $index => $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold 
                            @if($index == 0) bg-yellow-500 text-white
                            @elseif($index == 1) bg-gray-400 text-white
                            @elseif($index == 2) bg-orange-400 text-white
                            @else bg-gray-200 text-gray-600 @endif">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $category['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $category['books_count'] }} {{ __('admin.books') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-green-600">${{ number_format($category['revenue'], 2) }}</p>
                        <p class="text-xs text-gray-400">{{ __('admin.revenue') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_data') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">{{ __('admin.recent_orders') }}</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-500 hover:underline">{{ __('admin.view_all') }}</a>
        </div>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-blue-100 text-blue-600 text-sm font-bold">
                            {{ substr($order->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">${{ number_format($order->total, 2) }}</p>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ __('admin.' . $order->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_data') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Popular Books -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">{{ __('admin.popular_books') }}</h3>
            <a href="{{ route('admin.books.index') }}" class="text-sm text-blue-500 hover:underline">{{ __('admin.view_all') }}</a>
        </div>
        <div class="space-y-3 max-h-96 overflow-y-auto">
            @forelse($popularBooks as $book)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    @if($book->cover)
                        <img src="{{ asset('storage/' . $book->cover) }}" 
                             alt="{{ $book->title }}" 
                             class="w-12 h-16 object-cover rounded">
                    @else
                        <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                            <i class="fas fa-book text-gray-400"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ Str::limit($book->title, 25) }}</p>
                        <p class="text-xs text-gray-500">{{ $book->author->name ?? 'N/A' }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-green-600 font-semibold">${{ number_format($book->final_price, 2) }}</span>
                            <span class="text-xs text-gray-400">
                                <i class="fas fa-eye"></i> {{ number_format($book->views_count) }}
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full {{ $book->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $book->status ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_data') }}</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Users & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-800">{{ __('admin.recent_users') ?? 'អ្នកប្រើប្រាស់ថ្មីៗ' }}</h3>
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-blue-500 hover:underline">{{ __('admin.view_all') }}</a>
        </div>
        <div class="space-y-3">
            @forelse($recentUsers ?? [] as $user)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3b82f6&color=fff' }}" 
                         alt="{{ $user->name }}" 
                         class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_data') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-4">{{ __('admin.quick_actions') ?? 'សកម្មភាពរហ័ស' }}</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.books.create') }}" 
               class="p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition text-center">
                <i class="fas fa-plus-circle text-blue-600 text-2xl mb-2 block"></i>
                <span class="text-sm text-gray-700">{{ __('admin.add_new_book') }}</span>
            </a>
            
            <a href="{{ route('admin.categories.create') }}" 
               class="p-4 bg-green-50 rounded-xl hover:bg-green-100 transition text-center">
                <i class="fas fa-tags text-green-600 text-2xl mb-2 block"></i>
                <span class="text-sm text-gray-700">{{ __('admin.add_new_category') }}</span>
            </a>
            
            <a href="{{ route('admin.authors.create') }}" 
               class="p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition text-center">
                <i class="fas fa-user-plus text-purple-600 text-2xl mb-2 block"></i>
                <span class="text-sm text-gray-700">{{ __('admin.add_new_author') }}</span>
            </a>
            
            <a href="{{ route('admin.coupons.create') }}" 
               class="p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition text-center">
                <i class="fas fa-ticket-alt text-yellow-600 text-2xl mb-2 block"></i>
                <span class="text-sm text-gray-700">{{ __('admin.add_new_coupon') ?? 'បន្ថែមប័ណ្ណថ្មី' }}</span>
            </a>
            
            <a href="{{ route('admin.banners.create') }}" 
               class="p-4 bg-pink-50 rounded-xl hover:bg-pink-100 transition text-center">
                <i class="fas fa-image text-pink-600 text-2xl mb-2 block"></i>
                <span class="text-sm text-gray-700">{{ __('admin.add_new_banner') }}</span>
            </a>
            
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
               class="p-4 bg-red-50 rounded-xl hover:bg-red-100 transition text-center">
                <i class="fas fa-clock text-red-600 text-2xl mb-2 block"></i>
                <span class="text-sm text-gray-700">{{ __('admin.pending_orders') }}</span>
                @if($stats['pending_orders'] > 0)
                    <span class="inline-block mt-1 px-2 py-0.5 bg-red-500 text-white text-xs rounded-full">
                        {{ $stats['pending_orders'] }}
                    </span>
                @endif
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ============ REVENUE CHART ============
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 
                     'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'],
            datasets: [{
                label: '{{ __("admin.total_revenue") }}',
                data: {{ json_encode($chartData) }},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            }
        }
    });

    // ============ CATEGORY CHART ============
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($topCategories);
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.name),
            datasets: [{
                data: categoryData.map(item => item.books_count),
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(236, 72, 153, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(168, 85, 247, 0.8)',
                ],
                borderColor: '#fff',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // ============ CHANGE CHART PERIOD ============
    function changeChartPeriod(period) {
        // Update chart data based on period
        // This would require AJAX call to fetch new data
        const buttons = document.querySelectorAll('[onclick^="changeChartPeriod"]');
        buttons.forEach(btn => {
            btn.classList.remove('bg-blue-500', 'text-white');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });
        event.target.classList.remove('bg-gray-100', 'text-gray-600');
        event.target.classList.add('bg-blue-500', 'text-white');
        
        // Fetch new data
        fetch(`/admin/dashboard/chart-data?period=${period}`)
            .then(response => response.json())
            .then(data => {
                revenueChart.data.datasets[0].data = data;
                revenueChart.update();
            })
            .catch(error => console.error('Error:', error));
    }

    // ============ AUTO REFRESH ============
    // Auto refresh dashboard every 5 minutes
    setTimeout(function() {
        location.reload();
    }, 300000);
</script>
@endpush
@endsection
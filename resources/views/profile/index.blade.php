{{-- resources/views/profile/index.blade.php --}}
@extends('layouts.app')

@section('title', __('profile.title') ?? 'My Profile')
@section('page-title', __('profile.title') ?? 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Profile Header -->
    <div class="neu-card p-6 mb-8 animate-fade-in-up">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <!-- Avatar -->
            <div class="relative">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff&size=120' }}" 
                     alt="{{ auth()->user()->name }}" 
                     class="w-24 h-24 rounded-full object-cover border-4 border-cyan-500/20">
                <button onclick="document.getElementById('avatar-input').click()" 
                        class="absolute bottom-0 right-0 p-1.5 rounded-full bg-cyan-500 hover:bg-cyan-600 text-white transition">
                    <i class="fas fa-camera text-xs"></i>
                </button>
                <form id="avatar-form" action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" onchange="this.form.submit()">
                </form>
            </div>
            
            <!-- User Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl font-bold dark:text-slate-100 light:text-slate-900">{{ auth()->user()->name }}</h1>
                <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ auth()->user()->email }}</p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('profile.verified') ?? 'Verified' }}
                    </span>
                    <span class="text-xs dark:text-slate-500 light:text-slate-500">
                        <i class="fas fa-calendar-alt mr-1"></i> 
                        {{ __('profile.member_since') ?? 'Member since' }} {{ auth()->user()->created_at->format('M Y') }}
                    </span>
                    @if(auth()->user()->last_login_at)
                        <span class="text-xs dark:text-slate-500 light:text-slate-500">
                            <i class="fas fa-clock mr-1"></i> 
                            {{ __('profile.last_login') ?? 'Last login' }} {{ auth()->user()->last_login_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="flex gap-2 flex-wrap justify-center">
                <a href="{{ route('profile.purchased-books') }}" class="neu-button-primary px-4 py-2 text-sm rounded-xl">
                    <i class="fas fa-book mr-2"></i> {{ __('profile.my_books') ?? 'My Books' }}
                </a>
                <a href="{{ route('orders.index') }}" class="neu-button px-4 py-2 text-sm rounded-xl">
                    <i class="fas fa-shopping-bag mr-2"></i> {{ __('profile.my_orders') ?? 'My Orders' }}
                </a>
                <a href="{{ route('profile.edit') }}" class="neu-button px-4 py-2 text-sm rounded-xl">
                    <i class="fas fa-edit mr-2"></i> {{ __('profile.edit_profile') ?? 'Edit Profile' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="neu-card p-4 text-center">
            <p class="text-2xl font-bold text-cyan-400">{{ $ordersCount ?? 0 }}</p>
            <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ __('profile.total_orders') ?? 'Total Orders' }}</p>
        </div>
        <div class="neu-card p-4 text-center">
            <p class="text-2xl font-bold text-emerald-400">${{ number_format($totalSpent ?? 0, 2) }}</p>
            <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ __('profile.total_spent') ?? 'Total Spent' }}</p>
        </div>
        <div class="neu-card p-4 text-center">
            <p class="text-2xl font-bold text-purple-400">{{ $purchasedBooksCount ?? 0 }}</p>
            <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ __('profile.books_purchased') ?? 'Books Purchased' }}</p>
        </div>
        <div class="neu-card p-4 text-center">
            <p class="text-2xl font-bold text-amber-400">{{ $reviewsCount ?? 0 }}</p>
            <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ __('profile.reviews') ?? 'Reviews' }}</p>
        </div>
    </div>

    <!-- Profile Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders & Favorites -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Orders -->
            <div class="neu-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800">
                        <i class="fas fa-shopping-bag text-cyan-400 mr-2"></i> {{ __('profile.recent_orders') ?? 'Recent Orders' }}
                    </h3>
                    <a href="{{ route('orders.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                        {{ __('profile.view_all') ?? 'View All' }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($recentOrders) && $recentOrders->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentOrders as $order)
                            <div class="flex flex-wrap items-center justify-between gap-2 p-3 rounded-xl dark:bg-slate-800/30 light:bg-slate-100/50">
                                <div>
                                    <p class="font-medium dark:text-slate-200 light:text-slate-800 text-sm">{{ $order->order_number }}</p>
                                    <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-bold text-cyan-400">${{ number_format($order->total, 2) }}</span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->status === 'completed') bg-emerald-500/20 text-emerald-400
                                        @elseif($order->status === 'pending') bg-amber-500/20 text-amber-400
                                        @else bg-red-500/20 text-red-400 @endif">
                                        <i class="fas 
                                            @if($order->status === 'completed') fa-check-circle
                                            @elseif($order->status === 'pending') fa-clock
                                            @else fa-times-circle @endif mr-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <a href="{{ route('orders.show', $order) }}" class="text-cyan-400 hover:text-cyan-300 transition">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-shopping-bag text-3xl dark:text-slate-700 light:text-slate-300 block mb-2"></i>
                        <p class="dark:text-slate-500 light:text-slate-500 text-sm">{{ __('profile.no_orders') ?? 'No orders yet' }}</p>
                        <a href="{{ route('books.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition mt-2 inline-block">
                            {{ __('profile.start_shopping') ?? 'Start Shopping' }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Favorite Books -->
            <div class="neu-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800">
                        <i class="fas fa-heart text-cyan-400 mr-2"></i> {{ __('profile.favorite_books') ?? 'Favorite Books' }}
                    </h3>
                    <a href="{{ route('favorites.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                        {{ __('profile.view_all') ?? 'View All' }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($favoriteBooks) && $favoriteBooks->count() > 0)
                    <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach($favoriteBooks as $book)
                            <a href="{{ route('books.show', $book) }}" class="group">
                                <div class="rounded-xl overflow-hidden">
                                    @if($book->cover)
                                        <img src="{{ asset('storage/' . $book->cover) }}" 
                                             alt="{{ $book->title }}" 
                                             class="w-full aspect-[3/4] object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full aspect-[3/4] dark:bg-slate-700/50 light:bg-slate-100 flex items-center justify-center">
                                            <i class="fas fa-book dark:text-slate-600 light:text-slate-300 text-2xl"></i>
                                        </div>
                                    @endif
                                    <p class="text-xs font-medium dark:text-slate-200 light:text-slate-800 truncate mt-1 text-center">{{ Str::limit($book->title, 15) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-heart text-3xl dark:text-slate-700 light:text-slate-300 block mb-2"></i>
                        <p class="dark:text-slate-500 light:text-slate-500 text-sm">{{ __('profile.no_favorites') ?? 'No favorite books yet' }}</p>
                        <a href="{{ route('books.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition mt-2 inline-block">
                            {{ __('profile.explore_books') ?? 'Explore Books' }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Personal Info & My Books -->
        <div class="space-y-6">
            <!-- Personal Info -->
            <div class="neu-card p-6">
                <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-user text-cyan-400 mr-2"></i> {{ __('profile.personal_info') ?? 'Personal Information' }}
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="dark:text-slate-500 light:text-slate-500">{{ __('profile.name') ?? 'Name' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ auth()->user()->name }}</p>
                    </div>
                    <div>
                        <p class="dark:text-slate-500 light:text-slate-500">{{ __('profile.email') ?? 'Email' }}</p>
                        <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ auth()->user()->email }}</p>
                    </div>
                    @if(auth()->user()->phone)
                        <div>
                            <p class="dark:text-slate-500 light:text-slate-500">{{ __('profile.phone') ?? 'Phone' }}</p>
                            <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ auth()->user()->phone }}</p>
                        </div>
                    @endif
                    @if(auth()->user()->address)
                        <div>
                            <p class="dark:text-slate-500 light:text-slate-500">{{ __('profile.address') ?? 'Address' }}</p>
                            <p class="font-medium dark:text-slate-200 light:text-slate-800">{{ auth()->user()->address }}</p>
                        </div>
                    @endif
                </div>
                <a href="{{ route('profile.edit') }}" class="block text-center neu-button-primary text-sm py-2 rounded-xl mt-4">
                    <i class="fas fa-edit mr-2"></i> {{ __('profile.edit_profile') ?? 'Edit Profile' }}
                </a>
            </div>
            
            <!-- My Books -->
            <div class="neu-card p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800">
                        <i class="fas fa-book text-cyan-400 mr-2"></i> {{ __('profile.my_books') ?? 'My Books' }}
                    </h3>
                    <a href="{{ route('profile.purchased-books') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition">
                        {{ __('profile.view_all') ?? 'View All' }} <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                @if(isset($purchasedBooks) && $purchasedBooks->count() > 0)
                    <div class="space-y-3">
                        @foreach($purchasedBooks->take(3) as $purchase)
                            <div class="flex items-center gap-3 p-2 rounded-xl dark:bg-slate-800/30 light:bg-slate-100/50">
                                <div class="w-12 h-16 flex-shrink-0 rounded-lg overflow-hidden">
                                    @if($purchase->book && $purchase->book->cover)
                                        <img src="{{ asset('storage/' . $purchase->book->cover) }}" 
                                             alt="{{ $purchase->book->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full dark:bg-slate-700/50 light:bg-slate-200 flex items-center justify-center">
                                            <i class="fas fa-book dark:text-slate-500 light:text-slate-400 text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium dark:text-slate-200 light:text-slate-800 truncate">{{ $purchase->book?->title ?? __('profile.book_unavailable') ?? 'Book unavailable' }}</p>
                                    <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ $purchase->book?->author?->name ?? 'N/A' }}</p>
                                </div>
                                <div class="flex gap-1">
                                    @if($purchase->book?->pdf_file)
                                        <a href="{{ route('profile.read-book', $purchase) }}" 
                                           class="text-cyan-400 hover:text-cyan-300 transition p-1.5 rounded-lg hover:bg-cyan-500/10"
                                           title="Read">
                                            <i class="fas fa-book-open text-sm"></i>
                                        </a>
                                        <a href="{{ route('profile.download-book', $purchase) }}" 
                                           class="text-emerald-400 hover:text-emerald-300 transition p-1.5 rounded-lg hover:bg-emerald-500/10"
                                           title="Download">
                                            <i class="fas fa-download text-sm"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-book text-3xl dark:text-slate-700 light:text-slate-300 block mb-2"></i>
                        <p class="dark:text-slate-500 light:text-slate-500 text-sm">{{ __('profile.no_purchased_books') ?? 'No purchased books yet' }}</p>
                        <a href="{{ route('books.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300 transition mt-2 inline-block">
                            {{ __('profile.explore_books') ?? 'Explore Books' }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Account Actions -->
            <div class="neu-card p-6">
                <h3 class="text-lg font-semibold dark:text-slate-200 light:text-slate-800 mb-4">
                    <i class="fas fa-cog text-cyan-400 mr-2"></i> {{ __('profile.account_actions') ?? 'Account Actions' }}
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center justify-between p-3 rounded-xl dark:bg-slate-800/30 light:bg-slate-100/50 hover:dark:bg-slate-800/50 hover:light:bg-slate-100 transition">
                        <span class="text-sm dark:text-slate-300 light:text-slate-700">{{ __('profile.edit_profile') ?? 'Edit Profile' }}</span>
                        <i class="fas fa-chevron-right text-slate-500"></i>
                    </a>
                    <a href="{{ route('profile.edit') }}#password" class="flex items-center justify-between p-3 rounded-xl dark:bg-slate-800/30 light:bg-slate-100/50 hover:dark:bg-slate-800/50 hover:light:bg-slate-100 transition">
                        <span class="text-sm dark:text-slate-300 light:text-slate-700">{{ __('profile.change_password') ?? 'Change Password' }}</span>
                        <i class="fas fa-chevron-right text-slate-500"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-between p-3 rounded-xl dark:bg-red-500/10 light:bg-red-50/50 hover:dark:bg-red-500/20 hover:light:bg-red-50 transition">
                            <span class="text-sm text-red-400">{{ __('profile.logout') ?? 'Logout' }}</span>
                            <i class="fas fa-sign-out-alt text-red-400"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
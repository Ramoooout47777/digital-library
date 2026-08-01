{{-- resources/views/admin/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.dashboard')) - {{ 'Digital Library' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        * {
            font-family: 'Inter', 'Battambang', system-ui, sans-serif;
        }
        
        /* ============ SIDEBAR ============ */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 270px;
            transition: transform 0.3s ease, width 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .sidebar.collapsed .sidebar-icon {
            margin-right: 0;
        }
        .sidebar.collapsed .sidebar-link {
            justify-content: center;
            padding: 12px;
        }
        .sidebar.collapsed .sidebar-link span {
            display: none;
        }
        .sidebar.collapsed .sidebar-link i {
            font-size: 20px;
        }
        .sidebar.collapsed .brand-text {
            display: none;
        }
        .sidebar.collapsed .brand-icon {
            font-size: 24px;
        }
        .sidebar.collapsed .sidebar-footer {
            display: none;
        }
        
        /* ============ MAIN CONTENT ============ */
        .main-content {
            margin-left: 270px;
            min-height: 100vh;
            background: #f1f5f9;
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 70px;
        }
        
        /* ============ SIDEBAR LINKS ============ */
        .sidebar-link {
            transition: all 0.2s ease;
            border-radius: 10px;
            margin: 2px 8px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            position: relative;
        }
        .sidebar-link:hover {
            background: rgba(255,255,255,0.1);
            color: #ffffff;
            transform: translateX(4px);
        }
        .sidebar-link.active {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
            box-shadow: inset 3px 0 0 #3b82f6;
        }
        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }
        .sidebar-link .badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        /* ============ TOP BAR ============ */
        .topbar {
            background: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 24px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        /* ============ STAT CARDS ============ */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        
        /* ============ TABLE CARD ============ */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
            overflow: hidden;
        }
        
        /* ============ FORM INPUTS ============ */
        .form-input {
            @apply w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition;
        }
        .form-input.error {
            @apply border-red-500 focus:ring-red-500;
        }
        .form-label {
            @apply block text-sm font-medium text-gray-700 mb-1.5;
        }
        .form-error {
            @apply text-red-500 text-xs mt-1;
        }
        
        /* ============ BUTTONS ============ */
        .btn {
            @apply px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center justify-center gap-2;
        }
        .btn-primary {
            @apply bg-blue-500 hover:bg-blue-600 text-white;
        }
        .btn-success {
            @apply bg-green-500 hover:bg-green-600 text-white;
        }
        .btn-danger {
            @apply bg-red-500 hover:bg-red-600 text-white;
        }
        .btn-warning {
            @apply bg-yellow-500 hover:bg-yellow-600 text-white;
        }
        .btn-gray {
            @apply bg-gray-500 hover:bg-gray-600 text-white;
        }
        .btn-sm {
            @apply px-3 py-1.5 text-sm;
        }
        .btn-xs {
            @apply px-2 py-1 text-xs;
        }
        
        /* ============ TOAST / ALERT ============ */
        .alert {
            @apply px-4 py-3 rounded-lg mb-4 flex items-center justify-between;
            animation: slideDown 0.3s ease;
        }
        .alert-success {
            @apply bg-green-50 border-l-4 border-green-500 text-green-700;
        }
        .alert-error {
            @apply bg-red-50 border-l-4 border-red-500 text-red-700;
        }
        .alert-warning {
            @apply bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700;
        }
        .alert-info {
            @apply bg-blue-50 border-l-4 border-blue-500 text-blue-700;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* ============ RESPONSIVE ============ */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .main-content.expanded {
                margin-left: 0;
            }
            .topbar {
                padding: 10px 16px;
            }
            .topbar-title {
                font-size: 16px;
            }
        }
        
        /* ============ DROPDOWN ============ */
        .dropdown {
            position: relative;
        }
        .dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 8px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.04);
            min-width: 220px;
            padding: 6px;
            z-index: 50;
            display: none;
        }
        .dropdown-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }
        .dropdown-item {
            @apply flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm text-gray-700;
        }
        .dropdown-item i {
            @apply w-5 text-gray-400;
        }
        .dropdown-divider {
            @apply border-t border-gray-100 my-1;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* ============ LANGUAGE SWITCHER ============ */
        .lang-btn {
            @apply px-2.5 py-1 rounded-md text-xs font-medium transition;
        }
        .lang-btn:hover {
            @apply bg-gray-100;
        }
        .lang-btn.active {
            @apply bg-blue-500 text-white;
        }
        .lang-btn.active:hover {
            @apply bg-blue-600;
        }
        
        /* ============ SCROLLBAR ============ */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* ============ UTILITY ============ */
        .text-balance {
            text-wrap: balance;
        }
        .truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- ============================================================ -->
    <!-- SIDEBAR -->
    <!-- ============================================================ -->
    <aside class="sidebar bg-gray-900 text-white" id="sidebar">
        <!-- Brand -->
        <div class="p-4 border-b border-gray-800 flex items-center gap-3">
            <div class="brand-icon text-2xl text-blue-400">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="brand-text">
                <h1 class="text-lg font-bold">{{ __('admin.hero_badge') }}</h1>
                <p class="text-xs text-gray-400">{{ __('admin.dashboard_menu') }}</p>
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-3 px-2 pb-20">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.dashboard_menu') }}</span>
            </a>
            
            <!-- Books -->
            <a href="{{ route('admin.books.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}">
                <i class="fas fa-book sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.books_menu') }}</span>
            </a>
            
            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.categories_menu') }}</span>
            </a>
            
            <!-- Authors -->
            <a href="{{ route('admin.authors.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                <i class="fas fa-user-edit sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.authors_menu') }}</span>
            </a>
            
            <!-- Publishers -->
            <a href="{{ route('admin.publishers.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}">
                <i class="fas fa-building sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.publishers_menu') }}</span>
            </a>
            
            <!-- Orders -->
            <a href="{{ route('admin.orders.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.orders_menu') }}</span>
                @php
                    $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                @endphp
                @if($pendingOrders > 0)
                    <span class="badge">{{ $pendingOrders }}</span>
                @endif
            </a>
            
            <!-- Customers -->
            <a href="{{ route('admin.customers.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.customers_menu') }}</span>
            </a>
            
            <!-- Coupons -->
            <a href="{{ route('admin.coupons.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.coupons_menu') }}</span>
            </a>
            
            <!-- Banners -->
            <a href="{{ route('admin.banners.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="fas fa-image sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.banners_menu') }}</span>
            </a>
            
            <!-- Notifications -->
            <a href="{{ route('admin.notifications.index') }}" 
               class="sidebar-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.notifications_menu') }}</span>
                @php
                    $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
            </a>
            
            <!-- Settings -->
            <a href="{{ route('admin.settings') }}" 
               class="sidebar-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                <i class="fas fa-cog sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.settings_menu') }}</span>
            </a>
            
            <hr class="border-gray-800 my-3">
            
            <!-- View Website -->
            <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                <i class="fas fa-globe sidebar-icon"></i>
                <span class="sidebar-text">{{ __('admin.view_all') ?? 'ទស្សនា Website' }}</span>
            </a>
            
            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left">
                    <i class="fas fa-sign-out-alt sidebar-icon"></i>
                    <span class="sidebar-text">{{ __('admin.logout_menu') }}</span>
                </button>
            </form>
        </nav>
        
        <!-- Sidebar Footer -->
        <div class="sidebar-footer absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800 text-center">
            <p class="text-xs text-gray-500">v{{ config('app.version', '1.0.0') }}</p>
        </div>
    </aside>

    <!-- ============================================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================================ -->
    <main class="main-content" id="mainContent">
        
        <!-- Top Bar -->
        <nav class="topbar flex flex-wrap items-center justify-between gap-3">
            <!-- Left Side -->
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="text-gray-600 hover:text-gray-800 transition md:hidden">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <button id="sidebar-collapse" class="text-gray-400 hover:text-gray-600 transition hidden sm:block" title="Toggle Sidebar">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
                <h2 class="topbar-title text-xl font-semibold text-gray-800">@yield('page-title', __('admin.dashboard'))</h2>
            </div>
            
            <!-- Right Side -->
            <div class="flex items-center gap-3 flex-wrap">
                
                <!-- Language Switcher ប្រើ Pure HTML/CSS (មិនបាច់មាន Alpine.js) -->
            <details class="relative inline-block text-left dropdown-flags">
                
                <summary class="inline-flex justify-between items-center gap-x-2 rounded-lg bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-200 transition list-none cursor-pointer select-none">
                    @if(session('locale') == 'km')
                        <span class="w-5 h-3.5 bg-cover bg-center rounded-sm shadow-sm" style="background-image: url('https://flagcdn.com/kh.svg')"></span>
                        <span>កម្ពុជា</span>
                    @elseif(session('locale') == 'zh')
                        <span class="w-5 h-3.5 bg-cover bg-center rounded-sm shadow-sm" style="background-image: url('https://flagcdn.com/cn.svg')"></span>
                        <span>中文</span>
                    @else
                        <span class="w-5 h-3.5 bg-cover bg-center rounded-sm shadow-sm" style="background-image: url('https://flagcdn.com/gb.svg')"></span>
                        <span>EN</span>
                    @endif
                    
                    <svg class="-mr-1 h-5 w-5 text-gray-400 custom-arrow" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </summary>

            <!-- បញ្ជីភាសាសម្រាប់ជ្រើសរើស -->
            <div class="absolute right-0 z-10 mt-2 w-36 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                <div class="py-1">
                    <a href="{{ route('admin.switch-language', 'km') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ session('locale') == 'km' ? 'bg-blue-50 font-bold text-blue-600' : '' }}">
                        <span class="w-5 h-3.5 bg-cover bg-center rounded-sm" style="background-image: url('https://flagcdn.com/kh.svg')"></span>
                        <span>ខ្មែរ</span>
                    </a>
                    <a href="{{ route('admin.switch-language', 'en') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ session('locale') == 'en' ? 'bg-blue-50 font-bold text-blue-600' : '' }}">
                        <span class="w-5 h-3.5 bg-cover bg-center rounded-sm" style="background-image: url('https://flagcdn.com/gb.svg')"></span>
                        <span>English</span>
                    </a>
                    <a href="{{ route('admin.switch-language', 'zh') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ session('locale') == 'zh' ? 'bg-blue-50 font-bold text-blue-600' : '' }}">
                        <span class="w-5 h-3.5 bg-cover bg-center rounded-sm" style="background-image: url('https://flagcdn.com/cn.svg')"></span>
                        <span>中文</span>
                    </a>
                </div>
            </div>
        </details>

            <!-- លាក់ Icon ព្រួញលំនាំដើមរបស់ HTML <summary> ចេញ -->
            <style>
                .dropdown-flags summary::-webkit-details-marker { display: none; }
                .dropdown-flags summary { list-style: none; }
            </style>
                
                <!-- Notifications -->
                <div class="dropdown">
                    <button id="notification-toggle" class="text-gray-600 hover:text-gray-800 relative p-2 rounded-full hover:bg-gray-100 transition">
                        <i class="fas fa-bell text-xl"></i>
                        @php
                            $unreadCount = \App\Models\Notification::where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div id="notification-dropdown" class="dropdown-menu w-80">
                        <div class="flex items-center justify-between px-4 py-2 border-b">
                            <span class="font-semibold text-gray-800">{{ __('admin.notifications_menu') }}</span>
                            @if($unreadCount > 0)
                                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-500 hover:underline">
                                        {{ __('admin.mark_all_as_read') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @php
                                $notifications = \App\Models\Notification::latest()->limit(5)->get();
                            @endphp
                            @forelse($notifications as $notification)
                                <a href="#" class="dropdown-item {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800">{{ $notification->title }}</p>
                                        <p class="text-xs text-gray-500 truncate">{{ $notification->message }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notification->is_read)
                                        <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="p-6 text-center text-gray-400">
                                    <i class="fas fa-bell-slash text-3xl block mb-2"></i>
                                    {{ __('admin.no_notifications') }}
                                </div>
                            @endforelse
                        </div>
                        <div class="border-t p-2 text-center">
                            <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-500 hover:underline">
                                {{ __('admin.view_all') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- User Profile -->
                <div class="dropdown">
                    <button id="user-dropdown-toggle" class="flex items-center gap-3 p-1.5 rounded-lg hover:bg-gray-100 transition">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff&size=32' }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-8 h-8 rounded-full">
                        <span class="text-sm font-medium text-gray-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs hidden sm:block"></i>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div id="user-dropdown" class="dropdown-menu min-w-[200px]">
                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> {{ __('admin.profile_menu') }}
                            </a>
                            <a href="{{ route('admin.settings') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i> {{ __('admin.settings_menu') }}
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item w-full text-left text-red-600 hover:bg-red-50 cursor-pointer">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('admin.logout_menu') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        
        <!-- Page Content -->
        <div class="p-4 md:p-6">
            
            <!-- ============ ALERTS ============ -->
            @if(session('success'))
                <div class="alert alert-success">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-yellow-700 hover:text-yellow-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-blue-700 hover:text-blue-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-error">
                    <div>
                        <p class="font-semibold">{{ __('admin.error') }}</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
            
            <!-- ============ MAIN YIELD ============ -->
            @yield('content')
        </div>
    </main>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script>
        // ============================================================
        // SIDEBAR TOGGLE (Mobile)
        // ============================================================
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }
        
        // ============================================================
        // SIDEBAR COLLAPSE (Desktop)
        // ============================================================
        const collapseBtn = document.getElementById('sidebar-collapse');
        const mainContent = document.getElementById('mainContent');
        let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (collapseBtn) {
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
                collapseBtn.querySelector('i').classList.remove('fa-chevron-left');
                collapseBtn.querySelector('i').classList.add('fa-chevron-right');
            }
            
            collapseBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-chevron-left');
                icon.classList.toggle('fa-chevron-right');
                
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            });
        }
        
        // ============================================================
        // NOTIFICATION DROPDOWN
        // ============================================================
        const notifToggle = document.getElementById('notification-toggle');
        const notifDropdown = document.getElementById('notification-dropdown');
        
        if (notifToggle && notifDropdown) {
            notifToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (!notifDropdown.contains(e.target) && !notifToggle.contains(e.target)) {
                    notifDropdown.classList.remove('show');
                }
            });
        }
        
        // ============================================================
        // USER DROPDOWN
        // ============================================================
        const userToggle = document.getElementById('user-dropdown-toggle');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userToggle && userDropdown) {
            userToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });
            
            document.addEventListener('click', function(e) {
                if (!userDropdown.contains(e.target) && !userToggle.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }
        
        // ============================================================
        // AUTO-DISMISS ALERTS
        // ============================================================
        document.querySelectorAll('.alert').forEach(function(alert) {
            setTimeout(function() {
                alert.style.transition = 'opacity 0.5s, transform 0.5s';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(function() {
                    alert.remove();
                }, 500);
            }, 5000);
        });
        
        // ============================================================
        // CONFIRM DELETE
        // ============================================================
        document.querySelectorAll('[data-confirm]').forEach(function(element) {
            element.addEventListener('click', function(e) {
                const message = this.dataset.confirm || '{{ __("admin.confirm_delete") }}';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });
        
        // ============================================================
        // CLOSE SIDEBAR ON MOBILE WHEN CLICKING LINK
        // ============================================================
        document.querySelectorAll('.sidebar-link').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('open');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
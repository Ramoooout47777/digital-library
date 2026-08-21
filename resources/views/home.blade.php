{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — {{ __('home.welcome') ?? 'Digital Library' }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Khmer+OS&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        * {
            font-family: 'Inter', 'Khmer OS', system-ui, sans-serif;
        }

        /* ─── Scrollbar ─── */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        .dark ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #334155; }
        .light ::-webkit-scrollbar-track { background: #f1f5f9; }
        .light ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .light ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* ─── Theme Transitions ─── */
        .theme-transition,
        .theme-transition * {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─── Dark Mode ─── */
        .dark .cyber-bg {
            background: #0b1120;
            background-image: radial-gradient(ellipse at 15% 50%, rgba(56, 189, 248, 0.05) 0%, transparent 65%),
                              radial-gradient(ellipse at 85% 20%, rgba(168, 85, 247, 0.04) 0%, transparent 55%);
            min-height: 100vh;
        }
        .dark .neu-card {
            background: #0f172a;
            border-radius: 28px;
            box-shadow: 12px 12px 24px rgba(0, 0, 0, 0.7), -12px -12px 24px rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(51, 65, 85, 0.1);
        }
        .dark .neu-card-inset {
            background: #0f172a;
            border-radius: 28px;
            box-shadow: inset 8px 8px 16px rgba(0, 0, 0, 0.6), inset -8px -8px 16px rgba(30, 41, 59, 0.3);
        }
        .dark .neu-button {
            background: #0f172a;
            border-radius: 18px;
            box-shadow: 8px 8px 16px rgba(0, 0, 0, 0.6), -8px -8px 16px rgba(30, 41, 59, 0.4);
            color: #e2e8f0;
        }
        .dark .neu-button:hover {
            box-shadow: 4px 4px 8px rgba(0, 0, 0, 0.5), -4px -4px 8px rgba(30, 41, 59, 0.3);
            color: #ffffff;
        }
        .dark .neu-button-primary {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            box-shadow: 8px 8px 16px rgba(0, 0, 0, 0.6), -8px -8px 16px rgba(30, 41, 59, 0.4);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.1);
        }
        .dark .neu-button-primary:hover {
            color: #60d0fa;
            border-color: rgba(56, 189, 248, 0.3);
        }
        .dark .neu-book {
            background: #0f172a;
            border-radius: 24px;
            box-shadow: 8px 8px 16px rgba(0, 0, 0, 0.6), -8px -8px 16px rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(51, 65, 85, 0.08);
        }
        .dark .neu-book:hover {
            box-shadow: 12px 12px 24px rgba(0, 0, 0, 0.7), -12px -12px 24px rgba(30, 41, 59, 0.5);
            border-color: rgba(56, 189, 248, 0.15);
        }
        .dark .neu-input {
            background: #0f172a;
            border-radius: 18px;
            box-shadow: inset 6px 6px 12px rgba(0, 0, 0, 0.6), inset -6px -6px 12px rgba(30, 41, 59, 0.3);
            color: #e2e8f0;
        }
        .dark .neu-input:focus {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.5), inset -4px -4px 8px rgba(30, 41, 59, 0.2);
        }
        .dark .neu-input::placeholder {
            color: #475569;
        }
        .dark .neu-pill {
            background: #0f172a;
            border-radius: 100px;
            box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.5), -6px -6px 12px rgba(30, 41, 59, 0.3);
            color: #94a3b8;
        }
        .dark .neu-pill:hover {
            color: #e2e8f0;
        }
        .dark .neu-nav {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
            border-bottom: 1px solid rgba(51, 65, 85, 0.1);
        }
        .dark .lang-select {
            background-color: #1e293b;
            color: #e2e8f0;
            border: 1px solid #334155;
        }
        .dark .lang-select option {
            background-color: #1e293b;
            color: #e2e8f0;
        }
        .dark .lang-select:focus {
            border-color: #38bdf8;
            ring-color: #38bdf8;
        }
        .dark .mobile-menu {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(51, 65, 85, 0.1);
        }
        .dark .nav-link { color: #94a3b8; }
        .dark .nav-link:hover { color: #f1f5f9; }
        .dark .nav-link::after { background: linear-gradient(90deg, #38bdf8, #818cf8); }
        .dark .body-text { color: #94a3b8; }
        .dark .body-text-sm { color: #64748b; }
        .dark .stat-digit {
            background: linear-gradient(135deg, #e2e8f0, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .dark .stat-label { color: #475569; }
        .dark .neu-badge {
            background: #0f172a;
            border-radius: 100px;
            box-shadow: inset 3px 3px 6px rgba(0,0,0,0.6), inset -3px -3px 6px rgba(30,41,59,0.3);
        }
        .dark .admin-link {
            background: rgba(56, 189, 248, 0.06);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.08);
        }
        .dark .admin-link:hover {
            background: rgba(56, 189, 248, 0.12);
            border-color: rgba(56, 189, 248, 0.15);
        }
        .dark .profile-dropdown {
            background: #0f172a;
            border: 1px solid rgba(51, 65, 85, 0.1);
        }

        /* ─── Light Mode ─── */
        .light .cyber-bg {
            background: #f8fafc;
            background-image: radial-gradient(ellipse at 15% 50%, rgba(56, 189, 248, 0.08) 0%, transparent 65%),
                              radial-gradient(ellipse at 85% 20%, rgba(168, 85, 247, 0.06) 0%, transparent 55%);
            min-height: 100vh;
        }
        .light .neu-card {
            background: #e8ecf1;
            border-radius: 28px;
            box-shadow: 12px 12px 24px rgba(174, 184, 194, 0.6), -12px -12px 24px rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .light .neu-card-inset {
            background: #e8ecf1;
            border-radius: 28px;
            box-shadow: inset 8px 8px 16px rgba(174, 184, 194, 0.5), inset -8px -8px 16px rgba(255, 255, 255, 0.8);
        }
        .light .neu-button {
            background: #e8ecf1;
            border-radius: 18px;
            box-shadow: 8px 8px 16px rgba(174, 184, 194, 0.5), -8px -8px 16px rgba(255, 255, 255, 0.8);
            color: #1e293b;
        }
        .light .neu-button:hover {
            color: #0f172a;
        }
        .light .neu-button-primary {
            background: linear-gradient(135deg, #e8ecf1, #f0f4f9);
            box-shadow: 8px 8px 16px rgba(174, 184, 194, 0.5), -8px -8px 16px rgba(255, 255, 255, 0.8);
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.1);
        }
        .light .neu-button-primary:hover {
            color: #0284c7;
            border-color: rgba(14, 165, 233, 0.3);
        }
        .light .neu-book {
            background: #e8ecf1;
            border-radius: 24px;
            box-shadow: 8px 8px 16px rgba(174, 184, 194, 0.5), -8px -8px 16px rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .light .neu-book:hover {
            box-shadow: 12px 12px 24px rgba(174, 184, 194, 0.6), -12px -12px 24px rgba(255, 255, 255, 0.9);
            border-color: rgba(14, 165, 233, 0.15);
        }
        .light .neu-input {
            background: #e8ecf1;
            border-radius: 18px;
            box-shadow: inset 6px 6px 12px rgba(174, 184, 194, 0.5), inset -6px -6px 12px rgba(255, 255, 255, 0.8);
            color: #1e293b;
        }
        .light .neu-input:focus {
            box-shadow: inset 4px 4px 8px rgba(174, 184, 194, 0.5), inset -4px -4px 8px rgba(255, 255, 255, 0.8);
        }
        .light .neu-input::placeholder {
            color: #94a3b8;
        }
        .light .neu-pill {
            background: #e8ecf1;
            border-radius: 100px;
            box-shadow: 6px 6px 12px rgba(174, 184, 194, 0.5), -6px -6px 12px rgba(255, 255, 255, 0.8);
            color: #64748b;
        }
        .light .neu-pill:hover {
            color: #0f172a;
        }
        .light .neu-nav {
            background: rgba(232, 236, 241, 0.85);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(174, 184, 194, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .light .lang-select {
            background-color: #f1f5f9;
            color: #1e293b;
            border: 1px solid #d1d5db;
        }
        .light .lang-select option {
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .light .lang-select:focus {
            border-color: #0ea5e9;
            ring-color: #0ea5e9;
        }
        .light .mobile-menu {
            background: rgba(232, 236, 241, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .light .nav-link { color: #64748b; }
        .light .nav-link:hover { color: #0f172a; }
        .light .nav-link::after { background: linear-gradient(90deg, #0ea5e9, #818cf8); }
        .light .body-text { color: #475569; }
        .light .body-text-sm { color: #64748b; }
        .light .stat-digit {
            background: linear-gradient(135deg, #0f172a, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .light .stat-label { color: #94a3b8; }
        .light .neu-badge {
            background: #e8ecf1;
            border-radius: 100px;
            box-shadow: inset 3px 3px 6px rgba(174,184,194,0.5), inset -3px -3px 6px rgba(255,255,255,0.7);
        }
        .light .admin-link {
            background: rgba(14, 165, 233, 0.04);
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.06);
        }
        .light .admin-link:hover {
            background: rgba(14, 165, 233, 0.08);
        }
        .light .profile-dropdown {
            background: #e8ecf1;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* ─── Common Styles ─── */
        .neu-card, .neu-card-inset, .neu-book, .mobile-menu, .profile-dropdown {
            border-radius: 20px;
        }
        .neu-book { border-radius: 16px; }
        .neu-pill { border-radius: 100px; }

        .gradient-text-animated {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 35%, #a78bfa 65%, #38bdf8 100%);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-text 8s ease-in-out infinite;
        }
        @keyframes gradient-text {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .heading-xl {
            font-size: clamp(3rem, 7vw, 5.5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1.02;
        }
        .heading-lg {
            font-size: clamp(2.2rem, 4vw, 3.2rem);
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .heading-md {
            font-size: clamp(1.4rem, 2vw, 1.8rem);
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .stat-number {
            font-size: 3.2rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1.05;
        }

        .section-padding { padding: 6rem 0; }
        @media (max-width: 768px) { .section-padding { padding: 3.5rem 0; } }

        .hero-container {
            position: relative;
            overflow: hidden;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }
        .hero-container::before {
            content: '';
            position: absolute;
            top: -30%;
            left: -30%;
            width: 160%;
            height: 160%;
            background: radial-gradient(ellipse at 30% 50%, rgba(56, 189, 248, 0.04), transparent 60%),
                        radial-gradient(ellipse at 70% 20%, rgba(168, 85, 247, 0.03), transparent 50%);
            animation: blob 15s ease-in-out infinite;
            pointer-events: none;
        }
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }

        .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 8px 0;
            text-decoration: none;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link:hover::after { width: 100%; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-24px) rotate(1deg); }
        }
        .animate-float {
            animation: float 7s ease-in-out infinite;
        }

        @keyframes slide-down {
            from { opacity: 0; transform: scale(0.95) translateY(-10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .profile-dropdown {
            min-width: 220px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            transform-origin: top right;
            animation: slide-down 0.2s ease-out;
        }

        /* ─── Language Select Custom Styles ─── */
        .lang-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 2.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            height: 40px;
            min-width: 140px;
        }
        .lang-select:focus {
            outline: none;
            ring: 2px;
            ring-offset: 2px;
        }
        .lang-select-wrapper {
            position: relative;
            display: inline-block;
        }
        .lang-select-wrapper .lang-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 1rem;
        }
        .lang-select-wrapper .lang-arrow {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            transition: transform 0.3s ease;
        }
        .lang-select-wrapper:hover .lang-arrow {
            transform: translateY(-50%) rotate(180deg);
        }

        .neu-badge-free { color: #34d399; }
        .neu-badge-featured { color: #fbbf24; }
        .neu-badge-new { color: #38bdf8; }

        /* ─── Swiper Custom Styles ─── */
        .swiper-button-prev::after,
        .swiper-button-next::after {
            font-size: 20px !important;
            font-weight: 700;
        }
        .swiper-pagination-bullet {
            background: rgba(255,255,255,0.3) !important;
            opacity: 1 !important;
        }
        .swiper-pagination-bullet-active {
            background: #38bdf8 !important;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            z-index: 5;
            animation: pulse-glow 2s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 10px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 25px rgba(239, 68, 68, 0.5); }
        }

        /* ─── Book Card Hover Effect ─── */
        .book-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        .book-card .book-image {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .book-card:hover .book-image {
            transform: scale(1.08);
        }
    </style>
</head>
<body class="{{ session('theme', 'dark') }} theme-transition" id="app">

@php
    $currentLocale = app()->getLocale();
@endphp

<!-- ============================================================ -->
<!-- NAVIGATION -->
<!-- ============================================================ -->
<nav class="neu-nav fixed top-0 left-0 right-0 z-50 py-4 px-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-400 to-indigo-400 flex items-center justify-center shadow-xl shadow-cyan-500/10 group-hover:scale-105 transition">
                <i class="fas fa-book-open text-slate-900 text-lg"></i>
            </div>
            <span class="text-xl font-bold tracking-tight dark:text-slate-100 light:text-slate-800">{{ config('app.name') }}</span>
        </a>

        <!-- Desktop Nav -->
        <div class="hidden lg:flex items-center gap-8">
            <a href="{{ route('home') }}" class="nav-link active">{{ __('home.home') ?? 'Home' }}</a>
            <a href="#books" class="nav-link">{{ __('home.books') ?? 'Books' }}</a>
            <a href="#categories" class="nav-link">{{ __('home.categories') ?? 'Categories' }}</a>
            <a href="#about" class="nav-link">{{ __('home.about') ?? 'About' }}</a>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="admin-link hidden sm:flex">
                    <i class="fas fa-tachometer-alt text-xs"></i>
                    <span class="hidden md:inline">{{ __('home.dashboard') ?? 'Dashboard' }}</span>
                </a>
            @endauth

            <!-- ============================================================ -->
            <!-- CHAT ICON -->
            <!-- ============================================================ -->
            @auth
                <a href="{{ route('chat.index') }}" 
                   class="neu-button w-11 h-11 rounded-xl flex items-center justify-center text-sm p-0 flex-shrink-0 relative">
                    <i class="fas fa-comment-dots text-lg"></i>
                    @if(isset($unreadChatCount) && $unreadChatCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center font-bold">
                            {{ $unreadChatCount > 9 ? '9+' : $unreadChatCount }}
                        </span>
                    @endif
                </a>
            @endauth

            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="neu-button w-11 h-11 rounded-xl flex items-center justify-center text-sm p-0 flex-shrink-0">
                <i id="theme-icon" class="fas fa-moon text-lg"></i>
            </button>

            <!-- Language Select -->
            <div class="hidden lg:flex items-center gap-1">
                <div class="lang-select-wrapper">
                    <span class="lang-icon">🌐</span>
                    <select onchange="window.location.href = this.value;"
                            class="lang-select dark:lang-select light:lang-select pl-8 pr-8 py-1.5">
                        <option value="{{ route('switch-language', 'km') }}" {{ $currentLocale == 'km' ? 'selected' : '' }}>ខ្មែរ</option>
                        <option value="{{ route('switch-language', 'en') }}" {{ $currentLocale == 'en' ? 'selected' : '' }}>English</option>
                        <option value="{{ route('switch-language', 'zh') }}" {{ $currentLocale == 'zh' ? 'selected' : '' }}>中文</option>
                    </select>
                    <span class="lang-arrow text-gray-400 dark:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </span>
                </div>
            </div>

            @guest
                <a href="{{ route('login') }}" class="text-sm font-medium dark:text-slate-400 light:text-slate-600 hover:dark:text-slate-200 hover:light:text-slate-900 transition px-3 py-1.5 hidden sm:block">
                    {{ __('home.login') ?? 'Login' }}
                </a>
                <a href="{{ route('register') }}" class="neu-button-primary px-5 py-2.5 text-sm font-semibold rounded-xl hidden sm:block">
                    <i class="fas fa-user-plus text-xs"></i> {{ __('home.register') ?? 'Register' }}
                </a>
            @else
                <!-- Profile Dropdown -->
                <div class="relative">
                    <button onclick="toggleProfileDropdown()"
                            class="flex items-center gap-2 hover:opacity-80 transition px-2 py-1 rounded-xl hover:bg-slate-700/10">
                        <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff&size=36' }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-8 h-8 rounded-full border-2 border-cyan-500/30">
                        <span class="text-sm font-medium dark:text-slate-200 light:text-slate-800 hidden xl:block">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs dark:text-slate-400 light:text-slate-500 hidden xl:block"></i>
                    </button>

                    <div id="profileDropdown" class="profile-dropdown hidden absolute right-0 mt-2 neu-card p-2 z-50">
                        <div class="px-3 py-3 border-b dark:border-slate-800/40 light:border-slate-200/60">
                            <p class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs dark:text-slate-500 light:text-slate-500">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-700/10 transition text-sm">
                            <i class="fas fa-user text-cyan-400 w-5"></i> {{ __('home.my_profile') ?? 'Profile' }}
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-700/10 transition text-sm">
                            <i class="fas fa-shopping-bag text-cyan-400 w-5"></i> {{ __('home.my_orders') ?? 'Orders' }}
                        </a>
                        <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-700/10 transition text-sm">
                            <i class="fas fa-heart text-red-400 w-5"></i> {{ __('home.favorites') ?? 'Favorites' }}
                        </a>
                        <div class="border-t dark:border-slate-800/40 light:border-slate-200/60 mt-2 pt-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-500/10 transition text-sm text-red-400">
                                    <i class="fas fa-sign-out-alt w-5"></i> {{ __('home.logout') ?? 'Logout' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest

            <button id="mobile-toggle" class="lg:hidden dark:text-slate-400 light:text-slate-600 hover:dark:text-slate-200 hover:light:text-slate-900 transition p-2">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden mt-4 mobile-menu p-6 space-y-4">
        <a href="{{ route('home') }}" class="block dark:text-slate-300 light:text-slate-700 hover:text-cyan-400 transition font-medium text-lg">{{ __('home.home') ?? 'Home' }}</a>
        <a href="#books" class="block dark:text-slate-300 light:text-slate-700 hover:text-cyan-400 transition font-medium text-lg">{{ __('home.books') ?? 'Books' }}</a>
        <a href="#categories" class="block dark:text-slate-300 light:text-slate-700 hover:text-cyan-400 transition font-medium text-lg">{{ __('home.categories') ?? 'Categories' }}</a>
        <a href="#about" class="block dark:text-slate-300 light:text-slate-700 hover:text-cyan-400 transition font-medium text-lg">{{ __('home.about') ?? 'About' }}</a>

        <div class="pt-4 border-t dark:border-slate-800/40 light:border-slate-200/60">
            <div class="lang-select-wrapper w-full">
                <span class="lang-icon">🌐</span>
                <select onchange="window.location.href = this.value;"
                        class="lang-select w-full dark:lang-select light:lang-select pl-8 pr-8 py-2.5 text-base">
                    <option value="{{ route('switch-language', 'km') }}" {{ $currentLocale == 'km' ? 'selected' : '' }}>🇰🇭 ខ្មែរ</option>
                    <option value="{{ route('switch-language', 'en') }}" {{ $currentLocale == 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                    <option value="{{ route('switch-language', 'zh') }}" {{ $currentLocale == 'zh' ? 'selected' : '' }}>🇨🇳 中文</option>
                </select>
                <span class="lang-arrow text-gray-400 dark:text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </div>
        </div>

        @guest
            <div class="flex gap-3 pt-2 border-t dark:border-slate-800/40 light:border-slate-200/60">
                <a href="{{ route('login') }}" class="flex-1 text-center neu-button text-sm py-3">{{ __('home.login') ?? 'Login' }}</a>
                <a href="{{ route('register') }}" class="flex-1 text-center neu-button-primary text-sm py-3">{{ __('home.register') ?? 'Register' }}</a>
            </div>
        @else
            <div class="pt-2 border-t dark:border-slate-800/40 light:border-slate-200/60 space-y-3">
                <a href="{{ route('profile.index') }}" class="block text-center neu-button-primary text-sm py-3">
                    <i class="fas fa-user mr-2"></i> {{ __('home.my_profile') ?? 'Profile' }}
                </a>
                <a href="{{ route('orders.index') }}" class="block text-center neu-button text-sm py-3">
                    <i class="fas fa-shopping-bag mr-2"></i> {{ __('home.my_orders') ?? 'Orders' }}
                </a>
                <a href="{{ route('favorites.index') }}" class="block text-center neu-button text-sm py-3">
                    <i class="fas fa-heart mr-2"></i> {{ __('home.favorites') ?? 'Favorites' }}
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-center text-sm font-medium dark:text-slate-400 light:text-slate-600 hover:text-red-400 transition py-3">
                        <i class="fas fa-sign-out-alt mr-2"></i>{{ __('home.logout') ?? 'Logout' }}
                    </button>
                </form>
            </div>
        @endguest
    </div>
</nav>

<!-- ============================================================ -->
<!-- HERO SECTION WITH SWIPER -->
<!-- ============================================================ -->
<section class="hero-container section-padding" style="padding-top: 8rem;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Left Content -->
            <div class="space-y-8">
                <div class="inline-flex items-center gap-3 px-5 py-3 rounded-full neu-card-inset">
                    <span class="w-3 h-3 rounded-full bg-cyan-400 animate-pulse"></span>
                    <span class="text-sm font-medium text-cyan-400 tracking-[0.15em] uppercase">{{ __('home.hero_badge') ?? 'Digital Library' }}</span>
                </div>

                <h1 class="heading-xl dark:text-slate-100 light:text-slate-900">
                    {{ __('home.hero_title') ?? 'Discover. Read.' }}
                    <br>
                    <span class="gradient-text-animated">{{ __('home.hero_title_highlight') ?? 'Without Limits.' }}</span>
                </h1>

                <p class="text-lg dark:text-slate-400 light:text-slate-600 leading-relaxed max-w-lg">
                    {{ __('home.hero_subtitle') ?? 'A curated digital library with thousands of books. Read anywhere, anytime.' }}
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="#books" class="neu-button-primary px-10 py-4 text-base font-semibold rounded-xl flex items-center gap-3">
                        <i class="fas fa-search text-lg"></i> {{ __('home.explore_books') ?? 'Explore Books' }}
                    </a>
                    <a href="#categories" class="neu-button px-10 py-4 text-base font-semibold rounded-xl flex items-center gap-3">
                        <i class="fas fa-th-list text-lg"></i> {{ __('home.view_categories') ?? 'Categories' }}
                    </a>
                </div>

                <div class="flex items-center gap-12 pt-8 border-t dark:border-slate-800/40 light:border-slate-200/60">
                    <div>
                        <div class="stat-digit stat-number">{{ $stats['total_books'] ?? 0 }}+</div>
                        <div class="stat-label text-xs font-medium uppercase tracking-[0.15em]">{{ __('home.total_books') ?? 'Total Books' }}</div>
                    </div>
                    <div>
                        <div class="stat-digit stat-number">{{ $stats['total_users'] ?? 0 }}+</div>
                        <div class="stat-label text-xs font-medium uppercase tracking-[0.15em]">{{ __('home.total_users') ?? 'Readers' }}</div>
                    </div>
                    <div>
                        <div class="stat-digit stat-number">{{ $stats['total_authors'] ?? 0 }}+</div>
                        <div class="stat-label text-xs font-medium uppercase tracking-[0.15em]">{{ __('home.total_authors') ?? 'Authors' }}</div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Swiper Slider -->
            <div class="relative hidden lg:block animate-float">
                <div class="neu-card p-4">
                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3]">
                        @if(isset($banners) && $banners->count() > 0)
                            <div class="swiper heroSwiperFull w-full h-full group">
                                <div class="swiper-wrapper">
                                    @foreach($banners as $banner)
                                        <div class="swiper-slide relative">
                                            @if($banner->link)
                                                <a href="{{ $banner->link }}" class="block w-full h-full relative overflow-hidden">
                                            @else
                                                <div class="w-full h-full relative overflow-hidden">
                                            @endif
                                                <img src="{{ $banner->image ? asset('storage/' . $banner->image) : 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&h=600&fit=crop&q=80' }}"
                                                     alt="{{ $banner->title ?? 'Banner' }}"
                                                     class="w-full h-full object-cover transform transition-transform duration-[5000ms] hover:scale-110">

                                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>

                                                <div class="absolute bottom-0 left-0 right-0 p-8">
                                                    <div class="space-y-2">
                                                        @if($banner->title)
                                                            <h3 class="text-2xl font-bold text-white leading-tight">
                                                                {{ $banner->title }}
                                                            </h3>
                                                        @endif
                                                        @if($banner->description)
                                                            <p class="text-base text-slate-200 line-clamp-2 opacity-90 max-w-md">
                                                                {{ $banner->description }}
                                                            </p>
                                                        @endif
                                                        @if($banner->discount_percentage)
                                                            <span class="inline-block px-3 py-1 bg-red-500/80 text-white text-xs font-bold rounded-full">
                                                                -{{ $banner->discount_percentage }}% OFF
                                                            </span>
                                                        @endif
                                                        @if($banner->link)
                                                            <div class="pt-2">
                                                                <span class="inline-flex items-center gap-2 text-cyan-400 text-xs font-bold uppercase tracking-wider group/link">
                                                                    {{ __('home.learn_more') ?? 'Learn More' }}
                                                                    <i class="fas fa-arrow-right transform group-hover/link:translate-x-1 transition-transform"></i>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @if($banner->link)
                                                </a>
                                            @else
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="swiper-button-prev !text-white/50 after:!text-xl hover:!text-white transition opacity-0 group-hover:opacity-100"></div>
                                <div class="swiper-button-next !text-white/50 after:!text-xl hover:!text-white transition opacity-0 group-hover:opacity-100"></div>
                                <div class="swiper-pagination !bottom-6 !text-right !px-8"></div>
                            </div>
                        @else
                            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&h=600&fit=crop&q=80"
                                 alt="{{ __('home.hero_image_alt') ?? 'Reading' }}"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-cyan-500/5 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 neu-card-inset p-5 rounded-xl">
                                <p class="text-sm font-medium dark:text-slate-300 light:text-slate-700 leading-relaxed">
                                    <i class="fas fa-quote-left text-cyan-400 mr-3 opacity-60"></i>
                                    {{ __('home.hero_quote') ?? 'Reading is to the mind what exercise is to the body.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl animate-float pointer-events-none"></div>
                <div class="absolute -top-20 -left-20 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl animate-float pointer-events-none"></div>
            </div>
        </div>

        <!-- Mobile Swiper Slider -->
        <div class="lg:hidden w-full mt-8">
            @if(isset($banners) && $banners->count() > 0)
                <div class="swiper heroSwiper rounded-2xl overflow-hidden shadow-2xl border border-slate-700/50">
                    <div class="swiper-wrapper">
                        @foreach($banners as $banner)
                            <div class="swiper-slide relative aspect-[16/9]">
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" class="block w-full h-full">
                                @endif
                                <img src="{{ $banner->image ? asset('storage/' . $banner->image) : 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&h=600&fit=crop&q=80' }}"
                                     alt="{{ $banner->title ?? 'Banner' }}"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-lg font-bold text-white">{{ $banner->title ?? '' }}</h3>
                                    @if($banner->discount_percentage)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-red-500/80 text-white text-xs font-bold rounded-full">
                                            -{{ $banner->discount_percentage }}%
                                        </span>
                                    @endif
                                </div>
                                @if($banner->link)
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FEATURES SECTION -->
<!-- ============================================================ -->
<section class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-sm font-medium text-cyan-400 tracking-[0.15em] uppercase mb-3">Features</p>
            <h2 class="heading-lg dark:text-slate-100 light:text-slate-900">{{ __('home.why_choose_us') ?? 'Why Choose Us?' }}</h2>
            <p class="text-base dark:text-slate-400 light:text-slate-600 mt-3 max-w-lg mx-auto">{{ __('home.features_subtitle') ?? 'Designed for the modern reader.' }}</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="neu-card p-8 text-center">
                <div class="w-20 h-20 rounded-2xl neu-card-inset flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-open text-cyan-400 text-3xl"></i>
                </div>
                <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-base">{{ __('home.feature_books') ?? 'Vast Library' }}</h4>
                <p class="text-sm dark:text-slate-500 light:text-slate-500 mt-2 leading-relaxed">{{ __('home.feature_books_desc') ?? 'Thousands of books across genres.' }}</p>
            </div>

            <div class="neu-card p-8 text-center">
                <div class="w-20 h-20 rounded-2xl neu-card-inset flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-mobile-alt text-emerald-400 text-3xl"></i>
                </div>
                <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-base">{{ __('home.feature_read_anywhere') ?? 'Read Anywhere' }}</h4>
                <p class="text-sm dark:text-slate-500 light:text-slate-500 mt-2 leading-relaxed">{{ __('home.feature_read_anywhere_desc') ?? 'Any device, any location.' }}</p>
            </div>

            <div class="neu-card p-8 text-center">
                <div class="w-20 h-20 rounded-2xl neu-card-inset flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-download text-purple-400 text-3xl"></i>
                </div>
                <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-base">{{ __('home.feature_download') ?? 'Offline Ready' }}</h4>
                <p class="text-sm dark:text-slate-500 light:text-slate-500 mt-2 leading-relaxed">{{ __('home.feature_download_desc') ?? 'Download and read offline.' }}</p>
            </div>

            <div class="neu-card p-8 text-center">
                <div class="w-20 h-20 rounded-2xl neu-card-inset flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-amber-400 text-3xl"></i>
                </div>
                <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-base">{{ __('home.feature_quality') ?? 'Premium Quality' }}</h4>
                <p class="text-sm dark:text-slate-500 light:text-slate-500 mt-2 leading-relaxed">{{ __('home.feature_quality_desc') ?? 'Carefully curated content.' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- DISCOUNTED BOOKS SECTION -->
<!-- ============================================================ -->
@if(isset($discountedBooks) && $discountedBooks->count() > 0)
<section id="books" class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <p class="text-sm font-medium text-red-400 tracking-[0.15em] uppercase mb-2">Sale</p>
                <h2 class="heading-lg dark:text-slate-100 light:text-slate-900">
                    <i class="fas fa-tags text-red-400 mr-2"></i>
                    {{ __('home.discounted_books') ?? 'Discounted Books' }}
                </h2>
                <p class="text-base dark:text-slate-400 light:text-slate-600 mt-1">Grab these deals before they're gone</p>
            </div>
            <a href="{{ route('books.index', ['discount' => 'true']) }}" class="text-sm font-medium text-cyan-400 hover:text-cyan-300 transition flex items-center gap-2">
                {{ __('home.view_all') ?? 'View All' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($discountedBooks as $book)
                <div class="neu-book book-card group">
                    <div class="relative overflow-hidden rounded-t-2xl">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}"
                                     alt="{{ $book->title }}"
                                     class="book-image w-full aspect-[3/4] object-cover">
                            @else
                                <div class="book-image w-full aspect-[3/4] bg-slate-800/30 flex items-center justify-center">
                                    <i class="fas fa-book dark:text-slate-600 light:text-slate-400 text-5xl"></i>
                                </div>
                            @endif
                        </a>

                        <div class="discount-badge">
                            -{{ number_format($book->discount_percentage, 0) }}%
                        </div>

                        @auth
                            <button onclick="toggleFavorite({{ $book->id }}, this)"
                                    class="absolute top-3 left-3 w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg z-10">
                                <i class="fas fa-heart text-sm
                                    @if(auth()->user()->isFavorited($book->id))
                                        text-red-500
                                    @else
                                        text-white/60 hover:text-red-400
                                    @endif
                                    transition-colors duration-300">
                                </i>
                            </button>
                        @endauth
                    </div>

                    <div class="p-4">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate">{{ $book->title }}</h4>
                        </a>
                        <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1 truncate">{{ $book->author->name ?? 'N/A' }}</p>
                        <div class="flex items-center gap-2 mt-3">
                            <span class="text-sm font-bold text-cyan-400">
                                ${{ number_format($book->final_price, 2) }}
                            </span>
                            <span class="text-xs dark:text-slate-500 light:text-slate-400 line-through">
                                ${{ number_format($book->price, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============================================================ -->
<!-- POPULAR BOOKS SECTION -->
<!-- ============================================================ -->
<section id="books" class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <p class="text-sm font-medium text-cyan-400 tracking-[0.15em] uppercase mb-2">Collection</p>
                <h2 class="heading-lg dark:text-slate-100 light:text-slate-900">{{ __('home.popular_books') ?? 'Popular Books' }}</h2>
                <p class="text-base dark:text-slate-400 light:text-slate-600 mt-1">Discover the most loved books by our readers</p>
            </div>
            <a href="{{ route('books.index') }}" class="text-sm font-medium text-cyan-400 hover:text-cyan-300 transition flex items-center gap-2">
                {{ __('home.view_all') ?? 'View All' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
            @forelse($popularBooks ?? [] as $index => $book)
                <div class="neu-book book-card group">
                    <div class="relative overflow-hidden rounded-t-2xl">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}"
                                     alt="{{ $book->title }}"
                                     class="book-image w-full aspect-[3/4] object-cover">
                            @else
                                <div class="book-image w-full aspect-[3/4] bg-slate-800/30 flex items-center justify-center">
                                    <i class="fas fa-book dark:text-slate-600 light:text-slate-400 text-5xl"></i>
                                </div>
                            @endif
                        </a>

                        @auth
                            <button onclick="toggleFavorite({{ $book->id }}, this)"
                                    class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg z-10"
                                    title="{{ auth()->user()->isFavorited($book->id) ? 'Remove from favorites' : 'Add to favorites' }}">
                                <i class="fas fa-heart text-sm
                                    @if(auth()->user()->isFavorited($book->id))
                                        text-red-500
                                    @else
                                        text-white/60 hover:text-red-400
                                    @endif
                                    transition-colors duration-300">
                                </i>
                            </button>
                        @endauth

                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($index < 2)
                                <span class="neu-badge neu-badge-featured"><i class="fas fa-crown mr-1"></i> Featured</span>
                            @elseif($book->is_free)
                                <span class="neu-badge neu-badge-free"><i class="fas fa-gift mr-1"></i> Free</span>
                            @elseif($book->created_at->diffInDays(now()) < 7)
                                <span class="neu-badge neu-badge-new"><i class="fas fa-sparkles mr-1"></i> New</span>
                            @endif
                        </div>
                    </div>

                    <div class="p-4">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate">{{ $book->title }}</h4>
                        </a>
                        <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1 truncate">{{ $book->author->name ?? 'N/A' }}</p>
                        <div class="flex justify-between items-center mt-3">
                            @if($book->is_free)
                                <span class="text-emerald-400 font-bold text-sm">{{ __('home.free') ?? 'Free' }}</span>
                            @else
                                <span class="text-cyan-400 font-bold text-sm">${{ number_format($book->final_price, 2) }}</span>
                            @endif
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($book->average_rating))
                                            <i class="fas fa-star text-amber-400/80 text-[10px]"></i>
                                        @else
                                            <i class="far fa-star dark:text-slate-600 light:text-slate-300 text-[10px]"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-[10px] font-medium dark:text-slate-500 light:text-slate-500">{{ number_format($book->average_rating, 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-book-open text-6xl dark:text-slate-700 light:text-slate-300 block mb-4"></i>
                    <p class="dark:text-slate-500 light:text-slate-500 text-lg">{{ __('home.no_books') ?? 'No books available.' }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CATEGORIES SECTION -->
<!-- ============================================================ -->
<section id="categories" class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <p class="text-sm font-medium text-cyan-400 tracking-[0.15em] uppercase mb-2">Browse</p>
                <h2 class="heading-lg dark:text-slate-100 light:text-slate-900">{{ __('home.categories') ?? 'Categories' }}</h2>
                <p class="text-base dark:text-slate-400 light:text-slate-600 mt-1">Find your next read by category</p>
            </div>
            <a href="{{ route('categories.index') }}" class="text-sm font-medium text-cyan-400 hover:text-cyan-300 transition flex items-center gap-2">
                {{ __('home.view_all') ?? 'View All' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
            @forelse($categories ?? [] as $index => $category)
                <a href="{{ route('categories.show', $category) }}"
                   class="neu-card p-5 text-center transition-all duration-300 hover:scale-105">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                             alt="{{ $category->name }}"
                             class="w-20 h-20 rounded-full object-cover mx-auto border-2 border-cyan-500/20">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500 to-indigo-500 flex items-center justify-center mx-auto">
                            <i class="fas fa-tag text-white text-3xl"></i>
                        </div>
                    @endif
                    <h4 class="font-medium dark:text-slate-200 light:text-slate-800 text-sm mt-3 truncate">{{ $category->name }}</h4>
                    <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1">
                        {{ $category->books_count ?? 0 }} {{ __('home.books') ?? 'Books' }}
                    </p>
                </a>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-tags text-5xl dark:text-slate-700 light:text-slate-300 block mb-4"></i>
                    <p class="dark:text-slate-500 light:text-slate-500 text-lg">{{ __('home.no_categories') ?? 'No categories available.' }}</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FREE BOOKS SECTION -->
<!-- ============================================================ -->
@if(isset($freeBooks) && $freeBooks->count() > 0)
<section class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12">
            <div>
                <p class="text-sm font-medium text-emerald-400 tracking-[0.15em] uppercase mb-2">Free</p>
                <h2 class="heading-lg dark:text-slate-100 light:text-slate-900">{{ __('home.free_books') ?? 'Free Books' }}</h2>
                <p class="text-base dark:text-slate-400 light:text-slate-600 mt-1">Read without spending a penny</p>
            </div>
            <a href="{{ route('books.index', ['free' => 1]) }}" class="text-sm font-medium text-emerald-400 hover:text-emerald-300 transition flex items-center gap-2">
                {{ __('home.view_all') ?? 'View All' }}
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($freeBooks as $index => $book)
                <div class="neu-book book-card group border-emerald-500/10">
                    <div class="relative overflow-hidden rounded-t-2xl">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}"
                                     alt="{{ $book->title }}"
                                     class="book-image w-full aspect-[3/4] object-cover">
                            @else
                                <div class="book-image w-full aspect-[3/4] bg-slate-800/30 flex items-center justify-center">
                                    <i class="fas fa-book dark:text-slate-600 light:text-slate-400 text-5xl"></i>
                                </div>
                            @endif
                        </a>

                        @auth
                            <button onclick="toggleFavorite({{ $book->id }}, this)"
                                    class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/40 backdrop-blur-sm hover:bg-black/60 flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg z-10">
                                <i class="fas fa-heart text-sm
                                    @if(auth()->user()->isFavorited($book->id))
                                        text-red-500
                                    @else
                                        text-white/60 hover:text-red-400
                                    @endif
                                    transition-colors duration-300">
                                </i>
                            </button>
                        @endauth

                        <div class="absolute top-3 left-3">
                            <span class="neu-badge neu-badge-free"><i class="fas fa-gift mr-1"></i> Free</span>
                        </div>
                    </div>

                    <div class="p-4">
                        <a href="{{ route('books.show', $book) }}" class="block">
                            <h4 class="font-semibold dark:text-slate-200 light:text-slate-800 text-sm truncate">{{ $book->title }}</h4>
                        </a>
                        <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1 truncate">{{ $book->author->name ?? 'N/A' }}</p>
                        <div class="flex justify-between items-center mt-3">
                            <span class="text-emerald-400 font-bold text-sm">{{ __('home.free') ?? 'Free' }}</span>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-download dark:text-slate-500 light:text-slate-400 text-xs"></i>
                                <span class="text-xs dark:text-slate-500 light:text-slate-500">{{ number_format($book->downloads_count ?? 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ============================================================ -->
<!-- ABOUT SECTION -->
<!-- ============================================================ -->
<section id="about" class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-sm font-medium text-cyan-400 tracking-[0.15em] uppercase mb-3">About</p>
                <h2 class="heading-lg dark:text-slate-100 light:text-slate-900">{{ __('home.about_title') ?? 'About Our Digital Library' }}</h2>
                <p class="text-base dark:text-slate-400 light:text-slate-600 mt-4 leading-relaxed">
                    {{ __('home.about_desc') ?? 'A modern digital library built for the next generation of readers. Access thousands of books from anywhere.' }}
                </p>
                <ul class="space-y-4 mt-8">
                    <li class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-xl neu-card-inset flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-cyan-400 text-sm"></i>
                        </span>
                        <span class="text-base dark:text-slate-400 light:text-slate-600">{{ __('home.about_point1') ?? 'Thousands of books from renowned authors.' }}</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-xl neu-card-inset flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-cyan-400 text-sm"></i>
                        </span>
                        <span class="text-base dark:text-slate-400 light:text-slate-600">{{ __('home.about_point2') ?? 'Read on any device — phone, tablet, or desktop.' }}</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-xl neu-card-inset flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-cyan-400 text-sm"></i>
                        </span>
                        <span class="text-base dark:text-slate-400 light:text-slate-600">{{ __('home.about_point3') ?? 'Download books for offline reading.' }}</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="w-8 h-8 rounded-xl neu-card-inset flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-check text-cyan-400 text-sm"></i>
                        </span>
                        <span class="text-base dark:text-slate-400 light:text-slate-600">{{ __('home.about_point4') ?? 'Affordable pricing with free books available.' }}</span>
                    </li>
                </ul>
            </div>

            <div class="neu-card p-3 relative">
                <div class="rounded-2xl overflow-hidden aspect-[4/3]">
                    <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=800&h=600&fit=crop&q=80"
                         alt="{{ __('home.about_image_alt') ?? 'Library' }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="absolute -bottom-4 -right-4 neu-card-inset px-5 py-3 rounded-xl">
                    <span class="text-sm font-semibold dark:text-slate-300 light:text-slate-700">
                        <i class="fas fa-database text-cyan-400 mr-2"></i>
                        {{ $stats['total_books'] ?? 0 }}+ {{ __('home.total_books') ?? 'Books' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- STATISTICS BANNER -->
<!-- ============================================================ -->
<section class="py-20 border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="neu-card p-12 md:p-16">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-10 text-center">
                <div>
                    <div class="stat-number text-4xl md:text-5xl">{{ $stats['total_books'] ?? 0 }}</div>
                    <div class="text-sm font-medium uppercase tracking-[0.15em] dark:text-slate-500 light:text-slate-500 mt-2">{{ __('home.total_books') ?? 'Books' }}</div>
                </div>
                <div>
                    <div class="stat-number text-4xl md:text-5xl">{{ $stats['total_users'] ?? 0 }}</div>
                    <div class="text-sm font-medium uppercase tracking-[0.15em] dark:text-slate-500 light:text-slate-500 mt-2">{{ __('home.total_users') ?? 'Readers' }}</div>
                </div>
                <div>
                    <div class="stat-number text-4xl md:text-5xl">{{ $stats['total_authors'] ?? 0 }}</div>
                    <div class="text-sm font-medium uppercase tracking-[0.15em] dark:text-slate-500 light:text-slate-500 mt-2">{{ __('home.total_authors') ?? 'Authors' }}</div>
                </div>
                <div>
                    <div class="stat-number text-4xl md:text-5xl">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="text-sm font-medium uppercase tracking-[0.15em] dark:text-slate-500 light:text-slate-500 mt-2">{{ __('home.total_orders') ?? 'Orders' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- NEWSLETTER SECTION -->
<!-- ============================================================ -->
<section id="contact" class="section-padding border-t dark:border-slate-800/40 light:border-slate-200/60">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="neu-card p-10 md:p-14 text-center">
            <div class="w-20 h-20 rounded-2xl neu-card-inset flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-paper-plane text-cyan-400 text-3xl"></i>
            </div>
            <h3 class="heading-md dark:text-slate-100 light:text-slate-900">{{ __('home.newsletter_title') ?? 'Stay in the Loop' }}</h3>
            <p class="text-base dark:text-slate-400 light:text-slate-600 mt-3 max-w-lg mx-auto">
                {{ __('home.newsletter_subtitle') ?? 'Get the latest book releases and exclusive offers.' }}
            </p>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="mt-8 flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                @csrf
                <input type="email" name="email" placeholder="{{ __('home.enter_email') ?? 'Enter your email' }}"
                       class="neu-input flex-1 text-base" required>
                <button type="submit" class="neu-button-primary px-8 py-4 text-base font-semibold whitespace-nowrap rounded-xl">
                    <i class="fas fa-paper-plane mr-2"></i> {{ __('home.subscribe') ?? 'Subscribe' }}
                </button>
            </form>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FOOTER -->
<!-- ============================================================ -->
<footer class="border-t dark:border-slate-800/40 light:border-slate-200/60 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
            <div>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-indigo-400 flex items-center justify-center">
                        <i class="fas fa-book-open text-slate-900 text-sm"></i>
                    </div>
                    <span class="text-base font-bold dark:text-slate-100 light:text-slate-800">{{ config('app.name') }}</span>
                </div>
                <p class="text-sm dark:text-slate-500 light:text-slate-500 leading-relaxed max-w-xs">
                    {{ __('home.footer_desc') ?? 'A modern digital library for the next generation of readers.' }}
                </p>
                <div class="flex gap-4 mt-6">
                    <a href="#" class="neu-button w-12 h-12 rounded-xl flex items-center justify-center"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="#" class="neu-button w-12 h-12 rounded-xl flex items-center justify-center"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="neu-button w-12 h-12 rounded-xl flex items-center justify-center"><i class="fab fa-github text-lg"></i></a>
                    <a href="#" class="neu-button w-12 h-12 rounded-xl flex items-center justify-center"><i class="fab fa-discord text-lg"></i></a>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-semibold dark:text-slate-400 light:text-slate-600 uppercase tracking-wider mb-5">{{ __('home.quick_links') ?? 'Quick Links' }}</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.home') ?? 'Home' }}</a></li>
                    <li><a href="#books" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.books') ?? 'Books' }}</a></li>
                    <li><a href="#categories" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.categories') ?? 'Categories' }}</a></li>
                    <li><a href="#about" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.about') ?? 'About' }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold dark:text-slate-400 light:text-slate-600 uppercase tracking-wider mb-5">{{ __('home.support') ?? 'Support' }}</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.faq') ?? 'FAQ' }}</a></li>
                    <li><a href="#" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.contact') ?? 'Contact' }}</a></li>
                    <li><a href="#" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.privacy_policy') ?? 'Privacy' }}</a></li>
                    <li><a href="#" class="dark:text-slate-500 light:text-slate-500 hover:dark:text-slate-300 hover:light:text-slate-800 transition">{{ __('home.terms') ?? 'Terms' }}</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-semibold dark:text-slate-400 light:text-slate-600 uppercase tracking-wider mb-5">{{ __('home.contact_info') ?? 'Contact' }}</h4>
                <ul class="space-y-3 text-sm dark:text-slate-500 light:text-slate-500">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-cyan-400/60 text-sm mt-0.5"></i>
                        <span>{{ $settings['address'] ?? 'Phnom Penh, Cambodia' }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-envelope text-cyan-400/60 text-sm mt-0.5"></i>
                        <span>{{ $settings['contact_email'] ?? 'info@bookstore.com' }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-phone text-cyan-400/60 text-sm mt-0.5"></i>
                        <span>{{ $settings['contact_phone'] ?? '+855 70 771 359' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t dark:border-slate-800/40 light:border-slate-200/60 text-center text-sm dark:text-slate-500 light:text-slate-500">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('home.all_rights_reserved') ?? 'All rights reserved.' }}</p>
        </div>
    </div>
</footer>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // ─── Theme Toggle ───
    function toggleTheme() {
        const html = document.documentElement;
        const body = document.getElementById('app');
        const icon = document.getElementById('theme-icon');

        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            body.classList.remove('dark');
            body.classList.add('light');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            localStorage.setItem('theme', 'light');
        } else {
            html.classList.add('dark');
            body.classList.remove('light');
            body.classList.add('dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            localStorage.setItem('theme', 'dark');
        }
    }

    // ─── Load Theme from LocalStorage ───
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        const html = document.documentElement;
        const body = document.getElementById('app');
        const icon = document.getElementById('theme-icon');

        if (savedTheme === 'light') {
            html.classList.remove('dark');
            body.classList.remove('dark');
            body.classList.add('light');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        } else {
            html.classList.add('dark');
            body.classList.remove('light');
            body.classList.add('dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
        }

        // ─── Swiper Initializations ───
        if (typeof Swiper !== 'undefined') {
            // Desktop Slider
            new Swiper('.heroSwiperFull', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function (index, className) {
                        return '<span class="' + className + ' !w-2 !h-2 !rounded-full !bg-slate-400/50 transition-all duration-300"></span>';
                    },
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                on: {
                    init: function () {
                        updateActiveBullet(this);
                    },
                    slideChange: function () {
                        updateActiveBullet(this);
                    }
                }
            });

            // Mobile Slider
            new Swiper('.heroSwiper', {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        }
    });

    function updateActiveBullet(swiper) {
        const bullets = swiper.pagination.bullets;
        if (!bullets) return;

        for (let i = 0; i < bullets.length; i++) {
            bullets[i].classList.remove('!w-6', '!bg-cyan-400');
            if (i === swiper.realIndex) {
                bullets[i].classList.add('!w-6', '!bg-cyan-400');
            }
        }
    }

    // ─── Profile Dropdown ───
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('profileDropdown');
        const button = event.target.closest('button');
        if (button && button.onclick && button.onclick.toString().indexOf('toggleProfileDropdown') !== -1) {
            return;
        }
        if (dropdown && !dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });

    // ─── Mobile Menu ───
    document.getElementById('mobile-toggle')?.addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });

    // ─── Smooth Scroll ───
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                document.getElementById('mobile-menu')?.classList.add('hidden');
            }
        });
    });

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('mobile-menu');
        const toggle = document.getElementById('mobile-toggle');
        if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !toggle.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });

    // ─── Favorite Toggle ───
    function toggleFavorite(bookId, button) {
        const icon = button.querySelector('i');
        const isFavorited = icon.classList.contains('text-red-500');

        if (isFavorited) {
            icon.classList.remove('text-red-500');
            icon.classList.add('text-white/60');
        } else {
            icon.classList.remove('text-white/60');
            icon.classList.add('text-red-500');
        }

        fetch('{{ route("favorites.toggle") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ book_id: bookId })
        })
        .then(response => response.json())
        .catch(error => console.error('Error:', error));
    }
</script>

</body>
</html>
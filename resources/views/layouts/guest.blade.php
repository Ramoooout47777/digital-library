{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800;14..32,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Khmer+OS&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', 'Khmer OS', system-ui, sans-serif;
        }
        
        /* ─── Dark Mode ─── */
        .dark .cyber-bg {
            background: #0b1120;
            background-image: 
                radial-gradient(ellipse at 15% 50%, rgba(56, 189, 248, 0.05) 0%, transparent 65%),
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
            padding: 12px 24px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
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
            padding: 12px 24px;
            border-radius: 18px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .dark .neu-button-primary:hover {
            color: #60d0fa;
            border-color: rgba(56, 189, 248, 0.3);
        }
        .dark .neu-input {
            background: #0f172a;
            border-radius: 18px;
            box-shadow: inset 6px 6px 12px rgba(0, 0, 0, 0.6), inset -6px -6px 12px rgba(30, 41, 59, 0.3);
            color: #e2e8f0;
            padding: 14px 20px;
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
        }
        .dark .neu-input:focus {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.5), inset -4px -4px 8px rgba(30, 41, 59, 0.2);
        }
        .dark .neu-input::placeholder {
            color: #475569;
        }
        .dark .neu-input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #475569;
        }
        .dark .neu-input-with-icon {
            padding-left: 48px;
        }
        .dark .neu-divider {
            border-color: rgba(51, 65, 85, 0.3);
        }
        .dark .neu-label {
            color: #94a3b8;
        }
        .dark .neu-link {
            color: #38bdf8;
        }
        .dark .neu-link:hover {
            color: #60d0fa;
        }
        .dark .neu-error {
            color: #f87171;
        }
        
        /* ─── Light Mode ─── */
        .light .cyber-bg {
            background: #f8fafc;
            background-image: 
                radial-gradient(ellipse at 15% 50%, rgba(56, 189, 248, 0.08) 0%, transparent 65%),
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
            padding: 12px 24px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }
        .light .neu-button:hover {
            color: #0f172a;
        }
        .light .neu-button-primary {
            background: linear-gradient(135deg, #e8ecf1, #f0f4f9);
            box-shadow: 8px 8px 16px rgba(174, 184, 194, 0.5), -8px -8px 16px rgba(255, 255, 255, 0.8);
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.1);
            padding: 12px 24px;
            border-radius: 18px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .light .neu-button-primary:hover {
            color: #0284c7;
            border-color: rgba(14, 165, 233, 0.3);
        }
        .light .neu-input {
            background: #e8ecf1;
            border-radius: 18px;
            box-shadow: inset 6px 6px 12px rgba(174, 184, 194, 0.5), inset -6px -6px 12px rgba(255, 255, 255, 0.8);
            color: #1e293b;
            padding: 14px 20px;
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
        }
        .light .neu-input:focus {
            box-shadow: inset 4px 4px 8px rgba(174, 184, 194, 0.5), inset -4px -4px 8px rgba(255, 255, 255, 0.8);
        }
        .light .neu-input::placeholder {
            color: #94a3b8;
        }
        .light .neu-input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .light .neu-input-with-icon {
            padding-left: 48px;
        }
        .light .neu-divider {
            border-color: rgba(203, 213, 225, 0.4);
        }
        .light .neu-label {
            color: #64748b;
        }
        .light .neu-link {
            color: #0ea5e9;
        }
        .light .neu-link:hover {
            color: #0284c7;
        }
        .light .neu-error {
            color: #ef4444;
        }
        
        /* ─── Common Styles ─── */
        .neu-card, .neu-card-inset {
            border-radius: 20px;
        }
        
        .theme-transition,
        .theme-transition * {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* ─── Keyframe Animations ─── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.05); }
            50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.15); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }
        
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-float { animation: float 4s ease-in-out infinite; }
        .animate-pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
        .gradient-text-animated {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 35%, #a78bfa 65%, #38bdf8 100%);
            background-size: 300% 300%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 8s ease-in-out infinite;
        }
        
        .login-container {
            max-width: 420px;
            width: 100%;
        }
        
        .login-icon {
            width: 72px;
            height: 72px;
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto;
        }
        .dark .login-icon {
            background: #0f172a;
            box-shadow: 8px 8px 16px rgba(0,0,0,0.6), -8px -8px 16px rgba(30,41,59,0.4);
        }
        .light .login-icon {
            background: #e8ecf1;
            box-shadow: 8px 8px 16px rgba(174,184,194,0.5), -8px -8px 16px rgba(255,255,255,0.8);
        }
        
        .lang-pill {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 100px;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
        }
        .dark .lang-pill {
            color: #64748b;
            border: 1px solid rgba(51, 65, 85, 0.2);
        }
        .dark .lang-pill:hover {
            border-color: rgba(56, 189, 248, 0.3);
            color: #e2e8f0;
        }
        .dark .lang-pill.active {
            background: rgba(56, 189, 248, 0.08);
            border-color: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
        }
        .light .lang-pill {
            color: #94a3b8;
            border: 1px solid rgba(203, 213, 225, 0.3);
        }
        .light .lang-pill:hover {
            border-color: rgba(14, 165, 233, 0.3);
            color: #0f172a;
        }
        .light .lang-pill.active {
            background: rgba(14, 165, 233, 0.06);
            border-color: rgba(14, 165, 233, 0.2);
            color: #0ea5e9;
        }
        
        .section-padding { padding: 5rem 0; }
        @media (max-width: 768px) { .section-padding { padding: 3rem 0; } }
        
        .input-error {
            border-color: #ef4444 !important;
        }
        .dark .input-error {
            border-color: #f87171 !important;
        }
        .input-error:focus {
            ring-color: #ef4444 !important;
        }
        .dark .input-error:focus {
            ring-color: #f87171 !important;
        }
        
        .error-message {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .dark .error-message {
            color: #f87171;
        }
        .light .error-message {
            color: #ef4444;
        }
    </style>
    
    @stack('styles')
</head>
<body class="{{ session('theme', 'dark') }} theme-transition" id="app">

<!-- ============================================================ -->
<!-- NAVIGATION -->
<!-- ============================================================ -->
<nav class="neu-card fixed top-0 left-0 right-0 z-50 py-3 px-4 rounded-none border-b dark:border-slate-700/50 light:border-slate-200/50">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-cyan-400 to-indigo-400 flex items-center justify-center shadow-xl shadow-cyan-500/10 group-hover:scale-105 transition">
                <i class="fas fa-book-open text-slate-900 text-sm"></i>
            </div>
            <span class="text-lg font-bold tracking-tight dark:text-slate-100 light:text-slate-800">{{ config('app.name') }}</span>
        </a>
        
        <!-- Right Side -->
        <div class="flex items-center gap-3">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="neu-button w-10 h-10 rounded-xl flex items-center justify-center text-sm p-0">
                <i id="theme-icon" class="fas fa-moon text-lg"></i>
            </button>
            
            <!-- Language Switcher -->
            <div class="flex items-center gap-1">
                <a href="{{ route('switch-language', 'km') }}" 
                   class="lang-pill {{ app()->getLocale() == 'km' ? 'active' : '' }}"
                   onclick="event.preventDefault(); document.getElementById('switch-form-km').submit();">ខ្មែរ</a>
                <form id="switch-form-km" action="{{ route('switch-language', 'km') }}" method="GET" class="hidden"></form>
                
                <a href="{{ route('switch-language', 'en') }}" 
                   class="lang-pill {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                   onclick="event.preventDefault(); document.getElementById('switch-form-en').submit();">EN</a>
                <form id="switch-form-en" action="{{ route('switch-language', 'en') }}" method="GET" class="hidden"></form>
                
                <a href="{{ route('switch-language', 'zh') }}" 
                   class="lang-pill {{ app()->getLocale() == 'zh' ? 'active' : '' }}"
                   onclick="event.preventDefault(); document.getElementById('switch-form-zh').submit();">中文</a>
                <form id="switch-form-zh" action="{{ route('switch-language', 'zh') }}" method="GET" class="hidden"></form>
            </div>
            
            @guest
                @if(request()->routeIs('login'))
                    <a href="{{ route('register') }}" class="neu-button-primary px-5 py-2 text-sm font-semibold rounded-xl">
                        <i class="fas fa-user-plus text-xs"></i> {{ __('auth.register') ?? 'Register' }}
                    </a>
                @elseif(request()->routeIs('register'))
                    <a href="{{ route('login') }}" class="neu-button-primary px-5 py-2 text-sm font-semibold rounded-xl">
                        <i class="fas fa-sign-in-alt text-xs"></i> {{ __('auth.login') ?? 'Login' }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium dark:text-slate-400 light:text-slate-600 hover:dark:text-slate-200 hover:light:text-slate-900 transition px-3 py-1.5">
                        {{ __('auth.login') ?? 'Login' }}
                    </a>
                    <a href="{{ route('register') }}" class="neu-button-primary px-5 py-2 text-sm font-semibold rounded-xl">
                        <i class="fas fa-user-plus text-xs"></i> {{ __('auth.register') ?? 'Register' }}
                    </a>
                @endif
            @else
                <a href="{{ route('dashboard') }}" class="neu-button-primary px-5 py-2 text-sm font-semibold rounded-xl">
                    <i class="fas fa-tachometer-alt text-xs"></i> {{ __('auth.dashboard') ?? 'Dashboard' }}
                </a>
            @endguest
        </div>
    </div>
</nav>

<!-- ============================================================ -->
<!-- MAIN CONTENT -->
<!-- ============================================================ -->
<main class="pt-20 cyber-bg">
    <div class="container mx-auto px-4 py-4 min-h-screen flex items-center justify-center">
        <div class="login-container">
            @if(session('status'))
                <div class="neu-card p-4 mb-4 border-l-4 border-emerald-500">
                    <p class="text-emerald-400 text-sm">{{ session('status') }}</p>
                </div>
            @endif
            
            @if($errors->any())
                <div class="neu-card p-4 mb-4 border-l-4 border-red-500">
                    @foreach($errors->all() as $error)
                        <p class="text-red-400 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
</main>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
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
    });
</script>

@stack('scripts')

</body>
</html>
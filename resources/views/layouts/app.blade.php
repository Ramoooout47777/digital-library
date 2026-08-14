{{-- resources/views/layouts/app.blade.php --}}
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
        }
        .dark .neu-button-primary:hover {
            color: #60d0fa;
            border-color: rgba(56, 189, 248, 0.3);
        }
        .dark .neu-nav {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.6);
            border-bottom: 1px solid rgba(51, 65, 85, 0.1);
        }
        .dark .neu-input {
            background: #0f172a;
            border-radius: 18px;
            box-shadow: inset 6px 6px 12px rgba(0, 0, 0, 0.6), inset -6px -6px 12px rgba(30, 41, 59, 0.3);
            color: #e2e8f0;
            padding: 12px 20px;
            border: none;
            outline: none;
            width: 100%;
        }
        .dark .neu-input:focus {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.5), inset -4px -4px 8px rgba(30, 41, 59, 0.2);
        }
        .dark .neu-input::placeholder {
            color: #475569;
        }
        .dark .neu-book {
            background: #0f172a;
            border-radius: 24px;
            box-shadow: 8px 8px 16px rgba(0, 0, 0, 0.6), -8px -8px 16px rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(51, 65, 85, 0.08);
            overflow: hidden;
        }
        .dark .neu-book:hover {
            box-shadow: 12px 12px 24px rgba(0, 0, 0, 0.7), -12px -12px 24px rgba(30, 41, 59, 0.5);
            border-color: rgba(56, 189, 248, 0.15);
        }
        .dark .neu-pill {
            background: #0f172a;
            border-radius: 100px;
            box-shadow: 6px 6px 12px rgba(0, 0, 0, 0.5), -6px -6px 12px rgba(30, 41, 59, 0.3);
            color: #94a3b8;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .dark .neu-pill:hover {
            color: #e2e8f0;
        }
        .dark .lang-select {
            background-color: #1e293b;
            color: #e2e8f0;
            border: 1px solid #334155;
            padding: 8px 16px;
            border-radius: 12px;
            cursor: pointer;
        }
        .dark .lang-select option {
            background-color: #1e293b;
            color: #e2e8f0;
        }
        .dark .mobile-menu {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(51, 65, 85, 0.1);
        }
        .dark .admin-link {
            background: rgba(56, 189, 248, 0.06);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.08);
            padding: 6px 16px;
            border-radius: 100px;
            transition: all 0.3s ease;
        }
        .dark .admin-link:hover {
            background: rgba(56, 189, 248, 0.12);
        }
        .dark .nav-link {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 6px 0;
            text-decoration: none;
        }
        .dark .nav-link:hover {
            color: #f1f5f9;
        }
        .dark .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #38bdf8, #818cf8);
            transition: width 0.4s ease;
        }
        .dark .nav-link:hover::after {
            width: 100%;
        }
        .dark .body-text {
            color: #94a3b8;
        }
        .dark .body-text-sm {
            color: #64748b;
        }
        .dark .stat-label {
            color: #475569;
        }
        .dark .stat-digit {
            background: linear-gradient(135deg, #e2e8f0, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .dark .lang-pill {
            color: #64748b;
            border: 1px solid rgba(51, 65, 85, 0.2);
            padding: 4px 12px;
            border-radius: 100px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
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

        /* ─── Light Mode ─── */
        .light .cyber-bg {
            background: #f8fafc;
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
        }
        .light .neu-button-primary:hover {
            color: #0284c7;
            border-color: rgba(14, 165, 233, 0.3);
        }
        .light .neu-nav {
            background: rgba(232, 236, 241, 0.85);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(174, 184, 194, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        .light .neu-input {
            background: #e8ecf1;
            border-radius: 18px;
            box-shadow: inset 6px 6px 12px rgba(174, 184, 194, 0.5), inset -6px -6px 12px rgba(255, 255, 255, 0.8);
            color: #1e293b;
            padding: 12px 20px;
            border: none;
            outline: none;
            width: 100%;
        }
        .light .neu-input:focus {
            box-shadow: inset 4px 4px 8px rgba(174, 184, 194, 0.5), inset -4px -4px 8px rgba(255, 255, 255, 0.8);
        }
        .light .neu-input::placeholder {
            color: #94a3b8;
        }
        .light .neu-book {
            background: #e8ecf1;
            border-radius: 24px;
            box-shadow: 8px 8px 16px rgba(174, 184, 194, 0.5), -8px -8px 16px rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
        }
        .light .neu-book:hover {
            box-shadow: 12px 12px 24px rgba(174, 184, 194, 0.6), -12px -12px 24px rgba(255, 255, 255, 0.9);
            border-color: rgba(14, 165, 233, 0.15);
        }
        .light .neu-pill {
            background: #e8ecf1;
            border-radius: 100px;
            box-shadow: 6px 6px 12px rgba(174, 184, 194, 0.5), -6px -6px 12px rgba(255, 255, 255, 0.8);
            color: #64748b;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .light .neu-pill:hover {
            color: #0f172a;
        }
        .light .lang-select {
            background-color: #f1f5f9;
            color: #1e293b;
            border: 1px solid #d1d5db;
            padding: 8px 16px;
            border-radius: 12px;
            cursor: pointer;
        }
        .light .lang-select option {
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .light .mobile-menu {
            background: rgba(232, 236, 241, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .light .admin-link {
            background: rgba(14, 165, 233, 0.04);
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.06);
            padding: 6px 16px;
            border-radius: 100px;
            transition: all 0.3s ease;
        }
        .light .admin-link:hover {
            background: rgba(14, 165, 233, 0.08);
        }
        .light .nav-link {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding: 6px 0;
            text-decoration: none;
        }
        .light .nav-link:hover {
            color: #0f172a;
        }
        .light .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #0ea5e9, #818cf8);
            transition: width 0.4s ease;
        }
        .light .nav-link:hover::after {
            width: 100%;
        }
        .light .body-text {
            color: #475569;
        }
        .light .body-text-sm {
            color: #64748b;
        }
        .light .stat-label {
            color: #94a3b8;
        }
        .light .stat-digit {
            background: linear-gradient(135deg, #0f172a, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .light .lang-pill {
            color: #94a3b8;
            border: 1px solid rgba(203, 213, 225, 0.3);
            padding: 4px 12px;
            border-radius: 100px;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
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

        /* ─── Common Styles ─── */
        .neu-card, .neu-card-inset, .neu-book, .mobile-menu {
            border-radius: 20px;
        }
        .neu-book {
            border-radius: 16px;
        }

        /* ─── Keyframe Animations ─── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.1); }
            50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.2); }
        }
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
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

        .section-padding { padding: 5rem 0; }
        @media (max-width: 768px) { .section-padding { padding: 3rem 0; } }

        .theme-transition,
        .theme-transition * {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>

    @stack('styles')
</head>
<body class="{{ session('theme', 'dark') }} theme-transition" id="app">

<!-- ============================================================ -->
<!-- NAVIGATION -->
<!-- ============================================================ -->
<nav class="neu-nav fixed top-0 left-0 right-0 z-50 py-3 px-4">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-cyan-400 to-indigo-400 flex items-center justify-center shadow-xl shadow-cyan-500/10 group-hover:scale-105 transition">
                <i class="fas fa-book-open text-slate-900 text-sm"></i>
            </div>
            <span class="text-lg font-bold tracking-tight dark:text-slate-100 light:text-slate-800">{{ __('home.hero_badge') }}</span>
        </a>

        <!-- Right Side -->
        <div class="flex items-center gap-3">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="neu-button w-10 h-10 rounded-xl flex items-center justify-center text-sm p-0">
                <i id="theme-icon" class="fas fa-moon text-lg"></i>
            </button>

            <button id="mobile-toggle" class="lg:hidden dark:text-slate-400 light:text-slate-600 hover:dark:text-slate-200 hover:light:text-slate-900 transition p-2">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>
</nav>

<!-- ============================================================ -->
<!-- MAIN CONTENT -->
<!-- ============================================================ -->
<main class="pt-20 cyber-bg">
    <div class="container mx-auto px-4 py-4">
        @if(session('success'))
            <div class="neu-card p-4 mb-4 border-l-4 border-emerald-500">
                <p class="text-emerald-400">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="neu-card p-4 mb-4 border-l-4 border-red-500">
                <p class="text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="neu-card p-4 mb-4 border-l-4 border-blue-500">
                <p class="text-blue-400">{{ session('info') }}</p>
            </div>
        @endif

        @yield('content')
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
        let theme = 'dark';

        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            body.classList.remove('dark');
            body.classList.add('light');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            theme = 'light';
        } else {
            html.classList.add('dark');
            body.classList.remove('light');
            body.classList.add('dark');
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            theme = 'dark';
        }

        localStorage.setItem('theme', theme);

        // Sync with backend
        fetch(`/switch-theme/${theme}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        });
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

    // ─── Mobile Menu ───
    document.getElementById('mobile-toggle')?.addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    });
</script>

@stack('scripts')

</body>
</html>

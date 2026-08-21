{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            font-family: 'Inter', 'Khmer OS', system-ui, sans-serif;
        }
        .container {
            max-width: 1280px;
        }s
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book-open text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-800">{{ config('app.name') }}</span>
                </a>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('books.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                        {{ __('home.books') ?? 'សៀវភៅ' }}
                    </a>
                    <a href="{{ route('categories.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                        {{ __('home.categories') ?? 'ប្រភេទ' }}
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8 text-center text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('home.all_rights_reserved') ?? 'រក្សាសិទ្ធិគ្រប់យ៉ាង' }}</p>
        </div>
    </footer>
</body>
</html>
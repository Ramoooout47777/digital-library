{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('title', __('auth.login') ?? 'Login')

@section('content')
<div class="neu-card p-8 animate-fade-in-up">
    <!-- Icon -->
    <div class="login-icon animate-pulse-glow">
        <i class="fas fa-user-circle text-cyan-400"></i>
    </div>
    
    <!-- Title -->
    <h2 class="text-2xl font-bold text-center dark:text-slate-100 light:text-slate-800 mt-4">
        {{ __('auth.login') ?? 'Login' }}
    </h2>
    <p class="text-sm text-center dark:text-slate-400 light:text-slate-600 mt-1">
        {{ __('auth.welcome_back') ?? 'Welcome back! Please login to your account.' }}
    </p>
    
    <!-- Form -->
    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf
        
        <!-- Email -->
        <div>
            <label class="neu-label block text-sm font-medium mb-1.5">{{ __('auth.email') ?? 'Email Address' }}</label>
            <div class="relative">
                <span class="neu-input-icon">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="neu-input neu-input-with-icon @error('email') input-error @enderror"
                       placeholder="{{ __('auth.enter_email') ?? 'Enter your email' }}"
                       required autofocus>
            </div>
            @error('email')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Password -->
        <div>
            <label class="neu-label block text-sm font-medium mb-1.5">{{ __('auth.password') ?? 'Password' }}</label>
            <div class="relative">
                <span class="neu-input-icon">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" name="password" 
                       class="neu-input neu-input-with-icon @error('password') input-error @enderror"
                       placeholder="{{ __('auth.enter_password') ?? 'Enter your password' }}"
                       required>
            </div>
            @error('password')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="remember" 
                       class="w-4 h-4 rounded dark:bg-slate-800 light:bg-white border dark:border-slate-700 light:border-slate-300 text-cyan-500 focus:ring-cyan-500">
                <span class="text-sm dark:text-slate-400 light:text-slate-600">{{ __('auth.remember_me') ?? 'Remember Me' }}</span>
            </label>
            
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm neu-link">
                    {{ __('auth.forgot_password') ?? 'Forgot Password?' }}
                </a>
            @endif
        </div>
        
        <!-- Submit -->
        <button type="submit" class="neu-button-primary w-full py-3 text-center text-base font-semibold rounded-xl">
            <i class="fas fa-sign-in-alt mr-2"></i>
            {{ __('auth.login') ?? 'Login' }}
        </button>
    </form>
    
    <!-- Register Link -->
    <div class="mt-6 text-center">
        <p class="text-sm dark:text-slate-400 light:text-slate-600">
            {{ __('auth.dont_have_account') ?? "Don't have an account?" }}
            <a href="{{ route('register') }}" class="neu-link font-medium">
                {{ __('auth.register') ?? 'Register' }}
            </a>
        </p>
    </div>
    
    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t dark:border-slate-700/50 light:border-slate-200/50"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 dark:bg-slate-800 light:bg-white dark:text-slate-500 light:text-slate-500">{{ __('auth.or') ?? 'Or' }}</span>
        </div>
    </div>
    
    <!-- Back to Home -->
    <div class="text-center">
        <a href="{{ route('home') }}" class="text-sm dark:text-slate-500 light:text-slate-500 hover:text-cyan-400 transition">
            <i class="fas fa-arrow-left mr-1"></i>
            {{ __('auth.back_to_home') ?? 'Back to Home' }}
        </a>
    </div>
</div>
@endsection
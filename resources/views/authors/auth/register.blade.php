{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.guest')

@section('title', __('auth.register') ?? 'Register')

@section('content')
<div class="neu-card p-8 animate-fade-in-up">
    <!-- Icon -->
    <div class="login-icon animate-pulse-glow">
        <i class="fas fa-user-plus text-cyan-400"></i>
    </div>
    
    <!-- Title -->
    <h2 class="text-2xl font-bold text-center dark:text-slate-100 light:text-slate-800 mt-4">
        {{ __('auth.register') ?? 'Register' }}
    </h2>
    {{-- <p class="text-sm text-center dark:text-slate-400 light:text-slate-600 mt-1">
        {{ __('auth.create_account') ?? 'Create your account to get started.' }}
    </p> --}}
    
    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf
        
        <!-- Name -->
        <div>
            <label class="neu-label block text-sm font-medium mb-1.5">{{ __('auth.name') ?? 'Full Name' }}</label>
            <div class="relative">
                <span class="neu-input-icon">
                    <i class="fas fa-user"></i>
                </span>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="neu-input neu-input-with-icon @error('name') input-error @enderror"
                       placeholder="{{ __('auth.enter_name') ?? 'Enter your full name' }}"
                       required autofocus>
            </div>
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>
        
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
                       required>
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
        
        <!-- Confirm Password -->
        <div>
            <label class="neu-label block text-sm font-medium mb-1.5">{{ __('auth.confirm_password') ?? 'Confirm Password' }}</label>
            <div class="relative">
                <span class="neu-input-icon">
                    <i class="fas fa-check-circle"></i>
                </span>
                <input type="password" name="password_confirmation" 
                       class="neu-input neu-input-with-icon"
                       placeholder="{{ __('auth.confirm_password_placeholder') ?? 'Confirm your password' }}"
                       required>
            </div>
        </div>
        
        <!-- Terms & Conditions -->
        <div class="flex items-center gap-2">
            <input type="checkbox" name="terms" id="terms" value="1"
                   class="w-4 h-4 rounded dark:bg-slate-800 light:bg-white border dark:border-slate-700 light:border-slate-300 text-cyan-500 focus:ring-cyan-500">
            <label for="terms" class="text-sm dark:text-slate-400 light:text-slate-600">
                {{ __('auth.agree_terms') ?? 'I agree to the' }}
                <a href="#" class="neu-link">{{ __('auth.terms_of_service') ?? 'Terms of Service' }}</a>
                {{ __('auth.and') ?? 'and' }}
                <a href="#" class="neu-link">{{ __('auth.privacy_policy') ?? 'Privacy Policy' }}</a>
            </label>
        </div>
        @error('terms')
            <p class="error-message">{{ $message }}</p>
        @enderror
        
        <!-- Submit -->
        <button type="submit" class="neu-button-primary w-full py-3 text-center text-base font-semibold rounded-xl">
            <i class="fas fa-user-plus mr-2"></i>
            {{ __('auth.register') ?? 'Register' }}
        </button>
    </form>
    
    <!-- Login Link -->
    <div class="mt-6 text-center">
        <p class="text-sm dark:text-slate-400 light:text-slate-600">
            {{ __('auth.already_have_account') ?? 'Already have an account?' }}
            <a href="{{ route('login') }}" class="neu-link font-medium">
                {{ __('auth.login') ?? 'Login' }}
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
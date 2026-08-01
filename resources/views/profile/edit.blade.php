{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', __('profile.edit_profile') ?? 'Edit Profile')
@section('page-title', __('profile.edit_profile') ?? 'Edit Profile')

@push('styles')
<style>
    /* ─── KEYFRAME ANIMATIONS ─── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(56, 189, 248, 0.1); }
        50% { box-shadow: 0 0 40px rgba(56, 189, 248, 0.2); }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .animate-scale-in { animation: scaleIn 0.5s ease-out forwards; }
    .animate-slide-right { animation: slideInRight 0.5s ease-out forwards; }
    .animate-pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
    .animate-shimmer { 
        background: linear-gradient(90deg, rgba(56,189,248,0.03) 0%, rgba(56,189,248,0.08) 50%, rgba(56,189,248,0.03) 100%);
        background-size: 200% 100%;
        animation: shimmer 4s ease-in-out infinite;
    }

    /* ─── Form Styles ─── */
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .dark .form-label {
        color: #94a3b8;
    }
    .light .form-label {
        color: #475569;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: none;
        outline: none;
        transition: all 0.3s ease;
    }
    .dark .form-input {
        background: #0f172a;
        box-shadow: inset 6px 6px 12px rgba(0, 0, 0, 0.5), inset -6px -6px 12px rgba(30, 41, 59, 0.3);
        color: #e2e8f0;
    }
    .dark .form-input:focus {
        box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.4), inset -4px -4px 8px rgba(30, 41, 59, 0.2);
    }
    .dark .form-input::placeholder {
        color: #475569;
    }
    .light .form-input {
        background: #e8ecf1;
        box-shadow: inset 6px 6px 12px rgba(174, 184, 194, 0.4), inset -6px -6px 12px rgba(255, 255, 255, 0.7);
        color: #1e293b;
    }
    .light .form-input:focus {
        box-shadow: inset 4px 4px 8px rgba(174, 184, 194, 0.4), inset -4px -4px 8px rgba(255, 255, 255, 0.7);
    }
    .light .form-input::placeholder {
        color: #94a3b8;
    }
    .form-input.error {
        box-shadow: inset 6px 6px 12px rgba(239, 68, 68, 0.2), inset -6px -6px 12px rgba(30, 41, 59, 0.3);
        border: 1px solid #ef4444;
    }
    .dark .form-input.error {
        border-color: #f87171;
    }
    .form-error {
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    .dark .form-error {
        color: #f87171;
    }
    .light .form-error {
        color: #ef4444;
    }
    
    /* ─── Avatar Upload ─── */
    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }
    .avatar-wrapper .avatar-overlay {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }
    .avatar-wrapper:hover .avatar-overlay {
        opacity: 1;
    }
    .avatar-wrapper .avatar-overlay i {
        color: white;
        font-size: 1.5rem;
    }
    
    /* ─── Password Section ─── */
    .password-section {
        border-top: 1px solid rgba(51, 65, 85, 0.2);
        padding-top: 1.5rem;
        margin-top: 1.5rem;
    }
    .dark .password-section {
        border-color: rgba(51, 65, 85, 0.3);
    }
    .light .password-section {
        border-color: rgba(203, 213, 225, 0.4);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- ============================================================ -->
        <!-- HEADER -->
        <!-- ============================================================ -->
        <div class="flex items-center gap-4 mb-6 animate-fade-in-up">
            <a href="{{ route('profile.index') }}" class="neu-button w-10 h-10 rounded-xl flex items-center justify-center p-0">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold dark:text-slate-100 light:text-slate-900">
                    <i class="fas fa-user-edit text-cyan-400 mr-2"></i>
                    {{ __('profile.edit_profile') ?? 'Edit Profile' }}
                </h1>
                <p class="text-sm dark:text-slate-400 light:text-slate-500">{{ __('profile.update_info') ?? 'Update your personal information' }}</p>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- EDIT PROFILE FORM -->
        <!-- ============================================================ -->
        <div class="neu-card p-6 animate-scale-in">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <!-- Name -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.name') ?? 'Name' }} <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                           class="form-input @error('name') error @enderror"
                           placeholder="{{ __('profile.enter_name') ?? 'Enter your full name' }}" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.email') ?? 'Email' }} <span class="text-red-400">*</span></label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                           class="form-input @error('email') error @enderror"
                           placeholder="{{ __('profile.enter_email') ?? 'Enter your email' }}" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.phone') ?? 'Phone' }}</label>
                    <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" 
                           class="form-input @error('phone') error @enderror"
                           placeholder="{{ __('profile.enter_phone') ?? 'Enter your phone number' }}">
                    @error('phone')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Address -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.address') ?? 'Address' }}</label>
                    <textarea name="address" rows="3" 
                              class="form-input @error('address') error @enderror"
                              placeholder="{{ __('profile.enter_address') ?? 'Enter your address' }}">{{ old('address', auth()->user()->address) }}</textarea>
                    @error('address')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-4 border-t dark:border-slate-700/50 light:border-slate-200/50">
                    <button type="submit" class="neu-button-primary flex-1 py-3 rounded-xl text-center font-semibold">
                        <i class="fas fa-save mr-2"></i> {{ __('profile.save_changes') ?? 'Save Changes' }}
                    </button>
                    <a href="{{ route('profile.index') }}" class="neu-button flex-1 py-3 rounded-xl text-center">
                        <i class="fas fa-times mr-2"></i> {{ __('profile.cancel') ?? 'Cancel' }}
                    </a>
                </div>
            </form>
        </div>

        <!-- ============================================================ -->
        <!-- UPDATE AVATAR -->
        <!-- ============================================================ -->
        <div class="neu-card p-6 mt-6 animate-slide-right">
            <h2 class="text-xl font-bold dark:text-slate-100 light:text-slate-900 mb-4">
                <i class="fas fa-camera text-cyan-400 mr-2"></i>
                {{ __('profile.update_avatar') ?? 'Update Avatar' }}
            </h2>
            
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <!-- Current Avatar -->
                <div class="avatar-wrapper">
                    <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=3b82f6&color=fff&size=120' }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-24 h-24 rounded-full object-cover border-2 border-cyan-500/20">
                    <label for="avatar-upload" class="avatar-overlay">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                
                <div class="flex-1">
                    <p class="text-sm dark:text-slate-400 light:text-slate-500">
                        {{ __('profile.avatar_help') ?? 'Click the camera icon to upload a new avatar.' }}
                    </p>
                    <p class="text-xs dark:text-slate-500 light:text-slate-500 mt-1">
                        JPG, PNG, WEBP (Max 2MB)
                    </p>
                    
                    <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <div class="flex gap-3">
                            <input type="file" id="avatar-upload" name="avatar" accept="image/*" 
                                   class="hidden" onchange="this.form.submit()">
                            <button type="button" onclick="document.getElementById('avatar-upload').click()" 
                                    class="neu-button-primary px-4 py-2 text-sm rounded-xl">
                                <i class="fas fa-upload mr-2"></i> {{ __('profile.choose_image') ?? 'Choose Image' }}
                            </button>
                            @if(auth()->user()->avatar)
                                <form action="{{ route('profile.avatar') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="neu-button px-4 py-2 text-sm rounded-xl text-red-400 hover:text-red-300"
                                            onclick="return confirm('{{ __('profile.confirm_remove_avatar') ?? 'Remove avatar?' }}')">
                                        <i class="fas fa-trash mr-2"></i> {{ __('profile.remove') ?? 'Remove' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </form>
                    @error('avatar')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- CHANGE PASSWORD -->
        <!-- ============================================================ -->
        <div class="neu-card p-6 mt-6 animate-slide-right" style="animation-delay: 0.1s;">
            <h2 class="text-xl font-bold dark:text-slate-100 light:text-slate-900 mb-4">
                <i class="fas fa-key text-cyan-400 mr-2"></i>
                {{ __('profile.change_password') ?? 'Change Password' }}
            </h2>
            
            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <!-- Current Password -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.current_password') ?? 'Current Password' }} <span class="text-red-400">*</span></label>
                    <input type="password" name="current_password" 
                           class="form-input @error('current_password') error @enderror"
                           placeholder="{{ __('profile.enter_current_password') ?? 'Enter current password' }}" required>
                    @error('current_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- New Password -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.new_password') ?? 'New Password' }} <span class="text-red-400">*</span></label>
                    <input type="password" name="new_password" 
                           class="form-input @error('new_password') error @enderror"
                           placeholder="{{ __('profile.enter_new_password') ?? 'Enter new password' }}" required>
                    @error('new_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label">{{ __('profile.confirm_password') ?? 'Confirm Password' }} <span class="text-red-400">*</span></label>
                    <input type="password" name="new_password_confirmation" 
                           class="form-input"
                           placeholder="{{ __('profile.confirm_new_password') ?? 'Confirm new password' }}" required>
                </div>
                
                <!-- Password Requirements -->
                <div class="text-xs dark:text-slate-500 light:text-slate-500 space-y-1">
                    <p><i class="fas fa-info-circle mr-1"></i> {{ __('profile.password_requirements') ?? 'Password must:' }}</p>
                    <ul class="list-disc list-inside ml-4 space-y-0.5">
                        <li>{{ __('profile.password_min_chars') ?? 'Be at least 8 characters long' }}</li>
                        <li>{{ __('profile.password_letters') ?? 'Contain at least one uppercase and one lowercase letter' }}</li>
                        <li>{{ __('profile.password_numbers') ?? 'Contain at least one number' }}</li>
                    </ul>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="neu-button-primary w-full py-3 rounded-xl text-center font-semibold">
                    <i class="fas fa-key mr-2"></i> {{ __('profile.update_password') ?? 'Update Password' }}
                </button>
            </form>
        </div>

        <!-- ============================================================ -->
        <!-- DANGER ZONE -->
        <!-- ============================================================ -->
        <div class="neu-card p-6 mt-6 border-l-4 border-red-500 animate-slide-right" style="animation-delay: 0.2s;">
            <h2 class="text-xl font-bold text-red-400 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ __('profile.danger_zone') ?? 'Danger Zone' }}
            </h2>
            <p class="text-sm dark:text-slate-400 light:text-slate-500 mb-4">
                {{ __('profile.danger_zone_desc') ?? 'Once you delete your account, there is no going back. Please be certain.' }}
            </p>
            
            <form action="{{ route('profile.destroy') }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-4 py-2 rounded-xl transition-all duration-300 hover:scale-105"
                        onclick="return confirm('{{ __('profile.confirm_delete_account') ?? 'Are you sure you want to delete your account? This action cannot be undone.' }}')">
                    <i class="fas fa-trash mr-2"></i> {{ __('profile.delete_account') ?? 'Delete Account' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
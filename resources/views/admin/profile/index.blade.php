{{-- resources/views/admin/profile/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.profile_menu'))
@section('page-title', __('admin.profile_menu'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Profile Info -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Avatar -->
            <div class="flex flex-col items-center">
                <div class="relative">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3b82f6&color=fff&size=120' }}" 
                         alt="{{ $user->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-blue-500">
                    <button onclick="document.getElementById('avatar-input').click()" 
                            class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mt-3">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-800">
                        {{ $user->roles->first()->name ?? 'Admin' }}
                    </span>
                </p>
            </div>

            <!-- Stats -->
            <div class="mt-6 pt-6 border-t">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.registered_at') ?? 'Registered At' }}</span>
                        <span class="font-medium">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.last_login') ?? 'Last Login' }}</span>
                        <span class="font-medium">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">{{ __('admin.phone') ?? 'Phone' }}</span>
                        <span class="font-medium">{{ $user->phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Forms -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Update Profile Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.edit_profile') ?? 'Edit Profile' }}
            </h3>
            
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">{{ __('admin.name') ?? 'Name' }}</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               class="form-input @error('name') error @enderror" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">{{ __('admin.email') }}</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               class="form-input @error('email') error @enderror" required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">{{ __('admin.phone') ?? 'Phone' }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               class="form-input @error('phone') error @enderror">
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">{{ __('admin.address') }}</label>
                        <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                               class="form-input @error('address') error @enderror">
                        @error('address')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i> {{ __('admin.update') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Update Avatar Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.update_avatar') ?? 'Update Avatar' }}
            </h3>
            
            <form action="{{ route('admin.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <input type="file" name="avatar" id="avatar-input" accept="image/*" 
                               class="hidden" onchange="this.form.submit()">
                        <label for="avatar-input" class="btn btn-gray cursor-pointer">
                            <i class="fas fa-upload mr-2"></i> {{ __('admin.choose_image') ?? 'Choose Image' }}
                        </label>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, WEBP (Max 2MB)</p>
                    </div>
                    @if($user->avatar)
                        <button type="submit" form="admin-avatar-delete-form" class="btn btn-danger btn-sm" 
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
                @error('avatar')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </form>
            @if($user->avatar)
                <form id="admin-avatar-delete-form" action="{{ route('admin.profile.avatar.destroy') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.change_password') ?? 'Change Password' }}
            </h3>
            
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-4">
                    <div>
                        <label class="form-label">{{ __('admin.current_password') ?? 'Current Password' }}</label>
                        <input type="password" name="current_password" 
                               class="form-input @error('current_password') error @enderror" required>
                        @error('current_password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">{{ __('admin.new_password') ?? 'New Password' }}</label>
                        <input type="password" name="new_password" 
                               class="form-input @error('new_password') error @enderror" required>
                        @error('new_password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="form-label">{{ __('admin.confirm_password') }}</label>
                        <input type="password" name="new_password_confirmation" 
                               class="form-input" required>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key mr-2"></i> {{ __('admin.update_password') ?? 'Update Password' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit avatar form when file is selected
    document.getElementById('avatar-input')?.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            this.form.submit();
        }
    });
</script>
@endpush
@endsection
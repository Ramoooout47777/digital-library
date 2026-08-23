{{-- resources/views/admin/customers/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.edit_customer') ?? 'កែអតិថិជន')
@section('page-title', __('admin.edit_customer') ?? 'កែអតិថិជន')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.email') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                           required>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.address') }}</label>
                    <textarea name="address" rows="2" 
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Avatar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.avatar') ?? 'រូបភាព' }}</label>
                    @if($customer->avatar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $customer->avatar) }}" 
                                 alt="{{ $customer->name }}" 
                                 class="w-24 h-24 rounded-full object-cover border-2 border-blue-500">
                        </div>
                    @endif
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('avatar').click()">
                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                        <p class="text-xs text-gray-500">{{ __('admin.click_to_upload') }}</p>
                        <p class="text-xs text-gray-400">JPG, PNG, WEBP (Max 2MB)</p>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                    </div>
                    <div id="avatar-preview" class="mt-2 hidden">
                        <img id="avatar-preview-img" src="" alt="Preview" class="w-24 h-24 rounded-full object-cover border-2 border-blue-500">
                        <button type="button" onclick="removeAvatar()" class="text-red-500 text-sm mt-1">
                            <i class="fas fa-times"></i> {{ __('admin.remove') }}
                        </button>
                    </div>
                    @error('avatar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $customer->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600">
                        <span class="text-sm font-medium">{{ __('admin.active') }}</span>
                        <span class="text-xs text-gray-400 ml-auto">{{ __('admin.uncheck_to_deactivate') }}</span>
                    </label>
                </div>
                
                <!-- Role -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.role') ?? 'តួនាទី' }}</label>
                    <select name="role" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                        <option value="customer" {{ $customer->hasRole('customer') ? 'selected' : '' }}>{{ __('admin.customer') ?? 'អតិថិជន' }}</option>
                        <option value="admin" {{ $customer->hasRole('admin') ? 'selected' : '' }}>{{ __('admin.admin') ?? 'អ្នកគ្រប់គ្រង' }}</option>
                        
                        @if($customer->hasRole('super-admin'))
                            <option value="super-admin" selected>{{ __('admin.super_admin') ?? 'អ្នកគ្រប់គ្រងកំពូល' }}</option>
                        @endif
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Statistics -->
                <div class="bg-gray-50 rounded-lg p-4 mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">{{ __('admin.statistics') }}</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="text-gray-500">{{ __('admin.total_orders') }}</span>
                            <p class="font-medium">{{ $customer->orders_count }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('admin.total_spent') }}</span>
                            <p class="font-medium text-green-600">${{ number_format($customer->total_spent ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('admin.registered_at') }}</span>
                            <p class="font-medium">{{ $customer->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500">{{ __('admin.last_login') }}</span>
                            <p class="font-medium">{{ $customer->last_login_at ? $customer->last_login_at->diffForHumans() : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>{{ __('admin.update') }}
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>
            
            @if($customer->id != auth()->id() && $customer->orders_count == 0)
                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline ml-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition"
                            onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                        <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                    </button>
                </form>
            @elseif($customer->id == auth()->id())
                <span class="ml-auto text-sm text-yellow-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ __('admin.cannot_delete_self') ?? 'មិនអាចលុបខ្លួនឯងបានទេ' }}
                </span>
            @else
                <span class="ml-auto text-sm text-yellow-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ __('admin.cannot_delete_has_orders') ?? 'មិនអាចលុបបានទេ ព្រោះមានការកម្មង់' }}
                </span>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview-img').src = e.target.result;
                document.getElementById('avatar-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeAvatar() {
        document.getElementById('avatar').value = '';
        document.getElementById('avatar-preview').classList.add('hidden');
    }
</script>
@endpush
@endsection
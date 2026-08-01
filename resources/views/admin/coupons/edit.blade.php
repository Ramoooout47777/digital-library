{{-- resources/views/admin/coupons/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.edit_coupon') ?? 'កែប័ណ្ណបញ្ចុះតម្លៃ')
@section('page-title', __('admin.edit_coupon') ?? 'កែប័ណ្ណបញ្ចុះតម្លៃ')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Coupon Code -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.coupon_code') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="code" value="{{ old('code', $coupon->code) }}" 
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                               required>
                        <button type="button" onclick="generateCode()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg transition whitespace-nowrap">
                            <i class="fas fa-sync-alt"></i> {{ __('admin.generate') ?? 'បង្កើត' }}
                        </button>
                    </div>
                    @error('code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Discount Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.discount_type') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="discount_type" id="discount_type" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount_type') border-red-500 @enderror" required>
                        <option value="percentage" {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>{{ __('admin.discount_type_percentage') }}</option>
                        <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>{{ __('admin.discount_type_fixed') }}</option>
                    </select>
                    @error('discount_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Discount Value -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.discount_value') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500" id="discount_symbol">
                            {{ $coupon->discount_type == 'percentage' ? '%' : '$' }}
                        </span>
                        <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" step="0.01" min="0"
                               class="form-input w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount_value') border-red-500 @enderror"
                               required>
                    </div>
                    @error('discount_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Min Order Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_amount') }}</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" step="0.01" min="0"
                               class="form-input w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('min_order_amount') border-red-500 @enderror">
                    </div>
                    @error('min_order_amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Max Discount Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_discount_amount') }}</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">$</span>
                        <input type="number" name="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" step="0.01" min="0"
                               class="form-input w-full px-3 py-2 pl-8 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('max_discount_amount') border-red-500 @enderror">
                    </div>
                    @error('max_discount_amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Usage Limit -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.usage_limit') }}</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('usage_limit') border-red-500 @enderror">
                    @error('usage_limit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Used Count (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.used_count') }}</label>
                    <input type="number" value="{{ $coupon->used_count }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" readonly>
                </div>
                
                <!-- Expires At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.expires_at') }}</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('expires_at') border-red-500 @enderror">
                    @error('expires_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600">
                        <span class="text-sm font-medium">{{ __('admin.active') }}</span>
                        <span class="text-xs text-gray-400 ml-auto">{{ __('admin.uncheck_to_deactivate') }}</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>{{ __('admin.update') }}
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>
            
            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline ml-auto">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition"
                        onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                    <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                </button>
            </form>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function generateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.querySelector('input[name="code"]').value = code;
    }
    
    // Update discount symbol based on type
    document.getElementById('discount_type')?.addEventListener('change', function() {
        const symbol = document.getElementById('discount_symbol');
        if (this.value === 'percentage') {
            symbol.textContent = '%';
        } else {
            symbol.textContent = '$';
        }
    });
</script>
@endpush
@endsection
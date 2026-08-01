{{-- resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.settings_menu'))
@section('page-title', __('admin.settings_menu'))

@section('content')
<div class="space-y-6">

    <!-- ============================================================ -->
    <!-- GENERAL SETTINGS -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-cog mr-2 text-blue-500"></i>
            {{ __('admin.general_settings') }}
        </h3>
        
        <form action="{{ route('admin.settings.general') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.app_name') }}</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.app_description') }}</label>
                    <input type="text" name="app_description" value="{{ old('app_description', $settings['app_description'] ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.contact_email') }}</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.contact_phone') }}</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.address') }}</label>
                    <textarea name="address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $settings['address'] ?? '') }}</textarea>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.save_settings') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- TYPE MANAGEMENT -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="font-semibold text-lg text-gray-800">
                <i class="fas fa-tags mr-2 text-purple-500"></i>
                {{ __('admin.type_management') }}
            </h3>
            <button onclick="openTypeModal()" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition text-sm flex items-center">
                <i class="fas fa-plus mr-1"></i> {{ __('admin.add_new_type') }}
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">{{ __('admin.name') }}</th>
                        <th class="px-4 py-2 text-left">{{ __('admin.slug') }}</th>
                        <th class="px-4 py-2 text-left">{{ __('admin.description') }}</th>
                        <th class="px-4 py-2 text-center">{{ __('admin.status') }}</th>
                        <th class="px-4 py-2 text-center">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($types ?? [] as $type)
                        <tr>
                            <td class="px-4 py-2">{{ $type->id }}</td>
                            <td class="px-4 py-2 font-medium">{{ $type->name }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ $type->slug }}</td>
                            <td class="px-4 py-2 text-gray-500">{{ Str::limit($type->description, 30) }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="px-2 py-1 text-xs rounded-full {{ $type->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $type->status ? __('admin.active') : __('admin.inactive') }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="editType({{ $type->id }}, '{{ $type->name }}', '{{ $type->description }}', {{ $type->status }})" 
                                            class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.settings.types.destroy', $type) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" 
                                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-400">{{ __('admin.no_types') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- PRINT SETTINGS -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-print mr-2 text-indigo-500"></i>
            {{ __('admin.print_settings') }}
        </h3>
        
        <form action="{{ route('admin.settings.print') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.print_type') }}</label>
                    <select name="print_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="digital" {{ ($printSettings->print_type ?? 'digital') == 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="offset" {{ ($printSettings->print_type ?? '') == 'offset' ? 'selected' : '' }}>Offset</option>
                        <option value="screen" {{ ($printSettings->print_type ?? '') == 'screen' ? 'selected' : '' }}>Screen</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.paper_size') }}</label>
                    <select name="paper_size" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="A4" {{ ($printSettings->paper_size ?? 'A4') == 'A4' ? 'selected' : '' }}>A4</option>
                        <option value="A3" {{ ($printSettings->paper_size ?? '') == 'A3' ? 'selected' : '' }}>A3</option>
                        <option value="A5" {{ ($printSettings->paper_size ?? '') == 'A5' ? 'selected' : '' }}>A5</option>
                        <option value="Letter" {{ ($printSettings->paper_size ?? '') == 'Letter' ? 'selected' : '' }}>Letter</option>
                        <option value="Legal" {{ ($printSettings->paper_size ?? '') == 'Legal' ? 'selected' : '' }}>Legal</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.print_quality') }}</label>
                    <select name="print_quality" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="high" {{ ($printSettings->print_quality ?? 'high') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ ($printSettings->print_quality ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ ($printSettings->print_quality ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.copies') }}</label>
                    <input type="number" name="copies" value="{{ old('copies', $printSettings->copies ?? 1) }}" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.color_mode') }}</label>
                    <select name="color_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="color" {{ ($printSettings->color_mode ?? 'color') == 'color' ? 'selected' : '' }}>Color</option>
                        <option value="black_white" {{ ($printSettings->color_mode ?? '') == 'black_white' ? 'selected' : '' }}>Black & White</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.price_per_page') }}</label>
                    <input type="number" name="price_per_page" value="{{ old('price_per_page', $printSettings->price_per_page ?? 0.05) }}" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.save_settings') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- ORDER SETTINGS -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-shopping-cart mr-2 text-green-500"></i>
            {{ __('admin.order_settings') }}
        </h3>
        
        <form action="{{ route('admin.settings.order') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_amount') }}</label>
                    <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $orderSettings->min_order_amount ?? 0) }}" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_order_amount') }}</label>
                    <input type="number" name="max_order_amount" value="{{ old('max_order_amount', $orderSettings->max_order_amount ?? '') }}" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.order_timeout') }} ({{ __('admin.minutes') }})</label>
                    <input type="number" name="order_timeout" value="{{ old('order_timeout', $orderSettings->order_timeout ?? 30) }}" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="auto_confirm" value="1" {{ ($orderSettings->auto_confirm ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.auto_confirm') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="auto_complete" value="1" {{ ($orderSettings->auto_complete ?? false) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.auto_complete') ?? 'បញ្ចប់ដោយស្វ័យប្រវត្តិ' }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.payment_grace_period') }} ({{ __('admin.minutes') }})</label>
                    <input type="number" name="payment_grace_period" value="{{ old('payment_grace_period', $orderSettings->payment_grace_period ?? 15) }}" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.save_settings') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- DISCOUNT SETTINGS -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-percent mr-2 text-yellow-500"></i>
            {{ __('admin.discount_settings') }}
        </h3>
        
        <form action="{{ route('admin.settings.discount') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.default_discount') }} (%)</label>
                    <input type="number" name="default_discount" value="{{ old('default_discount', $discountSettings->default_discount ?? 0) }}" step="0.01" min="0" max="100" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_discount') }} (%)</label>
                    <input type="number" name="max_discount" value="{{ old('max_discount', $discountSettings->max_discount ?? 50) }}" step="0.01" min="0" max="100" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.discount_type') }}</label>
                    <select name="discount_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="percentage" {{ ($discountSettings->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>{{ __('admin.discount_type_percentage') }}</option>
                        <option value="fixed" {{ ($discountSettings->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>{{ __('admin.discount_type_fixed') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_for_discount') }}</label>
                    <input type="number" name="min_order_for_discount" value="{{ old('min_order_for_discount', $discountSettings->min_order_for_discount ?? 0) }}" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="auto_apply" value="1" {{ ($discountSettings->auto_apply ?? false) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.auto_apply') }}</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.save_settings') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- COUPON SETTINGS -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-ticket-alt mr-2 text-pink-500"></i>
            {{ __('admin.coupon_settings') }}
        </h3>
        
        <form action="{{ route('admin.settings.coupon') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.coupon_type') }}</label>
                    <select name="coupon_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="percentage" {{ ($couponSettings->coupon_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>{{ __('admin.discount_type_percentage') }}</option>
                        <option value="fixed" {{ ($couponSettings->coupon_type ?? '') == 'fixed' ? 'selected' : '' }}>{{ __('admin.discount_type_fixed') }}</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.default_discount_value') }}</label>
                    <input type="number" name="default_discount_value" value="{{ old('default_discount_value', $couponSettings->default_discount_value ?? 10) }}" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.coupon_duration') }} ({{ __('admin.days') }})</label>
                    <input type="number" name="coupon_duration" value="{{ old('coupon_duration', $couponSettings->coupon_duration ?? 30) }}" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_coupon_per_user') }}</label>
                    <input type="number" name="max_coupon_per_user" value="{{ old('max_coupon_per_user', $couponSettings->max_coupon_per_user ?? 3) }}" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_for_coupon') }}</label>
                    <input type="number" name="min_order_for_coupon" value="{{ old('min_order_for_coupon', $couponSettings->min_order_for_coupon ?? 0) }}" step="0.01" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="coupon_auto_apply" value="1" {{ ($couponSettings->coupon_auto_apply ?? false) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.auto_apply') }}</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.save_settings') }}
                </button>
            </div>
        </form>
    </div>

    <!-- ============================================================ -->
    <!-- NOTIFICATION SETTINGS -->
    <!-- ============================================================ -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-bell mr-2 text-orange-500"></i>
            {{ __('admin.notification_settings') }}
        </h3>
        
        <form action="{{ route('admin.settings.notification') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="email_notifications" value="1" {{ ($notificationSettings->email_notifications ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.email_notifications') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="sms_notifications" value="1" {{ ($notificationSettings->sms_notifications ?? false) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.sms_notifications') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="push_notifications" value="1" {{ ($notificationSettings->push_notifications ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.push_notifications') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="order_notifications" value="1" {{ ($notificationSettings->order_notifications ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.order_notifications') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="promotion_notifications" value="1" {{ ($notificationSettings->promotion_notifications ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.promotion_notifications') }}</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="system_notifications" value="1" {{ ($notificationSettings->system_notifications ?? true) ? 'checked' : '' }} 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.system_notifications') ?? 'ជូនដំណឹងប្រព័ន្ធ' }}</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                    <i class="fas fa-save mr-2"></i> {{ __('admin.save_settings') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- TYPE MODAL (Create/Edit) -->
<!-- ============================================================ -->
<div id="typeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800" id="typeModalTitle">{{ __('admin.add_new_type') }}</h3>
            <button onclick="closeTypeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="typeForm" action="{{ route('admin.settings.types.store') }}" method="POST" class="p-4">
            @csrf
            <input type="hidden" name="_method" id="typeMethod" value="POST">
            <input type="hidden" name="type_id" id="typeId">
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="typeName" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.description') }}</label>
                    <textarea name="description" id="typeDescription" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="status" id="typeStatus" value="1" checked 
                               class="w-4 h-4 text-blue-600 rounded">
                        <span class="text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>
            
            <div class="flex items-center gap-3 mt-4 pt-4 border-t">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> <span id="typeSubmitText">{{ __('admin.save') }}</span>
                </button>
                <button type="button" onclick="closeTypeModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    {{ __('admin.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ============================================================
    // TYPE MODAL FUNCTIONS
    // ============================================================
    
    function openTypeModal() {
        document.getElementById('typeModal').classList.remove('hidden');
        document.getElementById('typeModalTitle').textContent = '{{ __("admin.add_new_type") }}';
        document.getElementById('typeId').value = '';
        document.getElementById('typeName').value = '';
        document.getElementById('typeDescription').value = '';
        document.getElementById('typeStatus').checked = true;
        document.getElementById('typeSubmitText').textContent = '{{ __("admin.save") }}';
        
        const form = document.getElementById('typeForm');
        form.action = '{{ route("admin.settings.types.store") }}';
        document.getElementById('typeMethod').value = 'POST';
    }
    
    function editType(id, name, description, status) {
        document.getElementById('typeModal').classList.remove('hidden');
        document.getElementById('typeModalTitle').textContent = '{{ __("admin.edit_type") }}';
        document.getElementById('typeId').value = id;
        document.getElementById('typeName').value = name;
        document.getElementById('typeDescription').value = description || '';
        document.getElementById('typeStatus').checked = status === 1 || status === true;
        document.getElementById('typeSubmitText').textContent = '{{ __("admin.update") }}';
        
        const form = document.getElementById('typeForm');
        form.action = `/admin/settings/types/${id}`;
        document.getElementById('typeMethod').value = 'PUT';
    }
    
    function closeTypeModal() {
        document.getElementById('typeModal').classList.add('hidden');
    }
    
    // Close modal on outside click
    document.getElementById('typeModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeTypeModal();
        }
    });
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTypeModal();
        }
    });
</script>
@endpush
@endsection
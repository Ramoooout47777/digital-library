{{-- resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.settings_menu') ?? 'System Settings')
@section('page-title', __('admin.settings_menu') ?? 'System Settings')

@section('content')
<div class="space-y-6">

    <!-- Validation Errors Alert -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <div>
                    <h3 class="text-sm font-semibold text-red-800">{{ __('admin.validation_error') ?? 'Please check the form for errors' }}</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- TABS NAVIGATION HEADER -->
    <div class="bg-white rounded-xl shadow-sm p-2 border border-gray-100 flex flex-wrap gap-1 overflow-x-auto">
        <button type="button" onclick="switchTab('general')" id="tab-btn-general"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-cog text-blue-500"></i>
            <span>{{ __('admin.general_settings') ?? 'General' }}</span>
        </button>
        <button type="button" onclick="switchTab('types')" id="tab-btn-types"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-tags text-purple-500"></i>
            <span>{{ __('admin.types') ?? 'Book Types' }}</span>
        </button>
        <button type="button" onclick="switchTab('authors')" id="tab-btn-authors"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-user-pen text-amber-500"></i>
            <span>{{ __('admin.authors_menu') ?? 'Authors' }}</span>
        </button>
        <button type="button" onclick="switchTab('publishers')" id="tab-btn-publishers"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-building text-teal-500"></i>
            <span>{{ __('admin.publishers_menu') ?? 'Publishers' }}</span>
        </button>
        <button type="button" onclick="switchTab('print')" id="tab-btn-print"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-print text-indigo-500"></i>
            <span>{{ __('admin.print_settings') ?? 'Print' }}</span>
        </button>
        <button type="button" onclick="switchTab('order')" id="tab-btn-order"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-shopping-cart text-green-500"></i>
            <span>{{ __('admin.order_settings') ?? 'Orders' }}</span>
        </button>
        <button type="button" onclick="switchTab('discount')" id="tab-btn-discount"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-percent text-yellow-500"></i>
            <span>{{ __('admin.discount_settings') ?? 'Discounts' }}</span>
        </button>
        <button type="button" onclick="switchTab('coupon')" id="tab-btn-coupon"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-ticket-alt text-pink-500"></i>
            <span>{{ __('admin.coupon_settings') ?? 'Coupons' }}</span>
        </button>
        <button type="button" onclick="switchTab('notification')" id="tab-btn-notification"
                class="tab-btn px-4 py-2.5 rounded-lg font-medium text-sm transition-all flex items-center gap-2">
            <i class="fas fa-bell text-rose-500"></i>
            <span>{{ __('admin.notification_settings') ?? 'Notifications' }}</span>
        </button>
    </div>

    <!-- ============================================================ -->
    <!-- 1. GENERAL SETTINGS TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-general" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-cog text-blue-500"></i>
                        {{ __('admin.general_settings') ?? 'General Settings' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Configure main store metadata, contact details, and system state.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.general') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.app_name') ?? 'Application Name' }} <span class="text-red-500">*</span></label>
                        <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" required
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.app_description') ?? 'Application Description' }}</label>
                        <input type="text" name="app_description" value="{{ old('app_description', $settings['app_description'] ?? '') }}"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.contact_email') ?? 'Contact Email' }} <span class="text-red-500">*</span></label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" required
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.contact_phone') ?? 'Contact Phone' }}</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.telegram_channel') ?? 'Telegram Channel URL' }}</label>
                        <input type="url" name="telegram_channel" value="{{ old('telegram_channel', $settings['telegram_channel'] ?? '') }}"
                               placeholder="https://t.me/your_channel"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.address') ?? 'Store Address' }}</label>
                        <textarea name="address" rows="2" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('address', $settings['address'] ?? '') }}</textarea>
                    </div>

                    <!-- Maintenance Mode -->
                    <div class="md:col-span-2 border-t border-gray-100 pt-6 mt-2">
                        <div class="bg-amber-50/60 rounded-xl p-4 border border-amber-200">
                            <h4 class="text-md font-semibold text-amber-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-tools text-amber-600"></i>
                                {{ __('admin.maintenance_mode') ?? 'Maintenance Mode' }}
                            </h4>

                            <div class="flex items-center gap-4 mb-4">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="maintenance_mode" value="1" {{ (old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1') ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">{{ __('admin.enable_maintenance_mode') ?? 'Enable Maintenance Mode' }}</span>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.maintenance_message') ?? 'Maintenance Message' }}</label>
                                <textarea name="maintenance_message" rows="2" placeholder="{{ __('admin.maintenance_placeholder') ?? 'We are currently performing system maintenance. Please check back soon.' }}" class="w-full px-3.5 py-2.5 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment QR Code -->
                    <div class="md:col-span-2 border-t border-gray-100 pt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.payment_qr_code') ?? 'QR Code For Payment' }}</label>
                        <div class="flex items-start gap-6">
                            @php
                                $qrPath = \App\Models\Setting::where('key', 'payment_qr_code')->where('group', 'general')->value('value');
                            @endphp
                            @if($qrPath)
                                <div class="w-32 h-32 border border-gray-200 rounded-lg overflow-hidden bg-gray-50 flex-shrink-0 p-1">
                                    <img src="{{ asset('storage/' . $qrPath) }}" alt="QR Code" class="w-full h-full object-contain">
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="payment_qr_code" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-2">Recommended: 500x500px image (JPG, PNG, WEBP, max 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> {{ __('admin.save_settings') ?? 'Save Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 2. TYPE MANAGEMENT TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-types" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-tags text-purple-500"></i>
                        {{ __('admin.type_management') ?? 'Book Types Management' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Manage available book classifications (e.g. Hardcover, Paperback, E-book, Audio).</p>
                </div>
                <button type="button" onclick="openTypeModal()"
                        class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i> {{ __('admin.add_new_type') ?? 'Add New Type' }}
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">{{ __('admin.name') ?? 'Name' }}</th>
                            <th class="px-4 py-3">{{ __('admin.slug') ?? 'Slug' }}</th>
                            <th class="px-4 py-3">{{ __('admin.description') ?? 'Description' }}</th>
                            <th class="px-4 py-3 text-center">{{ __('admin.status') ?? 'Status' }}</th>
                            <th class="px-4 py-3 text-center">{{ __('admin.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($types ?? [] as $type)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3 font-medium text-gray-500">{{ $type->id }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $type->name }}</td>
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $type->slug }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ Str::limit($type->description, 50) ?: '-' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $type->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $type->status ? (__('admin.active') ?? 'Active') : (__('admin.inactive') ?? 'Inactive') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button"
                                                data-type="{{ json_encode([
                                                    'id' => $type->id,
                                                    'name' => $type->name,
                                                    'description' => $type->description ?? '',
                                                    'status' => $type->status ? 1 : 0,
                                                    'action' => route('admin.settings.types.update', $type)
                                                ]) }}"
                                                onclick="editTypeFromData(this)"
                                                class="text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.types.destroy', $type) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                                    onclick="return confirm('{{ __('admin.confirm_delete') ?? 'Are you sure you want to delete this type?' }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                    <i class="fas fa-tags text-3xl mb-2 text-gray-300 block"></i>
                                    {{ __('admin.no_types') ?? 'No book types found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 3. AUTHORS MANAGEMENT TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-authors" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user-pen text-amber-500"></i>
                        {{ __('admin.authors_menu') ?? 'Authors Management' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Quickly view, create, and update authors.</p>
                </div>
                <button type="button" onclick="openAuthorModal()"
                        class="bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i> {{ __('admin.add_new_author') ?? 'Add New Author' }}
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3">Photo</th>
                            <th class="px-4 py-3">{{ __('admin.name') ?? 'Name' }}</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Website</th>
                            <th class="px-4 py-3 text-center">{{ __('admin.status') ?? 'Status' }}</th>
                            <th class="px-4 py-3 text-center">{{ __('admin.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($authors ?? [] as $author)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-2">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                        @if($author->image)
                                            <img src="{{ asset('storage/' . $author->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-sm">
                                                {{ strtoupper(substr($author->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $author->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $author->email ?: '-' }}</td>
                                <td class="px-4 py-3 text-blue-600 text-xs">
                                    @if($author->website)
                                        <a href="{{ $author->website }}" target="_blank" class="hover:underline">{{ Str::limit($author->website, 30) }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $author->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $author->status ? (__('admin.active') ?? 'Active') : (__('admin.inactive') ?? 'Inactive') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button"
                                                data-author="{{ json_encode([
                                                    'id' => $author->id,
                                                    'name' => $author->name,
                                                    'email' => $author->email ?? '',
                                                    'bio' => $author->bio ?? '',
                                                    'website' => $author->website ?? '',
                                                    'status' => $author->status ? 1 : 0,
                                                    'action' => route('admin.settings.authors.update', $author)
                                                ]) }}"
                                                onclick="editAuthorFromData(this)"
                                                class="text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.authors.destroy', $author) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                                    onclick="return confirm('{{ __('admin.confirm_delete') ?? 'Are you sure you want to delete this author?' }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                    <i class="fas fa-user-pen text-3xl mb-2 text-gray-300 block"></i>
                                    {{ __('admin.no_authors') ?? 'No authors found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 4. PUBLISHERS MANAGEMENT TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-publishers" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-building text-teal-500"></i>
                        {{ __('admin.publishers_menu') ?? 'Publishers Management' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Quickly view, create, and update book publishers.</p>
                </div>
                <button type="button" onclick="openPublisherModal()"
                        class="bg-teal-600 hover:bg-teal-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
                    <i class="fas fa-plus"></i> {{ __('admin.add_new_publisher') ?? 'Add New Publisher' }}
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3">Logo</th>
                            <th class="px-4 py-3">{{ __('admin.name') ?? 'Name' }}</th>
                            <th class="px-4 py-3">Phone / Email</th>
                            <th class="px-4 py-3">Website</th>
                            <th class="px-4 py-3 text-center">{{ __('admin.status') ?? 'Status' }}</th>
                            <th class="px-4 py-3 text-center">{{ __('admin.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($publishers ?? [] as $publisher)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-2">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                        @if($publisher->logo)
                                            <img src="{{ asset('storage/' . $publisher->logo) }}" class="w-full h-full object-contain">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 font-bold text-sm">
                                                {{ strtoupper(substr($publisher->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $publisher->name }}</td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    <div>{{ $publisher->phone ?: '-' }}</div>
                                    <div class="text-gray-400">{{ $publisher->email ?: '' }}</div>
                                </td>
                                <td class="px-4 py-3 text-blue-600 text-xs">
                                    @if($publisher->website)
                                        <a href="{{ $publisher->website }}" target="_blank" class="hover:underline">{{ Str::limit($publisher->website, 30) }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $publisher->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $publisher->status ? (__('admin.active') ?? 'Active') : (__('admin.inactive') ?? 'Inactive') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button"
                                                data-publisher="{{ json_encode([
                                                    'id' => $publisher->id,
                                                    'name' => $publisher->name,
                                                    'address' => $publisher->address ?? '',
                                                    'phone' => $publisher->phone ?? '',
                                                    'email' => $publisher->email ?? '',
                                                    'website' => $publisher->website ?? '',
                                                    'status' => $publisher->status ? 1 : 0,
                                                    'action' => route('admin.settings.publishers.update', $publisher)
                                                ]) }}"
                                                onclick="editPublisherFromData(this)"
                                                class="text-blue-600 hover:text-blue-800 font-medium">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.settings.publishers.destroy', $publisher) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                                    onclick="return confirm('{{ __('admin.confirm_delete') ?? 'Are you sure you want to delete this publisher?' }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                                    <i class="fas fa-building text-3xl mb-2 text-gray-300 block"></i>
                                    {{ __('admin.no_publishers') ?? 'No publishers found' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 5. PRINT SETTINGS TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-print" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-print text-indigo-500"></i>
                        {{ __('admin.print_settings') ?? 'Print Settings' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Configure default printing preferences, rates, paper sizes, and extra options.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.print') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.print_type') ?? 'Print Type' }}</label>
                        <select name="print_type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="digital" {{ ($printSettings->print_type ?? 'digital') == 'digital' ? 'selected' : '' }}>Digital Printing</option>
                            <option value="offset" {{ ($printSettings->print_type ?? '') == 'offset' ? 'selected' : '' }}>Offset Printing</option>
                            <option value="screen" {{ ($printSettings->print_type ?? '') == 'screen' ? 'selected' : '' }}>Screen Printing</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.paper_size') ?? 'Paper Size' }}</label>
                        <select name="paper_size" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="A4" {{ ($printSettings->paper_size ?? 'A4') == 'A4' ? 'selected' : '' }}>A4 (210 x 297 mm)</option>
                            <option value="A3" {{ ($printSettings->paper_size ?? '') == 'A3' ? 'selected' : '' }}>A3 (297 x 420 mm)</option>
                            <option value="A5" {{ ($printSettings->paper_size ?? '') == 'A5' ? 'selected' : '' }}>A5 (148 x 210 mm)</option>
                            <option value="Letter" {{ ($printSettings->paper_size ?? '') == 'Letter' ? 'selected' : '' }}>Letter (8.5 x 11 in)</option>
                            <option value="Legal" {{ ($printSettings->paper_size ?? '') == 'Legal' ? 'selected' : '' }}>Legal (8.5 x 14 in)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paper Type</label>
                        <select name="paper_type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="standard" {{ ($printSettings->paper_type ?? 'standard') == 'standard' ? 'selected' : '' }}>Standard (80gsm)</option>
                            <option value="premium" {{ ($printSettings->paper_type ?? '') == 'premium' ? 'selected' : '' }}>Premium (100gsm)</option>
                            <option value="glossy" {{ ($printSettings->paper_type ?? '') == 'glossy' ? 'selected' : '' }}>Glossy Photo</option>
                            <option value="matte" {{ ($printSettings->paper_type ?? '') == 'matte' ? 'selected' : '' }}>Matte Cardstock</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.print_quality') ?? 'Print Quality' }}</label>
                        <select name="print_quality" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="high" {{ ($printSettings->print_quality ?? 'high') == 'high' ? 'selected' : '' }}>High Quality (300+ DPI)</option>
                            <option value="medium" {{ ($printSettings->print_quality ?? '') == 'medium' ? 'selected' : '' }}>Medium (150 DPI)</option>
                            <option value="low" {{ ($printSettings->print_quality ?? '') == 'low' ? 'selected' : '' }}>Draft Mode</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.copies') ?? 'Default Copies' }}</label>
                        <input type="number" name="copies" value="{{ old('copies', $printSettings->copies ?? 1) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.color_mode') ?? 'Color Mode' }}</label>
                        <select name="color_mode" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                            <option value="color" {{ ($printSettings->color_mode ?? 'color') == 'color' ? 'selected' : '' }}>Full Color</option>
                            <option value="black_white" {{ ($printSettings->color_mode ?? '') == 'black_white' ? 'selected' : '' }}>Black & White</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.price_per_page') ?? 'Price Per Page ($)' }}</label>
                        <input type="number" name="price_per_page" value="{{ old('price_per_page', $printSettings->price_per_page ?? 0.05) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Setup Fee ($)</label>
                        <input type="number" name="setup_fee" value="{{ old('setup_fee', $printSettings->setup_fee ?? 0.00) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Shipping Fee ($)</label>
                        <input type="number" name="shipping_fee" value="{{ old('shipping_fee', $printSettings->shipping_fee ?? 0.00) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="md:col-span-3 border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="double_sided" value="1" {{ ($printSettings->double_sided ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Allow Double-Sided Printing</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="binding" value="1" {{ ($printSettings->binding ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Book Binding Option</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ ($printSettings->status ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">Print Services Enabled</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> {{ __('admin.save_settings') ?? 'Save Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 6. ORDER SETTINGS TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-order" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-green-500"></i>
                        {{ __('admin.order_settings') ?? 'Order Settings' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Configure order thresholds, automated workflows, and order timeouts.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.order') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_amount') ?? 'Min Order Amount ($)' }}</label>
                        <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $orderSettings->min_order_amount ?? 0) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_order_amount') ?? 'Max Order Amount ($)' }}</label>
                        <input type="number" name="max_order_amount" value="{{ old('max_order_amount', $orderSettings->max_order_amount ?? '') }}" step="0.01" min="0" placeholder="No maximum limit"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Quantity Per Item</label>
                        <input type="number" name="max_quantity_per_item" value="{{ old('max_quantity_per_item', $orderSettings->max_quantity_per_item ?? 10) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.order_timeout') ?? 'Order Timeout (minutes)' }}</label>
                        <input type="number" name="order_timeout" value="{{ old('order_timeout', $orderSettings->order_timeout ?? 30) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.payment_grace_period') ?? 'Payment Grace Period (minutes)' }}</label>
                        <input type="number" name="payment_grace_period" value="{{ old('payment_grace_period', $orderSettings->payment_grace_period ?? 15) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Auto Complete Days</label>
                        <input type="number" name="auto_complete_days" value="{{ old('auto_complete_days', $orderSettings->auto_complete_days ?? 7) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm">
                    </div>

                    <div class="md:col-span-3 border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="auto_confirm" value="1" {{ ($orderSettings->auto_confirm ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">{{ __('admin.auto_confirm') ?? 'Auto Confirm New Orders' }}</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="auto_complete" value="1" {{ ($orderSettings->auto_complete ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">{{ __('admin.auto_complete') ?? 'Auto Complete Delivered Orders' }}</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="notify_on_new_order" value="1" {{ ($orderSettings->notify_on_new_order ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Notify Admins on New Order</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="notify_on_status_change" value="1" {{ ($orderSettings->notify_on_status_change ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Notify Customers on Status Change</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ ($orderSettings->status ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-green-600 rounded focus:ring-green-500">
                            <span class="text-sm font-medium text-gray-700">Order System Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> {{ __('admin.save_settings') ?? 'Save Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 7. DISCOUNT SETTINGS TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-discount" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-percent text-yellow-500"></i>
                        {{ __('admin.discount_settings') ?? 'Discount Settings' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Manage default store discount rates, maximum caps, and auto-apply rules.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.discount') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.default_discount') ?? 'Default Discount' }} (%)</label>
                        <input type="number" name="default_discount" value="{{ old('default_discount', $discountSettings->default_discount ?? 0) }}" step="0.01" min="0" max="100"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_discount') ?? 'Max Discount Cap' }} (%)</label>
                        <input type="number" name="max_discount" value="{{ old('max_discount', $discountSettings->max_discount ?? 50) }}" step="0.01" min="0" max="100"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.discount_type') ?? 'Discount Type' }}</label>
                        <select name="discount_type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                            <option value="percentage" {{ ($discountSettings->discount_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>{{ __('admin.discount_type_percentage') ?? 'Percentage (%)' }}</option>
                            <option value="fixed" {{ ($discountSettings->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>{{ __('admin.discount_type_fixed') ?? 'Fixed Amount ($)' }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_for_discount') ?? 'Min Order Amount for Discount ($)' }}</label>
                        <input type="number" name="min_order_for_discount" value="{{ old('min_order_for_discount', $discountSettings->min_order_for_discount ?? 0) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Quantity for Discount</label>
                        <input type="number" name="min_quantity_for_discount" value="{{ old('min_quantity_for_discount', $discountSettings->min_quantity_for_discount ?? 1) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 text-sm">
                    </div>

                    <div class="md:col-span-3 border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="auto_apply" value="1" {{ ($discountSettings->auto_apply ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-yellow-600 rounded focus:ring-yellow-500">
                            <span class="text-sm font-medium text-gray-700">{{ __('admin.auto_apply') ?? 'Auto-Apply Store Discounts' }}</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ ($discountSettings->status ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-yellow-600 rounded focus:ring-yellow-500">
                            <span class="text-sm font-medium text-gray-700">Discount System Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> {{ __('admin.save_settings') ?? 'Save Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 8. COUPON SETTINGS TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-coupon" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-ticket-alt text-pink-500"></i>
                        {{ __('admin.coupon_settings') ?? 'Coupon Settings' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Configure default coupon behaviors, usage limits, and validity periods.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.coupon') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.coupon_type') ?? 'Default Coupon Type' }}</label>
                        <select name="coupon_type" class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                            <option value="percentage" {{ ($couponSettings->coupon_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>{{ __('admin.discount_type_percentage') ?? 'Percentage (%)' }}</option>
                            <option value="fixed" {{ ($couponSettings->coupon_type ?? '') == 'fixed' ? 'selected' : '' }}>{{ __('admin.discount_type_fixed') ?? 'Fixed Amount ($)' }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.default_discount_value') ?? 'Default Discount Value' }}</label>
                        <input type="number" name="default_discount_value" value="{{ old('default_discount_value', $couponSettings->default_discount_value ?? 10) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.coupon_duration') ?? 'Default Validity (Days)' }}</label>
                        <input type="number" name="coupon_duration" value="{{ old('coupon_duration', $couponSettings->coupon_duration ?? 30) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.max_coupon_per_user') ?? 'Max Coupons Per User' }}</label>
                        <input type="number" name="max_coupon_per_user" value="{{ old('max_coupon_per_user', $couponSettings->max_coupon_per_user ?? 3) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Global Usage Limit</label>
                        <input type="number" name="global_usage_limit" value="{{ old('global_usage_limit', $couponSettings->global_usage_limit ?? 100) }}" min="1"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.min_order_for_coupon') ?? 'Min Order Amount for Coupon ($)' }}</label>
                        <input type="number" name="min_order_for_coupon" value="{{ old('min_order_for_coupon', $couponSettings->min_order_for_coupon ?? 0) }}" step="0.01" min="0"
                               class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 text-sm">
                    </div>

                    <div class="md:col-span-3 border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="coupon_auto_apply" value="1" {{ ($couponSettings->coupon_auto_apply ?? false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-pink-600 rounded focus:ring-pink-500">
                            <span class="text-sm font-medium text-gray-700">{{ __('admin.coupon_auto_apply') ?? 'Auto-Apply Eligible Coupons at Checkout' }}</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ ($couponSettings->status ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-pink-600 rounded focus:ring-pink-500">
                            <span class="text-sm font-medium text-gray-700">Coupon System Active</span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> {{ __('admin.save_settings') ?? 'Save Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- 9. NOTIFICATION SETTINGS TAB -->
    <!-- ============================================================ -->
    <div id="tab-content-notification" class="tab-content hidden space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-800 flex items-center gap-2">
                        <i class="fas fa-bell text-rose-500"></i>
                        {{ __('admin.notification_settings') ?? 'Notification Settings' }}
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Toggle notification delivery channels and trigger event alerts.</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.notification') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Notification Delivery Channels</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="email_notifications" value="1" {{ ($notificationSettings->email_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ __('admin.email_notifications') ?? 'Email Alerts' }}</div>
                                    <div class="text-xs text-gray-400">Send updates via Email</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="sms_notifications" value="1" {{ ($notificationSettings->sms_notifications ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ __('admin.sms_notifications') ?? 'SMS Alerts' }}</div>
                                    <div class="text-xs text-gray-400">Send updates via SMS</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="push_notifications" value="1" {{ ($notificationSettings->push_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <div>
                                    <div class="text-sm font-medium text-gray-800">{{ __('admin.push_notifications') ?? 'Push Alerts' }}</div>
                                    <div class="text-xs text-gray-400">Web/Mobile push</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="in_app_notifications" value="1" {{ ($notificationSettings->in_app_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <div>
                                    <div class="text-sm font-medium text-gray-800">In-App Alerts</div>
                                    <div class="text-xs text-gray-400">Dashboard inbox</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-3">Event Triggers</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="order_notifications" value="1" {{ ($notificationSettings->order_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('admin.order_notifications') ?? 'Order Events' }}</span>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="payment_notifications" value="1" {{ ($notificationSettings->payment_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <span class="text-sm font-medium text-gray-700">Payment Events</span>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="promotion_notifications" value="1" {{ ($notificationSettings->promotion_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('admin.promotion_notifications') ?? 'Promotions & Deals' }}</span>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="system_notifications" value="1" {{ ($notificationSettings->system_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <span class="text-sm font-medium text-gray-700">{{ __('admin.system_notifications') ?? 'System Updates' }}</span>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="security_notifications" value="1" {{ ($notificationSettings->security_notifications ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <span class="text-sm font-medium text-gray-700">Security Alerts</span>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="allow_user_preferences" value="1" {{ ($notificationSettings->allow_user_preferences ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                <span class="text-sm font-medium text-gray-700">Allow User Customization</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-rose-600 hover:bg-rose-700 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm flex items-center gap-2 text-sm">
                        <i class="fas fa-save"></i> {{ __('admin.save_settings') ?? 'Save Settings' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL 1: TYPE MODAL -->
<!-- ============================================================ -->
<div id="typeModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800" id="typeModalTitle">{{ __('admin.add_new_type') ?? 'Add New Type' }}</h3>
            <button type="button" onclick="closeTypeModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="typeForm" action="{{ route('admin.settings.types.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="typeMethod" value="POST">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.name') ?? 'Type Name' }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="typeName" required
                       class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.description') ?? 'Description' }}</label>
                <textarea name="description" id="typeDescription" rows="3"
                          class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"></textarea>
            </div>

            <label class="flex items-center gap-2 cursor-pointer pt-1">
                <input type="checkbox" name="status" id="typeStatus" value="1" checked
                       class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500">
                <span class="text-sm font-medium text-gray-700">{{ __('admin.active') ?? 'Active' }}</span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeTypeModal()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg transition text-sm">
                    {{ __('admin.cancel') ?? 'Cancel' }}
                </button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium px-5 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> <span id="typeSubmitText">{{ __('admin.save') ?? 'Save' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL 2: AUTHOR MODAL -->
<!-- ============================================================ -->
<div id="authorModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800" id="authorModalTitle">{{ __('admin.add_new_author') ?? 'Add New Author' }}</h3>
            <button type="button" onclick="closeAuthorModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="authorForm" action="{{ route('admin.settings.authors.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="authorMethod" value="POST">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.name') ?? 'Author Name' }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="authorName" required
                       class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="authorEmail"
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" id="authorWebsite" placeholder="https://"
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Biography</label>
                <textarea name="bio" id="authorBio" rows="2"
                          class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Author Image</label>
                <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 cursor-pointer">
            </div>

            <label class="flex items-center gap-2 cursor-pointer pt-1">
                <input type="checkbox" name="status" id="authorStatus" value="1" checked
                       class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                <span class="text-sm font-medium text-gray-700">{{ __('admin.active') ?? 'Active' }}</span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeAuthorModal()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg transition text-sm">
                    {{ __('admin.cancel') ?? 'Cancel' }}
                </button>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-medium px-5 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> <span id="authorSubmitText">{{ __('admin.save') ?? 'Save' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- MODAL 3: PUBLISHER MODAL -->
<!-- ============================================================ -->
<div id="publisherModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden">
        <div class="flex justify-between items-center p-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800" id="publisherModalTitle">{{ __('admin.add_new_publisher') ?? 'Add New Publisher' }}</h3>
            <button type="button" onclick="closePublisherModal()" class="text-gray-400 hover:text-gray-600 p-1">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="publisherForm" action="{{ route('admin.settings.publishers.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="publisherMethod" value="POST">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.name') ?? 'Publisher Name' }} <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="publisherName" required
                       class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="publisherEmail"
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="publisherPhone"
                           class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input type="url" name="website" id="publisherWebsite" placeholder="https://"
                       class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" id="publisherAddress" rows="2"
                          class="w-full px-3.5 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Publisher Logo</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer">
            </div>

            <label class="flex items-center gap-2 cursor-pointer pt-1">
                <input type="checkbox" name="status" id="publisherStatus" value="1" checked
                       class="w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                <span class="text-sm font-medium text-gray-700">{{ __('admin.active') ?? 'Active' }}</span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closePublisherModal()"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-4 py-2 rounded-lg transition text-sm">
                    {{ __('admin.cancel') ?? 'Cancel' }}
                </button>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium px-5 py-2 rounded-lg transition text-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> <span id="publisherSubmitText">{{ __('admin.save') ?? 'Save' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ============================================================
    // TAB SWITCHING FUNCTIONALITY
    // ============================================================
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-sm');
            btn.classList.add('text-gray-600', 'hover:bg-gray-100');
        });

        const activeContent = document.getElementById('tab-content-' + tabName);
        const activeBtn = document.getElementById('tab-btn-' + tabName);

        if (activeContent) {
            activeContent.classList.remove('hidden');
        } else {
            const fallbackContent = document.getElementById('tab-content-general');
            if (fallbackContent) fallbackContent.classList.remove('hidden');
            tabName = 'general';
        }

        if (activeBtn) {
            activeBtn.classList.remove('text-gray-600', 'hover:bg-gray-100');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-sm');
        }

        if (history.pushState) {
            window.history.pushState(null, '', '/admin/settings/' + tabName);
        }
    }

    // ============================================================
    // TYPE MODAL FUNCTIONS
    // ============================================================
    function openTypeModal() {
        document.getElementById('typeModal').classList.remove('hidden');
        document.getElementById('typeModalTitle').textContent = '{{ __("admin.add_new_type") ?? "Add New Type" }}';
        document.getElementById('typeName').value = '';
        document.getElementById('typeDescription').value = '';
        document.getElementById('typeStatus').checked = true;
        document.getElementById('typeSubmitText').textContent = '{{ __("admin.save") ?? "Save" }}';

        const form = document.getElementById('typeForm');
        form.action = '{{ route("admin.settings.types.store") }}';
        document.getElementById('typeMethod').value = 'POST';
    }

    function editTypeFromData(btn) {
        const data = JSON.parse(btn.getAttribute('data-type'));
        document.getElementById('typeModal').classList.remove('hidden');
        document.getElementById('typeModalTitle').textContent = '{{ __("admin.edit_type") ?? "Edit Type" }}';
        document.getElementById('typeName').value = data.name || '';
        document.getElementById('typeDescription').value = data.description || '';
        document.getElementById('typeStatus').checked = data.status == 1;
        document.getElementById('typeSubmitText').textContent = '{{ __("admin.update") ?? "Update" }}';

        const form = document.getElementById('typeForm');
        form.action = data.action;
        document.getElementById('typeMethod').value = 'PUT';
    }

    function closeTypeModal() {
        document.getElementById('typeModal').classList.add('hidden');
    }

    // ============================================================
    // AUTHOR MODAL FUNCTIONS
    // ============================================================
    function openAuthorModal() {
        document.getElementById('authorModal').classList.remove('hidden');
        document.getElementById('authorModalTitle').textContent = '{{ __("admin.add_new_author") ?? "Add New Author" }}';
        document.getElementById('authorName').value = '';
        document.getElementById('authorEmail').value = '';
        document.getElementById('authorBio').value = '';
        document.getElementById('authorWebsite').value = '';
        document.getElementById('authorStatus').checked = true;
        document.getElementById('authorSubmitText').textContent = '{{ __("admin.save") ?? "Save" }}';

        const form = document.getElementById('authorForm');
        form.action = '{{ route("admin.settings.authors.store") }}';
        document.getElementById('authorMethod').value = 'POST';
    }

    function editAuthorFromData(btn) {
        const data = JSON.parse(btn.getAttribute('data-author'));
        document.getElementById('authorModal').classList.remove('hidden');
        document.getElementById('authorModalTitle').textContent = 'Edit Author';
        document.getElementById('authorName').value = data.name || '';
        document.getElementById('authorEmail').value = data.email || '';
        document.getElementById('authorBio').value = data.bio || '';
        document.getElementById('authorWebsite').value = data.website || '';
        document.getElementById('authorStatus').checked = data.status == 1;
        document.getElementById('authorSubmitText').textContent = '{{ __("admin.update") ?? "Update" }}';

        const form = document.getElementById('authorForm');
        form.action = data.action;
        document.getElementById('authorMethod').value = 'PUT';
    }

    function closeAuthorModal() {
        document.getElementById('authorModal').classList.add('hidden');
    }

    // ============================================================
    // PUBLISHER MODAL FUNCTIONS
    // ============================================================
    function openPublisherModal() {
        document.getElementById('publisherModal').classList.remove('hidden');
        document.getElementById('publisherModalTitle').textContent = '{{ __("admin.add_new_publisher") ?? "Add New Publisher" }}';
        document.getElementById('publisherName').value = '';
        document.getElementById('publisherAddress').value = '';
        document.getElementById('publisherPhone').value = '';
        document.getElementById('publisherEmail').value = '';
        document.getElementById('publisherWebsite').value = '';
        document.getElementById('publisherStatus').checked = true;
        document.getElementById('publisherSubmitText').textContent = '{{ __("admin.save") ?? "Save" }}';

        const form = document.getElementById('publisherForm');
        form.action = '{{ route("admin.settings.publishers.store") }}';
        document.getElementById('publisherMethod').value = 'POST';
    }

    function editPublisherFromData(btn) {
        const data = JSON.parse(btn.getAttribute('data-publisher'));
        document.getElementById('publisherModal').classList.remove('hidden');
        document.getElementById('publisherModalTitle').textContent = 'Edit Publisher';
        document.getElementById('publisherName').value = data.name || '';
        document.getElementById('publisherAddress').value = data.address || '';
        document.getElementById('publisherPhone').value = data.phone || '';
        document.getElementById('publisherEmail').value = data.email || '';
        document.getElementById('publisherWebsite').value = data.website || '';
        document.getElementById('publisherStatus').checked = data.status == 1;
        document.getElementById('publisherSubmitText').textContent = '{{ __("admin.update") ?? "Update" }}';

        const form = document.getElementById('publisherForm');
        form.action = data.action;
        document.getElementById('publisherMethod').value = 'PUT';
    }

    function closePublisherModal() {
        document.getElementById('publisherModal').classList.add('hidden');
    }

    // Initial activation based on active tab from backend or URL
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = '{{ $activeTab ?? "general" }}';
        switchTab(activeTab);

        // Backdrop click handlers to close modals
        ['typeModal', 'authorModal', 'publisherModal'].forEach(modalId => {
            document.getElementById(modalId)?.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
        });

        // Close modals on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeTypeModal();
                closeAuthorModal();
                closePublisherModal();
            }
        });
    });
</script>
@endpush
@endsection

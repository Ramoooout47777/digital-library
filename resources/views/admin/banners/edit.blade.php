{{-- resources/views/admin/banners/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.edit_banner') ?? 'កែបដិបក្ខ')
@section('page-title', __('admin.edit_banner') ?? 'កែបដិបក្ខ')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.banner_title') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $banner->title) }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.description') }}</label>
                    <textarea name="description" rows="3"
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $banner->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Link -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.banner_link') }}</label>
                    <input type="url" name="link" value="{{ old('link', $banner->link) }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('link') border-red-500 @enderror"
                           placeholder="https://example.com">
                    @error('link')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Position -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.banner_position') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="position" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('position') border-red-500 @enderror" required>
                        <option value="home" {{ old('position', $banner->position) == 'home' ? 'selected' : '' }}>{{ __('admin.home') ?? 'ទំព័រដើម' }}</option>
                        <option value="sidebar" {{ old('position', $banner->position) == 'sidebar' ? 'selected' : '' }}>{{ __('admin.sidebar') ?? 'ប្រអប់ចំហៀង' }}</option>
                        <option value="footer" {{ old('position', $banner->position) == 'footer' ? 'selected' : '' }}>{{ __('admin.footer') ?? 'បាតក្រដាស' }}</option>
                        <option value="popup" {{ old('position', $banner->position) == 'popup' ? 'selected' : '' }}>{{ __('admin.popup') ?? 'ប៉ុបអាប់' }}</option>
                    </select>
                    @error('position')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Current Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.current_image') }}</label>
                    @if($banner->image)
                        <img src="{{ asset('storage/' . $banner->image) }}"
                             alt="{{ $banner->title }}"
                             class="w-full h-48 object-cover rounded-lg shadow mb-2">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">{{ __('admin.change_image') }}</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                        <p class="text-xs text-gray-500">{{ __('admin.click_to_upload') }}</p>
                        <p class="text-xs text-gray-400">JPG, PNG, WEBP (Max 2MB)</p>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <div id="image-preview" class="mt-2 hidden">
                        <img id="image-preview-img" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg shadow">
                        <button type="button" onclick="removeImage()" class="text-red-500 text-sm mt-1">
                            <i class="fas fa-times"></i> {{ __('admin.remove') }}
                        </button>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', $banner->order) }}" min="0"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.starts_at') }}</label>
                    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $banner->starts_at ? $banner->starts_at->format('Y-m-d\TH:i') : '') }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('starts_at') border-red-500 @enderror">
                    @error('starts_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.ends_at') }}</label>
                    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $banner->ends_at ? $banner->ends_at->format('Y-m-d\TH:i') : '') }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ends_at') border-red-500 @enderror">
                    @error('ends_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer">
                        <input type="checkbox" name="status" value="1" {{ old('status', $banner->status) ? 'checked' : '' }}
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
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>

            <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition ml-auto"
                    onclick="if(confirm('{{ __('admin.confirm_delete') ?? 'Are you sure you want to delete this banner?' }}')) document.getElementById('delete-banner-form').submit();">
                <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
            </button>
        </div>
    </form>

    <form id="delete-banner-form" action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview-img').src = e.target.result;
                document.getElementById('image-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('image-preview').classList.add('hidden');
    }
</script>
@endpush
@endsection

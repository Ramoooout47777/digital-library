{{-- resources/views/admin/categories/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.add_new_category') ?? 'បន្ថែមប្រភេទថ្មី')
@section('page-title', __('admin.add_new_category') ?? 'បន្ថែមប្រភេទថ្មី')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.category_name') ?? 'ឈ្មោះប្រភេទ' }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.slug') }}</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
                           placeholder="{{ __('admin.auto_generated') ?? 'បង្កើតដោយស្វ័យប្រវត្តិ' }}">
                    <p class="text-xs text-gray-400 mt-1">{{ __('admin.leave_empty_for_auto') ?? 'ទុកចោលដើម្បីបង្កើតដោយស្វ័យប្រវត្តិ' }}</p>
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Parent Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.parent_category') ?? 'ប្រភេទមេ' }}</label>
                    <select name="parent_id" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                        <option value="">{{ __('admin.no_parent') ?? 'គ្មានមេ' }}</option>
                        @foreach($parentCategories as $category)
                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Order -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.order') ?? 'លំដាប់' }}</label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" min="0"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('order') border-red-500 @enderror">
                    @error('order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.category_description') ?? 'ការពិពណ៌នា' }}</label>
                    <textarea name="description" rows="4" 
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.category_image') ?? 'រូបភាព' }}</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-500">{{ __('admin.click_to_upload') ?? 'ចុចដើម្បីផ្ទុកឡើង' }}</p>
                        <p class="text-xs text-gray-400">JPG, PNG, WEBP (Max 2MB)</p>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <div id="image-preview" class="mt-2 hidden">
                        <img id="image-preview-img" src="" alt="Preview" class="w-32 h-32 object-cover rounded shadow">
                        <button type="button" onclick="removeImage()" class="text-red-500 text-sm mt-1">
                            <i class="fas fa-times"></i> {{ __('admin.remove') ?? 'យកចេញ' }}
                        </button>
                    </div>
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                        <span class="text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>{{ __('admin.create') }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Auto generate slug from name
    document.querySelector('input[name="name"]').addEventListener('input', function() {
        const slugInput = document.querySelector('input[name="slug"]');
        if (!slugInput.value || slugInput.dataset.auto === 'true') {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^a-z0-9]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugInput.dataset.auto = 'true';
        }
    });
    
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
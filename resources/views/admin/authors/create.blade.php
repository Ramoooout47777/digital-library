{{-- resources/views/admin/authors/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.add_new_author'))
@section('page-title', __('admin.add_new_author'))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.authors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.author_name') }} <span class="text-red-500">*</span>
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
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.author_email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Website -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.author_website') }}</label>
                    <input type="url" name="website" value="{{ old('website') }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror"
                           placeholder="https://example.com">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Biography -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.author_bio') }}</label>
                    <textarea name="bio" rows="5" 
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio') }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.image') ?? 'រូបភាព' }}</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-500">{{ __('admin.click_to_upload') ?? 'ចុចដើម្បីផ្ទុកឡើង' }}</p>
                        <p class="text-xs text-gray-400">JPG, PNG, WEBP (Max 2MB)</p>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>
                    <div id="image-preview" class="mt-2 hidden">
                        <img id="image-preview-img" src="" alt="Preview" class="w-24 h-24 object-cover rounded-full border-2 border-blue-500">
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
            <a href="{{ route('admin.authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
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
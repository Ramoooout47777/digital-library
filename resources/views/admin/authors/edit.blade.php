{{-- resources/views/admin/authors/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.edit_author') ?? 'កែអ្នកនិពន្ធ')
@section('page-title', __('admin.edit_author') ?? 'កែអ្នកនិពន្ធ')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.authors.update', $author) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.author_name') ?? 'ឈ្មោះអ្នកនិពន្ធ' }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $author->name) }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.bio') ?? 'ជីវការី' }}</label>
                    <textarea name="bio" rows="4"
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bio') border-red-500 @enderror">{{ old('bio', $author->bio) }}</textarea>
                    @error('bio')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.email') ?? 'អ៊ីមែល' }}</label>
                    <input type="email" name="email" value="{{ old('email', $author->email) }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.website') ?? 'វែបសាយ' }}</label>
                    <input type="url" name="website" value="{{ old('website', $author->website) }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.author_image') ?? 'រូបភាពអ្នកនិពន្ធ' }}</label>
                    @if($author->image)
                        <img src="{{ asset('storage/' . $author->image) }}" alt="{{ $author->name }}" class="w-32 h-32 object-cover rounded shadow mb-2">
                    @else
                        <div class="w-32 h-32 bg-gray-200 rounded flex items-center justify-center mb-2">
                            <i class="fas fa-user text-gray-400 text-4xl"></i>
                        </div>
                    @endif

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                        <p class="text-xs text-gray-500">{{ __('admin.click_to_upload') ?? 'ចុចដើម្បីផ្ទុកឡើង' }}</p>
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

                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="status" value="1" {{ old('status', $author->status) ? 'checked' : '' }}>
                        <span class="text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>{{ __('admin.update') }}
            </button>
            <a href="{{ route('admin.authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>
        </div>
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

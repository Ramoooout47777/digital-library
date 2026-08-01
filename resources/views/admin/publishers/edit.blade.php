{{-- resources/views/admin/publishers/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.edit_publisher'))
@section('page-title', __('admin.edit_publisher'))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.publishers.update', $publisher) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.publisher_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $publisher->name) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.publisher_address') }}</label>
                    <textarea name="address" rows="3" 
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror">{{ old('address', $publisher->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.publisher_phone') }}</label>
                    <input type="text" name="phone" value="{{ old('phone', $publisher->phone) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $publisher->email) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Website -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.website') }}</label>
                    <input type="url" name="website" value="{{ old('website', $publisher->website) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('website') border-red-500 @enderror"
                           placeholder="https://example.com">
                    @error('website')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Current Logo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.current_logo') }}</label>
                    @if($publisher->logo)
                        <img src="{{ asset('storage/' . $publisher->logo) }}" alt="{{ $publisher->name }}" class="w-20 h-20 object-cover rounded shadow mb-2">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center mb-2">
                            <i class="fas fa-building text-gray-400 text-3xl"></i>
                        </div>
                    @endif
                    
                    <label class="block text-sm font-medium text-gray-700 mb-1 mt-2">{{ __('admin.change_logo') }}</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                         onclick="document.getElementById('logo').click()">
                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                        <p class="text-xs text-gray-500">{{ __('admin.click_to_upload') }}</p>
                        <input type="file" id="logo" name="logo" accept="image/*" class="hidden" onchange="previewLogo(this)">
                    </div>
                    <div id="logo-preview" class="mt-2 hidden">
                        <img id="logo-preview-img" src="" alt="Preview" class="w-20 h-20 object-cover rounded">
                        <button type="button" onclick="removeLogo()" class="text-red-500 text-sm mt-1">
                            <i class="fas fa-times"></i> {{ __('admin.remove') }}
                        </button>
                    </div>
                    @error('logo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="status" value="1" {{ old('status', $publisher->status) ? 'checked' : '' }}>
                        <span class="text-sm">{{ __('admin.active') }}</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>{{ __('admin.update') }}
            </button>
            <a href="{{ route('admin.publishers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>
            
            @if($publisher->books()->count() == 0)
                <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="inline ml-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition"
                            onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                        <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                    </button>
                </form>
            @else
                <span class="ml-auto text-sm text-yellow-600">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    {{ __('admin.cannot_delete_has_books') }}
                </span>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview-img').src = e.target.result;
                document.getElementById('logo-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeLogo() {
        document.getElementById('logo').value = '';
        document.getElementById('logo-preview').classList.add('hidden');
    }
</script>
@endpush
@endsection
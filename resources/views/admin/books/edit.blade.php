{{-- resources/views/admin/books/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.edit_book'))
@section('page-title', __('admin.edit_book'))

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.book_title') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                           required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.categories_menu') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror" required>
                        <option value="">{{ __('admin.select_category') ?? 'ជ្រើសរើសប្រភេទ' }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Author -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.author') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="author_id" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('author_id') border-red-500 @enderror" required>
                        <option value="">{{ __('admin.select_author') ?? 'ជ្រើសរើសអ្នកនិពន្ធ' }}</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Publisher -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.publishers_menu') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="publisher_id" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('publisher_id') border-red-500 @enderror" required>
                        <option value="">{{ __('admin.select_publisher') ?? 'ជ្រើសរើសគ្រឹះស្ថានបោះពុម្ព' }}</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('publisher_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- ISBN -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.isbn') }}</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}" 
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isbn') border-red-500 @enderror"
                           placeholder="978-3-16-148410-0">
                    @error('isbn')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Right Column -->
            <div class="space-y-4">
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.book_description') }}</label>
                    <textarea name="description" rows="4" 
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $book->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.price') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $book->price) }}" step="0.01" min="0"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                           required>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Discount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.discount') }}</label>
                    <input type="number" name="discount" value="{{ old('discount', $book->discount) }}" step="0.01" min="0" max="100"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('discount') border-red-500 @enderror">
                    @error('discount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Stock & Pages -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('admin.stock') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 @enderror"
                               required>
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('admin.pages') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="pages" value="{{ old('pages', $book->pages) }}" min="1"
                               class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pages') border-red-500 @enderror"
                               required>
                        @error('pages')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Language -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.language') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="language" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('language') border-red-500 @enderror" required>
                        <option value="km" {{ old('language', $book->language) == 'km' ? 'selected' : '' }}>ភាសាខ្មែរ</option>
                        <option value="en" {{ old('language', $book->language) == 'en' ? 'selected' : '' }}>English</option>
                        <option value="zh" {{ old('language', $book->language) == 'zh' ? 'selected' : '' }}>中文</option>
                    </select>
                    @error('language')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Published At -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.published_at') }}</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $book->published_at ? $book->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('published_at') border-red-500 @enderror">
                    @error('published_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- File Uploads -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Current Cover -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.current_cover') ?? 'គម្របបច្ចុប្បន្ន' }}</label>
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-32 h-40 object-cover rounded shadow">
                @else
                    <div class="w-32 h-40 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fas fa-book text-gray-400 text-2xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- New Cover -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.book_cover') }}</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                     onclick="document.getElementById('cover').click()">
                    <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-1"></i>
                    <p class="text-xs text-gray-500">{{ __('admin.change_cover') ?? 'ប្តូរគម្រប' }}</p>
                    <p class="text-xs text-gray-400">JPG, PNG, WEBP (Max 2MB)</p>
                    <input type="file" id="cover" name="cover" accept="image/*" class="hidden" onchange="previewCover(this)">
                </div>
                <div id="cover-preview" class="mt-2 hidden">
                    <img id="cover-preview-img" src="" alt="Cover Preview" class="w-32 h-40 object-cover rounded shadow">
                    <button type="button" onclick="removeCover()" class="text-red-500 text-sm mt-1">
                        <i class="fas fa-times"></i> {{ __('admin.remove') ?? 'យកចេញ' }}
                    </button>
                </div>
                @error('cover')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- PDF File -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.book_pdf') }}</label>
                @if($book->pdf_file)
                    <div class="mb-2">
                        <span class="text-sm text-green-600"><i class="fas fa-check-circle"></i> {{ __('admin.current_pdf') ?? 'PDF បច្ចុប្បន្ន' }}</span>
                        <span class="text-xs text-gray-500 block">{{ basename($book->pdf_file) }}</span>
                    </div>
                @endif
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition cursor-pointer"
                     onclick="document.getElementById('pdf_file').click()">
                    <i class="fas fa-file-pdf text-2xl text-gray-400 mb-1"></i>
                    <p class="text-xs text-gray-500">{{ __('admin.change_pdf') ?? 'ប្តូរ PDF' }}</p>
                    <p class="text-xs text-gray-400">PDF (Max 50MB)</p>
                    <input type="file" id="pdf_file" name="pdf_file" accept=".pdf" class="hidden">
                </div>
                <div id="pdf-preview" class="mt-2 hidden">
                    <span class="text-sm text-green-600"><i class="fas fa-check-circle"></i> PDF {{ __('admin.uploaded') ?? 'បានផ្ទុកឡើង' }}</span>
                    <button type="button" onclick="removePdf()" class="text-red-500 text-sm ml-2">
                        <i class="fas fa-times"></i> {{ __('admin.remove') ?? 'យកចេញ' }}
                    </button>
                </div>
                @error('pdf_file')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Options -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_free" value="1" {{ old('is_free', $book->is_free) ? 'checked' : '' }}>
                <span class="text-sm">{{ __('admin.is_free') }}</span>
            </label>
            
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $book->is_featured) ? 'checked' : '' }}>
                <span class="text-sm">{{ __('admin.is_featured') }}</span>
            </label>
            
            <label class="flex items-center gap-2">
                <input type="checkbox" name="status" value="1" {{ old('status', $book->status) ? 'checked' : '' }}>
                <span class="text-sm">{{ __('admin.active') }}</span>
            </label>
        </div>
        
        <!-- Submit Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-save mr-2"></i>{{ __('admin.update') }}
            </button>
            <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                <i class="fas fa-times mr-2"></i>{{ __('admin.cancel') }}
            </a>
            
            @if($book->trashed())
                <form action="{{ route('admin.books.restore', $book->id) }}" method="POST" class="inline ml-auto">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-undo mr-2"></i>{{ __('admin.restore') ?? 'ស្ដារ' }}
                    </button>
                </form>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
    function previewCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('cover-preview-img').src = e.target.result;
                document.getElementById('cover-preview').classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeCover() {
        document.getElementById('cover').value = '';
        document.getElementById('cover-preview').classList.add('hidden');
    }
    
    function removePdf() {
        document.getElementById('pdf_file').value = '';
        document.getElementById('pdf-preview').classList.add('hidden');
    }
    
    document.getElementById('pdf_file').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            document.getElementById('pdf-preview').classList.remove('hidden');
            document.querySelector('#pdf-preview span').textContent = this.files[0].name;
        }
    });
</script>
@endpush
@endsection
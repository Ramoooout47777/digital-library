{{-- resources/views/admin/books/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.books_menu'))
@section('page-title', __('admin.books_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.books.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.add_new_book') }}
        </a>
        <button onclick="document.getElementById('import-form').submit()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-upload mr-2"></i>
            {{ __('admin.import') }}
        </button>
        <a href="{{ route('admin.books.export') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-download mr-2"></i>
            {{ __('admin.export') }}
        </a>
    </div>
    
    <form action="{{ route('admin.books.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="{{ __('admin.search') }}..." 
               class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Import Form (Hidden) -->
<form id="import-form" action="{{ route('admin.books.bulk-upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="file" name="file" accept=".csv,.xlsx" onchange="this.form.submit()">
</form>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form action="{{ route('admin.books.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.categories_menu') }}</label>
            <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                <option value="trashed" {{ request('status') == 'trashed' ? 'selected' : '' }}>{{ __('admin.deleted') ?? 'បានលុប' }}</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.language') }}</label>
            <select name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="km" {{ request('language') == 'km' ? 'selected' : '' }}>ភាសាខ្មែរ</option>
                <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>English</option>
                <option value="zh" {{ request('language') == 'zh' ? 'selected' : '' }}>中文</option>
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Books Table -->
<div class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.book_cover') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.title') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.categories_menu') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.author') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.price') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.stock') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.language') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') ?? 'សកម្មភាព' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($books as $book)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm">{{ $book->id }}</td>
                        <td class="px-4 py-3">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-12 h-16 object-cover rounded shadow">
                            @else
                                <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $book->title }}</div>
                            @if($book->trashed())
                                <span class="text-xs text-red-500 bg-red-100 px-2 py-1 rounded-full">{{ __('admin.deleted') ?? 'បានលុប' }}</span>
                            @endif
                            @if($book->is_featured)
                                <span class="text-xs text-yellow-500 bg-yellow-100 px-2 py-1 rounded-full">{{ __('admin.is_featured') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $book->category->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $book->author->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            @if($book->is_free)
                                <span class="text-green-600 font-semibold">{{ __('admin.is_free') }}</span>
                            @else
                                <div class="font-semibold text-gray-900">${{ number_format($book->final_price, 2) }}</div>
                                @if($book->discount > 0)
                                    <div class="text-xs text-gray-400 line-through">${{ number_format($book->price, 2) }}</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs {{ $book->stock > 10 ? 'bg-green-100 text-green-800' : ($book->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $book->stock }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100">
                                {{ strtoupper($book->language) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="toggleStatus({{ $book->id }})" 
                                    class="px-3 py-1 text-xs rounded-full transition {{ $book->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $book->status ? __('admin.active') : __('admin.inactive') }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.books.show', $book) }}" 
                                   class="text-green-600 hover:text-green-800 transition" 
                                   title="{{ __('admin.view') ?? 'មើល' }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.books.edit', $book) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" 
                                   title="{{ __('admin.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if($book->trashed())
                                    <form action="{{ route('admin.books.restore', $book->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800 transition" title="{{ __('admin.restore') ?? 'ស្ដារ' }}">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.books.force-delete', $book->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" 
                                                onclick="return confirm('{{ __('admin.confirm_delete') }}')"
                                                title="{{ __('admin.delete_permanent') ?? 'លុបជាអចិន្ត្រៃយ៍' }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" 
                                                onclick="return confirm('{{ __('admin.confirm_delete') }}')"
                                                title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-book-open text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_data') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-4 py-3 border-t flex justify-between items-center flex-wrap gap-2">
        <div class="text-sm text-gray-500">
            {{ __('admin.showing') ?? 'បង្ហាញ' }} {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} 
            {{ __('admin.of') ?? 'នៃ' }} {{ $books->total() }} {{ __('admin.items') ?? 'ធាតុ' }}
        </div>
        {{ $books->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleStatus(bookId) {
        if (!confirm('{{ __("admin.confirm_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាព?" }}')) {
            return;
        }
        
        fetch(`/admin/books/${bookId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('{{ __("admin.error_occurred") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("admin.error_occurred") }}');
        });
    }
</script>
@endpush
@endsection
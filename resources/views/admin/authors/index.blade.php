{{-- resources/views/admin/authors/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.authors_menu'))
@section('page-title', __('admin.authors_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.authors.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.add_new_author') }}
        </a>
        <button onclick="document.getElementById('import-form').submit()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-upload mr-2"></i>
            {{ __('admin.import') }}
        </button>
        <a href="{{ route('admin.authors.export') }}" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-download mr-2"></i>
            {{ __('admin.export') }}
        </a>
    </div>
    
    <form action="{{ route('admin.authors.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="{{ __('admin.search') }}..." 
               class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Import Form (Hidden) -->
<form id="import-form" action="{{ route('admin.authors.bulk-upload') }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="file" name="file" accept=".csv,.xlsx" onchange="this.form.submit()">
</form>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form action="{{ route('admin.authors.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.sort_by') ?? 'តម្រៀបតាម' }}</label>
            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('admin.name_asc') ?? 'ឈ្មោះ ក-អ' }}</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('admin.name_desc') ?? 'ឈ្មោះ អ-ក' }}</option>
                <option value="books_count_desc" {{ request('sort') == 'books_count_desc' ? 'selected' : '' }}>{{ __('admin.most_books') ?? 'សៀវភៅច្រើនជាងគេ' }}</option>
                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>{{ __('admin.newest') ?? 'ថ្មីជាងគេ' }}</option>
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Authors Table -->
<div class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.image') ?? 'រូបភាព' }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.author_name') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.author_email') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.books_count') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.created_at') ?? 'បង្កើតនៅ' }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') ?? 'សកម្មភាព' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($authors as $author)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm">{{ $author->id }}</td>
                        <td class="px-4 py-3">
                            @if($author->image)
                                <img src="{{ asset('storage/' . $author->image) }}" 
                                     alt="{{ $author->name }}" 
                                     class="w-12 h-12 object-cover rounded-full border-2 border-gray-200">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center border-2 border-gray-200">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $author->name }}</div>
                            <div class="text-xs text-gray-500">{{ $author->slug }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($author->email)
                                <a href="mailto:{{ $author->email }}" class="text-blue-500 hover:underline">
                                    {{ $author->email }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <a href="{{ route('admin.books.index', ['author_id' => $author->id]) }}" 
                               class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs hover:bg-blue-200 transition">
                                {{ $author->books_count ?? $author->books()->count() }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="toggleStatus({{ $author->id }})" 
                                    class="px-3 py-1 text-xs rounded-full transition {{ $author->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $author->status ? __('admin.active') : __('admin.inactive') }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $author->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.authors.edit', $author) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" 
                                   title="{{ __('admin.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.authors.show', $author) }}" 
                                   class="text-green-600 hover:text-green-800 transition" 
                                   title="{{ __('admin.view') ?? 'មើល' }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($author->books()->count() == 0)
                                    <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" 
                                                onclick="return confirm('{{ __('admin.confirm_delete') }}')"
                                                title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="{{ __('admin.cannot_delete_has_books') ?? 'មិនអាចលុបបានទេ ព្រោះមានសៀវភៅ' }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-user-edit text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_authors') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-4 py-3 border-t flex justify-between items-center flex-wrap gap-2">
        <div class="text-sm text-gray-500">
            {{ __('admin.showing') ?? 'បង្ហាញ' }} {{ $authors->firstItem() ?? 0 }} - {{ $authors->lastItem() ?? 0 }} 
            {{ __('admin.of') ?? 'នៃ' }} {{ $authors->total() }} {{ __('admin.items') ?? 'ធាតុ' }}
        </div>
        {{ $authors->links() }}
    </div>
</div>

@push('scripts')
<script>
    function toggleStatus(authorId) {
        if (!confirm('{{ __("admin.confirm_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាព?" }}')) {
            return;
        }
        
        fetch(`/admin/authors/${authorId}/toggle-status`, {
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
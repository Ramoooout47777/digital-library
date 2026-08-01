{{-- resources/views/admin/categories/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.categories_menu'))
@section('page-title', __('admin.categories_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.add_new_category') ?? 'បន្ថែមប្រភេទថ្មី' }}
        </a>
        <button onclick="toggleView()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-list-ul mr-2"></i>
            <span id="view-toggle-text">{{ __('admin.tree_view') ?? 'មើលតាមមែកធាង' }}</span>
        </button>
    </div>
    
    <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="{{ __('admin.search') }}..." 
               class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.parent_category') ?? 'ប្រភេទមេ' }}</label>
            <select name="parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="null" {{ request('parent_id') == 'null' ? 'selected' : '' }}>{{ __('admin.no_parent') ?? 'គ្មានមេ' }}</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('parent_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Categories Table View -->
<div id="table-view" class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.category_image') ?? 'រូបភាព' }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.category') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.slug') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.parent_category') ?? 'ប្រភេទមេ' }}</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.books_count') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.order') ?? 'លំដាប់' }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') ?? 'សកម្មភាព' }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm">{{ $category->id }}</td>
                        <td class="px-4 py-3">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}" 
                                     class="w-12 h-12 object-cover rounded">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $category->name }}</div>
                            @if($category->children->count() > 0)
                                <span class="text-xs text-gray-500">{{ $category->children->count() }} {{ __('admin.sub_categories') ?? 'ប្រភេទរង' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $category->slug }}</td>
                        <td class="px-4 py-3 text-sm">{{ $category->parent->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                {{ $category->books_count ?? $category->books()->count() }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="toggleStatus({{ $category->id }})" 
                                    class="px-3 py-1 text-xs rounded-full transition {{ $category->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $category->status ? __('admin.active') : __('admin.inactive') }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">{{ $category->order }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="text-blue-600 hover:text-blue-800 transition" 
                                   title="{{ __('admin.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.categories.show', $category) }}" 
                                   class="text-green-600 hover:text-green-800 transition" 
                                   title="{{ __('admin.view') ?? 'មើល' }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition" 
                                            onclick="return confirm('{{ __('admin.confirm_delete') }}')"
                                            title="{{ __('admin.delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-tags text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_data') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-4 py-3 border-t">
        {{ $categories->links() }}
    </div>
</div>

<!-- Tree View -->
<div id="tree-view" class="hidden bg-white rounded-lg shadow p-6">
    <h3 class="font-semibold text-lg text-gray-800 mb-4">{{ __('admin.category_tree') ?? 'មែកធាងប្រភេទ' }}</h3>
    <div class="space-y-2">
        @foreach($categories as $category)
            @if(!$category->parent_id)
                <div class="border-l-4 border-blue-500 pl-4">
                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                        <div class="flex items-center gap-3">
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" class="w-8 h-8 object-cover rounded">
                            @endif
                            <span class="font-medium">{{ $category->name }}</span>
                            <span class="text-xs text-gray-500">({{ $category->books_count ?? $category->books()->count() }} {{ __('admin.books') }})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-edit"></i>
                            </a>
                            <span class="text-sm text-gray-400">|</span>
                            <span class="text-sm {{ $category->status ? 'text-green-500' : 'text-red-500' }}">
                                {{ $category->status ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </div>
                    </div>
                    
                    @if($category->children->count() > 0)
                        <div class="ml-8 space-y-1 mt-1">
                            @foreach($category->children as $child)
                                <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded border-l-2 border-gray-300 pl-4">
                                    <div class="flex items-center gap-3">
                                        @if($child->image)
                                            <img src="{{ asset('storage/' . $child->image) }}" class="w-6 h-6 object-cover rounded">
                                        @endif
                                        <span>{{ $child->name }}</span>
                                        <span class="text-xs text-gray-500">({{ $child->books_count ?? $child->books()->count() }} {{ __('admin.books') }})</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.categories.edit', $child) }}" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <span class="text-sm {{ $child->status ? 'text-green-500' : 'text-red-500' }}">
                                            {{ $child->status ? __('admin.active') : __('admin.inactive') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    let treeView = false;
    
    function toggleView() {
        treeView = !treeView;
        const tableView = document.getElementById('table-view');
        const treeViewEl = document.getElementById('tree-view');
        const toggleText = document.getElementById('view-toggle-text');
        
        if (treeView) {
            tableView.classList.add('hidden');
            treeViewEl.classList.remove('hidden');
            toggleText.textContent = '{{ __("admin.table_view") ?? "មើលតាមតារាង" }}';
        } else {
            tableView.classList.remove('hidden');
            treeViewEl.classList.add('hidden');
            toggleText.textContent = '{{ __("admin.tree_view") ?? "មើលតាមមែកធាង" }}';
        }
    }
    
    function toggleStatus(categoryId) {
        if (!confirm('{{ __("admin.confirm_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាព?" }}')) {
            return;
        }
        
        fetch(`/admin/categories/${categoryId}/toggle-status`, {
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
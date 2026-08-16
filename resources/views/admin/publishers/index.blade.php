{{-- resources/views/admin/publishers/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.publishers_menu'))
@section('page-title', __('admin.publishers_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.publishers.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.add_new_publisher') }}
        </a>

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="hidden flex gap-2">
            <button onclick="bulkStatus(true)" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-eye mr-2"></i> {{ __('admin.activate_all') ?? 'បើកទាំងអស់' }}
            </button>
            <button onclick="bulkStatus(false)" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-eye-slash mr-2"></i> {{ __('admin.deactivate_all') ?? 'បិទទាំងអស់' }}
            </button>
        </div>
    </div>

    <form action="{{ route('admin.publishers.index') }}" method="GET" class="flex gap-2">
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
    <form action="{{ route('admin.publishers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.sort_by') }}</label>
            <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>{{ __('admin.name_asc') }}</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>{{ __('admin.name_desc') }}</option>
                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>{{ __('admin.newest') }}</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.publishers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Publishers Table -->
<div class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.publisher_logo') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.publisher_name') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.publisher_address') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.publisher_phone') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.books_count') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.created_at') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($publishers as $publisher)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="{{ $publisher->id }}" class="publisher-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $publisher->id }}</td>
                        <td class="px-4 py-3">
                            @if($publisher->logo)
                                <img src="{{ asset('storage/' . $publisher->logo) }}"
                                     alt="{{ $publisher->name }}"
                                     class="w-12 h-12 object-cover rounded">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-building text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $publisher->name }}</div>
                            <div class="text-xs text-gray-500">{{ $publisher->slug }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ Str::limit($publisher->address, 30) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $publisher->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <a href="{{ route('admin.books.index', ['publisher_id' => $publisher->id]) }}"
                               class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs hover:bg-blue-200 transition">
                                {{ $publisher->books_count ?? $publisher->books()->count() }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="toggleStatus({{ $publisher->id }})"
                                    class="px-3 py-1 text-xs rounded-full transition {{ $publisher->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $publisher->status ? __('admin.active') : __('admin.inactive') }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $publisher->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.publishers.edit', $publisher) }}"
                                   class="text-blue-600 hover:text-blue-800 transition"
                                   title="{{ __('admin.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.publishers.show', $publisher) }}"
                                   class="text-green-600 hover:text-green-800 transition"
                                   title="{{ __('admin.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($publisher->books()->count() == 0)
                                    <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition"
                                                onclick="return confirm('{{ __('admin.confirm_delete') }}')"
                                                title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="{{ __('admin.cannot_delete_has_books') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-building text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_publishers') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t flex justify-between items-center flex-wrap gap-2">
        <div class="text-sm text-gray-500">
            {{ __('admin.showing') }} {{ $publishers->firstItem() ?? 0 }} - {{ $publishers->lastItem() ?? 0 }}
            {{ __('admin.of') }} {{ $publishers->total() }} {{ __('admin.items') }}
        </div>
        {{ $publishers->links() }}
    </div>
</div>

<!-- Publisher Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_publishers') }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.active') }}</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
    </div>
    <div class="bg-red-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.inactive') }}</p>
        <p class="text-2xl font-bold text-red-600">{{ $stats['inactive'] ?? 0 }}</p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_books') }}</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['books'] ?? 0 }}</p>
    </div>
</div>

@push('scripts')
<script>
    // Selection logic
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.publisher-checkbox');
    const bulkActions = document.getElementById('bulk-actions');

    function updateBulkVisibility() {
        const checkedCount = document.querySelectorAll('.publisher-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
        if (selectAll) {
            selectAll.checked = checkedCount === checkboxes.length && checkboxes.length > 0;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateBulkVisibility();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkVisibility);
    });

    function toggleStatus(publisherId) {
        if (!confirm('{{ __("admin.confirm_status_change") }}')) {
            return;
        }

        fetch(`/admin/publishers/${publisherId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("admin.error_occurred") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("admin.error_occurred") }}');
        });
    }

    function bulkStatus(status) {
        const selectedIds = Array.from(document.querySelectorAll('.publisher-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) return;

        if (!confirm('{{ __("admin.confirm_bulk_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាពធាតុដែលបានជ្រើសរើស?" }}')) {
            return;
        }

        fetch('{{ route("admin.publishers.bulk-status") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                ids: selectedIds,
                status: status
            })
        })
        .then(async response => {
            const data = await response.json();
            if (response.ok && data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("admin.error_occurred") }}');
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

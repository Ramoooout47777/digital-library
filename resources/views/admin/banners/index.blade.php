{{-- resources/views/admin/banners/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.banners_menu'))
@section('page-title', __('admin.banners_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.banners.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.add_new_banner') }}
        </a>
        <a href="{{ route('admin.banners.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-file-export mr-2"></i>
            {{ __('admin.export') }}
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

    <form action="{{ route('admin.banners.index') }}" method="GET" class="flex gap-2">
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
    <form action="{{ route('admin.banners.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.banner_position') }}</label>
            <select name="position" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="home" {{ request('position') == 'home' ? 'selected' : '' }}>{{ __('admin.home') ?? 'ទំព័រដើម' }}</option>
                <option value="sidebar" {{ request('position') == 'sidebar' ? 'selected' : '' }}>{{ __('admin.sidebar') ?? 'ប្រអប់ចំហៀង' }}</option>
                <option value="footer" {{ request('position') == 'footer' ? 'selected' : '' }}>{{ __('admin.footer') ?? 'បាតក្រដាស' }}</option>
                <option value="popup" {{ request('position') == 'popup' ? 'selected' : '' }}>{{ __('admin.popup') ?? 'ប៉ុបអាប់' }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.date_range') }}</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.banners.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Banners Grid -->
<div class="mb-4 flex items-center gap-2">
    <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
    <label for="select-all" class="text-sm font-medium text-gray-700 cursor-pointer">{{ __('admin.select_all') ?? 'ជ្រើសរើសទាំងអស់' }}</label>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($banners as $banner)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition group relative">
            <!-- Select Checkbox -->
            <div class="absolute top-2 left-2 z-10">
                <input type="checkbox" name="ids[]" value="{{ $banner->id }}" class="banner-checkbox w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer bg-white shadow-sm">
            </div>

            <!-- Banner Image -->
            <div class="relative h-48 bg-gray-100 overflow-hidden">
                @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}"
                         alt="{{ $banner->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                @endif

                <!-- Status Badge -->
                <div class="absolute top-2 right-2">
                    <span class="px-2 py-1 text-xs rounded-full {{ $banner->status ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                        {{ $banner->status ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </div>

                <!-- Position Badge -->
                <div class="absolute bottom-2 left-2">
                    <span class="px-2 py-1 text-xs rounded-full bg-black bg-opacity-50 text-white">
                        {{ ucfirst($banner->position) }}
                    </span>
                </div>
            </div>

            <!-- Banner Content -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 text-lg">{{ $banner->title }}</h3>
                @if($banner->description)
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $banner->description }}</p>
                @endif

                <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                    <span>
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ $banner->created_at->format('d/m/Y') }}
                    </span>
                    @if($banner->link)
                        <span>
                            <i class="fas fa-link mr-1"></i>
                            <a href="{{ $banner->link }}" target="_blank" class="text-blue-500 hover:underline">
                                {{ __('admin.view_link') ?? 'មើលតំណ' }}
                            </a>
                        </span>
                    @endif
                </div>

                @if($banner->starts_at || $banner->ends_at)
                    <div class="mt-2 text-xs">
                        @if($banner->starts_at)
                            <span class="text-gray-500">{{ __('admin.starts_at') ?? 'ចាប់ផ្តើម' }}: {{ $banner->starts_at->format('d/m/Y') }}</span>
                        @endif
                        @if($banner->ends_at)
                            <span class="text-gray-500 ml-2">{{ __('admin.ends_at') ?? 'បញ្ចប់' }}: {{ $banner->ends_at->format('d/m/Y') }}</span>
                        @endif
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center gap-2 mt-4 pt-3 border-t">
                    <a href="{{ route('admin.banners.edit', $banner) }}"
                       class="text-blue-600 hover:text-blue-800 transition text-sm flex items-center gap-1">
                        <i class="fas fa-edit"></i> {{ __('admin.edit') }}
                    </a>

                    <button onclick="toggleStatus({{ $banner->id }})"
                            class="text-sm flex items-center gap-1 {{ $banner->status ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }} transition ml-2">
                        <i class="fas {{ $banner->status ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        {{ $banner->status ? __('admin.deactivate') ?? 'បិទ' : __('admin.activate') ?? 'បើក' }}
                    </button>

                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline ml-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 transition text-sm flex items-center gap-1"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                            <i class="fas fa-trash"></i> {{ __('admin.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                <i class="fas fa-image text-4xl block mb-3 text-gray-300"></i>
                {{ __('admin.no_banners') ?? 'មិនមានបដិបក្ខ' }}
                <div class="mt-3">
                    <a href="{{ route('admin.banners.create') }}" class="text-blue-500 hover:underline">
                        <i class="fas fa-plus mr-1"></i> {{ __('admin.add_new_banner') }}
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $banners->links() }}
</div>

<!-- Banner Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_banners') ?? 'បដិបក្ខសរុប' }}</p>
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
        <p class="text-xs text-gray-500">{{ __('admin.positions') ?? 'ទីតាំង' }}</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['positions'] ?? 0 }}</p>
    </div>
</div>

@push('scripts')
<script>
    // Selection logic
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.banner-checkbox');
    const bulkActions = document.getElementById('bulk-actions');

    function updateBulkVisibility() {
        const checkedCount = document.querySelectorAll('.banner-checkbox:checked').length;
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

    function toggleStatus(bannerId) {
        if (!confirm('{{ __("admin.confirm_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាព?" }}')) {
            return;
        }

        fetch(`/admin/banners/${bannerId}/toggle-status`, {
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
        const selectedIds = Array.from(document.querySelectorAll('.banner-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) return;

        if (!confirm('{{ __("admin.confirm_bulk_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាពធាតុដែលបានជ្រើសរើស?" }}')) {
            return;
        }

        fetch('{{ route("admin.banners.bulk-status") }}', {
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

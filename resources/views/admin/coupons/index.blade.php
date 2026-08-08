{{-- resources/views/admin/coupons/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.coupons_menu'))
@section('page-title', __('admin.coupons_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.coupons.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.add_new_coupon') }}
        </a>
        <a href="{{ route('admin.coupons.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
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

    <form action="{{ route('admin.coupons.index') }}" method="GET" class="flex gap-2">
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
    <form action="{{ route('admin.coupons.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ __('admin.expired') ?? 'ផុតកំណត់' }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.discount_type') }}</label>
            <select name="discount_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="percentage" {{ request('discount_type') == 'percentage' ? 'selected' : '' }}>{{ __('admin.discount_type_percentage') }}</option>
                <option value="fixed" {{ request('discount_type') == 'fixed' ? 'selected' : '' }}>{{ __('admin.discount_type_fixed') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.expires_at') }}</label>
            <input type="date" name="expires_from" value="{{ request('expires_from') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.coupons.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Coupons Table -->
<div class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.coupon_code') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.discount_type') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.discount_value') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.min_order_amount') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.used_count') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.expires_at') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="{{ $coupon->id }}" class="coupon-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $coupon->id }}</td>
                        <td class="px-4 py-3">
                            <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded text-sm">
                                {{ $coupon->code }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 rounded-full text-xs
                                @if($coupon->discount_type == 'percentage') bg-purple-100 text-purple-800
                                @else bg-orange-100 text-orange-800 @endif">
                                {{ $coupon->discount_type == 'percentage' ? __('admin.discount_type_percentage') : __('admin.discount_type_fixed') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold">
                            @if($coupon->discount_type == 'percentage')
                                {{ $coupon->discount_value }}%
                            @else
                                ${{ number_format($coupon->discount_value, 2) }}
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($coupon->min_order_amount > 0)
                                ${{ number_format($coupon->min_order_amount, 2) }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="px-2 py-1 rounded-full text-xs {{ $coupon->usage_limit ? ($coupon->used_count >= $coupon->usage_limit ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800') : 'bg-blue-100 text-blue-800' }}">
                                {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($coupon->expires_at)
                                <span class="{{ $coupon->expires_at < now() ? 'text-red-500' : 'text-gray-600' }}">
                                    {{ $coupon->expires_at->format('d/m/Y') }}
                                    @if($coupon->expires_at < now())
                                        <span class="text-red-500 text-xs block">{{ __('admin.expired') }}</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-gray-400">{{ __('admin.never') ?? 'មិនផុត' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="toggleStatus({{ $coupon->id }})"
                                    class="px-2 py-1 text-xs rounded-full transition {{ $coupon->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $coupon->is_active ? __('admin.active') : __('admin.inactive') }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                   class="text-blue-600 hover:text-blue-800 transition"
                                   title="{{ __('admin.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline">
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
                            <i class="fas fa-ticket-alt text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_coupons') ?? 'មិនមានប័ណ្ណបញ្ចុះតម្លៃ' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t flex justify-between items-center flex-wrap gap-2">
        <div class="text-sm text-gray-500">
            {{ __('admin.showing') }} {{ $coupons->firstItem() ?? 0 }} - {{ $coupons->lastItem() ?? 0 }}
            {{ __('admin.of') }} {{ $coupons->total() }} {{ __('admin.items') }}
        </div>
        {{ $coupons->links() }}
    </div>
</div>

<!-- Coupon Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_coupons') ?? 'ប័ណ្ណសរុប' }}</p>
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
    <div class="bg-yellow-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.expired') }}</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['expired'] ?? 0 }}</p>
    </div>
</div>

@push('scripts')
<script>
    // Selection logic
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.coupon-checkbox');
    const bulkActions = document.getElementById('bulk-actions');

    function updateBulkVisibility() {
        const checkedCount = document.querySelectorAll('.coupon-checkbox:checked').length;
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

    function toggleStatus(couponId) {
        if (!confirm('{{ __("admin.confirm_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាព?" }}')) {
            return;
        }

        fetch(`/admin/coupons/${couponId}/toggle-status`, {
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
        const selectedIds = Array.from(document.querySelectorAll('.coupon-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) return;

        if (!confirm('{{ __("admin.confirm_bulk_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាពធាតុដែលបានជ្រើសរើស?" }}')) {
            return;
        }

        fetch('{{ route("admin.coupons.bulk-status") }}', {
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

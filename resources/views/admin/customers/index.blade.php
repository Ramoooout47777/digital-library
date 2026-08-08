{{-- resources/views/admin/customers/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.customers_menu'))
@section('page-title', __('admin.customers_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.customers.export') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-file-export mr-2"></i>
            {{ __('admin.export') }}
        </a>
        <button onclick="document.getElementById('import-form').submit()" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-upload mr-2"></i>
            {{ __('admin.import') }}
        </button>

        <!-- Bulk Actions -->
        <div id="bulk-actions" class="hidden flex gap-2">
            <button onclick="bulkStatus(true)" class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-user-check mr-2"></i> {{ __('admin.activate_all') ?? 'បើកទាំងអស់' }}
            </button>
            <button onclick="bulkStatus(false)" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition flex items-center">
                <i class="fas fa-user-slash mr-2"></i> {{ __('admin.deactivate_all') ?? 'បិទទាំងអស់' }}
            </button>
        </div>
    </div>

    <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="{{ __('admin.search') }}..."
               class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>

<!-- Import Form (Hidden) -->
<form id="import-form" action="{{ route('admin.customers.import') }}" method="POST" enctype="multipart/form-data" class="hidden">
    @csrf
    <input type="file" name="file" accept=".csv,.xlsx" onchange="this.form.submit()">
</form>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.role') ?? 'តួនាទី' }}</label>
            <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>{{ __('admin.customer') ?? 'អតិថិជន' }}</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>{{ __('admin.admin') ?? 'អ្នកគ្រប់គ្រង' }}</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.date_range') ?? 'កាលបរិច្ឆេទ' }}</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Customers Table -->
<div class="table-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b">
                    <th class="px-4 py-3 text-left">
                        <input type="checkbox" id="select-all" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.avatar') ?? 'រូបភាព' }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.name') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.email') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.phone') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.total_orders') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">{{ __('admin.total_spent') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.status') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.registered_at') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-sm">
                            <input type="checkbox" name="ids[]" value="{{ $customer->id }}" class="customer-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer" {{ $customer->id === auth()->id() ? 'disabled' : '' }}>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $customer->id }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ $customer->avatar ? asset('storage/' . $customer->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($customer->name) . '&background=3b82f6&color=fff&size=40' }}"
                                 alt="{{ $customer->name }}"
                                 class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                            <div class="text-xs text-gray-500">
                                @foreach($customer->roles as $role)
                                    <span class="px-1.5 py-0.5 bg-gray-100 rounded-full text-xs">{{ $role->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <a href="mailto:{{ $customer->email }}" class="text-blue-500 hover:underline">
                                {{ $customer->email }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $customer->phone ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                {{ $customer->orders_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center font-semibold text-green-600">
                            ${{ number_format($customer->total_spent ?? 0, 2) }}
                        </td>
                        <td class="px-4 py-3">
                            <button onclick="toggleStatus({{ $customer->id }})"
                                    class="px-2 py-1 text-xs rounded-full transition {{ $customer->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $customer->is_active ? __('admin.active') : __('admin.inactive') }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $customer->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.customers.show', $customer) }}"
                                   class="text-blue-600 hover:text-blue-800 transition"
                                   title="{{ __('admin.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.customers.edit', $customer) }}"
                                   class="text-green-600 hover:text-green-800 transition"
                                   title="{{ __('admin.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($customer->orders_count == 0 && $customer->id != auth()->id())
                                    <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition"
                                                onclick="return confirm('{{ __('admin.confirm_delete') }}')"
                                                title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @elseif($customer->id == auth()->id())
                                    <span class="text-gray-400 cursor-not-allowed" title="{{ __('admin.cannot_delete_self') ?? 'មិនអាចលុបខ្លួនឯងបានទេ' }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="{{ __('admin.cannot_delete_has_orders') ?? 'មិនអាចលុបបានទេ ព្រោះមានការកម្មង់' }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl block mb-3 text-gray-300"></i>
                            {{ __('admin.no_customers') ?? 'មិនមានអតិថិជន' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t flex justify-between items-center flex-wrap gap-2">
        <div class="text-sm text-gray-500">
            {{ __('admin.showing') }} {{ $customers->firstItem() ?? 0 }} - {{ $customers->lastItem() ?? 0 }}
            {{ __('admin.of') }} {{ $customers->total() }} {{ __('admin.items') }}
        </div>
        {{ $customers->links() }}
    </div>
</div>

<!-- Customer Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_customers') ?? 'អតិថិជនសរុប' }}</p>
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
        <p class="text-xs text-gray-500">{{ __('admin.new_this_month') ?? 'ថ្មីក្នុងខែនេះ' }}</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['new_this_month'] ?? 0 }}</p>
    </div>
</div>

@push('scripts')
<script>
    // Selection logic
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.customer-checkbox');
    const bulkActions = document.getElementById('bulk-actions');

    function updateBulkVisibility() {
        const checkedCount = document.querySelectorAll('.customer-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
        if (selectAll) {
            const enabledCheckboxes = document.querySelectorAll('.customer-checkbox:not(:disabled)');
            const checkedEnabledCount = document.querySelectorAll('.customer-checkbox:not(:disabled):checked').length;
            selectAll.checked = checkedEnabledCount === enabledCheckboxes.length && enabledCheckboxes.length > 0;
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = this.checked;
            });
            updateBulkVisibility();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkVisibility);
    });

    function toggleStatus(customerId) {
        if (!confirm('{{ __("admin.confirm_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាព?" }}')) {
            return;
        }

        fetch(`/admin/customers/${customerId}/toggle-status`, {
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
        const selectedIds = Array.from(document.querySelectorAll('.customer-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) return;

        if (!confirm('{{ __("admin.confirm_bulk_status_change") ?? "តើអ្នកចង់ប្តូរស្ថានភាពធាតុដែលបានជ្រើសរើស?" }}')) {
            return;
        }

        fetch('{{ route("admin.customers.bulk-status") }}', {
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

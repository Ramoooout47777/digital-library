{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.notifications_menu'))
@section('page-title', __('admin.notifications_menu'))

@section('content')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <button onclick="openCreateModal()" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>
            {{ __('admin.send_notification') }}
        </button>
        
        <!-- Mark All as Read - AJAX -->
        <button onclick="markAllAsRead()" 
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-check-double mr-2"></i>
            {{ __('admin.mark_all_as_read') }}
        </button>
        
        <!-- Delete All - AJAX -->
        <button onclick="deleteAllNotifications()" 
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition flex items-center">
            <i class="fas fa-trash-alt mr-2"></i>
            {{ __('admin.delete_all') ?? 'លុបទាំងអស់' }}
        </button>
    </div>
    
    <form action="{{ route('admin.notifications.index') }}" method="GET" class="flex gap-2">
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
    <form action="{{ route('admin.notifications.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.status') }}</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>{{ __('admin.read') ?? 'បានអាន' }}</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>{{ __('admin.unread') ?? 'មិនទាន់អាន' }}</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.notification_type') }}</label>
            <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>{{ __('admin.info') }}</option>
                <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>{{ __('admin.success') }}</option>
                <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>{{ __('admin.warning') }}</option>
                <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>{{ __('admin.error') }}</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.user') ?? 'អ្នកប្រើ' }}</label>
            <select name="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">{{ __('admin.view_all') }}</option>
                <option value="all" {{ request('user_id') == 'all' ? 'selected' : '' }}>{{ __('admin.all_users') ?? 'អ្នកប្រើទាំងអស់' }}</option>
                @foreach($users ?? [] as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition w-full">
                <i class="fas fa-filter mr-2"></i>{{ __('admin.filter') }}
            </button>
            <a href="{{ route('admin.notifications.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition w-full text-center">
                <i class="fas fa-undo mr-2"></i>{{ __('admin.reset') }}
            </a>
        </div>
    </form>
</div>

<!-- Notifications List -->
<div class="space-y-3" id="notifications-list">
    @forelse($notifications as $notification)
        <div class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition {{ $notification->is_read ? '' : 'border-l-4 border-blue-500 bg-blue-50' }}" 
             id="notification-{{ $notification->id }}">
            <div class="flex items-start gap-4">
                <!-- Icon -->
                <div class="flex-shrink-0 mt-1">
                    @if($notification->type == 'success')
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    @elseif($notification->type == 'warning')
                        <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>
                    @elseif($notification->type == 'error')
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600"></i>
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <h4 class="font-semibold text-gray-800 {{ $notification->is_read ? '' : 'text-blue-700' }}">
                                {{ $notification->title }}
                            </h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            @if($notification->data)
                                <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded">
                                    <pre class="whitespace-pre-wrap">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            @endif
                            <div class="flex flex-wrap items-center gap-3 mt-2 text-xs text-gray-400">
                                <span>
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                                <span>
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ ucfirst($notification->type) }}
                                </span>
                                @if($notification->user)
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $notification->user->name }}
                                    </span>
                                @else
                                    <span>
                                        <i class="fas fa-users mr-1"></i>
                                        {{ __('admin.all_users') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if(!$notification->is_read)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                        class="text-blue-500 hover:text-blue-700 text-sm flex items-center gap-1">
                                    <i class="fas fa-check"></i> {{ __('admin.mark_as_read') }}
                                </button>
                            @endif
                            
                            <button onclick="deleteNotification({{ $notification->id }})" 
                                    class="text-red-500 hover:text-red-700 text-sm flex items-center gap-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            <i class="fas fa-bell-slash text-4xl block mb-3 text-gray-300"></i>
            {{ __('admin.no_notifications') }}
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $notifications->links() }}
</div>

<!-- Notification Statistics -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.total_notifications') ?? 'ការជូនដំណឹងសរុប' }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
    </div>
    <div class="bg-blue-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.unread') }}</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['unread'] ?? 0 }}</p>
    </div>
    <div class="bg-green-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.read') }}</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['read'] ?? 0 }}</p>
    </div>
    <div class="bg-purple-50 rounded-lg shadow p-4 text-center">
        <p class="text-xs text-gray-500">{{ __('admin.by_type') ?? 'តាមប្រភេទ' }}</p>
        <p class="text-2xl font-bold text-purple-600">{{ $stats['types'] ?? 0 }}</p>
    </div>
</div>

<!-- ============ CREATE MODAL ============ -->
<div id="create-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-6 border-b">
            <h3 class="text-xl font-semibold text-gray-800">{{ __('admin.send_notification') }}</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="create-notification-form" action="{{ route('admin.notifications.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.title') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="notification-title" required
                           class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Message -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('admin.message') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message" id="notification-message" rows="4" required
                              class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.type') }}</label>
                    <select name="type" id="notification-type" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="info">{{ __('admin.info') }}</option>
                        <option value="success">{{ __('admin.success') }}</option>
                        <option value="warning">{{ __('admin.warning') }}</option>
                        <option value="error">{{ __('admin.error') }}</option>
                    </select>
                </div>
                
                <!-- User -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.user') }}</label>
                    <select name="user_id" id="notification-user" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('admin.all_users') }}</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">{{ __('admin.user_hint') ?? 'ទុកចោលដើម្បីផ្ញើទៅកាន់អ្នកប្រើទាំងអស់' }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 mt-6 pt-6 border-t">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-paper-plane mr-2"></i> {{ __('admin.send') ?? 'ផ្ញើ' }}
                </button>
                <button type="button" onclick="closeCreateModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i> {{ __('admin.cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ================================================================
    // MODAL FUNCTIONS
    // ================================================================
    
    function openCreateModal() {
        document.getElementById('create-modal').classList.remove('hidden');
        document.getElementById('notification-title').value = '';
        document.getElementById('notification-message').value = '';
        document.getElementById('notification-type').value = 'info';
        document.getElementById('notification-user').value = '';
    }
    
    function closeCreateModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }
    
    // Close modal on outside click
    document.getElementById('create-modal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
    
    // ================================================================
    // MARK AS READ - AJAX
    // ================================================================
    
    function markAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/read`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Marked as read!', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Failed to mark as read', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    });
}
    
    // ================================================================
    // MARK ALL AS READ - AJAX
    // ================================================================
    
    function markAllAsRead() {
        if (!confirm('{{ __("admin.confirm_mark_all_read") ?? "Mark all notifications as read?" }}')) {
            return;
        }
        
        fetch('{{ route("admin.notifications.mark-all-read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'All notifications marked as read!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to mark all as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    }
    
    // ================================================================
    // DELETE NOTIFICATION - AJAX
    // ================================================================
    
    function deleteNotification(notificationId) {
    if (!confirm('{{ __("admin.confirm_delete") }}')) {
        return;
    }
    
    fetch(`/admin/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Notification deleted!', 'success');
            // Remove from DOM
            const element = document.getElementById(`notification-${notificationId}`);
            if (element) {
                element.style.transition = 'all 0.5s ease';
                element.style.opacity = '0';
                element.style.transform = 'translateX(100px)';
                setTimeout(() => element.remove(), 500);
            }
        } else {
            showToast(data.message || 'Failed to delete notification', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    });
}
    
    // ================================================================
    // DELETE ALL - AJAX
    // ================================================================
    
    function deleteAllNotifications() {
        if (!confirm('{{ __("admin.confirm_delete_all") ?? "Are you sure you want to delete all notifications?" }}')) {
            return;
        }
        
        fetch('{{ route("admin.notifications.delete-all") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'All notifications deleted!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to delete all notifications', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    }
    
    // ================================================================
    // TOAST NOTIFICATION
    // ================================================================
    
    function showToast(message, type = 'success') {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        };
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };
        
        // Remove existing toast
        const existing = document.querySelector('.toast-notification');
        if (existing) existing.remove();
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-20 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-x-full`;
        toast.innerHTML = `<i class="fas ${icons[type]} mr-2"></i> ${message}`;
        document.body.appendChild(toast);
        
        // Show toast
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        
        // Hide and remove toast after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
    
    // ================================================================
    // UPDATE COUNTS
    // ================================================================
    
    function updateCounts() {
        // Update unread count in sidebar
        const badge = document.querySelector('.sidebar-link .badge');
        if (badge) {
            const currentCount = parseInt(badge.textContent) || 0;
            if (currentCount > 0) {
                badge.textContent = currentCount - 1;
                if (badge.textContent === '0') {
                    badge.remove();
                }
            }
        }
        
        // Update statistics
        const unreadStat = document.querySelector('.bg-blue-50 .text-2xl');
        if (unreadStat) {
            const current = parseInt(unreadStat.textContent) || 0;
            unreadStat.textContent = Math.max(0, current - 1);
        }
    }
</script>
@endpush
@endsection
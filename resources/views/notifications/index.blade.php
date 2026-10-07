@extends('layouts.app')

@section('title', 'My Notifications')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold dark:text-white flex items-center gap-3">
            <i class="fas fa-bell text-cyan-400"></i>
            My Notifications
        </h1>
        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="neu-button text-sm px-6 py-2 rounded-xl">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="neu-card p-6 flex gap-4 transition-all duration-300 {{ $notification->is_read ? 'opacity-70' : 'border-l-4 border-cyan-500' }}">
                <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center
                    {{ $notification->type == 'success' ? 'bg-emerald-500/20 text-emerald-400' :
                       ($notification->type == 'error' ? 'bg-red-500/20 text-red-400' :
                       ($notification->type == 'warning' ? 'bg-amber-500/20 text-amber-400' : 'bg-cyan-500/20 text-cyan-400')) }}">
                    <i class="fas {{ $notification->type_icon }} text-xl"></i>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="font-bold dark:text-white">{{ $notification->title }}</h3>
                        <span class="text-xs dark:text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="dark:text-gray-400 text-sm leading-relaxed">{{ $notification->message }}</p>

                    @if(!$notification->is_read)
                        <button onclick="markAsRead({{ $notification->id }}, this)"
                                class="mt-3 text-xs text-cyan-400 hover:underline">
                            Mark as read
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="neu-card p-12 text-center">
                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bell-slash text-3xl text-slate-600"></i>
                </div>
                <h3 class="text-xl font-bold dark:text-white mb-2">No notifications yet</h3>
                <p class="dark:text-gray-500 max-w-xs mx-auto">We'll let you know when something important happens.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
</div>

@push('scripts')
<script>
    function markAsRead(id, btn) {
        fetch(`/notifications/${id}/read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = btn.closest('.neu-card');
                card.classList.add('opacity-70');
                card.classList.remove('border-l-4', 'border-cyan-500');
                btn.remove();
            }
        });
    }
</script>
@endpush
@endsection

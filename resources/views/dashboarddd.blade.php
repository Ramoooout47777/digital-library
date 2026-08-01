<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Customer Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Your books, purchases, and reading activity.</p>
            </div>
            <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                <i class="fas fa-book-open"></i>
                Browse Books
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
            @endif

            @if(session('info'))
                <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">{{ session('info') }}</div>
            @endif

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Orders</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['orders'] ?? 0) }}</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Books Owned</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($stats['purchases'] ?? 0) }}</p>
                </div>
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Reading Access</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ ($purchases ?? collect())->count() }}</p>
                </div>
            </div>

            @isset($selectedOrder)
                <div class="rounded-xl bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Order {{ $selectedOrder->order_number }}</h3>
                            <p class="text-sm text-gray-500">Status: {{ ucfirst($selectedOrder->status) }} | Payment: {{ ucfirst($selectedOrder->payment_status) }}</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold text-gray-700">${{ number_format($selectedOrder->total, 2) }}</span>
                    </div>
                    <div class="mt-4 divide-y divide-gray-100">
                        @foreach($selectedOrder->items as $item)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $item->book_title }}</p>
                                    <p class="text-sm text-gray-500">Qty {{ $item->quantity }}</p>
                                </div>
                                <p class="font-semibold text-gray-900">${{ number_format($item->total, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endisset

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <section class="lg:col-span-2 rounded-xl bg-white p-5 shadow-sm">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">My Library</h3>
                        <a href="{{ route('books.index', ['free' => 1]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Free books</a>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @forelse($purchases ?? [] as $purchase)
                            <div class="flex gap-3 rounded-lg border border-gray-100 p-3">
                                <div class="h-20 w-14 shrink-0 overflow-hidden rounded bg-gray-100">
                                    @if($purchase->book?->cover)
                                        <img src="{{ asset('storage/' . $purchase->book->cover) }}" alt="{{ $purchase->book->title }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-gray-400"><i class="fas fa-book"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-gray-900">{{ $purchase->book?->title }}</p>
                                    <p class="truncate text-sm text-gray-500">{{ $purchase->book?->author?->name }}</p>
                                    <div class="mt-3 flex gap-2">
                                        <a href="{{ route('books.show', $purchase->book) }}" class="rounded-md bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">Read</a>
                                        <a href="{{ route('books.download', $purchase->book) }}" class="rounded-md bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700 hover:bg-green-100">Download</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-gray-200 p-8 text-center text-gray-500 sm:col-span-2">
                                You do not own any books yet.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-xl bg-white p-5 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Recent Orders</h3>
                    <div class="space-y-3">
                        @forelse($orders ?? [] as $order)
                            <a href="{{ route('orders.show', $order) }}" class="block rounded-lg border border-gray-100 p-3 hover:bg-gray-50">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                    <span class="text-sm font-semibold text-gray-700">${{ number_format($order->total, 2) }}</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">{{ ucfirst($order->status) }} | {{ $order->created_at->diffForHumans() }}</p>
                            </a>
                        @empty
                            <p class="rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">No orders yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-xl bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Free Books To Read Now</h3>
                    <a href="{{ route('books.index', ['free' => 1]) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
                </div>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-6">
                    @forelse($recommendedBooks ?? [] as $book)
                        <a href="{{ route('books.show', $book) }}" class="group">
                            <div class="overflow-hidden rounded-lg bg-gray-100">
                                @if($book->cover)
                                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="aspect-[3/4] w-full object-cover transition group-hover:scale-105">
                                @else
                                    <div class="flex aspect-[3/4] items-center justify-center text-gray-400"><i class="fas fa-book text-3xl"></i></div>
                                @endif
                            </div>
                            <p class="mt-2 truncate text-sm font-semibold text-gray-900">{{ $book->title }}</p>
                            <p class="truncate text-xs text-gray-500">{{ $book->author?->name }}</p>
                        </a>
                    @empty
                        <p class="col-span-full rounded-lg border border-dashed border-gray-200 p-6 text-center text-sm text-gray-500">No free books available.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>

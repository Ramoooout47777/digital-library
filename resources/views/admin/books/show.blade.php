{{-- resources/views/admin/books/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.book_details') . ' - ' . $book->title)
@section('page-title', __('admin.book_details'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Book Cover & Actions -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-4">
            <!-- Book Cover -->
            <div class="flex justify-center mb-4">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" 
                         alt="{{ $book->title }}" 
                         class="w-48 h-64 object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-48 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- Quick Actions -->
            <div class="space-y-2">
                <a href="{{ route('admin.books.edit', $book) }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>{{ __('admin.edit_book') }}
                </a>
                
                @if($book->pdf_file)
                    <a href="{{ asset('storage/' . $book->pdf_file) }}" 
                       target="_blank" 
                       class="block w-full bg-green-500 hover:bg-green-600 text-white text-center px-4 py-2 rounded-lg transition">
                        <i class="fas fa-file-pdf mr-2"></i>{{ __('admin.view_pdf') ?? 'មើល PDF' }}
                    </a>
                    <a href="{{ asset('storage/' . $book->pdf_file) }}" 
                       download 
                       class="block w-full bg-purple-500 hover:bg-purple-600 text-white text-center px-4 py-2 rounded-lg transition">
                        <i class="fas fa-download mr-2"></i>{{ __('admin.download_pdf') ?? 'ទាញយក PDF' }}
                    </a>
                @endif
                
                @if($book->sample_pdf)
                    <a href="{{ asset('storage/' . $book->sample_pdf) }}" 
                       target="_blank" 
                       class="block w-full bg-yellow-500 hover:bg-yellow-600 text-white text-center px-4 py-2 rounded-lg transition">
                        <i class="fas fa-file-pdf mr-2"></i>{{ __('admin.view_sample') ?? 'មើលសំណាក' }}
                    </a>
                @endif
                
                @if(!$book->trashed())
                    <button onclick="toggleStatus({{ $book->id }})" 
                            class="block w-full {{ $book->status ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white text-center px-4 py-2 rounded-lg transition">
                        <i class="fas {{ $book->status ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                        {{ $book->status ? __('admin.inactive') : __('admin.active') }}
                    </button>
                    
                    <button onclick="confirmDelete({{ $book->id }})" 
                            class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-4 py-2 rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i>{{ __('admin.delete_book') }}
                    </button>
                @else
                    <form action="{{ route('admin.books.restore', $book->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center px-4 py-2 rounded-lg transition">
                            <i class="fas fa-undo mr-2"></i>{{ __('admin.restore') ?? 'ស្ដារ' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.books.force-delete', $book->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-700 hover:bg-red-800 text-white text-center px-4 py-2 rounded-lg transition"
                                onclick="return confirm('{{ __('admin.confirm_delete_permanent') ?? 'តើអ្នកប្រាកដថាចង់លុបជាអចិន្ត្រៃយ៍?' }}')">
                            <i class="fas fa-trash-alt mr-2"></i>{{ __('admin.delete_permanent') ?? 'លុបជាអចិន្ត្រៃយ៍' }}
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.books.index') }}" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_list') ?? 'ត្រឡប់ទៅបញ្ជី' }}
                </a>
            </div>
        </div>
        
        <!-- Statistics Card -->
        <div class="bg-white rounded-lg shadow p-4 mt-6">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('admin.statistics') ?? 'ស្ថិតិ' }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.views') }}</span>
                    <span class="font-medium">{{ number_format($book->views_count) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.downloads') ?? 'ទាញយក' }}</span>
                    <span class="font-medium">{{ number_format($book->downloads_count) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_ratings') ?? 'ការវាយតម្លៃ' }}</span>
                    <span class="font-medium">{{ number_format($book->total_ratings) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.average_rating') ?? 'មធ្យមភាគ' }}</span>
                    <span class="font-medium">
                        @if($book->average_rating > 0)
                            {{ number_format($book->average_rating, 1) }} / 5
                            <span class="text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($book->average_rating))
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </span>
                        @else
                            {{ __('admin.no_ratings') ?? 'មិនទាន់មានការវាយតម្លៃ' }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_sales') ?? 'ការលក់' }}</span>
                    <span class="font-medium">{{ number_format($book->purchases()->count()) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_revenue') }}</span>
                    <span class="font-medium text-green-600">${{ number_format($book->purchases()->sum('price_paid'), 2) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column - Book Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.basic_information') ?? 'ព័ត៌មានមូលដ្ឋាន' }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.title') }}</label>
                    <p class="text-gray-900 font-medium">{{ $book->title }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.slug') }}</label>
                    <p class="text-gray-900">{{ $book->slug }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.categories_menu') }}</label>
                    <p class="text-gray-900">
                        <a href="{{ route('admin.categories.show', $book->category) }}" class="text-blue-500 hover:underline">
                            {{ $book->category->name ?? 'N/A' }}
                        </a>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.author') }}</label>
                    <p class="text-gray-900">
                        <a href="{{ route('admin.authors.show', $book->author) }}" class="text-blue-500 hover:underline">
                            {{ $book->author->name ?? 'N/A' }}
                        </a>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.publishers_menu') }}</label>
                    <p class="text-gray-900">
                        <a href="{{ route('admin.publishers.show', $book->publisher) }}" class="text-blue-500 hover:underline">
                            {{ $book->publisher->name ?? 'N/A' }}
                        </a>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.isbn') }}</label>
                    <p class="text-gray-900">{{ $book->isbn ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.language') }}</label>
                    <p class="text-gray-900">
                        <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">
                            {{ strtoupper($book->language) }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.pages') }}</label>
                    <p class="text-gray-900">{{ number_format($book->pages) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.file_size') ?? 'ទំហំឯកសារ' }}</label>
                    <p class="text-gray-900">
                        @if($book->file_size > 0)
                            {{ number_format($book->file_size / 1024, 2) }} MB
                        @else
                            N/A
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.status') }}</label>
                    <p>
                        <span class="px-2 py-1 rounded-full text-xs {{ $book->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $book->status ? __('admin.active') : __('admin.inactive') }}
                        </span>
                        @if($book->trashed())
                            <span class="px-2 py-1 rounded-full text-xs bg-red-200 text-red-800 ml-2">
                                {{ __('admin.deleted') ?? 'បានលុប' }}
                            </span>
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.published_at') }}</label>
                    <p class="text-gray-900">{{ $book->published_at ? $book->published_at->format('d/m/Y H:i') : 'N/A' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Pricing & Stock -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.pricing_stock') ?? 'តម្លៃ និងស្តុក' }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.price') }}</label>
                    <p class="text-gray-900 font-semibold">${{ number_format($book->price, 2) }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.discount') }}</label>
                    <p class="text-gray-900">
                        @if($book->discount > 0)
                            <span class="text-green-600">${{ number_format($book->discount, 2) }}</span>
                            <span class="text-xs text-gray-400">({{ number_format(($book->discount / $book->price) * 100, 1) }}%)</span>
                        @else
                            <span class="text-gray-400">{{ __('admin.none') ?? 'គ្មាន' }}</span>
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.final_price') ?? 'តម្លៃចុងក្រោយ' }}</label>
                    <p class="text-gray-900 font-bold text-blue-600">
                        @if($book->is_free)
                            <span class="text-green-600">{{ __('admin.free') ?? 'ឥតគិតថ្លៃ' }}</span>
                        @else
                            ${{ number_format($book->final_price, 2) }}
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.stock') }}</label>
                    <p class="text-gray-900">
                        <span class="px-2 py-1 rounded-full text-xs {{ $book->stock > 10 ? 'bg-green-100 text-green-800' : ($book->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ number_format($book->stock) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.book_description') }}
            </h3>
            <div class="prose max-w-none">
                @if($book->description)
                    <p class="text-gray-700 leading-relaxed">{{ $book->description }}</p>
                @else
                    <p class="text-gray-400">{{ __('admin.no_description') ?? 'មិនមានការពិពណ៌នា' }}</p>
                @endif
            </div>
        </div>
        
        <!-- Metadata -->
        @if($book->metadata)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                    {{ __('admin.metadata') ?? 'ទិន្នន័យបន្ថែម' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach(json_decode($book->metadata, true) ?? [] as $key => $value)
                        <div class="flex justify-between border-b py-1">
                            <span class="text-sm text-gray-600">{{ $key }}</span>
                            <span class="text-sm text-gray-900">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Recent Reviews -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="font-semibold text-lg text-gray-800">
                    {{ __('admin.recent_reviews') ?? 'ការវាយតម្លៃថ្មីៗ' }}
                </h3>
                <span class="text-sm text-gray-500">
                    {{ __('admin.total') }}: {{ number_format($book->reviews()->count()) }}
                </span>
            </div>
            
            @if($book->reviews()->count() > 0)
                <div class="space-y-3">
                    @foreach($book->reviews()->latest()->limit(5)->get() as $review)
                        <div class="border-b last:border-0 pb-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-800">{{ $review->user->name ?? 'N/A' }}</span>
                                        <span class="text-yellow-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_reviews') ?? 'មិនទាន់មានការវាយតម្លៃ' }}</p>
            @endif
            
            @if($book->reviews()->count() > 5)
                <div class="text-center mt-3">
                    <a href="#" class="text-blue-500 hover:underline text-sm">
                        {{ __('admin.view_all_reviews') ?? 'មើលការវាយតម្លៃទាំងអស់' }}
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Recent Purchases -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="font-semibold text-lg text-gray-800">
                    {{ __('admin.recent_purchases') ?? 'ការទិញថ្មីៗ' }}
                </h3>
                <span class="text-sm text-gray-500">
                    {{ __('admin.total') }}: {{ number_format($book->purchases()->count()) }}
                </span>
            </div>
            
            @if($book->purchases()->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2 text-left">{{ __('admin.customer') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.order_number') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.price') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($book->purchases()->latest()->limit(5)->get() as $purchase)
                                <tr>
                                    <td class="px-3 py-2">{{ $purchase->user->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2">
                                        <a href="{{ route('admin.orders.show', $purchase->order) }}" class="text-blue-500 hover:underline">
                                            {{ $purchase->order->order_number ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-3 py-2">${{ number_format($purchase->price_paid, 2) }}</td>
                                    <td class="px-3 py-2 text-gray-500">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-gray-400 py-4">{{ __('admin.no_purchases') ?? 'មិនទាន់មានការទិញ' }}</p>
            @endif
        </div>
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
    
    function confirmDelete(bookId) {
        if (confirm('{{ __("admin.confirm_delete") }}')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/books/${bookId}`;
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrf);
            
            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
@endsection
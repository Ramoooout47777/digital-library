{{-- resources/views/admin/publishers/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.publisher_description_help') . ' - ' . $publisher->name)
@section('page-title', __('admin.publisher_description_help'))

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
            <!-- Logo -->
            <div class="flex justify-center mb-4">
                @if($publisher->logo)
                    <img src="{{ asset('storage/' . $publisher->logo) }}" 
                         alt="{{ $publisher->name }}" 
                         class="w-32 h-32 object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-gray-400 text-5xl"></i>
                    </div>
                @endif
            </div>
            
            <h2 class="text-2xl font-bold text-center text-gray-800">{{ $publisher->name }}</h2>
            @if($publisher->website)
                <p class="text-center text-sm text-blue-500 mt-1">
                    <a href="{{ $publisher->website }}" target="_blank" class="hover:underline">
                        <i class="fas fa-globe mr-1"></i>{{ $publisher->website }}
                    </a>
                </p>
            @endif
            
            <!-- Quick Actions -->
            <div class="space-y-2 mt-4">
                <a href="{{ route('admin.publishers.edit', $publisher) }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>{{ __('admin.edit') }}
                </a>
                
                @if($publisher->books()->count() == 0)
                    <form action="{{ route('admin.publishers.destroy', $publisher) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-4 py-2 rounded-lg transition"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                            <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.publishers.index') }}" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_list') }}
                </a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow p-4 mt-6">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('admin.statistics') }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.books_count') }}</span>
                    <span class="font-medium">{{ number_format($publisher->books()->count()) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_revenue') }}</span>
                    <span class="font-medium text-green-600">${{ number_format($publisher->books()->sum('final_price'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.status') }}</span>
                    <span>
                        <span class="px-2 py-1 rounded-full text-xs {{ $publisher->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $publisher->status ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                <i class="fas fa-address-card mr-2 text-blue-500"></i>
                {{ __('admin.contact_information') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.publisher_name') }}</label>
                    <p class="text-gray-900 font-medium">{{ $publisher->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.slug') }}</label>
                    <p class="text-gray-900">{{ $publisher->slug }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.publisher_address') }}</label>
                    <p class="text-gray-900">{{ $publisher->address ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.publisher_phone') }}</label>
                    <p class="text-gray-900">{{ $publisher->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.email') }}</label>
                    @if($publisher->email)
                        <p class="text-gray-900">
                            <a href="mailto:{{ $publisher->email }}" class="text-blue-500 hover:underline">
                                {{ $publisher->email }}
                            </a>
                        </p>
                    @else
                        <p class="text-gray-400">N/A</p>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.website') }}</label>
                    @if($publisher->website)
                        <p class="text-gray-900">
                            <a href="{{ $publisher->website }}" target="_blank" class="text-blue-500 hover:underline">
                                {{ $publisher->website }}
                            </a>
                        </p>
                    @else
                        <p class="text-gray-400">N/A</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Books by Publisher -->
        @if($publisher->books()->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-semibold text-lg text-gray-800">
                        <i class="fas fa-book mr-2 text-blue-500"></i>
                        {{ __('admin.books_by_publisher') }}
                    </h3>
                    <span class="text-sm text-gray-500">
                        {{ __('admin.total') }}: {{ number_format($publisher->books()->count()) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($publisher->books()->latest()->limit(10)->get() as $book)
                        <a href="{{ route('admin.books.show', $book) }}" 
                           class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 transition">
                            @if($book->cover)
                                <img src="{{ asset('storage/' . $book->cover) }}" 
                                     alt="{{ $book->title }}" 
                                     class="w-16 h-20 object-cover rounded">
                            @else
                                <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium text-gray-800">{{ Str::limit($book->title, 25) }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-500">{{ $book->author->name ?? 'N/A' }}</span>
                                    <span class="text-xs text-green-600 font-semibold">${{ number_format($book->final_price, 2) }}</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    @endforeach
                </div>
                
                @if($publisher->books()->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.books.index', ['publisher_id' => $publisher->id]) }}" 
                           class="text-blue-500 hover:underline text-sm">
                            {{ __('admin.view_all_books') }}
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
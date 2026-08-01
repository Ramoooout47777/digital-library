{{-- resources/views/admin/authors/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.author_details') ?? 'ព័ត៌មានលម្អិតអ្នកនិពន្ធ')
@section('page-title', __('admin.author_details') ?? 'ព័ត៌មានលម្អិតអ្នកនិពន្ធ')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-4">
            <!-- Author Image -->
            <div class="flex justify-center mb-4">
                @if($author->image)
                    <img src="{{ asset('storage/' . $author->image) }}" 
                         alt="{{ $author->name }}" 
                         class="w-48 h-48 object-cover rounded-full shadow-lg border-4 border-gray-200">
                @else
                    <div class="w-48 h-48 bg-gray-200 rounded-full flex items-center justify-center border-4 border-gray-200">
                        <i class="fas fa-user text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- Author Name -->
            <h2 class="text-2xl font-bold text-center text-gray-800">{{ $author->name }}</h2>
            @if($author->website)
                <p class="text-center text-sm text-blue-500 mt-1">
                    <a href="{{ $author->website }}" target="_blank" class="hover:underline">
                        <i class="fas fa-globe mr-1"></i>{{ $author->website }}
                    </a>
                </p>
            @endif
            
            <!-- Quick Actions -->
            <div class="space-y-2 mt-4">
                <a href="{{ route('admin.authors.edit', $author) }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>{{ __('admin.edit') }}
                </a>
                
                @if($author->books()->count() == 0)
                    <form action="{{ route('admin.authors.destroy', $author) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-4 py-2 rounded-lg transition"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                            <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.authors.index') }}" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('admin.back_to_list') ?? 'ត្រឡប់ទៅបញ្ជី' }}
                </a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow p-4 mt-6">
            <h3 class="font-semibold text-gray-700 mb-3">{{ __('admin.statistics') ?? 'ស្ថិតិ' }}</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.books_count') }}</span>
                    <span class="font-medium">{{ number_format($author->books()->count()) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_revenue') }}</span>
                    <span class="font-medium text-green-600">${{ number_format($author->books()->sum('final_price'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_views') ?? 'ចំនួនទស្សនា' }}</span>
                    <span class="font-medium">{{ number_format($author->books()->sum('views_count')) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.status') }}</span>
                    <span>
                        <span class="px-2 py-1 rounded-full text-xs {{ $author->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $author->status ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.created_at') ?? 'បង្កើតនៅ' }}</span>
                    <span class="font-medium">{{ $author->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.updated_at') ?? 'កែប្រែនៅ' }}</span>
                    <span class="font-medium">{{ $author->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Biography -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.biography') ?? 'ជីវប្រវត្តិ' }}
            </h3>
            @if($author->bio)
                <p class="text-gray-700 leading-relaxed">{{ $author->bio }}</p>
            @else
                <p class="text-gray-400">{{ __('admin.no_bio') ?? 'មិនមានជីវប្រវត្តិ' }}</p>
            @endif
        </div>
        
        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.contact_information') ?? 'ព័ត៌មានទំនាក់ទំនង' }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.author_name') }}</label>
                    <p class="text-gray-900 font-medium">{{ $author->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.slug') }}</label>
                    <p class="text-gray-900">{{ $author->slug }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.author_email') }}</label>
                    @if($author->email)
                        <p class="text-gray-900">
                            <a href="mailto:{{ $author->email }}" class="text-blue-500 hover:underline">
                                {{ $author->email }}
                            </a>
                        </p>
                    @else
                        <p class="text-gray-400">-</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.author_website') }}</label>
                    @if($author->website)
                        <p class="text-gray-900">
                            <a href="{{ $author->website }}" target="_blank" class="text-blue-500 hover:underline">
                                {{ $author->website }}
                            </a>
                        </p>
                    @else
                        <p class="text-gray-400">-</p>
                    @endif
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.status') }}</label>
                    <p>
                        <span class="px-2 py-1 rounded-full text-xs {{ $author->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $author->status ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.total_books') ?? 'ចំនួនសៀវភៅ' }}</label>
                    <p class="text-gray-900 font-semibold">{{ number_format($author->books()->count()) }}</p>
                </div>
            </div>
        </div>
        
        <!-- Books by Author -->
        @if($author->books()->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-semibold text-lg text-gray-800">
                        {{ __('admin.books_by_author') ?? 'សៀវភៅរបស់អ្នកនិពន្ធ' }}
                    </h3>
                    <span class="text-sm text-gray-500">
                        {{ __('admin.total') }}: {{ number_format($author->books()->count()) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($author->books()->latest()->limit(10)->get() as $book)
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
                                <p class="font-medium text-gray-800">{{ Str::limit($book->title, 30) }}</p>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    <span class="text-xs text-gray-500">{{ $book->category->name ?? 'N/A' }}</span>
                                    <span class="text-xs text-green-600 font-semibold">${{ number_format($book->final_price, 2) }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs {{ $book->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $book->status ? __('admin.active') : __('admin.inactive') }}
                                    </span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    @endforeach
                </div>
                
                @if($author->books()->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.books.index', ['author_id' => $author->id]) }}" 
                           class="text-blue-500 hover:underline text-sm">
                            {{ __('admin.view_all_books') ?? 'មើលសៀវភៅទាំងអស់' }}
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center py-8">
                    <i class="fas fa-book-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">{{ __('admin.no_books_found') ?? 'មិនមានសៀវភៅរបស់អ្នកនិពន្ធនេះ' }}</p>
                    <a href="{{ route('admin.books.create', ['author_id' => $author->id]) }}" 
                       class="inline-block mt-3 text-blue-500 hover:text-blue-700">
                        <i class="fas fa-plus mr-1"></i> {{ __('admin.add_new_book') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
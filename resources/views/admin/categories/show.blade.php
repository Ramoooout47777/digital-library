{{-- resources/views/admin/categories/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('admin.category_details') ?? 'ព័ត៌មានលម្អិតប្រភេទ')
@section('page-title', __('admin.category_details') ?? 'ព័ត៌មានលម្អិតប្រភេទ')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-4">
            <!-- Category Image -->
            <div class="flex justify-center mb-4">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" 
                         alt="{{ $category->name }}" 
                         class="w-48 h-48 object-cover rounded-lg shadow-lg">
                @else
                    <div class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tag text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>
            
            <!-- Quick Actions -->
            <div class="space-y-2">
                <a href="{{ route('admin.categories.edit', $category) }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>{{ __('admin.edit') }}
                </a>
                
                @if($category->books()->count() == 0)
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block w-full bg-red-500 hover:bg-red-600 text-white text-center px-4 py-2 rounded-lg transition"
                                onclick="return confirm('{{ __('admin.confirm_delete') }}')">
                            <i class="fas fa-trash mr-2"></i>{{ __('admin.delete') }}
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('admin.categories.index') }}" 
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
                    <span class="font-medium">{{ number_format($category->books()->count()) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.sub_categories') ?? 'ប្រភេទរង' }}</span>
                    <span class="font-medium">{{ number_format($category->children()->count()) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">{{ __('admin.total_revenue') }}</span>
                    <span class="font-medium text-green-600">${{ number_format($category->books()->sum('final_price'), 2) }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.basic_information') ?? 'ព័ត៌មានមូលដ្ឋាន' }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.category_name') ?? 'ឈ្មោះប្រភេទ' }}</label>
                    <p class="text-gray-900 font-medium">{{ $category->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.slug') }}</label>
                    <p class="text-gray-900">{{ $category->slug }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.parent_category') ?? 'ប្រភេទមេ' }}</label>
                    <p class="text-gray-900">
                        @if($category->parent)
                            <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-blue-500 hover:underline">
                                {{ $category->parent->name }}
                            </a>
                        @else
                            <span class="text-gray-400">{{ __('admin.no_parent') ?? 'គ្មានមេ' }}</span>
                        @endif
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.order') ?? 'លំដាប់' }}</label>
                    <p class="text-gray-900">{{ $category->order }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.status') }}</label>
                    <p>
                        <span class="px-2 py-1 rounded-full text-xs {{ $category->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->status ? __('admin.active') : __('admin.inactive') }}
                        </span>
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.created_at') ?? 'បង្កើតនៅ' }}</label>
                    <p class="text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">{{ __('admin.updated_at') ?? 'កែប្រែនៅ' }}</label>
                    <p class="text-gray-900">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Description -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                {{ __('admin.category_description') ?? 'ការពិពណ៌នា' }}
            </h3>
            @if($category->description)
                <p class="text-gray-700 leading-relaxed">{{ $category->description }}</p>
            @else
                <p class="text-gray-400">{{ __('admin.no_description') ?? 'មិនមានការពិពណ៌នា' }}</p>
            @endif
        </div>
        
        <!-- Sub Categories -->
        @if($category->children->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-semibold text-lg text-gray-800 mb-4 border-b pb-2">
                    {{ __('admin.sub_categories') ?? 'ប្រភេទរង' }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($category->children as $child)
                        <a href="{{ route('admin.categories.show', $child) }}" 
                           class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 transition">
                            @if($child->image)
                                <img src="{{ asset('storage/' . $child->image) }}" class="w-10 h-10 object-cover rounded">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-tag text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $child->name }}</p>
                                <p class="text-xs text-gray-500">{{ $child->books()->count() }} {{ __('admin.books') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Books in Category -->
        @if($category->books()->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="font-semibold text-lg text-gray-800">
                        {{ __('admin.books_in_category') ?? 'សៀវភៅក្នុងប្រភេទនេះ' }}
                    </h3>
                    <span class="text-sm text-gray-500">
                        {{ __('admin.total') }}: {{ number_format($category->books()->count()) }}
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-3 py-2 text-left">{{ __('admin.title') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.author') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.price') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.status') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('admin.actions') ?? 'សកម្មភាព' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($category->books()->latest()->limit(10)->get() as $book)
                                <tr>
                                    <td class="px-3 py-2">{{ $book->title }}</td>
                                    <td class="px-3 py-2">{{ $book->author->name ?? 'N/A' }}</td>
                                    <td class="px-3 py-2">${{ number_format($book->final_price, 2) }}</td>
                                    <td class="px-3 py-2">
                                        <span class="px-2 py-1 rounded-full text-xs {{ $book->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $book->status ? __('admin.active') : __('admin.inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <a href="{{ route('admin.books.show', $book) }}" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($category->books()->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.books.index', ['category_id' => $category->id]) }}" 
                           class="text-blue-500 hover:underline text-sm">
                            {{ __('admin.view_all_books') ?? 'មើលសៀវភៅទាំងអស់' }}
                        </a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
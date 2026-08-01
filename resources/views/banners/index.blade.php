{{-- resources/views/admin/banners/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Banners')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold dark:text-slate-200">Manage Banners</h1>
        <a href="{{ route('admin.banners.create') }}" 
           class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> Add Banner
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-500/20 text-emerald-400 p-4 rounded-lg mb-4 border border-emerald-500/20">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50">
        <table class="w-full">
            <thead class="dark:bg-slate-700/30 light:bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold dark:text-slate-300 light:text-slate-700">Image</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold dark:text-slate-300 light:text-slate-700">Title</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold dark:text-slate-300 light:text-slate-700">Discount</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold dark:text-slate-300 light:text-slate-700">Position</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold dark:text-slate-300 light:text-slate-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold dark:text-slate-300 light:text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr class="border-t dark:border-slate-700/30 light:border-slate-200/30">
                    <td class="px-4 py-3">
                        @if($banner->image)
                            <img src="{{ asset('storage/' . $banner->image) }}" 
                                 alt="{{ $banner->title }}" 
                                 class="w-16 h-12 object-cover rounded">
                        @else
                            <div class="w-16 h-12 dark:bg-slate-700/30 light:bg-slate-100 rounded flex items-center justify-center">
                                <i class="fas fa-image dark:text-slate-500 light:text-slate-300"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium dark:text-slate-200 light:text-slate-800">{{ $banner->title }}</div>
                        <div class="text-xs dark:text-slate-400 light:text-slate-500">{{ $banner->subtitle }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($banner->discount_percentage)
                            <span class="text-red-400 font-semibold">{{ $banner->discount_percentage }}%</span>
                        @elseif($banner->discount_amount)
                            <span class="text-cyan-400 font-semibold">${{ number_format($banner->discount_amount, 2) }} OFF</span>
                        @else
                            <span class="text-slate-500">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm dark:text-slate-300 light:text-slate-700 capitalize">{{ $banner->position }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                            @if($banner->status_color === 'green') bg-emerald-500/20 text-emerald-400
                            @elseif($banner->status_color === 'yellow') bg-amber-500/20 text-amber-400
                            @elseif($banner->status_color === 'red') bg-red-500/20 text-red-400
                            @else bg-gray-500/20 text-gray-400 @endif">
                            <i class="fas fa-circle text-[6px]"></i>
                            {{ $banner->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.banners.edit', $banner) }}" 
                               class="text-cyan-400 hover:text-cyan-300 transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.banners.toggle', $banner) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-amber-400 hover:text-amber-300 transition">
                                    <i class="fas fa-{{ $banner->is_active ? 'eye' : 'eye-slash' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Delete this banner?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center dark:text-slate-400 light:text-slate-500">
                        <i class="fas fa-images text-4xl block mb-2 dark:text-slate-600 light:text-slate-300"></i>
                        No banners found. <a href="{{ route('admin.banners.create') }}" class="text-cyan-400 hover:underline">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
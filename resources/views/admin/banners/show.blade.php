{{-- resources/views/admin/banners/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Banner Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold dark:text-slate-200">Banner Details</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.banners.edit', $banner) }}" 
               class="bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.banners.index') }}" 
               class="bg-slate-700 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>
    </div>

    <div class="dark:bg-slate-800/50 light:bg-white rounded-xl overflow-hidden border dark:border-slate-700/50 light:border-slate-200/50 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Image -->
            <div>
                @if($banner->image)
                    <img src="{{ asset('storage/' . $banner->image) }}" 
                         alt="{{ $banner->title }}" 
                         class="w-full rounded-lg object-cover max-h-80">
                @else
                    <div class="w-full h-60 dark:bg-slate-700/30 light:bg-slate-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-image text-4xl dark:text-slate-500 light:text-slate-300"></i>
                    </div>
                @endif
            </div>

            <!-- Details -->
            <div class="space-y-4">
                <div>
                    <label class="text-sm dark:text-slate-400 light:text-slate-500">Title</label>
                    <p class="text-lg font-semibold dark:text-slate-200 light:text-slate-800">{{ $banner->title }}</p>
                </div>

                @if($banner->subtitle)
                <div>
                    <label class="text-sm dark:text-slate-400 light:text-slate-500">Subtitle</label>
                    <p class="text-base dark:text-slate-300 light:text-slate-700">{{ $banner->subtitle }}</p>
                </div>
                @endif

                @if($banner->description)
                <div>
                    <label class="text-sm dark:text-slate-400 light:text-slate-500">Description</label>
                    <p class="text-base dark:text-slate-300 light:text-slate-700">{{ $banner->description }}</p>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    @if($banner->discount_percentage)
                    <div>
                        <label class="text-sm dark:text-slate-400 light:text-slate-500">Discount</label>
                        <p class="text-lg font-bold text-red-400">{{ $banner->discount_percentage }}%</p>
                    </div>
                    @endif

                    @if($banner->discount_amount)
                    <div>
                        <label class="text-sm dark:text-slate-400 light:text-slate-500">Discount Amount</label>
                        <p class="text-lg font-bold text-cyan-400">${{ number_format($banner->discount_amount, 2) }}</p>
                    </div>
                    @endif

                    <div>
                        <label class="text-sm dark:text-slate-400 light:text-slate-500">Position</label>
                        <p class="text-base dark:text-slate-300 light:text-slate-700 capitalize">{{ $banner->position }}</p>
                    </div>

                    <div>
                        <label class="text-sm dark:text-slate-400 light:text-slate-500">Status</label>
                        <p class="text-base">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                @if($banner->is_active) bg-emerald-500/20 text-emerald-400
                                @else bg-red-500/20 text-red-400 @endif">
                                <i class="fas fa-circle text-[6px]"></i>
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                </div>

                @if($banner->book)
                <div>
                    <label class="text-sm dark:text-slate-400 light:text-slate-500">Associated Book</label>
                    <a href="{{ route('books.show', $banner->book) }}" 
                       class="text-cyan-400 hover:text-cyan-300 transition">
                        {{ $banner->book->title }}
                    </a>
                </div>
                @endif

                @if($banner->button_text)
                <div>
                    <label class="text-sm dark:text-slate-400 light:text-slate-500">Button</label>
                    <div class="flex items-center gap-3">
                        <span class="text-base dark:text-slate-300 light:text-slate-700">{{ $banner->button_text }}</span>
                        <a href="{{ $banner->button_link ?? '#' }}" 
                           class="px-4 py-1 rounded-lg text-sm text-white transition hover:opacity-80"
                           style="background: {{ $banner->button_color ?? '#38bdf8' }}">
                            Preview
                        </a>
                    </div>
                </div>
                @endif

                @if($banner->start_date || $banner->end_date)
                <div class="grid grid-cols-2 gap-4">
                    @if($banner->start_date)
                    <div>
                        <label class="text-sm dark:text-slate-400 light:text-slate-500">Start Date</label>
                        <p class="text-base dark:text-slate-300 light:text-slate-700">
                            {{ $banner->start_date->format('M d, Y H:i') }}
                        </p>
                    </div>
                    @endif

                    @if($banner->end_date)
                    <div>
                        <label class="text-sm dark:text-slate-400 light:text-slate-500">End Date</label>
                        <p class="text-base dark:text-slate-300 light:text-slate-700">
                            {{ $banner->end_date->format('M d, Y H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
                @endif

                <div>
                    <label class="text-sm dark:text-slate-400 light:text-slate-500">Sort Order</label>
                    <p class="text-base dark:text-slate-300 light:text-slate-700">{{ $banner->sort_order }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php
// app/Http/Controllers/Admin/AdminBannerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    /**
     * Display a listing of banners.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('title', 'LIKE', "%{$request->search}%")
                  ->orWhere('description', 'LIKE', "%{$request->search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status === 'active' ? 1 : 0);
        }

        // Filter by position
        if ($request->has('position') && $request->position) {
            $query->where('position', $request->position);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        $banners = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(12);

        // Statistics
        $stats = [
            'total' => Banner::count(),
            'active' => Banner::where('status', true)->count(),
            'inactive' => Banner::where('status', false)->count(),
            'positions' => Banner::distinct('position')->count(),
        ];

        return view('admin.banners.index', compact('banners', 'stats'));
    }

    /**
     * Show the form for creating a new banner.
     */
        public function show(Banner $banner)
        {
            // Load banner with relationships
            $banner->load('book');
            return view('admin.banners.show', compact('banner'));
        }
        public function create()
        {
            return view('admin.banners.create');
        }

    /**
     * Store a newly created banner.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'link' => ['nullable', 'url', 'max:255'],
            'position' => ['required', 'string', 'in:home,sidebar,footer,popup'],
            'order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        // Upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('success', __('admin.banner_created') ?? 'Banner created successfully');
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner.
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'link' => ['nullable', 'url', 'max:255'],
            'position' => ['required', 'string', 'in:home,sidebar,footer,popup'],
            'order' => ['nullable', 'integer', 'min:0'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['status'] = $request->has('status');

        // Upload new image
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', __('admin.banner_updated') ?? 'Banner updated successfully');
    }

    /**
     * Remove the specified banner.
     */
    public function destroy(Banner $banner)
    {
        // Delete image
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', __('admin.banner_deleted') ?? 'Banner deleted successfully');
    }

    /**
     * Toggle banner status.
     */
    public function toggleStatus(Banner $banner)
    {
        $banner->status = !$banner->status;
        $banner->save();

        return response()->json([
            'success' => true,
            'status' => $banner->status,
            'message' => $banner->status ? __('admin.banner_activated') : __('admin.banner_deactivated'),
        ]);
    }

    /**
     * Reorder banners.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['exists:banners,id'],
        ]);

        foreach ($request->order as $index => $id) {
            Banner::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Export banners to CSV.
     */
    public function export(Request $request)
    {
        $query = Banner::query();

        if ($request->has('status')) {
            $query->where('status', $request->status === 'active' ? 1 : 0);
        }

        $banners = $query->get();

        $filename = 'banners_' . date('Y-m-d_His') . '.csv';
        $path = storage_path('app/public/exports/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');
        
        fputcsv($file, [
            'ID', 'Title', 'Description', 'Image', 'Link', 
            'Position', 'Order', 'Status', 'Starts At', 'Ends At', 'Created At'
        ]);

        foreach ($banners as $banner) {
            fputcsv($file, [
                $banner->id,
                $banner->title,
                $banner->description,
                $banner->image,
                $banner->link,
                $banner->position,
                $banner->order,
                $banner->status ? 'Active' : 'Inactive',
                $banner->starts_at ? $banner->starts_at->format('Y-m-d H:i:s') : '',
                $banner->ends_at ? $banner->ends_at->format('Y-m-d H:i:s') : '',
                $banner->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
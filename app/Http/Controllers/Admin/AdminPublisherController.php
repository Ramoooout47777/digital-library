<?php
// app/Http/Controllers/Admin/AdminPublisherController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminPublisherController extends Controller
{
    /**
     * Display a listing of publishers.
     */
    public function index(Request $request)
    {
        $query = Publisher::withCount('books');

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('address', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status === 'active' ? 1 : 0);
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $publishers = $query->paginate(20);

        // Statistics
        $stats = [
            'total' => Publisher::count(),
            'active' => Publisher::where('status', true)->count(),
            'inactive' => Publisher::where('status', false)->count(),
            'books' => \App\Models\Book::count(),
        ];

        return view('admin.publishers.index', compact('publishers', 'stats'));
    }

    /**
     * Show the form for creating a new publisher.
     */
    public function create()
    {
        return view('admin.publishers.create');
    }

    /**
     * Store a newly created publisher.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:publishers'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        Publisher::create($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', __('admin.publisher_created') ?? 'Publisher created successfully');
    }

    /**
     * Display the specified publisher.
     */
    public function show(Publisher $publisher)
    {
        $publisher->load(['books.author']);
        return view('admin.publishers.show', compact('publisher'));
    }

    /**
     * Show the form for editing the specified publisher.
     */
    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    /**
     * Update the specified publisher.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers,name,' . $publisher->id],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:publishers,email,' . $publisher->id],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('logo')) {
            if ($publisher->logo) {
                Storage::disk('public')->delete($publisher->logo);
            }
            $data['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        $publisher->update($data);

        return redirect()->route('admin.publishers.index')
            ->with('success', __('admin.publisher_updated') ?? 'Publisher updated successfully');
    }

    /**
     * Remove the specified publisher.
     */
    public function destroy(Publisher $publisher)
    {
        if ($publisher->books()->count() > 0) {
            return redirect()->route('admin.publishers.index')
                ->with('error', __('admin.cannot_delete_has_books') ?? 'Cannot delete publisher with books');
        }

        if ($publisher->logo) {
            Storage::disk('public')->delete($publisher->logo);
        }

        $publisher->delete();

        return redirect()->route('admin.publishers.index')
            ->with('success', __('admin.publisher_deleted') ?? 'Publisher deleted successfully');
    }

    /**
     * Toggle publisher status.
     */
    public function toggleStatus(Publisher $publisher)
    {
        $publisher->status = !$publisher->status;
        $publisher->save();

        return response()->json([
            'success' => true,
            'status' => $publisher->status,
            'message' => $publisher->status ? __('admin.publisher_activated') : __('admin.publisher_deactivated'),
        ]);
    }

    /**
     * Bulk update status for publishers
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:publishers,id'],
            'status' => ['required', 'boolean'],
        ]);

        Publisher::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => __('admin.bulk_status_updated') ?? 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពដោយជោគជ័យ',
        ]);
    }

    /**
     * Export publishers to CSV.
     */
    public function export(Request $request)
    {
        $query = Publisher::withCount('books');

        if ($request->has('status')) {
            $query->where('status', $request->status === 'active' ? 1 : 0);
        }

        $publishers = $query->get();

        $filename = 'publishers_' . date('Y-m-d_His') . '.csv';
        $path = storage_path('app/public/exports/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');

        fputcsv($file, [
            'ID', 'Name', 'Slug', 'Address', 'Phone', 'Email',
            'Website', 'Books Count', 'Status', 'Created At'
        ]);

        foreach ($publishers as $publisher) {
            fputcsv($file, [
                $publisher->id,
                $publisher->name,
                $publisher->slug,
                $publisher->address ?? '',
                $publisher->phone ?? '',
                $publisher->email ?? '',
                $publisher->website ?? '',
                $publisher->books_count,
                $publisher->status ? 'Active' : 'Inactive',
                $publisher->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}

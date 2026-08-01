<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsWithAdminViews;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    use RespondsWithAdminViews;

    public function index()
    {
        return $this->viewOrJson('admin.categories.index', [
            'categories' => Category::with('parent')->withCount('books')->oldest('order')->paginate(20),
        ]);
    }

    public function create()
    {
        return $this->viewOrJson('admin.categories.create', [
            'parentCategories' => Category::parentCategories()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return $this->stored('admin.categories.index', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return $this->viewOrJson('admin.categories.show', ['category' => $category->load('books')]);
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

     public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'nullable|integer|min:0',
            'status' => 'sometimes|boolean',
        ]);

        $data = $request->all();
        $data['slug'] = $request->slug ?: Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'ប្រភេទត្រូវបានកែប្រែដោយជោគជ័យ');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->input('categories', []) as $item) {
            Category::whereKey($item['id'] ?? null)->update(['order' => $item['order'] ?? 0]);
        }

        return response()->json(['message' => 'Categories reordered successfully.']);
    }

    private function validated(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}

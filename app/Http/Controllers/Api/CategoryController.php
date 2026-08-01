<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->withCount('books')
            ->oldest('order')
            ->get();

        return response()->json(['data' => CategoryResource::collection($categories)]);
    }

    public function tree(): JsonResponse
    {
        $categories = Category::active()
            ->parentCategories()
            ->with(['children' => fn ($query) => $query->active()->oldest('order')])
            ->withCount('books')
            ->oldest('order')
            ->get();

        return response()->json(['data' => CategoryResource::collection($categories)]);
    }

    public function show(Category $category): JsonResponse
    {
        $category->loadCount('books');
        $books = $category->books()->with(['category', 'author', 'publisher'])->available()->latest()->paginate(12);

        return response()->json([
            'data' => new CategoryResource($category),
            'books' => BookResource::collection($books)->response()->getData(true),
        ]);
    }
}

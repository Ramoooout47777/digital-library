<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $books = $request->user()->favorites()
            ->with(['category', 'author', 'publisher'])
            ->latest('favorites.created_at')
            ->paginate($request->integer('per_page', 12));

        return response()->json(BookResource::collection($books)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['book_id' => ['required', 'exists:books,id']]);

        $request->user()->favorites()->syncWithoutDetaching([$data['book_id']]);

        return response()->json(['message' => 'Book added to favorites.'], 201);
    }

    public function destroy(Request $request, Book $book): JsonResponse
    {
        $request->user()->favorites()->detach($book->id);

        return response()->json(['message' => 'Book removed from favorites.']);
    }

    public function toggle(Request $request, Book $book): JsonResponse
    {
        $result = $request->user()->favorites()->toggle($book->id);

        return response()->json([
            'is_favorite' => count($result['attached']) > 0,
        ]);
    }
}

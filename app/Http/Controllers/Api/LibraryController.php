<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function __construct(private BookService $books)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => BookResource::collection($this->books->getUserLibrary($request->user()->id)),
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $purchases = $request->user()->purchases()
            ->with(['book.category', 'book.author', 'book.publisher'])
            ->latest()
            ->paginate($request->integer('per_page', 12));

        return response()->json($purchases);
    }

    public function check(Request $request, int $bookId): JsonResponse
    {
        $book = Book::findOrFail($bookId);

        return response()->json([
            'is_purchased' => $request->user()->hasPurchased($book),
            'can_download' => $request->user()->canDownload($book),
        ]);
    }
}

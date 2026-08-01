<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\BookDetailResource;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Review;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function __construct(private BookService $books)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Book::query()
            ->with(['category', 'author', 'publisher'])
            ->available();

        $query->when($request->search, fn ($query, $term) => $query->search($term));
        $query->when($request->category_id, fn ($query, $id) => $query->byCategory($id));
        $query->when($request->author_id, fn ($query, $id) => $query->byAuthor($id));
        $query->when($request->publisher_id, fn ($query, $id) => $query->byPublisher($id));
        $query->when($request->boolean('is_free'), fn ($query) => $query->free());
        $query->when($request->boolean('is_featured'), fn ($query) => $query->featured());

        $books = $query->latest('published_at')->paginate($request->integer('per_page', 12));

        return response()->json(BookResource::collection($books)->response()->getData(true));
    }

    public function featured(): JsonResponse
    {
        return response()->json([
            'data' => BookResource::collection($this->books->getFeaturedBooks()),
        ]);
    }

    public function newReleases(): JsonResponse
    {
        return response()->json([
            'data' => BookResource::collection($this->books->getNewBooks()),
        ]);
    }

    public function popular(): JsonResponse
    {
        return response()->json([
            'data' => BookResource::collection($this->books->getPopularBooks()),
        ]);
    }

    public function show(Request $request, Book $book): BookDetailResource
    {
        $book->incrementViews();
        $book = $this->books->getBookWithPermissions($book->id, $request->user()?->id);
        $book->is_in_favorite = $request->user()?->favorites()->whereKey($book->id)->exists() ?? false;

        return new BookDetailResource($book->load(['category', 'author', 'publisher']));
    }

    public function preview(Book $book): JsonResponse
    {
        return response()->json([
            'sample_pdf' => $book->sample_pdf_url,
            'message' => $book->sample_pdf ? null : 'No preview is available for this book.',
        ]);
    }

    public function download(Request $request, Book $book)
    {
        abort_unless($request->user()->canDownload($book), 403);
        abort_unless($book->pdf_file && Storage::disk('public')->exists($book->pdf_file), 404);

        $book->incrementDownloads();

        return Storage::disk('public')->download($book->pdf_file);
    }

    public function toggleFavorite(Request $request, Book $book): JsonResponse
    {
        $result = $request->user()->favorites()->toggle($book->id);

        return response()->json([
            'is_favorite' => count($result['attached']) > 0,
        ]);
    }

    public function addReview(ReviewRequest $request, Book $book): JsonResponse
    {
        abort_unless($request->user()->canDownload($book), 403);

        $review = Review::updateOrCreate(
            ['user_id' => $request->user()->id, 'book_id' => $book->id],
            $request->validated() + ['status' => true]
        );

        $book->updateRating();

        return response()->json(['data' => $review->load('user')], 201);
    }
}

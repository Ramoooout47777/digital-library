<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\RespondsWithAdminViews;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Services\BookService;
use Illuminate\Http\Request;

class AdminBookController extends Controller
{
    use RespondsWithAdminViews;

    public function __construct(private BookService $books)
    {
    }

    public function index(Request $request)
    {
        $books = Book::with(['category', 'author', 'publisher'])
            ->when($request->search, fn ($query, $term) => $query->search($term))
            ->latest()
            ->paginate(15);

        return $this->viewOrJson('admin.books.index', ['books' => $books]);
    }

    public function create()
    {
        return $this->viewOrJson('admin.books.create', $this->formData());
    }

    public function store(BookRequest $request)
    {
        $data = $request->validated();
        $data['uploaded_by_id'] = $request->user()->id;

        $this->books->createBook($data, $request->file('cover'), $request->file('pdf_file'));

        return $this->stored('admin.books.index', 'Book created successfully.');
    }

    public function show(Book $book)
    {
        return $this->viewOrJson('admin.books.show', [
            'book' => $book->load(['category', 'author', 'publisher', 'reviews.user']),
        ]);
    }

    public function edit(Book $book)
    {
        return $this->viewOrJson('admin.books.edit', $this->formData() + ['book' => $book]);
    }

    public function update(BookRequest $request, Book $book)
    {
        $this->books->updateBook($book, $request->validated(), $request->file('cover'), $request->file('pdf_file'));

        return $this->stored('admin.books.index', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->books->deleteBook($book);

        return back()->with('success', 'Book deleted successfully.');
    }

    public function toggleStatus(Request $request, Book $book)
    {
        $book->status = !$book->status;
        $book->save();

        return response()->json([
            'success' => true,
            'status' => $book->status,
            'message' => $book->status ? __('admin.book_activated') : __('admin.book_deactivated'),
        ]);
    }

    /**
     * Bulk update status for books
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:books,id'],
            'status' => ['required', 'boolean'],
        ]);

        Book::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => __('admin.bulk_status_updated') ?? 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពដោយជោគជ័យ',
        ]);
    }

    public function restore(int $id)
    {
        Book::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Book restored successfully.');
    }

    public function forceDelete(int $id)
    {
        Book::onlyTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Book permanently deleted.');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['file' => ['required', 'file', 'mimes:csv,txt', 'max:10240']]);

        return back()->with('success', 'Bulk upload file received.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::active()->oldest('name')->get(),
            'authors' => Author::active()->oldest('name')->get(),
            'publishers' => Publisher::active()->oldest('name')->get(),
        ];
    }
}

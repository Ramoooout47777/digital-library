<?php
// app/Services/BookService.php

namespace App\Services;

use App\Models\Book;
use App\Models\Purchase;
use App\Models\Review;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookService
{
    protected $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    /**
     * Create a new book
     */
    public function createBook(array $data, $cover = null, $pdf = null)
    {
        return DB::transaction(function () use ($data, $cover, $pdf) {
            // Upload cover
            if ($cover) {
                $coverPath = $this->uploadService->uploadImage(
                    $cover,
                    'books/cover',
                    ['width' => 400, 'height' => 600, 'quality' => 85]
                );
                $data['cover'] = $coverPath;
            }

            // Upload PDF
            if ($pdf) {
                $pdfPath = $this->uploadService->uploadPdf(
                    $pdf,
                    'books/pdf',
                    ['generate_preview' => true]
                );
                $data['pdf_file'] = $pdfPath;
            }

            // Calculate final price
            $data['final_price'] = $this->calculateFinalPrice($data['price'] ?? 0, $data['discount'] ?? 0, $data['is_free'] ?? false);

            return Book::create($data);
        });
    }

    /**
     * Update a book
     */
    public function updateBook(Book $book, array $data, $cover = null, $pdf = null)
    {
        return DB::transaction(function () use ($book, $data, $cover, $pdf) {
            // Update cover
            if ($cover) {
                $coverPath = $this->uploadService->uploadImage(
                    $cover,
                    'books/cover',
                    ['width' => 400, 'height' => 600, 'quality' => 85]
                );
                
                // Delete old cover
                if ($book->cover) {
                    $this->uploadService->delete($book->cover);
                }
                
                $data['cover'] = $coverPath;
            }

            // Update PDF
            if ($pdf) {
                $pdfPath = $this->uploadService->uploadPdf(
                    $pdf,
                    'books/pdf',
                    ['generate_preview' => true]
                );
                
                // Delete old PDF
                if ($book->pdf_file) {
                    $this->uploadService->delete($book->pdf_file);
                }
                
                $data['pdf_file'] = $pdfPath;
            }

            // Calculate final price
            if (isset($data['price']) || isset($data['discount']) || isset($data['is_free'])) {
                $data['final_price'] = $this->calculateFinalPrice(
                    $data['price'] ?? $book->price,
                    $data['discount'] ?? $book->discount,
                    $data['is_free'] ?? $book->is_free
                );
            }

            $book->update($data);
            
            // Clear cache
            $this->clearCache($book->id);
            
            return $book;
        });
    }

    /**
     * Delete a book
     */
    public function deleteBook(Book $book)
    {
        return DB::transaction(function () use ($book) {
            // Delete cover
            if ($book->cover) {
                $this->uploadService->delete($book->cover);
            }
            
            // Delete PDF
            if ($book->pdf_file) {
                $this->uploadService->delete($book->pdf_file);
            }
            
            // Delete sample PDF
            if ($book->sample_pdf) {
                $this->uploadService->delete($book->sample_pdf);
            }
            
            // Delete book (soft delete)
            $book->delete();
            
            // Clear cache
            $this->clearCache($book->id);
            
            return true;
        });
    }

    /**
     * Get book with cache
     */
    public function getBookWithCache($id)
    {
        $cacheKey = "book_{$id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($id) {
            return Book::with(['category', 'author', 'publisher', 'reviews'])
                ->findOrFail($id);
        });
    }

    /**
     * Get user's purchased books
     */
    public function getUserLibrary($userId)
    {
        $cacheKey = "user_library_{$userId}";
        
        return Cache::remember($cacheKey, 3600, function () use ($userId) {
            return Purchase::with(['book'])
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->get()
                ->pluck('book');
        });
    }

    /**
     * Check if user has purchased a book
     */
    public function hasUserPurchased($userId, $bookId)
    {
        return Purchase::where('user_id', $userId)
            ->where('book_id', $bookId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get book with access permissions
     */
    public function getBookWithPermissions($bookId, $userId = null)
    {
        $book = $this->getBookWithCache($bookId);
        
        $book->is_purchased = false;
        $book->can_download = false;
        
        if ($userId) {
            $book->is_purchased = $this->hasUserPurchased($userId, $bookId);
            $book->can_download = $book->is_free || $book->is_purchased;
        }
        
        return $book;
    }

    /**
     * Get book reviews
     */
    public function getBookReviews($bookId, $limit = 10)
    {
        return Review::with(['user'])
            ->where('book_id', $bookId)
            ->where('status', true)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get book statistics
     */
    public function getBookStats($bookId)
    {
        $book = Book::findOrFail($bookId);
        
        return [
            'views' => $book->views_count,
            'downloads' => $book->downloads_count,
            'reviews' => $book->total_ratings,
            'rating' => $book->average_rating,
            'purchases' => Purchase::where('book_id', $bookId)->count(),
        ];
    }

    /**
     * Get related books
     */
    public function getRelatedBooks($bookId, $limit = 6)
    {
        $book = Book::findOrFail($bookId);
        
        return Book::where('category_id', $book->category_id)
            ->where('id', '!=', $bookId)
            ->available()
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate final price
     */
    protected function calculateFinalPrice($price, $discount = 0, $isFree = false)
    {
        if ($isFree) {
            return 0;
        }
        
        return max(0, $price - $discount);
    }

    /**
     * Clear cache
     */
    protected function clearCache($bookId)
    {
        Cache::forget("book_{$bookId}");
        Cache::forget("home_data");
        Cache::forget("featured_books");
    }

    /**
     * Get popular books
     */
    public function getPopularBooks($limit = 10)
    {
        return Cache::remember('popular_books', 3600, function () use ($limit) {
            return Book::available()
                ->orderBy('views_count', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get featured books
     */
    public function getFeaturedBooks($limit = 10)
    {
        return Cache::remember('featured_books', 3600, function () use ($limit) {
            return Book::available()
                ->featured()
                ->limit($limit)
                ->get();
        });
    }

    /**
     * Get new books
     */
    public function getNewBooks($limit = 10)
    {
        return Cache::remember('new_books', 3600, function () use ($limit) {
            return Book::available()
                ->latest('published_at')
                ->limit($limit)
                ->get();
        });
    }
}
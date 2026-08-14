<?php
// app/Models/Book.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'author_id',
        'publisher_id',
        'uploaded_by_id',
        'approved_by_id',
        'title',
        'slug',
        'description',
        'cover',
        'pdf_file',
        'sample_pdf',
        'isbn',
        'language',
        'pages',
        'file_size',
        'price',
        'discount',
        'final_price',
        'stock',
        'is_free',
        'is_featured',
        'status',
        'views_count',
        'downloads_count',
        'average_rating',
        'total_ratings',
        'metadata',
        'published_at'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'file_size' => 'integer',
        'pages' => 'integer',
        'stock' => 'integer',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'average_rating' => 'decimal:2',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'total_ratings' => 'integer',
        'metadata' => 'array',
        'published_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($book) {
            if (empty($book->slug)) {
                $book->slug = $book->generateUniqueSlug();
            }
            
            // Calculate final price
            $book->final_price = $book->calculateFinalPrice();
        });
        
        static::updating(function ($book) {
            // Regenerate slug if title changes
            if ($book->isDirty('title')) {
                $book->slug = $book->generateUniqueSlug();
            }
            
            // Recalculate final price when price or discount changes
            if ($book->isDirty(['price', 'discount', 'is_free'])) {
                $book->final_price = $book->calculateFinalPrice();
            }
        });
    }
    
    /**
     * Generate a unique slug from the title, with fallback for non-Latin characters
     */
    protected function generateUniqueSlug()
    {
        // Try to create slug from title
        $slug = Str::slug($this->title);
        
        // If slug is empty (non-Latin characters), use ID with a base
        if (empty($slug)) {
            $slug = 'book-' . ($this->id ?? Str::uuid());
        }
        
        // Ensure uniqueness
        $count = static::where('slug', $slug)
            ->where('id', '!=', $this->id ?? null)
            ->count();
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot(['quantity', 'price', 'discount', 'total'])
                    ->withTimestamps();
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // Accessors
    public function getCoverUrlAttribute()
    {
        return $this->cover ? asset('storage/' . $this->cover) : null;
    }

    public function getPdfUrlAttribute()
    {
        return $this->pdf_file ? asset('storage/' . $this->pdf_file) : null;
    }

    public function getSamplePdfUrlAttribute()
    {
        return $this->sample_pdf ? asset('storage/' . $this->sample_pdf) : null;
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' $';
    }

    public function getFormattedFinalPriceAttribute()
    {
        return number_format($this->final_price, 2) . ' $';
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->price > 0 && $this->discount > 0) {
            return round(($this->discount / $this->price) * 100);
        }
        return 0;
    }

    public function getIsDiscountedAttribute()
    {
        return $this->discount > 0 && $this->price > $this->final_price;
    }

    // Helper Methods
    public function calculateFinalPrice(): float
    {
        if ($this->is_free) {
            return 0;
        }
        
        $price = $this->price ?? 0;
        $discount = $this->discount ?? 0;
        
        return max(0, $price - $discount);
    }

    public function isPurchasedBy(User $user): bool
    {
        if ($this->is_free) {
            return true;
        }
        
        return $user->purchases()
            ->where('book_id', $this->id)
            ->where('status', 'active')
            ->exists();
    }

    public function canDownloadBy(User $user): bool
    {
        if ($this->is_free) {
            return true;
        }
        
        return $this->isPurchasedBy($user);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    public function updateRating()
    {
        $this->load('reviews');
        $reviews = $this->reviews()->where('status', true);
        
        $this->average_rating = $reviews->avg('rating') ?? 0;
        $this->total_ratings = $reviews->count();
        $this->save();
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', true)
                    ->where('stock', '>', 0)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopePaid($query)
    {
        return $query->where('is_free', false);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('isbn', 'LIKE', "%{$term}%");
        });
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    public function scopeByPublisher($query, $publisherId)
    {
        return $query->where('publisher_id', $publisherId);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('final_price', [$min, $max]);
    }
}
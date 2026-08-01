<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'image',
        'status',
        'order'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Boot method for auto-generating slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeParentCategories($query)
    {
        return $query->whereNull('parent_id');
    }
     public function scopeWithAvailableBooksCount($query)
    {
        return $query->withCount(['books' => function($q) {
            $q->available();
        }]);
    }

    // ============================================================
    // SCOPE: Only categories with books (SQLite compatible)
    // ============================================================
    public function scopeWithBooks($query)
    {
        return $query->whereHas('books', function($q) {
            $q->available();
        });
    }
}
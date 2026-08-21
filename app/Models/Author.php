<?php
// app/Models/Author.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'image',
        'email',
        'website',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($author) {
            if (empty($author->slug)) {
                $author->slug = $author->generateUniqueSlug();
            }
        });

        static::updating(function ($author) {
            if ($author->isDirty('name')) {
                $author->slug = $author->generateUniqueSlug();
            }
        });
    }

    protected function generateUniqueSlug()
    {
        $slug = Str::slug($this->name);

        if (empty($slug)) {
            $slug = 'author-' . Str::uuid();
        }

        $baseSlug = $slug;
        $suffix = 2;

        while (static::where('slug', $slug)
            ->where('id', '!=', $this->getKey())
            ->exists()) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        return $slug;
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'LIKE', "%{$term}%")
            ->orWhere('bio', 'LIKE', "%{$term}%")
            ->orWhere('email', 'LIKE', "%{$term}%");
    }
}
<?php
// app/Models/Publisher.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'logo',
        'email',
        'phone',
        'website',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($publisher) {
            if (empty($publisher->slug)) {
                $publisher->slug = Str::slug($publisher->name);
            }
        });
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
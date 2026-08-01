<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'address',
        'avatar',
        'is_active',
        'last_login_at',
        'preferences'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'preferences' => 'array',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function books()
    {
        return $this->hasMany(Book::class, 'uploaded_by_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
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
        return $this->belongsToMany(Book::class, 'favorites')->withTimestamps();
    }

    public function isFavorited($bookId)
    {
        return $this->favorites()->where('book_id', $bookId)->exists();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Helper Methods
    public function isAdmin(): bool
    {
        return $this->hasRole(['super-admin', 'admin']);
    }
     public function isCustomer(): bool
    {
        return $this->is_admin === false;
    }

    public function hasPurchased(Book $book): bool
    {
        return $this->purchases()
            ->where('book_id', $book->id)
            ->where('status', 'active')
            ->exists();
    }

    public function canDownload(Book $book): bool
    {
        if ($book->is_free) {
            return true;
        }
        
        return $this->hasPurchased($book);
    }
}
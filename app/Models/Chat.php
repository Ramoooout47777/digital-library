<?php
// app/Models/Chat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one',
        'user_two',
        'last_message_at',
        'status',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    
    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function unreadMessages()
    {
        return $this->messages()->where('is_read', false);
    }

    // ============================================================
    // SCOPES
    // ============================================================
    
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_one', $userId)
                    ->orWhere('user_two', $userId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ============================================================
    // HELPERS
    // ============================================================
    
    public function getOtherUser($userId)
    {
        return $this->user_one == $userId ? $this->userTwo : $this->userOne;
    }

    public function hasUnreadMessages($userId)
    {
        return $this->messages()
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->count() > 0;
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => 'green',
            'archived' => 'yellow',
            'blocked' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Active',
            'archived' => 'Archived',
            'blocked' => 'Blocked',
            default => ucfirst($this->status),
        };
    }
}
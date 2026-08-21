<?php
// app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'type',
        'attachment',
        'chat_id', // បន្ថែមសម្រាប់ភ្ជាប់ជាមួយ Chat
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    // ============================================================
    // HELPERS
    // ============================================================
    
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
        ]);
    }

    public function isFromUser($userId)
    {
        return $this->sender_id === $userId;
    }

    public function isToUser($userId)
    {
        return $this->receiver_id === $userId;
    }

    public function getTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getAttachmentUrlAttribute()
    {
        if ($this->attachment) {
            return asset('storage/' . $this->attachment);
        }
        return null;
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'text' => 'fa-comment',
            'image' => 'fa-image',
            'file' => 'fa-file',
            'pdf' => 'fa-file-pdf',
            default => 'fa-comment',
        };
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'text' => 'Text',
            'image' => 'Image',
            'file' => 'File',
            'pdf' => 'PDF',
            default => ucfirst($this->type),
        };
    }
}
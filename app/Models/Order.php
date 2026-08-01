<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    // ============================================================
    // ORDER STATUS CONSTANTS
    // ============================================================
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PACKED = 'packed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'discount_amount',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'order_status',        // ← បន្ថែមនេះ
        'shipping_address',
        'coupon_code',
        'coupon_discount',
        'metadata',
        'completed_at',
        'confirmed_at',        // ← បន្ថែម
        'processing_at',       // ← បន្ថែម
        'packed_at',           // ← បន្ថែម
        'shipped_at',          // ← បន្ថែម
        'delivered_at',        // ← បន្ថែម
        'shipping_method',     // ← បន្ថែម
        'tracking_number',     // ← បន្ថែម
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'metadata' => 'array',
        'completed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'processing_at' => 'datetime',
        'packed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // ============================================================
    // BOOT METHOD
    // ============================================================
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            }
            if (empty($order->order_status)) {
                $order->order_status = self::STATUS_PENDING;
            }
        });
    }

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // ============================================================
    // STATUS METHODS
    // ============================================================
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_PACKED => 'Packed',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getStatusColors()
    {
        return [
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_CONFIRMED => 'bg-blue-100 text-blue-800',
            self::STATUS_PROCESSING => 'bg-indigo-100 text-indigo-800',
            self::STATUS_PACKED => 'bg-purple-100 text-purple-800',
            self::STATUS_SHIPPED => 'bg-cyan-100 text-cyan-800',
            self::STATUS_DELIVERED => 'bg-green-100 text-green-800',
            self::STATUS_COMPLETED => 'bg-emerald-100 text-emerald-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
        ];
    }

    public static function getStatusIcons()
    {
        return [
            self::STATUS_PENDING => 'fa-clock',
            self::STATUS_CONFIRMED => 'fa-check-circle',
            self::STATUS_PROCESSING => 'fa-spinner',
            self::STATUS_PACKED => 'fa-box',
            self::STATUS_SHIPPED => 'fa-truck',
            self::STATUS_DELIVERED => 'fa-home',
            self::STATUS_COMPLETED => 'fa-check-double',
            self::STATUS_CANCELLED => 'fa-times-circle',
        ];
    }

    public function getStatusBadgeAttribute()
    {
        $colors = self::getStatusColors();
        return $colors[$this->order_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->order_status] ?? $this->order_status;
    }

    public function getStatusIconAttribute()
    {
        $icons = self::getStatusIcons();
        return $icons[$this->order_status] ?? 'fa-circle';
    }

    public function canTransitionTo($status)
    {
        $transitions = [
            self::STATUS_PENDING => [self::STATUS_CONFIRMED, self::STATUS_CANCELLED],
            self::STATUS_CONFIRMED => [self::STATUS_PROCESSING, self::STATUS_CANCELLED],
            self::STATUS_PROCESSING => [self::STATUS_PACKED, self::STATUS_CANCELLED],
            self::STATUS_PACKED => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_DELIVERED],
            self::STATUS_DELIVERED => [self::STATUS_COMPLETED],
            self::STATUS_COMPLETED => [],
            self::STATUS_CANCELLED => [],
        ];

        return in_array($status, $transitions[$this->order_status] ?? []);
    }

    public function transitionTo($status)
    {
        if (!$this->canTransitionTo($status)) {
            throw new \Exception("Cannot transition from {$this->order_status} to {$status}");
        }

        $this->order_status = $status;
        $this->updateTimestampsForStatus($status);
        
        // Update main status for backward compatibility
        if (in_array($status, [self::STATUS_COMPLETED, self::STATUS_CANCELLED])) {
            $this->status = $status;
        }
        
        $this->save();
    }

    public function updateTimestampsForStatus($status)
    {
        $timestampMap = [
            self::STATUS_CONFIRMED => 'confirmed_at',
            self::STATUS_PROCESSING => 'processing_at',
            self::STATUS_PACKED => 'packed_at',
            self::STATUS_SHIPPED => 'shipped_at',
            self::STATUS_DELIVERED => 'delivered_at',
        ];

        if (isset($timestampMap[$status])) {
            $this->{$timestampMap[$status]} = now();
        }
    }

    public function getStatusProgressAttribute()
    {
        $progress = [
            self::STATUS_PENDING => 0,
            self::STATUS_CONFIRMED => 20,
            self::STATUS_PROCESSING => 40,
            self::STATUS_PACKED => 60,
            self::STATUS_SHIPPED => 80,
            self::STATUS_DELIVERED => 90,
            self::STATUS_COMPLETED => 100,
        ];

        return $progress[$this->order_status] ?? 0;
    }

    // ============================================================
    // SCOPES
    // ============================================================
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('order_status', $status);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 2) . ' $';
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 2) . ' $';
    }

    public function getCanCancelAttribute()
    {
        return in_array($this->order_status, [self::STATUS_PENDING, self::STATUS_CONFIRMED]);
    }
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }
}
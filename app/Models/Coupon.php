<?php
// app/Models/Coupon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'expires_at',
        'is_active'
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
        'expires_at' => 'datetime'
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================
    
    /**
     * Get the orders that used this coupon.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }

    // ============================================================
    // SCOPES
    // ============================================================
    
    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('usage_limit')
                          ->orWhereRaw('used_count < usage_limit');
                    });
    }

    /**
     * Scope a query to only include valid coupons for a specific total.
     */
    public function scopeValidForTotal($query, $total)
    {
        return $query->active()
                    ->where(function($q) use ($total) {
                        $q->where('min_order_amount', '<=', $total)
                          ->orWhere('min_order_amount', 0);
                    });
    }

    // ============================================================
    // HELPER METHODS
    // ============================================================
    
    /**
     * Check if coupon is valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at < now()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if coupon is valid for a specific total.
     */
    public function isValidForTotal($total): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->min_order_amount > 0 && $total < $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for a given total.
     */
    public function calculateDiscount($total): float
    {
        if (!$this->isValidForTotal($total)) {
            return 0;
        }

        $discount = $this->discount_type === 'percentage'
            ? ($total * $this->discount_value / 100)
            : $this->discount_value;

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = $this->max_discount_amount;
        }

        return min($discount, $total);
    }

    /**
     * Get formatted discount value.
     */
    public function getFormattedDiscountAttribute()
    {
        if ($this->discount_type === 'percentage') {
            return $this->discount_value . '%';
        }
        return '$' . number_format($this->discount_value, 2);
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        if (!$this->is_active) {
            return 'Inactive';
        }
        if ($this->expires_at && $this->expires_at < now()) {
            return 'Expired';
        }
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'Used Up';
        }
        return 'Active';
    }

    /**
     * Get status color.
     */
    public function getStatusColorAttribute()
    {
        if (!$this->is_active) {
            return 'red';
        }
        if ($this->expires_at && $this->expires_at < now()) {
            return 'yellow';
        }
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'gray';
        }
        return 'green';
    }

    /**
     * Find a coupon by code.
     */
    public static function findByCode($code)
    {
        return self::where('code', strtoupper($code))->first();
    }

    /**
     * Validate and apply coupon.
     */
    public static function validateAndApply($code, $total)
    {
        $coupon = self::findByCode($code);
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Coupon not found',
                'discount' => 0,
            ];
        }

        if (!$coupon->isValidForTotal($total)) {
            return [
                'valid' => false,
                'message' => 'Coupon is not valid for this order',
                'discount' => 0,
            ];
        }

        $discount = $coupon->calculateDiscount($total);

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully',
            'discount' => $discount,
            'coupon' => $coupon,
        ];
    }
}
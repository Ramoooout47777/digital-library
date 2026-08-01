<?php
// app/Models/CouponSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_type', 'default_discount_value', 'coupon_duration',
        'max_coupon_per_user', 'global_usage_limit', 'user_usage_limits',
        'min_order_for_coupon', 'min_quantity_for_coupon',
        'applicable_categories', 'applicable_books',
        'coupon_auto_apply', 'auto_apply_conditions',
        'custom_options', 'status'
    ];

    protected $casts = [
        'default_discount_value' => 'decimal:2',
        'min_order_for_coupon' => 'decimal:2',
        'coupon_auto_apply' => 'boolean',
        'user_usage_limits' => 'array',
        'applicable_categories' => 'array',
        'applicable_books' => 'array',
        'auto_apply_conditions' => 'array',
        'custom_options' => 'array',
        'status' => 'boolean',
    ];
}
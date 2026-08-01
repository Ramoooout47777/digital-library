<?php
// app/Models/DiscountSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'default_discount', 'max_discount', 'discount_type',
        'min_order_for_discount', 'min_quantity_for_discount',
        'applicable_categories', 'auto_apply', 'auto_apply_rules',
        'bulk_discount_rules', 'tiered_discounts',
        'custom_options', 'status'
    ];

    protected $casts = [
        'default_discount' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_order_for_discount' => 'decimal:2',
        'auto_apply' => 'boolean',
        'applicable_categories' => 'array',
        'auto_apply_rules' => 'array',
        'bulk_discount_rules' => 'array',
        'tiered_discounts' => 'array',
        'custom_options' => 'array',
        'status' => 'boolean',
    ];
}
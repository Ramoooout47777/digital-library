<?php
// app/Models/OrderSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_order_amount', 'max_order_amount', 'max_quantity_per_item',
        'max_items_per_order', 'order_timeout', 'payment_grace_period',
        'auto_confirm', 'auto_complete', 'auto_complete_days',
        'allowed_statuses', 'default_statuses',
        'notify_on_new_order', 'notify_on_status_change',
        'notification_emails', 'custom_options', 'status'
    ];

    protected $casts = [
        'min_order_amount' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'auto_confirm' => 'boolean',
        'auto_complete' => 'boolean',
        'notify_on_new_order' => 'boolean',
        'notify_on_status_change' => 'boolean',
        'allowed_statuses' => 'array',
        'default_statuses' => 'array',
        'notification_emails' => 'array',
        'custom_options' => 'array',
        'status' => 'boolean',
    ];
}
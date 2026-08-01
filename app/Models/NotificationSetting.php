<?php
// app/Models/NotificationSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_notifications', 'sms_notifications', 'push_notifications',
        'in_app_notifications', 'email_from', 'email_reply_to',
        'email_templates', 'sms_provider', 'sms_templates',
        'order_notifications', 'payment_notifications',
        'promotion_notifications', 'system_notifications',
        'security_notifications', 'allow_user_preferences',
        'default_user_preferences', 'custom_options', 'status'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'in_app_notifications' => 'boolean',
        'order_notifications' => 'boolean',
        'payment_notifications' => 'boolean',
        'promotion_notifications' => 'boolean',
        'system_notifications' => 'boolean',
        'security_notifications' => 'boolean',
        'allow_user_preferences' => 'boolean',
        'email_templates' => 'array',
        'sms_templates' => 'array',
        'default_user_preferences' => 'array',
        'custom_options' => 'array',
        'status' => 'boolean',
    ];
}
<?php
// database/seeders/SettingsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PrintSetting;
use App\Models\OrderSetting;
use App\Models\DiscountSetting;
use App\Models\CouponSetting;
use App\Models\NotificationSetting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Create Print Settings
        PrintSetting::create([
            'print_type' => 'digital',
            'paper_size' => 'A4',
            'paper_type' => 'glossy',
            'print_quality' => 'high',
            'copies' => 1,
            'color_mode' => 'color',
            'double_sided' => true,
            'binding' => false,
            'binding_type' => null,
            'price_per_page' => 0.05,
            'setup_fee' => 0,
            'shipping_fee' => 0,
            'status' => true,
        ]);

        // Create Order Settings
        OrderSetting::create([
            'min_order_amount' => 0,
            'max_order_amount' => null,
            'max_quantity_per_item' => 99,
            'max_items_per_order' => 50,
            'order_timeout' => 30,
            'payment_grace_period' => 15,
            'auto_confirm' => true,
            'auto_complete' => false,
            'auto_complete_days' => 7,
            'notify_on_new_order' => true,
            'notify_on_status_change' => true,
            'status' => true,
        ]);

        // Create Discount Settings
        DiscountSetting::create([
            'default_discount' => 0,
            'max_discount' => 50,
            'discount_type' => 'percentage',
            'min_order_for_discount' => 0,
            'min_quantity_for_discount' => 1,
            'auto_apply' => false,
            'status' => true,
        ]);

        // Create Coupon Settings
        CouponSetting::create([
            'coupon_type' => 'percentage',
            'default_discount_value' => 10,
            'coupon_duration' => 30,
            'max_coupon_per_user' => 3,
            'min_order_for_coupon' => 0,
            'min_quantity_for_coupon' => 1,
            'coupon_auto_apply' => false,
            'status' => true,
        ]);

        // Create Notification Settings
        NotificationSetting::create([
            'email_notifications' => true,
            'sms_notifications' => false,
            'push_notifications' => true,
            'in_app_notifications' => true,
            'order_notifications' => true,
            'payment_notifications' => true,
            'promotion_notifications' => true,
            'system_notifications' => true,
            'security_notifications' => true,
            'allow_user_preferences' => true,
            'status' => true,
        ]);

        $this->command->info('Settings seeded successfully!');
    }
}
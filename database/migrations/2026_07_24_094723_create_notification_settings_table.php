<?php
// database/migrations/xxxx_xx_xx_create_notification_settings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            
            // Channel Settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('in_app_notifications')->default(true);
            
            // Email Settings
            $table->string('email_from')->nullable();
            $table->string('email_reply_to')->nullable();
            $table->json('email_templates')->nullable();
            
            // SMS Settings
            $table->string('sms_provider')->nullable()->comment('twilio, plivo, etc');
            $table->json('sms_templates')->nullable();
            
            // Notification Types
            $table->boolean('order_notifications')->default(true);
            $table->boolean('payment_notifications')->default(true);
            $table->boolean('promotion_notifications')->default(true);
            $table->boolean('system_notifications')->default(true);
            $table->boolean('security_notifications')->default(true);
            
            // User Preferences
            $table->boolean('allow_user_preferences')->default(true);
            $table->json('default_user_preferences')->nullable();
            
            // Additional
            $table->json('custom_options')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
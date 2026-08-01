<?php
// database/migrations/xxxx_xx_xx_create_order_settings_table.php

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
        Schema::create('order_settings', function (Blueprint $table) {
            $table->id();
            
            // Order Limits
            $table->decimal('min_order_amount', 10, 2)->default(0);
            $table->decimal('max_order_amount', 10, 2)->nullable();
            $table->integer('max_quantity_per_item')->default(99);
            $table->integer('max_items_per_order')->default(50);
            
            // Order Timeout
            $table->integer('order_timeout')->default(30)->comment('minutes');
            $table->integer('payment_grace_period')->default(15)->comment('minutes');
            
            // Auto Actions
            $table->boolean('auto_confirm')->default(true);
            $table->boolean('auto_complete')->default(false);
            $table->integer('auto_complete_days')->default(7);
            
            // Order Status
            $table->json('allowed_statuses')->nullable();
            $table->json('default_statuses')->nullable();
            
            // Notifications
            $table->boolean('notify_on_new_order')->default(true);
            $table->boolean('notify_on_status_change')->default(true);
            $table->json('notification_emails')->nullable();
            
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
        Schema::dropIfExists('order_settings');
    }
};
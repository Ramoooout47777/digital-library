<?php
// database/migrations/xxxx_xx_xx_create_coupon_settings_table.php

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
        Schema::create('coupon_settings', function (Blueprint $table) {
            $table->id();
            
            // Coupon Defaults
            $table->string('coupon_type')->default('percentage')->comment('percentage, fixed');
            $table->decimal('default_discount_value', 10, 2)->default(10);
            $table->integer('coupon_duration')->default(30)->comment('days');
            
            // Usage Limits
            $table->integer('max_coupon_per_user')->default(3);
            $table->integer('global_usage_limit')->nullable();
            $table->json('user_usage_limits')->nullable();
            
            // Conditions
            $table->decimal('min_order_for_coupon', 10, 2)->default(0);
            $table->integer('min_quantity_for_coupon')->default(1);
            $table->json('applicable_categories')->nullable();
            $table->json('applicable_books')->nullable();
            
            // Auto Apply
            $table->boolean('coupon_auto_apply')->default(false);
            $table->json('auto_apply_conditions')->nullable();
            
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
        Schema::dropIfExists('coupon_settings');
    }
};
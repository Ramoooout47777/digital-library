<?php
// database/migrations/xxxx_xx_xx_create_discount_settings_table.php

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
        Schema::create('discount_settings', function (Blueprint $table) {
            $table->id();
            
            // Default Discount
            $table->decimal('default_discount', 5, 2)->default(0)->comment('percentage');
            $table->decimal('max_discount', 5, 2)->default(50)->comment('percentage');
            $table->string('discount_type')->default('percentage')->comment('percentage, fixed');
            
            // Discount Conditions
            $table->decimal('min_order_for_discount', 10, 2)->default(0);
            $table->integer('min_quantity_for_discount')->default(1);
            $table->json('applicable_categories')->nullable();
            
            // Auto Apply
            $table->boolean('auto_apply')->default(false);
            $table->json('auto_apply_rules')->nullable();
            
            // Bulk Discount
            $table->json('bulk_discount_rules')->nullable();
            $table->json('tiered_discounts')->nullable();
            
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
        Schema::dropIfExists('discount_settings');
    }
};
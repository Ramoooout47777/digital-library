<?php
// database/migrations/xxxx_xx_xx_add_order_status_fields_to_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add order_status if not exists
            if (!Schema::hasColumn('orders', 'order_status')) {
                $table->string('order_status')->default('pending')->after('status');
            }
            
            // Add status timestamps
            if (!Schema::hasColumn('orders', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable()->after('order_status');
            }
            if (!Schema::hasColumn('orders', 'processing_at')) {
                $table->timestamp('processing_at')->nullable()->after('confirmed_at');
            }
            if (!Schema::hasColumn('orders', 'packed_at')) {
                $table->timestamp('packed_at')->nullable()->after('processing_at');
            }
            if (!Schema::hasColumn('orders', 'shipped_at')) {
                $table->timestamp('shipped_at')->nullable()->after('packed_at');
            }
            if (!Schema::hasColumn('orders', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            }
            
            // Add shipping fields
            if (!Schema::hasColumn('orders', 'shipping_method')) {
                $table->string('shipping_method')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('shipping_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_status',
                'confirmed_at',
                'processing_at',
                'packed_at',
                'shipped_at',
                'delivered_at',
                'shipping_method',
                'tracking_number'
            ]);
        });
    }
};
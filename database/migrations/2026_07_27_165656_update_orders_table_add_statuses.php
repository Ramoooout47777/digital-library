<?php
// database/migrations/xxxx_xx_xx_update_orders_table_add_statuses.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_status')->default('pending')->after('status');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('packed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('shipping_address')->nullable()->change();
            $table->string('shipping_method')->nullable();
            $table->string('tracking_number')->nullable();
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
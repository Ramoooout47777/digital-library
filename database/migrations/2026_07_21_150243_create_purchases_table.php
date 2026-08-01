<?php
// database/migrations/xxxx_xx_xx_create_purchases_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->decimal('price_paid', 10, 2);
            $table->string('status')->default('active'); // active, expired, refunded
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            // ធានាថាអ្នកប្រើម្នាក់អាចទិញសៀវភៅមួយបានតែម្ដង
            $table->unique(['user_id', 'book_id']);
            
            $table->index(['user_id', 'book_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
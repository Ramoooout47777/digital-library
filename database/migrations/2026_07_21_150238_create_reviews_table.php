<?php
// database/migrations/xxxx_xx_xx_create_reviews_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->text('comment')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            
            // ធានាថាអ្នកប្រើម្នាក់អាចវាយតម្លៃសៀវភៅមួយបានតែម្ដង
            $table->unique(['user_id', 'book_id']);
            
            $table->index(['book_id', 'rating']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
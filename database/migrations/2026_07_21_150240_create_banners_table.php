<?php
// database/migrations/xxxx_xx_xx_create_banners_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('link')->nullable();
            $table->string('position')->default('home');
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            
            $table->index(['position', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
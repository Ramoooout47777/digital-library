<?php

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
         Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_one')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_two')->constrained('users')->onDelete('cascade');
            $table->timestamp('last_message_at')->nullable();
            $table->enum('status', ['active', 'archived', 'blocked'])->default('active');
            $table->timestamps();

            $table->index(['user_one', 'user_two']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};

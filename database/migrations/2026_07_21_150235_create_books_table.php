<?php
// database/migrations/xxxx_xx_xx_create_books_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->foreignId('publisher_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Basic Information
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            
            // Files
            $table->string('cover')->nullable();
            $table->string('pdf_file')->nullable();
            $table->string('sample_pdf')->nullable();
            
            // Book Details
            $table->string('isbn', 13)->nullable()->unique();
            $table->string('language', 10)->default('km');
            $table->integer('pages')->default(0);
            $table->integer('file_size')->default(0); // Size in KB
            
            // Pricing
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0);
            
            // Stock & Status
            $table->integer('stock')->default(0);
            $table->boolean('is_free')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            
            // Statistics
            $table->integer('views_count')->default(0);
            $table->integer('downloads_count')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->integer('total_ratings')->default(0);
            
            // Metadata
            $table->json('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('slug');
            $table->index('category_id');
            $table->index('author_id');
            $table->index('publisher_id');
            $table->index('status');
            $table->index('is_free');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index(['category_id', 'status']);
            $table->index(['author_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
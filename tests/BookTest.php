<?php
// tests/Feature/BookTest.php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_books()
    {
        $response = $this->get('/api/v1/books');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'price',
                    'final_price',
                    'is_free',
                ]
            ],
            'meta'
        ]);
    }

    public function test_user_can_view_book_detail()
    {
        $book = Book::factory()->create([
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/api/v1/books/{$book->id}");
        
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $book->id,
            'title' => $book->title,
        ]);
    }

    public function test_user_can_search_books()
    {
        $book = Book::factory()->create([
            'title' => 'Laravel for Beginners',
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/api/v1/books?search=Laravel');
        
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Laravel for Beginners',
        ]);
    }

    public function test_user_can_filter_books_by_category()
    {
        $category = \App\Models\Category::factory()->create();
        $book = Book::factory()->create([
            'category_id' => $category->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/api/v1/books?category_id={$category->id}");
        
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $book->id,
        ]);
    }
}